<?php
require_once('../config.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $verificationCode = $_POST['verificationCode'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // 其他验证逻辑，如邮箱格式验证、密码强度验证等...

    // 检查验证码是否正确
    if ($verificationCode != $_SESSION['verificationCode']) {
        echo "验证码错误，请重新输入。";
        exit;
    }

    // 检查验证码是否过期
    if (time() > $_SESSION['verificationCodeExpiry']) {
        echo "验证码已过期，请重新获取。";
        exit;
    }

    // 检查新密码和确认密码是否一致
    if ($newPassword != $confirmPassword) {
        echo "新密码和确认密码不一致，请重新输入。";
        exit;
    }

    // 其他处理逻辑，如更新密码到数据库等...

    // 重置成功后，清除验证码相关的 session 数据
    unset($_SESSION['verificationCode']);
    unset($_SESSION['verificationCodeExpiry']);

    echo "密码重置成功！";
}
?>
