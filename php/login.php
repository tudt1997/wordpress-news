<?php
// Include config file
require_once 'config.php';
session_start();
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Check if username is empty
    if (empty(test_input($_POST["username"]))) {
        $username_err = 'Hãy nhập tên đăng nhập.';
    } else {
        $username = test_input($_POST["username"]);
    }
    
    // Check if password is empty
    if (empty(test_input($_POST['password']))) {
        $password_err = 'Hãy nhập mật khẩu.';
    } else {
        $password = test_input($_POST['password']);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT username, password, name FROM user WHERE username = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $name);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            $_SESSION['username'] = $username;
                            $_SESSION['name'] = $name;
                        // header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = 'Mật khẩu bạn nhập không đúng.';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = 'Tài khoản không tồn tại.';
                }
            } else {
                echo "Có gì đó không ổn. Hãy thử lại sau.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } 
    if (!empty($username_err)) {
        echo $username_err . "\n";
    }
    if (!empty($password_err)) {
        echo $password_err . "\n";
    }
    // Close connection
    mysqli_close($conn);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>