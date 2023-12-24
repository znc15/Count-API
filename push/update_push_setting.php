<?php
  // 包含配置文件
  include '../config.php';

  // 获取用户的 Token
  $userToken = $_COOKIE['token'];

  // 创建连接
  $conn = new mysqli($host, $db_username, $db_password, $database, $port);

  // 检查连接
  if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
  }

  // 获取用户ID
  $stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
  $stmt->bind_param("s", $userToken);
  $stmt->execute();
  $stmt->bind_result($userId);
  $stmt->fetch();
  $stmt->close();

  // 检查是否找到用户
  if (!$userId) {
    die("无法找到用户");
  }

  // 获取前端传递的推送设置值
  $receiveEmail = $_POST['receiveEmail'];

  // 更新用户的推送设置
  $stmt = $conn->prepare("UPDATE users SET receive_email = ? WHERE id = ?");
  $stmt->bind_param("ii", $receiveEmail, $userId);
  $stmt->execute();

  // 关闭连接
  $stmt->close();
  $conn->close();
?>
