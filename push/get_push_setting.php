<?php
// 包含配置文件
include '../config.php';

// 创建连接
$conn = new mysqli($host, $db_username, $db_password, $database, $port);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$userToken = $_COOKIE['token'];

// 根据用户的 token 获取 receive_email 值
$stmt = $conn->prepare("SELECT receive_email FROM users WHERE token = ?");
$stmt->bind_param("s", $userToken);
$stmt->execute();
$stmt->bind_result($receiveEmail);
$stmt->fetch();
$stmt->close();

// 输出 receive_email 值
echo $receiveEmail;

// 关闭连接
$conn->close();
?>
