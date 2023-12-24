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

// 执行 MySQL 查询，获取用户总请求次数
$queryTotalRequest = "SELECT
                        u.token,
                        u.email,
                        u.receive_email,
                        COALESCE(SUM(vc.request_count), 0) AS total_request_count
                      FROM users u
                      LEFT JOIN urls ON u.token = urls.owner
                      LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken AND vc.visit_time >= CURDATE() - INTERVAL 7 DAY
                      GROUP BY u.token, u.email, u.receive_email";

$resultTotalRequest = mysqli_query($connection, $queryTotalRequest);

// 处理查询结果
while ($rowTotalRequest = mysqli_fetch_assoc($resultTotalRequest)) {
    $token = $rowTotalRequest['token'];
    $email = $rowTotalRequest['email'];
    $receiveEmail = $rowTotalRequest['receive_email'];
    $totalRequestCount = $rowTotalRequest['total_request_count'];

    // 记录请求到数据库
    $requestDate = date('Y-m-d');
    $requestSuccess = 0; // 默认为失败

    // 判断是否推送邮件
    if ($receiveEmail == 1) {
        // 获取用户所有链接请求次数
        $queryLinkRequest = "SELECT
                                u.token,
                                u.email,
                                urls.url,
                                COALESCE(SUM(vc.request_count), 0) AS link_request_count
                              FROM users u
                              LEFT JOIN urls ON u.token = urls.owner
                              LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken AND vc.visit_time >= CURDATE() - INTERVAL 7 DAY
                              WHERE u.token = '$token'
                              GROUP BY u.token, u.email, urls.url";

        $resultLinkRequest = mysqli_query($connection, $queryLinkRequest);

        //Content
        $body = "
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
                    .logo-container {
                        max-width: 100%;
                        height: auto;
                        margin-bottom: 20px;
                    }
                    .logo {
                        max-width: 100%;
                        height: auto;
                    }
                    h1 {
                        color: #3498db;
                    }
                    p {
                        line-height: 1.6;
                    }
                    .btn-container {
                        margin-top: 20px;
                    }
                    .btn {
                        display: inline-block;
                        padding: 10px 20px;
                        font-size: 16px;
                        background-color: #3498db;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .link-request-container {
                        text-align: left;
                        margin-top: 20px;
                    }
                    .link-request-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                    }
                    .link-request-table th, .link-request-table td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .link-request-table th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo-container'>
                        <img src='https://api.miaomc.cn/image/get' alt='Image' class='logo'>
                    </div>
                    <img src='{$logoimagedark}' alt='Logo' class='logo'>
                    <h1>{$siteName}-每周推送</h1>
                    <p>亲爱的{$email}，您本周所有网站总共被请求多少次：{$totalRequestCount}</p>
                    <div class='btn-container'>
                        <a href='{$siteUrl}' class='btn' target='_blank'>访问网站</a>
                    </div>
                    <div class='link-request-container'>
                        <p><strong>链接请求记录：</strong></p>
                        <table class='link-request-table'>
                            <tr>
                                <th>链接</th>
                                <th>请求次数</th>
                            </tr>";

        // 处理链接请求结果
        while ($rowLinkRequest = mysqli_fetch_assoc($resultLinkRequest)) {
            $url = $rowLinkRequest['url'];
            $linkRequestCount = $rowLinkRequest['link_request_count'];

            $body .= "
                <tr>
                    <td>{$url}</td>
                    <td>{$linkRequestCount}</td>
                </tr>";
        }

        $body .= "
                        </table>
                    </div>
                    <!-- 可以在这里添加更多 HTML 格式的内容 -->
                </div>
            </body>
            </html>
        ";

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
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = "{$siteName}-每周推送";
            $mail->Body    = $body;

            // 记录请求成功
            $requestSuccess = 1;

            $mail->send();
            echo "推送成功! 邮箱：{$email}<br>";
        } catch (Exception $e) {
            echo "推送失败: {$mail->ErrorInfo}，邮箱：{$email}<br>";
        }
    } else {
        echo "用户 {$email} 选择不接收邮件，未推送邮件。<br>";
    }

    // 记录请求到数据库
    $queryRecordRequest = "INSERT INTO request_log (user_token, email, request_date, success)
                           VALUES ('$token', '$email', '$requestDate', $requestSuccess)";

    mysqli_query($connection, $queryRecordRequest);
}

// 关闭数据库连接
mysqli_close($connection);

?>
