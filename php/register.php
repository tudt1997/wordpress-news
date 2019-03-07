<?php
// Include config file
require_once 'config.php';
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    // Validate username
    if (empty(test_input($_POST["username"]))) {
        $username_err = "Hãy nhập tên đăng nhập.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM user WHERE username = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = test_input($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Tên đăng nhập đã được đăng ký.";
                } else {
                    $username = test_input($_POST["username"]);
                }
            } else {
                echo "Có gì đó không ổn. Hãy thử lại sau.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if (empty(test_input($_POST['password']))) {
        $password_err = "Hãy nhập mật khẩu.";
    } elseif (strlen(test_input($_POST['password'])) < 6) {
        $password_err = "Mật khẩu phải có độ dài ít nhất 6 kí tự.";
    } else {
        $password = test_input($_POST['password']);
    }
    
    // Validate confirm password
    if (empty(test_input($_POST["confirm_password"]))) {
        $confirm_password_err = 'Xác minh mật khẩu.';
    } else {
        $confirm_password = test_input($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_err = 'Mật khẩu không khớp.';
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
         
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                // header("location: index.php");
            } else {
                echo "Có gì đó không ổn. Hãy thử lại sau..";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    } 
    
    // Close connection
    mysqli_close($conn);
}

if (!empty($username_err)) {
    echo $username_err . "\n";
}
if (!empty($password_err)) {
    echo $password_err . "\n";
}
if (!empty($confirm_password_err)) {
    echo $confirm_password_err . "\n";
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
