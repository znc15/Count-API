<?php
require_once '../config.php';
require_once '../vendor/geoip2.phar';

use GeoIp2\Database\Reader;

// 获取数据库连接
$db_connection = mysqli_connect($host, $db_username, $db_password, $database, $port);

// 检查连接是否成功
if (!$db_connection) {
    die(json_encode(['error' => '数据库连接失败：' . mysqli_connect_error()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 这里添加处理添加链接的逻辑，包括生成监测脚本文件、将链接信息存入数据库等
    // ...

    // 示例：将链接信息存入数据库（请根据实际情况修改）
    $url = $_POST['url'];
    $remark = $_POST['remark'];
    $region = $_POST['region'];

    // 获取当前用户的 Token（请根据实际情况修改）
    $userToken = $_COOKIE['token'];

    // 检查是否已存在相同的链接
    $checkDuplicateQuery = "SELECT COUNT(*) as count FROM urls WHERE url = '$url'";
    $duplicateResult = mysqli_query($db_connection, $checkDuplicateQuery);

    if ($duplicateResult) {
        $duplicateData = mysqli_fetch_assoc($duplicateResult);
        $duplicateCount = $duplicateData['count'];

        if ($duplicateCount > 0) {
            // 返回 JSON 响应，表示链接已存在
            echo json_encode(['error' => '该链接已存在，无法创建重复的监测链接。']);
            mysqli_close($db_connection);
            exit();
        }
    } else {
        // 返回 JSON 响应，表示检查重复链接时发生错误
        echo json_encode(['error' => '检查重复链接时发生错误：' . mysqli_error($db_connection)]);
        mysqli_close($db_connection);
        exit();
    }

    // 如果没有重复链接，继续执行插入操作
    $urlToken = uniqid();
    $query = "INSERT INTO urls (url, country, remark, created_at, owner, urltoken) 
            VALUES ('$url', '$region', '$remark', NOW(), '$userToken', '$urlToken')";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        // 生成监测脚本文件（存放在 storage 目录下，请根据实际情况修改）
        $scriptContent = "var urlToken = '$urlToken';\n";
        $scriptContent .= "// 在这里添加你的监测脚本内容\n";
        $scriptContent .= "\n";
        $scriptContent .= "// 通知服务器脚本已经被访问\n";
        $scriptContent .= "var xhr = new XMLHttpRequest();\n";
        $scriptContent .= "xhr.open(\"GET\", \"$siteUrl/links/record_visit.php?token=\" + urlToken, true);\n";
        $scriptContent .= "xhr.send();";

        $scriptFilename = "../storage/monitor_script_$urlToken.js";

        // 确保目录存在，如果不存在则创建
        if (!is_dir("../storage")) {
            mkdir("../storage", 0755, true);
        }

        // 写入文件
        $fileHandle = fopen($scriptFilename, 'w');

        if ($fileHandle) {
            if (fwrite($fileHandle, $scriptContent) !== false) {
                fclose($fileHandle);
                echo json_encode(['success' => true]);
                exit();
            } else {
                fclose($fileHandle);
                echo json_encode(['error' => '写入文件失败，请检查目录权限！']);
                exit();
            }
        } else {
            echo json_encode(['error' => '无法打开文件进行写入，请检查目录权限！']);
            exit();
        }

        // 记录访问信息
        recordVisitLog($urlToken);
    } else {
        echo json_encode(['error' => '数据库插入失败：' . mysqli_error($db_connection)]);
        exit();
    }

    // 关闭数据库连接
    mysqli_close($db_connection);

} else {
    // 处理访问 JS 文件的请求
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        // 记录 JS 文件访问信息
        recordVisitLog($token);

        // 返回 JS 文件内容
        header('Content-Type: application/javascript');
        echo "// 在这里添加你的监测脚本内容\n";
        echo "\n";
        echo "// 通知服务器脚本已经被访问\n";
        echo "var xhr = new XMLHttpRequest();\n";
        echo "xhr.open(\"GET\", \"$siteUrl/links/record_visit.php?token=$token\", true);\n";
        echo "xhr.send();";
    } else {
        echo json_encode(['error' => '无效的请求']);
        exit();
    }
}

// 记录访问信息的函数
function recordVisitLog($urlToken)
{
    global $db_connection;

    // 获取访问者的 IP 地址
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // 获取 IP 所属地区信息
    $region = getRegionByIpAddress($ipAddress);

    // 获取当前日期
    $currentDate = date('Y-m-d');

    // 查询是否已有该 IP 的访问记录
    $query = "SELECT id, request_count, DATE(visit_time) as visit_date 
              FROM visitcount 
              WHERE urltoken = '$urlToken' AND ip = '$ipAddress' 
              ORDER BY visit_time DESC LIMIT 1";

    $result = mysqli_query($db_connection, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // 如果存在记录
            $row = mysqli_fetch_assoc($result);
            $lastVisitDate = $row['visit_date'];

            if ($lastVisitDate != $currentDate) {
                // 如果最后一次访问日期不是今天，创建新的记录
                $insertQuery = "INSERT INTO visitcount (urltoken, ip, region, visit_time, request_count) 
                                VALUES ('$urlToken', '$ipAddress', '$region', NOW(), 1)";
                mysqli_query($db_connection, $insertQuery);
            } else {
                // 如果最后一次访问日期是今天，更新访问次数
                $requestCount = $row['request_count'] + 1;
                $visitId = $row['id'];
                $updateQuery = "UPDATE visitcount SET request_count = $requestCount WHERE id = $visitId";
                mysqli_query($db_connection, $updateQuery);
            }
        } else {
            // 如果不存在记录，插入新的记录
            $insertQuery = "INSERT INTO visitcount (urltoken, ip, region, visit_time, request_count) 
                            VALUES ('$urlToken', '$ipAddress', '$region', NOW(), 1)";
            mysqli_query($db_connection, $insertQuery);
        }
    }

    // 关闭结果集
    mysqli_free_result($result);
}

// 获取 IP 地址所属地区信息
function getRegionByIpAddress($ipAddress)
{
    // 使用 GeoIP2 Phar 文件进行查询
    $reader = new Reader('../includes/GeoIP2-Country.mmdb');  // 替换为正确的路径

    try {
        $record = $reader->country($ipAddress);
        return $record->country->isoCode;
    } catch (Exception $e) {
        // 处理异常
        return 'Unknown';
    }
}
?>
