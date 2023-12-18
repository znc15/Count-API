<?php
require_once '../../config.php';

function getLinkInfo($db_connection, $url) {
    $url = mysqli_real_escape_string($db_connection, $url);

    // 查询链接信息
    $query = "SELECT url, created_at FROM urls WHERE url = '$url'";
    $result = mysqli_query($db_connection, $query);

    if (!$result) {
        return ['error' => '查询链接信息失败: ' . mysqli_error($db_connection)];
    }

    if (mysqli_num_rows($result) > 0) {
        $linkInfo = mysqli_fetch_assoc($result);

        // 获取urltoken
        $urltokenQuery = "SELECT urltoken FROM urls WHERE url = '$url'";
        $urltokenResult = mysqli_query($db_connection, $urltokenQuery);

        if ($urltokenResult) {
            $urltoken = mysqli_fetch_assoc($urltokenResult)['urltoken'];

            // 获取当前时间
            $now = time();

            // 计算当天访问量
            $todayDate = date('Y-m-d', $now);
            $todayVisitCount = 0;

            // 查询 visitcount 表中当天的访问量
            $visitQuery = "SELECT request_count FROM visitcount WHERE urltoken IN (SELECT urltoken FROM urls WHERE url = '$url') AND DATE(visit_time) = '$todayDate'";
            $visitResult = mysqli_query($db_connection, $visitQuery);

            if ($visitResult) {
                while ($row = mysqli_fetch_assoc($visitResult)) {
                    $todayVisitCount += $row['request_count'];
                }
            } else {
                return ['error' => '查询当天访问量失败: ' . mysqli_error($db_connection)];
            }

            // 查询 visitcount 表中总的访问量
            $totalQuery = "SELECT SUM(request_count) AS totalVisitCount FROM visitcount WHERE urltoken IN (SELECT urltoken FROM urls WHERE url = '$url')";
            $totalResult = mysqli_query($db_connection, $totalQuery);

            if ($totalResult) {
                $totalVisitCount = mysqli_fetch_assoc($totalResult)['totalVisitCount'];

                // 返回 JSON 格式的链接信息
                return [
                    'url' => $linkInfo['url'],
                    'urltoken' => $urltoken,  // 使用从数据库获取的urltoken
                    'totalVisitCount' => $totalVisitCount,
                    'todayVisitCount' => $todayVisitCount,
                    'createTime' => $linkInfo['created_at']
                ];
            } else {
                return ['error' => '查询总访问量失败: ' . mysqli_error($db_connection)];
            }
        } else {
            return ['error' => '查询urltoken失败: ' . mysqli_error($db_connection)];
        }
    } else {
        return ['error' => '未找到匹配的链接'];
    }
}

// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    $db_connection = mysqli_connect($host, $db_username, $db_password, $database, $port);

    if (!$db_connection) {
        echo json_encode(['error' => '数据库连接失败: ' . mysqli_connect_error()]);
    } else {
        $linkInfo = getLinkInfo($db_connection, $url);
        echo json_encode($linkInfo);
    }
} else {
    echo json_encode(['error' => '无效的请求']);
}
?>
