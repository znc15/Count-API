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
        <title><?php echo $siteName; ?> - 控制面板</title>
        <meta content="<?php echo $siteName; ?> - 控制面板" name="description" />
        <meta content="<?php echo $siteName; ?> - 控制面板" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?php echo $iconimage; ?>">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
        <div id="weeklyRequestCount"></div>
        <script>
        // 将流量值转换为相应单位的函数
        function convertTrafficUnits(trafficValue) {
            if (trafficValue >= 1e12) {
                return (trafficValue / 1e12).toFixed(2) + ' TB';
            } else if (trafficValue >= 1e9) {
                return (trafficValue / 1e9).toFixed(2) + ' GB';
            } else {
                return trafficValue.toFixed(2) + ' MB';
            }
        }

        // 使用Fetch API异步获取数据
        fetch('calculate_traffic.php')
            .then(response => response.json())
            .then(data => {
                // 处理totalRequestCount的数据
                const totalRequestCountElement = document.getElementById('totalRequestCount');
                totalRequestCountElement.innerText = `${data.totalRequestCount[0].total_request_count}`;

                // 处理totalRequestCountToday的数据
                const totalRequestCountTodayElement = document.getElementById('totalRequestCountToday');
                totalRequestCountTodayElement.innerText = `${data.totalRequestCountToday[0].total_request_count_today}`;

                // 处理totalTraffic的数据并进行单位转换
                const totalTrafficElement = document.getElementById('totalTraffic');
                const totalTrafficValue = parseFloat(data.totalTraffic[0].total_traffic);
                totalTrafficElement.innerText = `${convertTrafficUnits(totalTrafficValue)}`;

                // 处理totalTrafficToday的数据并进行单位转换
                const totalTrafficTodayElement = document.getElementById('totalTrafficToday');
                const totalTrafficTodayValue = parseFloat(data.totalTrafficToday[0].total_traffic_today);
                totalTrafficTodayElement.innerText = `${convertTrafficUnits(totalTrafficTodayValue)}`;

                // 处理topDomains的数据并生成表格
                const topDomainsElement = document.getElementById('topDomains');
                const topDomainsData = data.topDomains;

                let topDomainsTableHTML = '<table class="table table-hover">';
                topDomainsTableHTML += '<thead>';
                topDomainsTableHTML += '<tr>';
                topDomainsTableHTML += '<th class="border-top-0">域名</th>';
                topDomainsTableHTML += '<th class="border-top-0">地区</th>';
                topDomainsTableHTML += '<th class="border-top-0">备注</th>';
                topDomainsTableHTML += '<th class="border-top-0">总请求量</th>';
                topDomainsTableHTML += '<th class="border-top-0">总流量</th>';
                topDomainsTableHTML += '</tr>';
                topDomainsTableHTML += '</thead>';
                topDomainsTableHTML += '<tbody>';

                topDomainsData.forEach(domain => {
                    topDomainsTableHTML += '<tr>';
                    topDomainsTableHTML += `<td>${domain.domain}</td>`;
                    topDomainsTableHTML += `<td>${domain.country}</td>`;
                    topDomainsTableHTML += `<td>${domain.remark}</td>`;
                    topDomainsTableHTML += `<td>${domain.total_request_count}</td>`;
                    topDomainsTableHTML += `<td>${convertTrafficUnits(parseFloat(domain.total_traffic))}</td>`;
                    topDomainsTableHTML += '</tr>';
                });

                topDomainsTableHTML += '</tbody>';
                topDomainsTableHTML += '</table>';

                topDomainsElement.innerHTML = topDomainsTableHTML;
    
                // 处理柱状图
                const barChartCanvas = document.getElementById('barChart').getContext('2d');
                const dates = data.weeklyRequestCount.map(entry => entry.visit_date);
                const requestCounts = data.weeklyRequestCount.map(entry => entry.daily_request_count);

                new Chart(barChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: dates,
                        datasets: [{
                            label: '每日请求量',
                            data: requestCounts,
                            backgroundColor: "#5b6be8",
                            borderColor: "#5b6be8",
                            borderWidth: 1,
                            hoverBackgroundColor: "#5b6be8",
                            hoverBorderColor: "#5b6be8",
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true, // 不从 0 开始
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('获取数据时出错：', error));
            </script>
    </head>
    <body class="fixed-left">
        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
        <!-- Begin page -->
        <div id="wrapper">
            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="../home/" class="logo"><i class="<?php echo $titleimage; ?>"></i> <?php echo $siteName; ?></a>
                    </div>
                </div>
                <div class="sidebar-inner slimscrollleft">
                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">主要</li>
                            <li class="active-v">
                                <a href="../home" class="waves-effect" style="color: rgb(91, 107, 232); background-color: rgba(91, 107, 232, 0.15);">
                                    <i class="mdi mdi-airplay"></i>
                                    <span style=" font-weight: 300;"> 首页 </span>
                                </a>
                            </li>
                            <li class="menu-title">设置</li>
                            <li>
                                <a href="../links/" class="waves-effect"><i class="mdi mdi-link-variant"></i><span> 域名 </span></a>
                            </li>
                            <li>
                                <a href="../push/" class="waves-effect"><i class="mdi mdi-radio-tower"></i><span> 推送 </span></a>
                            </li>                                                      
                            <li>
                                <a href="../settings/" class="waves-effect"><i class="mdi mdi-settings"></i><span> 设置 </span></a>
                            </li>
                            <li class="menu-title">版本</li>
                            <li>
                                <a href="https://github.com" class="waves-effect"> <i class="mdi mdi-arrow-up-bold"></i><span> 当前版本：0.1.0-beta </span></a>
                            </li>                                                        
                            </ul>
                        </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>
            <!-- Left Sidebar End -->
            <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <!-- Top Bar Start -->
                    <div class="topbar">
                        <nav class="navbar-custom">
                            <ul class="list-inline float-right mb-0">
                                <!-- language-->
                                <li class="list-inline-item dropdown notification-list hide-phone">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect text-white" href="#">
                                        Chinese/中文 <img src="assets/images/flags/chinese_flag.jpg" class="ml-2" height="16" alt=""/>
                                    </a>
                                </li>

                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="false" aria-expanded="false">
                                       <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $qq_number; ?>&s=100" alt="user" class="rounded-circle">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                        <!-- item-->
                                        <div class="dropdown-item noti-title">
                                            <h5>欢迎，<?php echo $is_admin ? '管理员' : '普通用户'; ?></h5>
                                        </div>
                                        <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> 个人信息</a>
                                        <a class="dropdown-item" href="../settings/"><i class="mdi mdi-settings m-r-5 text-muted"></i> 设置</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"><i class="mdi mdi-logout m-r-5 text-muted"></i> 登出</a>
                                    </div>
                                </li>

                            </ul>
                            <ul class="list-inline menu-left mb-0">
                                <li class="float-left">
                                    <button class="button-menu-mobile open-left waves-light waves-effect">
                                        <i class="mdi mdi-menu"></i>
                                    </button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>

                        </nav>

                    </div>
                    <!-- Top Bar End -->

                    <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="page-title-box">
                                        <div class="btn-group float-right">
                                            <ol class="breadcrumb hide-phone p-0 m-0">
                                                <li class="breadcrumb-item"><a href="#"><?php echo $siteName; ?></a></li>
                                                <li class="breadcrumb-item active">控制面板</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">控制面板</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- end page title end breadcrumb -->

                                    
                            <div class="row">
                                <!-- Column -->
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
                                                        <h5 class="mt-0 round-inner"><div id="totalRequestCountToday"></div></h5>
                                                        <p class="mb-0 text-muted">今日访问</p>                                                                 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
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
                                                        <h5 class="mt-0 round-inner"><div id="totalRequestCount"></div></h5>
                                                        <p class="mb-0 text-muted">总计访问</p>
                                                    </div>
                                                </div>                                                       
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
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
                                                        <h5 class="mt-0 round-inner"><div id="totalTrafficToday"></div></h5>
                                                        <p class="mb-0 text-muted">今日流量</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
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
                                                        <h5 class="mt-0 round-inner"><div id="totalTraffic"></div></h5>
                                                        <p class="mb-0 text-muted">总计流量</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
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
                                    <img class="card-img-top img-fluid" src="https://api.miaomc.cn/image/get?1" alt="Card image cap" style="height: 309px;">
                                        <div class="card-body">
                                            <p class="card-text">用户ID：<?php echo $id; ?></p>
                                            <p class="card-text">用户名：<?php echo $username; ?></p>
                                            <p class="card-text">用户组：<?php echo $is_admin ? '管理员' : '普通用户'; ?></p>
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
                                    <img class="card-img-top img-fluid" src="https://api.miaomc.cn/image/get?2" alt="Card image cap" style="height: 250px;">
                                        <div class="card-body">
                                        <h4 class="card-title font-20 mt-0">公告</h4>
                                            <p class="card-text">公告模板</p>
                                        <a href="#" class="btn btn-primary waves-effect waves-light">查看更多</a>
                                    </div>
                                </div>
                                </div> 
                            </div>                                                                             
                        </div><!-- container -->
                    </div> <!-- Page content Wrapper -->
                </div> <!-- content -->
                <footer class="footer">
                    © 2023 <?php echo $siteName; ?> | Design by Mannatthemes | Power By TCB Work
                </footer>
            </div>
            <!-- End Right content here -->
        </div>
        <!-- END wrapper -->
        <!-- jQuery  -->
        <script>
        // 获取 cookie 值的函数
        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
        }

        // 在你的代码中使用 getCookie 函数
        var token = getCookie('token');

        // 检查 token 是否存在
        if (!token) {
            // 如果存在，执行相应的操作
            // 例如，跳转到 home/index.php
            window.location.href = 'login.php';
        }

        </script>
        <script>
            // 使用异步请求检查 install.lock 文件内容
            fetch('../install/install.lock')
                .then(response => response.text())
                .then(data => {
                    // 判断 install.lock 文件内容是否为 false
                    if (data.trim().toLowerCase() === 'false') {
                        // 跳转到 index.php
                        window.location.href = '../install';
                    }
                })
                .catch(error => {
                    // 处理错误
                    console.error('Error fetching install.lock:', error);
                });
        </script>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="assets/plugins/skycons/skycons.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>       
        <!-- App js -->
        <!-- Chart JS -->
        <script src="assets/plugins/chart.js/chart.min.js"></script>
        <script src="assets/js/app.js"></script>
    </body>
</html>