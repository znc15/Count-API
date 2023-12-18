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
require_once("../config.php");// 替换为实际的 config.php 文件路径
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName; ?> - 用户注册</title>
    <!-- 引入 Bootstrap 样式 -->
    <link href="/assets/register/css/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h3 class="mb-4"><?php echo $siteName; ?> - 用户注册</h3>
        <form action="register_process.php" method="post">

            <div class="mb-3">
                <label for="username" class="form-label">用户名</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">邮箱</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="qq" class="form-label">QQ 号</label>
                <input type="text" class="form-control" name="qq" required>
            </div>
            <div class="mb-3">
                <label for="verificationCode" class="form-label">验证码</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="verificationCode" required>
                    <button type="button" class="btn btn-secondary" id="generateCode">获取验证码</button>
                </div>
                <small id="remainingTimeInfo" class="form-text text-muted"></small>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">密码</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">确认密码</label>
                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
            </div>

            <button type="submit" class="btn btn-primary">注册</button>
            <div class="mt-2">
                已有账户？<a href="login.php">立即登录</a>
            </div>
        </form>
    </div>
    <div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/assets/register/js/main.php"></script>

</body>
</html>
