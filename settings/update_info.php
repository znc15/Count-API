<?php
// 引入数据库配置文件
require_once '../config.php';

// 创建连接
$conn = new mysqli($host, $db_username, $db_password, $database, $port);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 处理表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取浏览器存储的 cookie 中的 token
    $token = $_COOKIE['token'];

    // 获取用户输入的新用户名和新邮箱
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];

    // 确保用户名和邮箱不为空
    if (!empty($newUsername) && !empty($newEmail)) {
        // 执行更新操作
        $sql = "UPDATE users SET username = '$newUsername', email = '$newEmail' WHERE token = '$token'";

        if ($conn->query($sql) === TRUE) {
            echo "更新成功";
        } else {
            echo "更新失败: " . $conn->error;
        }
    } else {
        echo "用户名和邮箱不能为空";
    }
}

// 关闭数据库连接
$conn->close();
?>