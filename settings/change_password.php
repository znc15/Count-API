<?php
// 引入数据库配置文件
require_once '../config.php';

$conn = new mysqli($host, $db_username, $db_password, $database, $port);

// 获取浏览器 cookie 中的 token 值
$token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if ($newPassword !== $confirmPassword) {
    echo json_encode(array("success" => false, "message" => "新密码和确认新密码不匹配"));
    exit;
}

// 删除旧密码验证的代码块
// $oldPassword = isset($_POST['old_password']) ? $_POST['old_password'] : '';
// $oldPasswordHash = password_hash($oldPassword, PASSWORD_DEFAULT);
// $sql = "SELECT COUNT(*) as count FROM users WHERE token = '$token' AND password = '$oldPassword'";
// $result = $conn->query($sql);
// $row = $result->fetch_assoc();

// 输出新密码的哈希值到 JSON 响应
// if ($row['count'] > 0) {
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateSql = "UPDATE users SET password = '$newPasswordHash' WHERE token = '$token'";

    if ($conn->query($updateSql) === TRUE) {
        echo json_encode(array("success" => true, "message" => "密码更新成功", "hash" => $newPasswordHash));
    } else {
        echo json_encode(array("success" => false, "message" => "密码更新失败：" . $conn->error, "hash" => ""));
    }
// } else {
//     echo json_encode(array("success" => false, "message" => "旧密码不正确", "hash" => ""));
// }

$conn->close();
?>
