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
    <title><?php echo $siteName; ?> - 忘记密码</title>
    <!-- 引入Bootstrap 5 CSS文件 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        form {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            width: 100%;
        }
        footer {
            text-align: center;
            padding: 15px;
            bottom: 0;
            width: 100%;
            background-color: transparent;
        }
        .links {
            text-align: center;
            margin-top: 9px;
        }

        .links a {
            display: inline-block;
            width: 49.361%;
        }
</style>
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

    <!-- 引入Bootstrap 5和Popper.js的JS文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 引入jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- 内联的JavaScript代码 -->
    <script>
        $(document).ready(function () {
            var cooldownTime = 60; // 冷却时间，单位：秒
            var cooldownInterval;
            var cooldownTimestamp;

            // 从本地存储中获取冷却时间戳
            var storedCooldownTimestamp = localStorage.getItem('cooldownTimestamp');
            if (storedCooldownTimestamp) {
                cooldownTimestamp = parseInt(storedCooldownTimestamp, 10);
                var remainingTime = cooldownTimestamp - Math.floor(Date.now() / 1000);

                if (remainingTime > 0) {
                    // 如果剩余时间大于0，则继续冷却
                    disableSendButton();
                    startCooldownTimer();
                }
            }

            // 邮箱验证码按钮点击事件
            $('#sendVerificationCode').click(function () {
                // 获取邮箱地址
                var email = $('#email').val();

                // 发送请求获取验证码
                $.ajax({
                    type: 'POST',
                    url: 'send_verification_code.php', // 替换为发送验证码的PHP文件路径
                    data: { email: email },
                    success: function (response) {
                        // 显示服务器返回的消息
                        $('#message').html('<div class="alert alert-info">' + response + '</div>');

                        // 设置冷却时间戳
                        cooldownTimestamp = Math.floor(Date.now() / 1000) + cooldownTime;

                        // 存储冷却时间戳到本地存储
                        localStorage.setItem('cooldownTimestamp', cooldownTimestamp);

                        // 禁用按钮并开始冷却倒计时
                        disableSendButton();
                        startCooldownTimer();
                    },
                    error: function () {
                        // 显示验证码发送失败的提示
                        $('#message').html('<div class="alert alert-danger">验证码发送失败，请稍后再试。</div>');
                    }
                });
            });

            // 监听表单提交事件
            $('#forgetPasswordForm').submit(function (event) {
                event.preventDefault(); // 阻止表单默认提交行为

                // 获取表单数据
                var formData = $(this).serialize();

                // 使用Ajax提交表单数据到服务器
                $.ajax({
                    type: 'POST',
                    url: 'forget_password_process.php', // 替换为处理密码重置的PHP文件路径
                    data: formData,
                    success: function (response) {
                        // 显示服务器返回的消息
                        $('#message').html('<div class="alert alert-info">' + response + '</div>');
                    }
                });
            });

            function disableSendButton() {
                // 将按钮样式设置为与修改密码按钮相同
                $('#sendVerificationCode').removeClass('btn-secondary').addClass('btn-secondary').prop('disabled', true);
            }

            function enableSendButton() {
                // 将按钮样式设置回原样
                $('#sendVerificationCode').removeClass('btn-secondary').addClass('btn-secondary').prop('disabled', false);
            }

            function startCooldownTimer() {
                // 设置定时器，每秒更新一次冷却时间
                cooldownInterval = setInterval(function () {
                    var remainingTime = cooldownTimestamp - Math.floor(Date.now() / 1000);

                    if (remainingTime <= 0) {
                        // 冷却时间结束，启用按钮，清除定时器
                        enableSendButton();
                        clearInterval(cooldownInterval);
                        $('#cooldownInfo').html(''); // 清空冷却时间显示
                        // 清除本地存储中的冷却时间戳
                        localStorage.removeItem('cooldownTimestamp');
                    } else {
                        // 更新冷却时间显示
                        $('#cooldownInfo').html('还需等待: ' + remainingTime + ' 秒');
                    }
                }, 1000);
            }
        });
    </script>
    <!-- 在登录页面的 JavaScript 部分中添加以下代码 -->
<script>
    $(document).ready(function () {
        // 检查是否存在 token
        var token = getCookie("token");

        if (token) {
            // 如果存在 token，直接跳转到 home/index.php
            window.location.href = "home/index.php";
        }
    });

    // 获取指定名称的 cookie 值
    function getCookie(name) {
        var cookies = document.cookie.split(";");

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();

            // 检查是否以指定名称开头
            if (cookie.startsWith(name + "=")) {
                return cookie.substring(name.length + 1);
            }
        }

        return null;
    }
</script>
</body>
<div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
    </div>
</html>