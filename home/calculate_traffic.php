<?php
// 导入数据库配置
require_once '../config.php';

// 获取从浏览器中获取的token（从 Cookie 中获取）
$browserToken = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

// 连接数据库
$db_connection = mysqli_connect($host, $db_username, $db_password, $database, $port);

// 检查数据库连接是否成功
if (!$db_connection) {
    die('数据库连接失败: ' . mysqli_connect_error());
}

// 执行第一个查询
$query1 = "SELECT u.token, SUM(vc.request_count) AS total_request_count
           FROM users u
           JOIN urls ON u.token = urls.owner
           LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken
           WHERE u.token = '$browserToken'
           GROUP BY u.token";

$result1 = mysqli_query($db_connection, $query1);

// 检查第一个查询是否成功
if (!$result1) {
    die('第一个查询失败: ' . mysqli_error($db_connection));
}

// 处理第一个查询结果
$data1 = array();

if (mysqli_num_rows($result1) > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        unset($row1['token']); // 删除 token 字段
        $data1[] = $row1;
    }
} else {
    // 如果没有数据，输出0
    $data1[] = ['total_request_count' => 0];
}

// 执行第二个查询
$query2 = "SELECT u.token, SUM(vc.request_count) AS total_request_count_today
           FROM users u
           JOIN urls ON u.token = urls.owner
           LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken
           WHERE DATE(vc.visit_time) = CURDATE()
           GROUP BY u.token";

$result2 = mysqli_query($db_connection, $query2);

// 处理第二个查询结果
$data2 = array();

if (mysqli_num_rows($result2) > 0) {
    while ($row2 = mysqli_fetch_assoc($result2)) {
        unset($row2['token']); // 删除 token 字段
        $data2[] = $row2;
    }
} else {
    // 如果没有数据，输出0
    $data2[] = ['total_request_count_today' => 0];
}

// 执行第三个查询
$query3 = "SELECT u.token, SUM(vc.request_count * 1.2) AS total_traffic
           FROM users u
           JOIN urls ON u.token = urls.owner
           LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken
           GROUP BY u.token";

$result3 = mysqli_query($db_connection, $query3);

// 处理第三个查询结果
$data3 = array();

if (mysqli_num_rows($result3) > 0) {
    while ($row3 = mysqli_fetch_assoc($result3)) {
        unset($row3['token']); // 删除 token 字段
        $data3[] = $row3;
    }
} else {
    // 如果没有数据，输出0
    $data3[] = ['total_traffic' => 0];
}

// 执行第四个查询
$query4 = "SELECT u.token, SUM(vc.request_count * 1.2) AS total_traffic_today
           FROM users u
           JOIN urls ON u.token = urls.owner
           LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken
           WHERE DATE(vc.visit_time) = CURDATE()
           GROUP BY u.token";

$result4 = mysqli_query($db_connection, $query4);

// 处理第四个查询结果
$data4 = array();

if (mysqli_num_rows($result4) > 0) {
    while ($row4 = mysqli_fetch_assoc($result4)) {
        unset($row4['token']); // 删除 token 字段
        $data4[] = $row4;
    }
} else {
    // 如果没有数据，输出0
    $data4[] = ['total_traffic_today' => 0];
}

// 执行第五个查询
$query5 = "SELECT u.token, SUM(vc.request_count) AS total_request_count_yesterday
           FROM users u
           JOIN urls ON u.token = urls.owner
           LEFT JOIN visitcount vc ON urls.urltoken = vc.urltoken
           WHERE u.token = '$browserToken'
           AND DATE(vc.visit_time) = CURDATE() - INTERVAL 1 DAY
           GROUP BY u.token";

$result5 = mysqli_query($db_connection, $query5);

// 检查第五个查询是否成功
if (!$result5) {
    die('第五个查询失败: ' . mysqli_error($db_connection));
}

// 处理第五个查询结果
$data5 = array();

if (mysqli_num_rows($result5) > 0) {
    while ($row5 = mysqli_fetch_assoc($result5)) {
        unset($row5['token']); // 删除 token 字段
        $data5[] = $row5;
    }
} else {
    // 如果没有数据，输出0
    $data5[] = ['total_request_count_yesterday' => 0];
}

// 执行第六个查询
$query6 = "SELECT DATE(vc.visit_time) AS visit_date, SUM(vc.request_count) AS daily_request_count
           FROM visitcount vc
           JOIN urls ON vc.urltoken = urls.urltoken
           JOIN users ON urls.owner = users.token
           WHERE users.token = '$browserToken'
           AND vc.visit_time >= CURDATE() - INTERVAL 7 DAY
           GROUP BY DATE(vc.visit_time)";

$result6 = mysqli_query($db_connection, $query6);

// 处理第六个查询结果
$data6 = array();

if (mysqli_num_rows($result6) > 0) {
    while ($row6 = mysqli_fetch_assoc($result6)) {
        $data6[] = $row6;
    }
} else {
    // 如果没有数据，输出0
    $data6[] = ['visit_date' => 0, 'daily_request_count' => 0];
}

// 执行第七个查询
$query7 = "SELECT
               SUBSTRING_INDEX(SUBSTRING_INDEX(urls.url, '/', 3), '//', -1) AS domain,
               urls.country,
               urls.remark,
               SUM(vc.request_count) AS total_request_count,
               SUM(vc.request_count * 1.2) AS total_traffic
           FROM visitcount vc
           JOIN urls ON vc.urltoken = urls.urltoken
           JOIN users ON urls.owner = users.token
           WHERE users.token = '$browserToken'
           GROUP BY domain, urls.country, urls.remark
           ORDER BY total_request_count DESC
           LIMIT 5";

$result7 = mysqli_query($db_connection, $query7);

// 处理第七个查询结果
$data7 = array();

if (mysqli_num_rows($result7) > 0) {
    while ($row7 = mysqli_fetch_assoc($result7)) {
        $data7[] = $row7;
    }
} else {
    // 如果没有数据，输出0
    $data7[] = ['domain' => 0, 'country' => 0, 'remark' => 0, 'total_request_count' => 0, 'total_traffic' => 0];
}


// 整合查询结果
$output = array(
    'totalRequestCount' => $data1,
    'totalRequestCountToday' => $data2,
    'totalTraffic' => $data3,
    'totalTrafficToday' => $data4,
    'totalRequestCountYesterday' => $data5,
    'weeklyRequestCount' => $data6,
    'topDomains' => $data7
);

// 输出整合结果
echo json_encode($output);

// 关闭数据库连接
mysqli_close($db_connection);
?>
