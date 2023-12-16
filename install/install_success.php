<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>安装成功</title>
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
            margin-top: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-5">安装成功</h2>
        <p class="text-center">您的网站已成功安装，请点击下面的按钮进入网站。</p>
        <div class="text-center mt-3">
            <a class="btn btn-primary" href="../">进入网站</a>
        </div>
    </div>

    <!-- 底部的 footer -->
    <footer class="text-muted">
        <p style="margin: 0;padding: 15px;">Copyright © 2023 · LittleSheep | Power by TCB Work</p>
    </footer>

    <!-- 添加Bootstrap的JS链接，确保在body结束前引入 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ... （其他脚本或链接） -->
</body>

</html>
