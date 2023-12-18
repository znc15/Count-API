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
require_once "../config.php"; // 替换为实际的 config.php 文件路径
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteName; ?> - 忘记密码</title>
    <!-- 引入Bootstrap 5 CSS文件 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/forget/css/main.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mb-4"><?php echo $siteName; ?> - 忘记密码</h2>
        <!-- 提示信息区域 -->
        <div id="message" class="mb-3"></div>

        <!-- 忘记密码表单 -->
        <form id="forgetPasswordForm">
            <div class="mb-3">
                <label for="email" class="form-label">邮箱地址</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="verificationCode" class="form-label">邮箱验证码</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="verificationCode" name="verificationCode" required>
                    <!-- 邮箱验证码按钮 -->
                    <button type="button" class="btn btn-secondary" id="sendVerificationCode">发送验证码</button>
                </div>
                <small id="cooldownInfo" class="form-text text-muted"></small>
            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label">新密码</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">确认新密码</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>

            <!-- 将按钮样式设置为与修改密码按钮相同 -->
            <button type="submit" class="btn btn-primary">修改密码</button>
            <div class="mt-2">
            想起密码？<a href="login.php">立即登录</a>
        </div>
        </form>
    </div>
    <div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
    </div>

    <!-- 引入Bootstrap 5和Popper.js的JS文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 引入jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- 内联的JavaScript代码 -->
    <script src="/assets/forget/js/main.js"></script>
    <!-- 在登录页面的 JavaScript 部分中添加以下代码 -->
    <script src="/assets/forget/js/cookie/js"></script>

</body>
</html>