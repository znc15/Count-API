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

// 执行 MySQL 查询
$query = "SELECT
            u.username,
            u.email,
            u.receive_email,
            COALESCE(SUM(vc.request_count), 0) AS total_request_count
          FROM users u
          LEFT JOIN urls ON u.token = urls.owner
          LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken AND vc.visit_time >= CURDATE() - INTERVAL 7 DAY
          GROUP BY u.username, u.email, u.receive_email";

$result = mysqli_query($connection, $query);

// 处理查询结果
while ($row = mysqli_fetch_assoc($result)) {
    $username = $row['username'];
    $email = $row['email'];
    $receiveEmail = $row['receive_email'];
    $totalRequestCount = $row['total_request_count'];

    // 判断是否推送邮件
    if ($receiveEmail == 1) {
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
            $mail->setFrom($smtpUsername, $siteName);
            $mail->addAddress($email, $username);

            //Content
            $mail->isHTML(true);
            $mail->Subject = "{$siteName}-每周推送";
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: 'Arial', sans-serif;
                            background-color: #f4f4f4;
                            color: #333;
                        }
                        .container {
                            max-width: 600px;
                            margin: 20px auto;
                            padding: 20px;
                            background-color: #fff;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            text-align: center;
                        }
                        .logo {
                            max-width: 100%;
                            height: auto;
                            margin-bottom: 20px;
                        }
                        h1 {
                            color: #3498db;
                        }
                        p {
                            line-height: 1.6;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <img src='{$logoimagedark}' alt='Logo' class='logo'>
                        <h1>{$siteName}-每周推送</h1>
                        <p>亲爱的{$username}，您本周所有网站总共被请求多少次：{$totalRequestCount}</p>
                        <!-- 可以在这里添加更多 HTML 格式的内容 -->
                    </div>
                </body>
                </html>
            ";

            $mail->send();
            echo "推送成功! 邮箱：{$email}<br>";
        } catch (Exception $e) {
            echo "推送失败: {$mail->ErrorInfo}，邮箱：{$email}<br>";
        }
    } else {
        echo "用户 {$username} 不接收邮件，不推送<br>";
    }
}

// 关闭数据库连接
mysqli_close($connection);

?>
