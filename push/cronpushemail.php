<?php

// 引入 PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 引入配置文件
require '../config.php';

// 加载 Composer 自动加载器
require '../vendor/autoload.php';

// 验证定时任务 token
if (!isset($_GET['crontoken']) || $_GET['crontoken'] !== $cronToken) {
    die("非法访问");
}

// 创建数据库连接
$connection = mysqli_connect($host, $db_username, $db_password, $database, $port);

// 检查连接是否成功
if (!$connection) {
    die("连接数据库失败: " . mysqli_connect_error());
}

    // 获取所有同意接收邮件的用户
    $queryUsers = "SELECT * FROM users WHERE receive_email = 1";
    $resultUsers = mysqli_query($connection, $queryUsers);

    while ($rowUser = mysqli_fetch_assoc($resultUsers)) {
        $userId = $rowUser['id'];
        $email = $rowUser['email'];

    // 查询该用户一周内的访问统计数据
    $query = "SELECT COALESCE(SUM(vc.request_count), 0) as total_requests
            FROM urls url
            JOIN visitcount vc ON url.urltoken = vc.urltoken
            WHERE url.owner = '$userId'
                AND vc.visit_time >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";

    // 添加下面这行输出，检查 SQL 查询语句是否正确
    echo "SQL 查询语句：{$query}<br>";

    $result = mysqli_query($connection, $query);

    // 获取总请求量
    $totalRequests = 0;
    if ($row = mysqli_fetch_assoc($result)) {
        // 添加下面这行输出，检查 $row 变量的内容
        echo "查询结果：";
        print_r($row);
        
        $totalRequests = $row['total_requests'];
    }

    // 添加下面这行输出，检查 $totalRequests 的值
    echo "总请求量：{$totalRequests}<br>";

    // 使用 PHPMailer 发送邮件
    $mail = new PHPMailer(true);

    // 使用 PHPMailer 发送邮件
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

        // 设置邮件内容的字符编码
        $mail->CharSet = 'UTF-8';

        //Recipients
        $mail->setFrom($smtpUsername, 'Your Sender Name');
        $mail->addAddress($email, 'Recipient Name');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Weekly Visit Count Summary';
        $mail->Body    = "亲爱的用户，您本周所有网站总共被请求多少次：" . $totalRequests;

        $mail->send();
        echo '推送成功!';
    } catch (Exception $e) {
        echo "推送失败: {$mail->ErrorInfo}";
    }
}

// 关闭数据库连接
mysqli_close($connection);

?>
