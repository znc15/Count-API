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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .mb-3 {
            margin-bottom: 15px;
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
<script>
    $(document).ready(function() {
        var remainingTime = <?php
            echo isset($_SESSION['lastSentTime'])
                ? max(60 - (time() - $_SESSION['lastSentTime']), 0)
                : 0;
        ?>;
        var intervalId;

        // 检查本地存储中是否有弹窗时间戳
        var popupTime = localStorage.getItem('popupTime');
        if (popupTime) {
            var currentTime = Math.floor(Date.now() / 1000);
            var timeDifference = currentTime - popupTime;

            if (timeDifference < 60 && timeDifference > 0) {
                remainingTime = 60 - timeDifference;

                // 显示剩余等待时间
                updateButtonText();
                intervalId = setInterval(function() {
                    remainingTime--;

                    if (remainingTime <= 0) {
                        clearInterval(intervalId);
                        updateButtonText(); // 还原按钮文字
                    } else {
                        updateButtonText(); // 更新按钮文字
                    }
                }, 1000);
            }

            // 清除本地存储的弹窗时间戳
            localStorage.removeItem('popupTime');
        } else if (remainingTime > 0) {
            // 如果没有弹窗时间戳但 remainingTime 大于 0，表示不是第一次加载页面
            // 显示剩余等待时间
            updateButtonText();
            intervalId = setInterval(function() {
                remainingTime--;

                if (remainingTime <= 0) {
                    clearInterval(intervalId);
                    updateButtonText(); // 还原按钮文字
                } else {
                    updateButtonText(); // 更新按钮文字
                }
            }, 1000);
        }

        // 获取验证码
        $("#generateCode").click(function() {
            if (remainingTime > 0) {
                alert("请等待 " + remainingTime + " 秒后再次获取验证码。");
                return;
            }

            var email = $("input[name='email']").val();

            // 记录弹窗时间戳到 localStorage
            localStorage.setItem('popupTime', Math.floor(Date.now() / 1000));

            // 在这里调用后台接口发送邮件，包含生成的验证码
            // 这里使用ajax请求模拟，实际中需要通过后台发送邮件
            $.ajax({
                url: 'send_verification_code.php', // 替换为实际的后台处理文件
                type: 'POST',
                data: { email: email },
                success: function(response) {
                    alert(response);

                    // 清除 localStorage 中的弹窗时间戳
                    localStorage.removeItem('popupTime');

                    // 显示倒计时
                    remainingTime = 60; // 设置等待时间为60秒
                    updateButtonText();

                    intervalId = setInterval(function() {
                        remainingTime--;

                        if (remainingTime <= 0) {
                            clearInterval(intervalId);
                            updateButtonText(); // 还原按钮文字
                        } else {
                            updateButtonText(); // 更新按钮文字
                        }
                    }, 1000);
                },
                error: function(error) {
                    alert('验证码发送失败');
                }
            });

            // 更新按钮文字
            function updateButtonText() {
                if (remainingTime > 0) {
                    $("#generateCode").text("获取验证码");
                    $("#generateCode").prop("disabled", true); // 禁用按钮
                    $("#remainingTimeInfo").text("还需等待 " + remainingTime + " 秒");
                } else {
                    $("#generateCode").text("获取验证码");
                    $("#generateCode").prop("disabled", false); // 启用按钮
                    $("#remainingTimeInfo").text(""); // 清空剩余等待时间
                }
            }
        });

        // 密码确认
        $("#confirmPassword").on('input', function() {
            var password = $("#password").val();
            var confirmPassword = $(this).val();

            if (password !== confirmPassword) {
                $(this).get(0).setCustomValidity('密码不匹配');
            } else {
                $(this).get(0).setCustomValidity('');
            }
        });
    });
</script>

</body>
<div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
    </div>
</html>
