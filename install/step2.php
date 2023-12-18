<?php
session_start();

// 检查是否已经安装
if (file_exists("install.lock")) {
    $installStatus = file_get_contents("install.lock");
    if ($installStatus === "true") {
        // 如果已经安装并且 install.lock 文件的内容为 "true"，则跳转到其他页面或显示错误信息
        header("Location: ../");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>安装引导 - 配置网站信息</title>
    <!-- 添加 Bootstrap 的 CSS 链接 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa; /* Optional background color for better visibility */
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 40px; /* Adjusted margin for better alignment */
        }

        form {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px; /* Adjusted margin for better spacing */
        }

        .mb-3 {
            width: 50%; /* 设置表单项的宽度为50% */
            box-sizing: border-box; /* 包含元素的边框和内边距在内，以避免宽度超过50% */
            float: left; /* 让表单项浮动到左侧，实现一行两个的效果 */
            padding: 0 15px; /* 为表单项添加一些间距 */
        }

        .btn-primary {
            width: 100%;
            clear: both; /* 清除浮动，确保按钮在两个表单项下面 */
        }

        footer {
            text-align: center;
            padding: 15px;
            bottom: 0;
            width: 100%;
            background-color: transparent;
        }

    </style>
</head>

<body>
    <div class="container">
        <form method="post" action="install_process.php">
            <h2>安装引导 - 配置网站信息</h2>

            <!-- 网站信息 -->
            <div class="mb-3">
                <label for="siteUrl" class="form-label">网站 URL</label>
                <input type="text" name="site_url" class="form-control" id="siteUrl" required>
            </div>

            <div class="mb-3">
                <label for="siteName" class="form-label">网站名字</label>
                <input type="text" name="site_name" class="form-control" id="siteName" required>
            </div>

            <div class="mb-3">
                <label for="siteInfo" class="form-label">网站说明</label>
                <input type="text" name="site_info" class="form-control" id="siteInfo" required>
            </div>
            <!-- 数据库信息 -->
            <div class="mb-3">
                <label for="dbHost" class="form-label">数据库主机</label>
                <input type="text" name="db_host" class="form-control" id="dbHost" required>
            </div>

            <div class="mb-3">
                <label for="dbPort" class="form-label">数据库端口</label>
                <input type="number" name="db_port" class="form-control" id="dbPort" required>
            </div>

            <div class="mb-3">
                <label for="dbUser" class="form-label">数据库用户名</label>
                <input type="text" name="db_user" class="form-control" id="dbUser" required>
            </div>

            <div class="mb-3">
                <label for="dbPassword" class="form-label">数据库密码</label>
                <input type="password" name="db_password" class="form-control" id="dbPassword" required>
            </div>

            <div class="mb-3">
                <label for="dbName" class="form-label">数据库名字</label>
                <input type="text" name="db_name" class="form-control" id="dbName" required>
            </div>
            <!-- 邮箱信息 -->
            <div class="mb-3">
                <label for="smtpHost" class="form-label">SMTP 服务器</label>
                <input type="text" name="smtp_host" class="form-control" id="smtpHost" required>
            </div>

            <div class="mb-3">
                <label for="smtpUsername" class="form-label">SMTP 账号</label>
                <input type="text" name="smtp_username" class="form-control" id="smtpUsername" required>
            </div>

            <div class="mb-3">
                <label for="smtpPassword" class="form-label">SMTP 密码</label>
                <input type="password" name="smtp_password" class="form-control" id="smtpPassword" required>
            </div>

            <div class="mb-3">
                <label for="smtpPort" class="form-label">SMTP 端口</label>
                <input type="text" name="smtp_port" class="form-control" id="smtpPort" required>
            </div>

            <div class="mb-3">
                <label for="smtpProtocol" class="form-label">SMTP 协议（SSL/TLS）</label>
                <select name="smtp_protocol" class="form-select" id="smtpProtocol" required>
                    <option value="ssl">SSL</option>
                    <option value="tls">TLS</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">开始安装</button>
        </form>
    </div>

    <!-- 底部的 footer -->
    <footer class="text-muted">
        <p style="margin: 0;padding: 15px;">Copyright © 2023 · LittleSheep | Power by TCB Work</p>
    </footer>

    <!-- 添加 Bootstrap 的 JS 链接 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
