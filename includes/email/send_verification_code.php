<?php
require_once('../../config.php');
require '../../vendor/autoload.php'; // 引入PHPMailer库

use PHPMailer\PHPMailer\PHPMailer;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // 检查距离上次请求验证码的时间
    $currentTime = time();
    $lastSentTime = isset($_SESSION['lastSentTime']) ? $_SESSION['lastSentTime'] : 0;

    if ($currentTime - $lastSentTime < 60) { // 60秒 = 1分钟，可以根据需要调整
        $remainingTime = 60 - ($currentTime - $lastSentTime);
        echo "请等待 {$remainingTime} 秒后再次获取验证码。";
        exit;
    }

    $verificationCode = rand(1000, 9999);

    // 发送邮件
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUsername;
        $mail->Password   = $smtpPassword;
        $mail->SMTPSecure = $smtpProtocol;
        $mail->Port       = $smtpPort;

        //Recipients
        $mail->setFrom($smtpUsername, $siteName); // 使用网站名字作为发件人姓名
        $mail->addAddress($email); // 收件人地址

        //Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // 设置编码为UTF-8
        $mail->Subject = '验证码';
        $mail->Body    = '您的验证码是：' . $verificationCode . '<br>验证码有效期为5分钟，请尽快使用。';

        $mail->send();
        echo "验证码已发送至您的邮箱，请查收。";

        // 记录发送时间
        $_SESSION['lastSentTime'] = $currentTime;
        $_SESSION['verificationCode'] = $verificationCode; // 记录验证码
        $_SESSION['verificationCodeExpiry'] = $currentTime + 300; // 验证码有效期为5分钟
    } catch (Exception $e) {
        echo "邮件发送失败： {$mail->ErrorInfo}";
    }
}
?>
