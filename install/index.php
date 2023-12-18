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

// 处理用户点击同意后的跳转
if (isset($_GET['agree'])) {
    $step = $_GET['agree'];
    // 检查是否已经安装，在每一步都检查
    if (!file_exists("install.lock")) {
        // 将安装状态写入 install.lock 文件
        file_put_contents("install.lock", "true");
    }
    header("Location: ?step={$step}");
    exit();
}

// 包含 Parsedown 库
require_once('../includes/Parsedown.php');

// 读取 user.md 文件的内容
$userMdContent = file_get_contents('./user.md');

// 创建 Parsedown 实例
$parsedown = new Parsedown();

// 将 Markdown 内容解析为 HTML
$htmlContent = $parsedown->text($userMdContent);

// 检查当前步骤
$currentStep = isset($_GET['step']) ? $_GET['step'] : 1;
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安装向导</title>
    <!-- 添加Bootstrap的CSS链接 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <!-- wrapper-top 用于包裹上面的内容 -->
    <div class="wrapper-top">
        <div class="container">
            <header class="text-center mt-3">
                <h1>欢迎安装 Count API</h1>
                <p>请按照以下步骤完成安装过程</p>
            </header>
            <main class="mt-4">
                <div class="tab-content mt-4">
                    <!-- 步骤 1 -->
                    <div class="tab-pane fade <?php echo $currentStep == 1 ? 'show active' : ''; ?>" id="step1" role="tabpanel">
                        <h2>同意用户协议</h2>
                        <div class="user-agreement">
                            <?php echo $htmlContent; ?>
                        </div>
                        <a class="btn btn-primary mt-3" href="../install/step2.php">接受</a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- wrapper-bottom 位于页面底部 -->
    <div class="wrapper-bottom">
        <footer class="text-muted">
            Copyright © 2023 · LittleSheep | Power by TCB Work
        </footer>
    </div>

    <!-- 添加Bootstrap的JS链接，确保在body结束前引入 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
