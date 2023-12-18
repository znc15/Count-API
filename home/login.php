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
    <link rel="stylesheet" href="../assets/login/css/main.css">
</head>
<body>
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
    <div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · <?php echo $siteName; ?> | Power by TCB Work
        </footer>
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
    <script src="../assets/login/js/main.js"></script>

</body>
</html>