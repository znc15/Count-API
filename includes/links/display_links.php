<?php
require_once '../../config.php';

function connectToDatabase() {
    global $host, $db_username, $db_password, $database, $port;
    return mysqli_connect($host, $db_username, $db_password, $database, $port);
}

$db_connection = connectToDatabase();

// 获取前台传递的用户令牌
$userToken = $_COOKIE['token'];

// 获取当前页码
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 6;
$offset = ($currentPage - 1) * $recordsPerPage;

// 使用预处理语句执行查询
$query = "SELECT id, url, country, remark
          FROM urls
          WHERE owner = ? LIMIT ?, ?";
$stmt = mysqli_prepare($db_connection, $query);
mysqli_stmt_bind_param($stmt, 'sii', $userToken, $offset, $recordsPerPage);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("数据库查询失败：" . mysqli_error($db_connection));
}

// 处理查询结果
if (mysqli_num_rows($result) > 0) {
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>ID</th><th>URL</th><th>地区</th><th>备注</th><th>操作</th></tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['url']}</td>";
        echo "<td>{$row['country']}</td>";
        echo "<td>{$row['remark']}</td>";
        echo "<td><a href='#' class='btn btn-primary btn-sm view-link-btn' data-url='{$row['url']}'>查看</a> <a href='#' class='btn btn-danger btn-sm delete-link' data-url='{$row['url']}'>删除</a></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    // 输出分页链接
    $totalRecordsQuery = "SELECT COUNT(*) as total FROM urls WHERE owner = ?";
    $stmt = mysqli_prepare($db_connection, $totalRecordsQuery);
    mysqli_stmt_bind_param($stmt, 's', $userToken);
    mysqli_stmt_execute($stmt);
    $totalRecordsResult = mysqli_stmt_get_result($stmt);
    $totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    echo "<div class='pagination mt-4'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='index.php?page=$i' class='page-link'>$i</a>";
    }
    echo "</div>";
} else {
    echo "<p class='text-muted'>暂无监测链接。</p>";
}

// 关闭数据库连接
mysqli_close($db_connection);
?>
