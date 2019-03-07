<?php
include './view/header.php';
include 'config.php';
include 'session.php';

if (empty($_GET['user_id']))
    $user = $current_user;
else {
    $user_id = $_GET['user_id'];
    $get_user_show = mysqli_query($conn, "SELECT * FROM user WHERE id = '$user_id'");
    $rows = mysqli_num_rows($get_user_show);
    if ($rows) {
        $user = mysqli_fetch_array($get_user_show);
    } else {
        header('location: index.php');
    }
}
?>
    <div class="main">
        <h2>Thông tin người dùng</h2>
        <div class="card">
            <div class="row">
                <div class="col-md-4">
                    <div class="image">
                        <img class="profile-avatar" src="images/avatar.png">
                        <?php echo '
              <div class="text-muted">' . $user['username'] . '</div>
              ';
                        ?>
                    </div>
                </div>
                <div class="col-md-4 info">
                    <?php echo '
            <h3><b>' . $user['name'] . '</b> <small>
              ' . (
                        ($user['id'] == 1) ? '<span class="badge badge-warning">Admin</span>'
                            : '<span class="badge badge-secondary">User</span>'
                        )
                        . '
              </small></h3>
            ';
                    ?><br>
                    <?php echo '
            <b>Giới tính:</b> ' . ($user['gender'] === 0 ? 'Nữ' : 'Nam') . '<br><br>
            <b>Ngày sinh:</b> ' . $user['dob'] . '<br><br>
            <b>Địa chỉ:</b> ' . $user['address'] . '<br><br>
            ';
                    ?>
                </div>
                <div class="col-md-4 info">
                    <?php echo '
            <b>Email:</b> ' . $user['email'] . '<br><br>
            <b>Số điện thoại:</b> ' . $user['phone'] . '<br><br>
            ';
                    ?>
                </div>
            </div>
        </div>
        <?php
        if ($user == $current_user)
            echo '
          <a href="user_edit.php" class="btn btn-primary">Chỉnh sửa thông tin cá nhân</a>
          ';
        ?>
    </div>
<?php include './view/footer.php'; ?>