<?php
require_once("../config.php");
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
//错误
if ($stmt === false) {
    die("错误: " . $conn->error);
}
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($id, $username, $email, $token, $qq_number, $is_admin, $reg_date);
$stmt->fetch();
// 关闭语句和连接
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
        <?php echo $siteName; ?> - 控制面板
    </title>
    <meta content="<?php echo $siteName; ?> - 控制面板" name="description" />
    <meta content="<?php echo $siteName; ?> - 控制面板" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo $iconimage; ?>">
    <link href="../assets/home/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/style.css" rel="stylesheet" type="text/css">
    <div id="weeklyRequestCount"></div>
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
                            <a href="../home" class="waves-effect"
                                style="color: rgb(91, 107, 232); background-color: rgba(91, 107, 232, 0.15);">
                                <i class="mdi mdi-airplay"></i>
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
                            <a id="deleteTokenBtn" class="waves-effect"> <i class="mdi mdi-exit-to-app"></i><span> 退出登录 </span></a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="content-page">
            <div class="content">
            <?php
                include_once('../assets/common/nav.php');
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
                                            <li class="breadcrumb-item active">控制面板</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">控制面板</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xl-3">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-3 align-self-center">
                                                <div class="round">
                                                    <i class="mdi mdi-webcam"></i>
                                                </div>
                                            </div>
                                            <div class="col-6 align-self-center text-center">
                                                <div class="m-l-10">
                                                    <h5 class="mt-0 round-inner">
                                                        <div id="totalRequestCountToday"></div>
                                                    </h5>
                                                    <p class="mb-0 text-muted">今日访问</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-3">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-3 align-self-center">
                                                <div class="round">
                                                    <i class="mdi mdi-account-multiple-plus"></i>
                                                </div>
                                            </div>
                                            <div class="col-6 text-center align-self-center">
                                                <div class="m-l-10 ">
                                                    <h5 class="mt-0 round-inner">
                                                        <div id="totalRequestCount"></div>
                                                    </h5>
                                                    <p class="mb-0 text-muted">总计访问</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-3">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-3 align-self-center">
                                                <div class="round ">
                                                    <i class="mdi mdi-basket"></i>
                                                </div>
                                            </div>
                                            <div class="col-6 align-self-center text-center">
                                                <div class="m-l-10 ">
                                                    <h5 class="mt-0 round-inner">
                                                        <div id="totalTrafficToday"></div>
                                                    </h5>
                                                    <p class="mb-0 text-muted">今日流量</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-3">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="col-3 align-self-center">
                                                <div class="round">
                                                    <i class="mdi mdi-rocket"></i>
                                                </div>
                                            </div>
                                            <div class="col-6 align-self-center text-center">
                                                <div class="m-l-10">
                                                    <h5 class="mt-0 round-inner">
                                                        <div id="totalTraffic"></div>
                                                    </h5>
                                                    <p class="mb-0 text-muted">总计流量</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-8">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <h5 class="header-title pb-3 mt-0">统计</h5>
                                        <div style="width: 80%; margin: auto;">
                                            <canvas id="barChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-4">
                                <div class="card m-b-30">
                                    <img class="card-img-top img-fluid" src="https://api.miaomc.cn/image/get?1"
                                        alt="Card image cap" style="height: 309px;">
                                    <div class="card-body">
                                        <p class="card-text">用户ID：
                                            <?php echo $id; ?>
                                        </p>
                                        <p class="card-text">用户名：
                                            <?php echo $username; ?>
                                        </p>
                                        <p class="card-text">用户组：
                                            <?php echo $is_admin ? '管理员' : '普通用户'; ?>
                                        </p>
                                        <a href="#" class="btn btn-primary waves-effect waves-light">查看更多</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xl-8 align-self-center">
                                <div class="card bg-white m-b-30">
                                    <div class="card-body new-user">
                                        <h5 class="header-title mb-4 mt-0">域名请求量（Top5）</h5>
                                        <div class="table-responsive">
                                            <div id="topDomains"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-4">
                                <div class="card m-b-30">
                                    <img class="card-img-top img-fluid" src="https://api.miaomc.cn/image/get?2"
                                        alt="Card image cap" style="height: 250px;">
                                    <div class="card-body">
                                        <h4 class="card-title font-20 mt-0">公告</h4>
                                        <p class="card-text">公告模板</p>
                                        <a href="#" class="btn btn-primary waves-effect waves-light">查看更多</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="deleteTokenModal" tabindex="-1" role="dialog" aria-labelledby="deleteTokenModalLabel" aria-hidden="true">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModalBtn">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <?php
            include_once('../assets/common/footer.php');
            ?>
        </div>
    </div>   
    <script src="../assets/home/assets/js/jquery.min.js"></script>
    <script src="../assets/home/assets/js/popper.min.js"></script>
    <script src="../assets/home/assets/js/bootstrap.min.js"></script>
    <script src="../assets/home/assets/js/modernizr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/home/assets/js/detect.js"></script>
    <script src="../assets/home/assets/js/fastclick.js"></script>
    <script src="../assets/home/assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/home/assets/js/jquery.blockUI.js"></script>
    <script src="../assets/home/assets/js/waves.js"></script>
    <script src="../assets/home/assets/js/jquery.nicescroll.js"></script>
    <script src="../assets/home/assets/js/jquery.scrollTo.min.js"></script>
    <script src="../assets/home/assets/plugins/skycons/skycons.min.js"></script>
    <script src="../assets/home/assets/plugins/raphael/raphael-min.js"></script>
    <!-- App js -->
    <!-- Chart JS -->
    <script src="../assets/home/assets/js/app.js"></script>
    <script src="../assets/home/assets/js/main.js"></script>
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
        location.reload();
    });
    </script>

</body>

</html>