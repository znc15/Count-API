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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取表单数据
    $siteUrl = $_POST["site_url"];
    $siteName = $_POST["site_name"];
    $siteInfo = $_POST["site_info"];

    $dbHost = $_POST["db_host"];
    $dbUser = $_POST["db_user"];
    $dbPassword = $_POST["db_password"];
    $dbName = $_POST["db_name"];
    $dbPort = $_POST["db_port"];

    // 获取邮箱配置数据
    $smtpHost = $_POST["smtp_host"];
    $smtpUsername = $_POST["smtp_username"];
    $smtpPassword = $_POST["smtp_password"];
    $smtpPort = $_POST["smtp_port"];
    $smtpProtocol = $_POST["smtp_protocol"];

    // 获取其他配置项
    // ...

    // 将配置信息写入 config.php 文件
    $configContent = "<?php\n";
    $configContent .= "// 网站配置\n";
    $configContent .= "\$siteName = \"$siteName\";\n";
    $configContent .= "\$siteUrl = \"$siteUrl\";\n";
    $configContent .= "\$siteInfo = \"$siteInfo\";\n";
    $configContent .= "\$iconimage = \"$iconimage\";\n";
    $configContent .= "\$titleimage = \"$titleimage\";\n";
    $configContent .= "\n";
    $configContent .= "// 数据库配置\n";
    $configContent .= "\$host = \"$dbHost\";\n";
    $configContent .= "\$db_username = \"$dbUser\";\n";
    $configContent .= "\$db_password = \"$dbPassword\";\n";
    $configContent .= "\$database = \"$dbName\";\n";
    $configContent .= "\$port = $dbPort;\n"; // 改为 $port
    $configContent .= "\n";
    $configContent .= "// 邮箱配置\n";
    $configContent .= "\$smtpHost = \"$smtpHost\";\n";
    $configContent .= "\$smtpUsername = \"$smtpUsername\";\n";
    $configContent .= "\$smtpPassword = \"$smtpPassword\";\n";
    $configContent .= "\$smtpPort = $smtpPort;\n";
    $configContent .= "\$smtpProtocol = \"$smtpProtocol\";\n";
    $configContent .= "\n";
    $configContent .= "// 其余配置\n";
    $configContent .= "\$logoimagedark = \"$logoimagedark\";\n";
    $configContent .= "\$logoimagelight = \"$logoimagelight \";\n";

    // 写入其他配置项...

    // 将配置信息写入 config.php 文件
    $configFilePath = "../config.php"; // 根据实际文件路径调整
    if (file_put_contents($configFilePath, $configContent) === FALSE) {
        die("无法写入配置文件，请检查文件写入权限");
    }

    // 连接数据库，执行数据库初始化操作
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    // 执行数据库初始化操作，例如创建表格等
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL,
        qq_number VARCHAR(20) NOT NULL,
        is_admin TINYINT(1) NOT NULL DEFAULT 0,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $stmt = $conn->prepare($sql);

    if ($stmt === FALSE) {
        die("Error in prepare: " . $conn->error);
    }

    if ($stmt->execute() === TRUE) {
        echo "用户表初始化成功\n";
    } else {
        echo "Error: " . $stmt->error;
    }

    // 创建url表
    $sqlUrl = "CREATE TABLE IF NOT EXISTS urls (
        id INT AUTO_INCREMENT PRIMARY KEY,
        url VARCHAR(255) NOT NULL,
        country VARCHAR(255) NOT NULL,
        remark VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        owner VARCHAR(255) NOT NULL,
        urltoken VARCHAR(255) NOT NULL UNIQUE
    )";

    $stmtUrl = $conn->prepare($sqlUrl);

    if ($stmtUrl === FALSE) {
        die("Error in prepare: " . $conn->error);
    }

    if ($stmtUrl->execute() === TRUE) {
        echo "URL表初始化成功\n";
    } else {
        echo "Error: " . $stmtUrl->error;
    }

    // 创建visitcount表
    $sqlVisitCount = "CREATE TABLE IF NOT EXISTS visitcount (
        id int(11) NOT NULL AUTO_INCREMENT,
        urltoken varchar(255) NOT NULL,
        ip varchar(45) NOT NULL,
        region varchar(255) NOT NULL,
        visit_time timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        request_count int(11) DEFAULT '0',
    )";

    $stmtVisitCount = $conn->prepare($sqlVisitCount);

    if ($stmtVisitCount === FALSE) {
        die("Error in prepare: " . $conn->error);
    }

    if ($stmtVisitCount->execute() === TRUE) {
        echo "访问统计表初始化成功\n";
    } else {
        echo "Error: " . $stmtVisitCount->error;
    }

    $stmt->close();
    $stmtUrl->close();
    $stmtVisitCount->close();
    $conn->close();

    // 跳转到 install_progress.php
    header("Location: step3.php");
    exit();
} else {
    // 如果不是通过表单提交的请求，直接输出错误信息
    die("非法请求");
}
?>
