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

// 连接数据库，获取配置信息
require_once('../config.php'); // 根据实际文件路径调整

// 处理管理员账号创建的表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $adminUsername = $_POST["admin_username"];
    $adminPassword = password_hash($_POST["admin_password"], PASSWORD_DEFAULT); // 使用密码哈希存储密码
    $adminEmail = $_POST["admin_email"];
    $adminQQ = $_POST["admin_qq"];
    $is_admin = 1;

    // 自动生成 Token
    $adminToken = bin2hex(random_bytes(16));

    // 连接数据库
    $conn = new mysqli($host, $db_username, $db_password, $database, $port);

    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    // 插入管理员账号信息
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, token, qq_number, is_admin) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssss", $adminUsername, $adminPassword, $adminEmail, $adminToken, $adminQQ);

    if ($stmt->execute() === TRUE) {
        // 修改 install.lock 的值为 true
        file_put_contents("install.lock", "true");
        
        // 跳转到创建成功页面
        header("Location: install_success.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>安装引导 - 创建管理员账号</title>
    <!-- 添加Bootstrap的CSS链接 -->
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
            width: 50%;
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
            width: 100%;
            box-sizing: border-box;
            padding: 0 15px;
        }

        .btn-primary {
            width: 100%;
            clear: both;
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
        <form method="post" action="">
            <h2>安装引导 - 创建管理员账号</h2>

            <!-- 管理员账号信息 -->
            <div class="mb-3">
                <label for="adminUsername" class="form-label">管理员账号</label>
                <input type="text" name="admin_username" class="form-control" id="adminUsername" required>
            </div>

            <div class="mb-3">
                <label for="adminPassword" class="form-label">管理员密码</label>
                <input type="password" name="admin_password" class="form-control" id="adminPassword" required>
            </div>

            <div class="mb-3">
                <label for="adminEmail" class="form-label">管理员邮箱</label>
                <input type="email" name="admin_email" class="form-control" id="adminEmail" required>
            </div>

            <div class="mb-3">
                <label for="adminQQ" class="form-label">管理员QQ号</label>
                <input type="text" name="admin_qq" class="form-control" id="adminQQ" required>
            </div>

            <button type="submit" class="btn btn-primary">创建管理员账号</button>
        </form>
    </div>

    <!-- 底部的 footer -->
    <footer class="text-muted">
        <p style="margin: 0;padding: 15px;">Copyright © 2023 · LittleSheep | Power by TCB Work</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
