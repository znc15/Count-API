<?php
require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $qq = $_POST['qq']; // 获取 QQ 号
    $verificationCodeInput = $_POST['verificationCode'];
    $is_admin = 0; // 默认为普通用户
    // 生成 token
    $token = bin2hex(random_bytes(16));
    // 在这里添加验证码验证逻辑
    // 比较 $verificationCodeInput 和 后台发送的验证码是否一致

    // 在此添加数据库插入逻辑
    $conn = new mysqli($host, $db_username, $db_password, $database, $port);

    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    // 检查邮箱和用户名是否已存在
    $checkExisting = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $result = $conn->query($checkExisting);

    if ($result->num_rows > 0) {
        echo "邮箱或用户名已存在，请重新输入。";
    } else {
        // 插入新用户
        $sql = "INSERT INTO users (username, password, email, token, qq_number, is_admin) VALUES ('$username', '$password', '$email', '$token', '$qq', $is_admin)";

        if ($conn->query($sql) === TRUE) {
            // 注册成功，显示美化的提示页面
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>注册成功</title>
                <!-- 引入 Bootstrap 样式 -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-5">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">注册成功！</h4>
                        <p>感谢您注册。您现在可以登录您的账户。</p>
                        <hr>
                        <p class="mb-0">返回<a href="../../home/login.php">登录页面</a></p>
                    </div>
                </div>
                <!-- 引入 Bootstrap JavaScript -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
            </body>
            </html>
            <?php
        } else {
            echo "注册失败，请稍后重试。";
        }
    }

    $conn->close();
}
?>
