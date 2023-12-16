<?php
require_once("../config.php");// 替换为实际的 config.php 文件路径

?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $siteName; ?> - 登录</title>
    <!-- 添加Bootstrap的CSS链接 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- ... （其他样式或链接） -->
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
</head>

<div class="container">
    <h3 class="mb-4"><?php echo $siteName; ?> - 用户登录</h3>
    <form action="login_process.php" method="post">

        <div class="mb-3">
            <label for="usernameOrEmail" class="form-label">用户名或邮箱</label>
            <input type="text" class="form-control" name="usernameOrEmail" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">密码</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="rememberMe">
            <label class="form-check-label" for="rememberMe">记住我</label>
        </div>

        <button type="submit" class="btn btn-primary">登录</button>
        <div class="row mt-3">
            <div class="col-6">
                <a href="register.php" class="btn btn-secondary w-100">注册</a>
            </div>
            <div class="col-6">
                <a href="forget_password.php" class="btn btn-secondary w-100">忘记密码？</a>
            </div>
        </div>
    </form>
</div>

<!-- 密码错误弹窗 -->
<div class="modal fade" id="passwordErrorModal" tabindex="-1" aria-labelledby="passwordErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordErrorModalLabel">错误</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                密码错误，请重新输入。
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<!-- 用户不存在弹窗 -->
<div class="modal fade" id="userNotFoundErrorModal" tabindex="-1" aria-labelledby="userNotFoundErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userNotFoundErrorModalLabel">错误</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                用户不存在，请检查用户名或邮箱。
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<!-- 登录成功 JavaScript -->
    <!-- 引入 jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- 引入 Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        $("form").submit(function (event) {
            // 阻止表单默认提交行为
            event.preventDefault();

            // 获取表单数据
            var formData = $(this).serialize();

            // 发送异步请求
            $.ajax({
                type: "POST",
                url: "login_process.php",
                data: formData,
                success: function (response) {
                    // 根据后台返回的标识显示相应的提示
                    if (response === 'CaptchaError') {
                        $("#captchaErrorModal").modal("show");
                    } else if (response === 'PasswordError') {
                        $("#passwordErrorModal").modal("show");
                    } else if (response === 'UserNotFound') {
                        $("#userNotFoundErrorModal").modal("show");
                    } else if (response === 'Success') {
                        // 登录成功，直接跳转到管理面板
                        window.location.replace("index.php");
                    }
                }
            });
        });

        // 添加模态框完全关闭后的事件处理
        $(".modal").on("hidden.bs.modal", function () {

        });
    });
    </script>
    <script>
    // 获取 cookie 值的函数
    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
    }

    // 在你的代码中使用 getCookie 函数
    var token = getCookie('token');

    // 检查 token 是否存在
    if (token) {
        // 如果存在，执行相应的操作
        // 例如，跳转到 home/index.php
        window.location.href = '../home';
    }

    </script>
</body>
<div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
    </div>
</html>