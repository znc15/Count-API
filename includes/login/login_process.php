<?php
session_start();
require_once('../../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['usernameOrEmail'];
    $password = $_POST['password'];
    $captchaInput = $_POST['captcha'];
    $rememberMe = isset($_POST['rememberMe']);

    // 检查验证码是否正确
    if (strtolower($captchaInput) !== strtolower($_SESSION['captcha'])) {
        echo 'CaptchaError';
        exit();
    }

    // 在此添加数据库查询和验证逻辑
    $conn = new mysqli($host, $db_username, $db_password, $database, $port);

    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    // 根据用户名或邮箱查询用户
    $query = "SELECT * FROM users WHERE username='$usernameOrEmail' OR email='$usernameOrEmail'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 验证密码
        if (password_verify($password, $user['password'])) {
 
        // 登录成功处理
        if ($rememberMe) {
            // 用户勾选了记住我，将 token 存储在浏览器的 cookie 中，有效期为 30 天
            $token = $user['token']; // 假设 token 列在 users 表中
            setcookie('token', $token, time() + (86400 * 30), "/");
        } else {
            // 用户没有勾选记住我，将 token 存储在浏览器的 cookie 中，有效期为 1 天
            $token = $user['token']; // 假设 token 列在 users 表中
            setcookie('token', $token, time() + 86400, "/");
        }

        echo 'Success';
        } else {
            echo 'PasswordError';
        }
    } else {
        echo 'UserNotFound';
    }

    $conn->close();
}
?>
