<?php
require_once "../config.php";
// 获取浏览器 Cookie 中的 token
$token = $_COOKIE['token'] ?? '';
// 创建数据库连接
$conn = new mysqli($host, $db_username, $db_password, $database, $port);
// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
// 执行查询
$sql = "SELECT `id`, `username`, `email`, `token`, `qq_number`, `is_admin`, `reg_date` FROM `users` WHERE `token` = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("错误: " . $conn->error);
}

$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($id, $username, $email, $token, $qq_number, $is_admin, $reg_date);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>
        <?php echo $siteName; ?> - 个人设置
    </title>
    <meta content="<?php echo $siteName; ?> - 个人设置" name="description" />
    <meta content="<?php echo $siteName; ?> - 个人设置" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo $iconimage; ?>">
    <link href="../assets/home/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-toggle@2.2.2/css/bootstrap-toggle.min.css">
</head>

<body class="fixed-left">
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>
    <div id="wrapper">
        <div class="left side-menu">
            <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                <i class="ion-close"></i>
            </button>
            <div class="topbar-left">
                <div class="text-center">
                    <a href="../home/" class="logo"><i class="<?php echo $titleimage; ?>"></i>
                        <?php echo $siteName; ?>
                    </a>
                </div>
            </div>
            <div class="sidebar-inner slimscrollleft">
                <div id="sidebar-menu">
                    <ul>
                        <li class="menu-title">主要</li>
                        <li class="active-v">
                            <a href="../home" class="waves-effect"">
                                    <i class=" mdi mdi-airplay"></i>
                                <span style=" font-weight: 300;"> 首页 </span>
                            </a>
                        </li>
                        <li class="menu-title">设置</li>
                        <li>
                            <a href="../links/" class="waves-effect"><i class="mdi mdi-link-variant"></i><span> 域名
                                </span></a>
                        </li>
                        <li>
                            <a href="../push/" class="waves-effect"><i class="mdi mdi-radio-tower"></i><span> 推送
                                </span></a>
                        </li>
                        <li>
                            <a href="../settings/" class="waves-effect"><i class="mdi mdi-settings"></i><span> 设置
                                </span></a>
                        </li>
                        <li class="menu-title">账号</li>
                        <li>
                            <a id="deleteTokenBtn" class="waves-effect"> <i class="mdi mdi-exit-to-app"></i><span> 退出登录
                                </span></a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="content-page">
            <div class="content">
                <?php
                include_once '../assets/common/nav.php';
                ?>
                <div class="page-content-wrapper ">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <div class="btn-group float-right">
                                        <ol class="breadcrumb hide-phone p-0 m-0">
                                            <li class="breadcrumb-item"><a href="#">
                                                    <?php echo $siteName; ?>
                                                </a></li>
                                            <li class="breadcrumb-item active">推送设置</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">推送设置</h4>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <h2>请求日志</h2>
                            <div class="form-group">
                                <button id="pushButton" class="btn btn-primary"></button>
                            </div>
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <?php
                                    // 包含配置文件
                                    include '../config.php';

                                    // 创建连接
                                    $conn = new mysqli($host, $db_username, $db_password, $database, $port);

                                    // 检查连接
                                    if ($conn->connect_error) {
                                        die("连接失败: " . $conn->connect_error);
                                    }
                                    $userToken = $_COOKIE['token'];

                                    $stmt = $conn->prepare("SELECT COUNT(*) FROM request_log WHERE user_token = ?");
                                    $stmt->bind_param("s", $userToken);
                                    $stmt->execute();

                                    // 获取总记录数
                                    $stmt->bind_result($totalRecords);
                                    $stmt->fetch();
                                    $stmt->close();

                                    // 每页显示的记录数
                                    $recordsPerPage = 5;

                                    // 计算总页数
                                    $totalPages = ceil($totalRecords / $recordsPerPage);

                                    // 获取当前页数，默认为第一页
                                    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

                                    // 计算查询的起始位置
                                    $startFrom = ($currentPage - 1) * $recordsPerPage;

                                    // 查询数据库
                                    $stmt = $conn->prepare("SELECT * FROM request_log WHERE user_token = ? LIMIT ?, ?");
                                    $stmt->bind_param("sii", $userToken, $startFrom, $recordsPerPage);
                                    $stmt->execute();

                                    // 获取结果
                                    $result = $stmt->get_result();

                                    // 输出数据
                                    echo "<table class='table table-bordered'>";
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<td>用户Token</td>";
                                    echo "<td>邮箱</td>";
                                    echo "<td>请求日期</td>";
                                    echo "<td>是否成功</td>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['user_token'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>" . $row['request_date'] . "</td>";
                                            echo "<td>" . ($row['success'] == 1 ? '成功' : '失败') . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>暂无数据</td></tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // 输出分页组件
                                    echo "<div class='container mt-3'>";
                                    echo "<ul class='pagination'>";

                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        echo "<li class='page-item " . ($i == $currentPage ? 'active' : '') . "'>";
                                        echo "<a class='page-link' href='?page=$i'>$i</a>";
                                        echo "</li>";
                                    }

                                    echo "</ul>";
                                    echo "</div>";

                                    // 关闭连接
                                    $stmt->close();
                                    $conn->close();
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- Alert 提示框 -->
                        <div class="alert alert-success alert-dismissible fade" role="alert" id="successAlert">
                            推送设置已成功更新！
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                        include_once '../assets/common/footer.php';
                        ?>
                    </div>
                    <div class="modal fade" id="deleteTokenModal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteTokenModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteTokenModalLabel">注意：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h4>已经退出登录！</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    id="closeModalBtn">关闭</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/common/authlogin.js"></script>
    <script src="../assets/home/assets/js/jquery.min.js"></script>
    <script src="../assets/home/assets/js/popper.min.js"></script>
    <script src="../assets/home/assets/js/bootstrap.min.js"></script>
    <script src="../assets/home/assets/js/modernizr.min.js"></script>
    <script src="../assets/home/assets/js/detect.js"></script>
    <script src="../assets/home/assets/js/fastclick.js"></script>
    <script src="../assets/home/assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/home/assets/js/jquery.blockUI.js"></script>
    <script src="../assets/home/assets/js/waves.js"></script>
    <script src="../assets/home/assets/js/jquery.nicescroll.js"></script>
    <script src="../assets/home/assets/js/jquery.scrollTo.min.js"></script>
    <script src="../assets/home/assets/js/app.js"></script>
    <script src="../assets/common/authlogin.js"></script>
    <script>
        // 添加按钮点击事件处理程序
        document.getElementById('deleteTokenBtn').addEventListener('click', function() {
            // 设置过期时间为过去的时间，即立即删除 Cookie
            document.cookie = 'token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';

            // 显示 Bootstrap Modal
            $('#deleteTokenModal').modal('show');
        });

        // 添加 Modal 关闭按钮点击事件
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            // 刷新页面
            window.location.href = '../index.php';
        });
    </script>
    <script>
        // 初始页面加载时设置按钮状态
        $(document).ready(function () {
            updateButtonState();
        });

        // 当按钮被点击时触发
        $('#pushButton').click(function () {
            // 获取当前按钮状态
            var currentState = ($('#pushButton').attr('data-state') === '1');

            // 发送 AJAX 请求到后端，更新用户的推送设置
            $.ajax({
                url: 'update_push_setting.php', // 后端处理文件的路径
                method: 'POST',
                data: { receiveEmail: currentState ? 0 : 1 }, // 发送的数据，切换状态
                success: function (response) {
                    // 显示修改成功的 Alert 提示框
                    $('#successAlert').addClass('show');

                    // 3秒后隐藏 Alert 提示框
                    setTimeout(function () {
                        $('#successAlert').removeClass('show');
                    }, 3000);

                    // 更新按钮状态
                    updateButtonState();
                },
                error: function (error) {
                    // 处理错误
                    console.error('更新设置失败:', error);
                }
            });
        });

        // 更新按钮状态
        function updateButtonState() {
            // 获取数据库中的状态
            $.ajax({
                url: 'get_push_setting.php', // 后端处理文件的路径，用于获取当前的推送设置
                method: 'GET',
                success: function (response) {
                    // 更新按钮的文本和 data-state 属性
                    if (response === '1') {
                        $('#pushButton').text('开启').attr('data-state', '1');
                    } else {
                        $('#pushButton').text('关闭').attr('data-state', '0');
                    }
                },
                error: function (error) {
                    // 处理错误
                    console.error('获取设置失败:', error);
                }
            });
        }
    </script>
</body>

</html>