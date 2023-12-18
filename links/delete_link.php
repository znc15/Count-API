<?php
require_once '../config.php';

// 创建数据库连接
$db_connection = mysqli_connect($host, $db_username, $db_password, $database, $port);

// 检查连接是否成功
if (!$db_connection) {
    die("数据库连接失败：" . mysqli_connect_error());
}

// 接收通过 AJAX 发送的 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取要删除的 URL
    $urlToDelete = $_POST['url'];

    // 使用参数化查询防止 SQL 注入
    $urlTokenQuery = "SELECT urltoken FROM urls WHERE url = ?";
    $visitCountDeleteQuery = "DELETE FROM visitcount WHERE urltoken = ?";

    // 准备语句
    $urlTokenStmt = mysqli_prepare($db_connection, $urlTokenQuery);
    $visitCountStmt = mysqli_prepare($db_connection, $visitCountDeleteQuery);

    // 绑定参数
    mysqli_stmt_bind_param($urlTokenStmt, "s", $urlToDelete);
    mysqli_stmt_bind_param($visitCountStmt, "s", $urlToken);

    // 执行 URL Token 查询语句
    mysqli_stmt_execute($urlTokenStmt);

    // 绑定结果变量
    mysqli_stmt_bind_result($urlTokenStmt, $urlToken);

    // 获取 URL Token
    mysqli_stmt_fetch($urlTokenStmt);

    // 关闭 URL Token 查询语句
    mysqli_stmt_close($urlTokenStmt);

    // 如果获取到了 URL Token，则执行 VisitCount 删除语句
    if ($urlToken) {
        // 执行 VisitCount 删除语句
        mysqli_stmt_execute($visitCountStmt);

        // 关闭 VisitCount 删除语句
        mysqli_stmt_close($visitCountStmt);

        // 执行 URLs 删除语句
        $urlDeleteQuery = "DELETE FROM urls WHERE url = ?";
        $urlDeleteStmt = mysqli_prepare($db_connection, $urlDeleteQuery);
        mysqli_stmt_bind_param($urlDeleteStmt, "s", $urlToDelete);
        $urlSuccess = mysqli_stmt_execute($urlDeleteStmt);

        // 关闭 URLs 删除语句
        mysqli_stmt_close($urlDeleteStmt);

        // 删除存储文件夹下的 monitor_script_urltoken.js 文件
        $fileToDelete = "../storage/monitor_script_$urlToken.js";
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        // 返回成功或失败的信息
        if ($urlSuccess) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($db_connection)]);
        }
    } else {
        // 如果未获取到 URL Token，返回错误信息
        echo json_encode(['success' => false, 'error' => 'URL not found']);
    }

    // 关闭数据库连接
    mysqli_close($db_connection);
} else {
    // 如果不是 POST 请求，返回错误信息
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
