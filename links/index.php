<?php
require_once '../config.php';

// 连接数据库
function connectToDatabase()
{
    global $host, $db_username, $db_password, $database, $port;
    return mysqli_connect($host, $db_username, $db_password, $database, $port);
}

$db_connection = connectToDatabase();

// 获取浏览器 Cookie 中的 token
$userToken = $_COOKIE['token'] ?? '';

// 执行查询用户信息的预处理语句
$userQuery = "SELECT `id`, `username`, `email`, `token`, `qq_number`, `is_admin`, `reg_date` FROM `users` WHERE `token` = ?";
$userStmt = mysqli_prepare($db_connection, $userQuery);
mysqli_stmt_bind_param($userStmt, 's', $userToken);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);

// 检查用户查询结果
if (!$userResult) {
    die("用户数据库查询失败：" . mysqli_error($db_connection));
}

// 获取用户信息
$userData = mysqli_fetch_assoc($userResult);
// 获取用户 QQ 号码
$qq_number = $userData['qq_number'];
$is_admin = $userData['is_admin'];
// 执行查询 URLs 的预处理语句
$urlQuery = "SELECT `url`, `remark`, `country` FROM `urls` WHERE `owner` = ?";
$urlStmt = mysqli_prepare($db_connection, $urlQuery);
mysqli_stmt_bind_param($urlStmt, 's', $userToken);
mysqli_stmt_execute($urlStmt);
$urlResult = mysqli_stmt_get_result($urlStmt);

// 检查 URLs 查询结果
if (!$urlResult) {
    die("URLs 数据库查询失败：" . mysqli_error($db_connection));
}

// 获取 URLs 信息
$urlData = mysqli_fetch_all($urlResult, MYSQLI_ASSOC);

// 关闭数据库连接
mysqli_close($db_connection);
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta content="<?php echo $siteName; ?> - 控制面板" name="description" />
    <meta content="<?php echo $siteName; ?> - 控制面板" name="author" />
    <title>
        <?php echo $siteName; ?> - 监测链接管理
    </title>
    <link rel="shortcut icon" href="<?php echo $iconimage; ?>">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        #loadingSpinner {
            display: none;
        }
        .active-v
        {
            color: rgb(91, 107, 232) !important; 
            background-color: rgba(91, 107, 232, 0.15) !important;
        }
    </style>
    <script>
        $(document).ready(function () {
            // 显示加载中的提示
            $('#loadingSpinner').show();

            // 获取当前页面的page参数
            var currentPage = new URL(window.location.href).searchParams.get("page");

            // 使用 jQuery 的 load 方法加载 display_links.php，并传递page参数
            $('#displayLinksContainer').load('display_links.php?page=' + currentPage, function () {
                // 隐藏加载中的提示
                $('#loadingSpinner').hide();
            });
        });
    </script>
</head>
<body class="fixed-left">
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
                            <a href="../home" class="waves-effect">
                                <i class="mdi mdi-airplay"></i>
                                <span style=" font-weight: 300;"> 首页 </span>
                            </a>
                        </li>
                        <li class="menu-title">设置</li>
                        <li>
                            <a href="../links/" class="waves-effect active-v"><i class="mdi mdi-link-variant"></i><span> 域名
                                </span></a>
                        </li>
                        <li>
                            <a href="mywallet.html" class="waves-effect "><i class="mdi mdi-radio-tower"></i><span> 推送
                                </span></a>
                        </li>
                        <li>
                            <a href="../settings/" class="waves-effect"><i class="mdi mdi-settings"></i><span> 设置
                                </span></a>
                        </li>
                        <li class="menu-title">版本</li>
                        <li>
                            <a href="https://github.com" class="waves-effect"> <i
                                    class="mdi mdi-arrow-up-bold"></i><span> 当前版本：0.1.0-beta </span></a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div> <!-- end sidebarinner -->
        </div> <!-- Left Sidebar End -->
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
                                    Chinese/中文 <img src="assets/images/flags/chinese_flag.jpg" class="ml-2" height="16"
                                        alt="" />
                                </a>
                            </li>

                            <li class="list-inline-item dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user"
                                    data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $qq_number; ?>&s=100" alt="user"
                                        class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                    <div class="dropdown-item noti-title">
                                        <h5>欢迎，
                                            <?php echo $is_admin ? '管理员' : '普通用户'; ?>
                                        </h5>
                                    </div>
                                    <a class="dropdown-item" href="#"><i
                                            class="mdi mdi-account-circle m-r-5 text-muted"></i> 个人信息</a>
                                    <a class="dropdown-item" href="#"><i class="mdi mdi-settings m-r-5 text-muted"></i>
                                        设置</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="mdi mdi-logout m-r-5 text-muted"></i>
                                        登出</a>
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
                        <!-- end page title end breadcrumb -->
                        <div class="row">
                            <div class="container">
                                <h2>监测链接列表</h2>
                                <!-- 添加监测链接的按钮 -->
                                <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addLinkModal"
                                    style="margin-bottom: 10px;">
                                    添加监测链接
                                </button>
                                <!-- 添加加载中的提示 -->
                                <div id="loadingSpinner" class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p>加载中...</p>
                                </div>
                                <div class="card m-b-30">
                                <div class="card-body">
                                <div id="displayLinksContainer"></div>
                                <div>
                                </div>
                            </div>

                            <!-- 查看链接信息的 Modal -->
                            <div class="modal" tabindex="-1" role="dialog" id="viewLinkModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">查看链接信息</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>URL:</strong> <span id="viewUrl"></span></p>
                                            <p><strong>URL Token: </strong> <span id="urlToken"></span></p>
                                            <p><strong>总访问量:</strong> <span id="totalVisitCount"></span></p>
                                            <p><strong>当天访问量:</strong> <span id="todayVisitCount"></span></p>
                                            <p><strong>创建时间（UTC=0）:</strong> <span id="createTime"></span></p>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">关闭</button>
                                            <button type="button" class="btn btn-primary" id="btnjs">复制嵌入代码</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 添加监测链接的 Modal -->
                            <div class="modal" tabindex="-1" role="dialog" id="addLinkModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">添加监测链接</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="addLinkForm" method="post" action="record_visit.php">
                                                <div class="form-group">
                                                    <label for="url">URL</label>
                                                    <input type="text" class="form-control" id="url" name="url"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remark">备注</label>
                                                    <input type="text" class="form-control" id="remark" name="remark"
                                                        required>
                                                </div>
                                                <!-- 在表单中添加下拉选择框 -->

                                                <div class="form-group">
                                                    <label for="region">地区</label>
                                                    <select class="form-control" id="region" name="region">
                                                        <!-- 这里为空，将通过 Ajax 异步加载 -->
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">提交</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 确认删除的 Modal -->
                            <div class="modal" tabindex="-1" role="dialog" id="confirmDeleteModal">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">确认删除链接</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>确定要删除链接吗？这将删除与该链接相关的所有数据。</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">取消</button>
                                            <button type="button" class="btn btn-danger"
                                                id="deleteLinkBtn">确认删除</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 成功的 Modal -->
                            <div class="modal fade" id="successModal" tabindex="-1" role="dialog"
                                aria-labelledby="successModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="successModalLabel">添加成功</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            添加成功！
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                data-dismiss="modal">确定</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 错误的 Modal -->
                            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog"
                                aria-labelledby="errorModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="errorModalLabel">添加失败</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>添加失败，请检查输入！</p>
                                            <p id="errorDetails"></p> <!-- 添加显示详细错误信息的元素 -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">确定</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- container -->
                    </div> <!-- Page content Wrapper -->
                </div> <!-- content -->
                <footer class="footer">
                    © 2023
                    <?php echo $siteName; ?> | Design by Mannatthemes | Power By TCB Work
                </footer>
            </div>
            <!-- End Right content here -->
        </div>
        </div>
        </div>
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
        <script src="assets/js/app.js"></script>
        <script async src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#addLinkForm').submit(function (e) {
                    e.preventDefault();

                    $.ajax({
                        url: 'record_visit.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (response) {
                            // 通过 console.log 输出服务器返回的响应，以进行调试
                            console.log('服务器响应：', response);

                            // 根据成功或失败显示相应的 modal
                            if (response.success) {
                                $('#successModal').modal('show');
                                // 点击确定按钮后刷新页面
                                $('#successModal').on('hidden.bs.modal', function () {
                                    location.reload();
                                });
                            } else {
                                $('#errorDetails').text(response.error || '发生未知错误，请重试。');
                                $('#errorModal').modal('show');
                                // 点击确定按钮后刷新页面
                                $('#errorModal').on('hidden.bs.modal', function () {
                                    location.reload();
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Ajax请求出错：', xhr, status, error);

                            // 显示错误的 modal
                            $('#errorDetails').text('发生未知错误，请重试。');
                            $('#errorModal').modal('show');
                            // 点击确定按钮后刷新页面
                            $('#errorModal').on('hidden.bs.modal', function () {
                                location.reload();
                            });
                        }
                    });
                });
            });
        </script>

        <script>
            // 点击添加监测链接按钮时触发的事件
            $('#addLinkModal').on('show.bs.modal', function () {
                // 发送 Ajax 请求获取国家数据
                $.ajax({
                    url: 'get_countries.php',
                    type: 'GET',
                    dataType: 'json',  // 明确指定返回的数据类型为 JSON
                    success: function (data) {
                        // 清空下拉选项
                        $('#region').empty();

                        // 将获取的国家数据添加到下拉选项中
                        $.each(data, function (key, value) {
                            $('#region').append('<option value="' + value.code + '">' + value.name + '</option>');
                        });
                    },
                    error: function (error) {
                        alert('获取国家数据失败！');
                    }
                });
            });
            // 点击查看链接
            $(document).on('click', '.view-link-btn', function () {
                var url = $(this).data('url');

                // 发送 AJAX 请求获取链接信息
                $.ajax({
                    url: 'get_link_info.php',
                    type: 'post',
                    data: { url: url },
                    success: function (data) {
                        var linkInfo = JSON.parse(data);

                        // 更新 Modal 内容
                        $('#viewUrl').text(linkInfo.url);
                        $('#urlToken').text(linkInfo.urltoken);
                        $('#totalVisitCount').text(linkInfo.totalVisitCount);
                        $('#todayVisitCount').text(linkInfo.todayVisitCount);
                        $('#createTime').text(linkInfo.createTime);

                        // 显示 Modal
                        $('#viewLinkModal').modal('show');
                    },
                    error: function (error) {
                        alert('获取链接信息失败！');
                    }
                });
            });

        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var clipboardBtn = document.getElementById('btnjs');

                if (clipboardBtn) {
                    clipboardBtn.addEventListener('click', function () {
                        // 获取当前选择的链接的urltoken
                        var urlToken = $('#urlToken').text();

                        // 构建嵌入代码
                        var embedCode = '<script src="<?php echo $siteUrl; ?>storage/monitor_script_' + urlToken + '.js"><\/script>';

                        // 创建弹窗
                        var modal = document.createElement('div');
                        modal.className = 'modal fade';
                        modal.id = 'myModal';
                        modal.setAttribute('tabindex', '-1');
                        modal.setAttribute('role', 'dialog');
                        modal.innerHTML = `
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">复制成功</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>代码已成功复制，请粘贴到你的网页中。</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
                            </div>
                        </div>
                    </div>
                `;

                        // 显示弹窗
                        document.body.appendChild(modal);
                        $('#myModal').modal('show');

                        // 复制代码到剪贴板
                        navigator.clipboard.writeText(embedCode)
                            .then(function () {
                                // 复制成功后的操作
                            })
                            .catch(function (err) {
                                console.error('复制失败：', err);
                            });
                    });
                } else {
                    console.error('找不到具有ID "btnjs" 的按钮元素。');
                }
            });
        </script>
        <script>
            // 点击删除链接
            $(document).on('click', '.delete-link', function () {
                var url = $(this).data('url');

                // 设置删除按钮的链接
                $('#deleteLinkBtn').data('url', url);

                // 显示确认删除的 Modal
                $('#confirmDeleteModal').modal('show');
            });

            $('#deleteLinkBtn').on('click', function () {
                console.log('Delete button clicked!');
                var url = $(this).data('url');

                // 发送 AJAX 请求删除链接
                $.ajax({
                    url: 'delete_link.php',
                    type: 'post',
                    data: { url: url },
                    success: function (data) {
                        // 关闭确认删除的 Modal
                        $('#confirmDeleteModal').modal('hide');

                        // 刷新页面或更新列表等操作
                        location.reload();  // 可根据需要进行更改
                    },
                    error: function (error) {
                        alert('删除链接失败！');
                    }
                });
            });
        </script>

    </body>

</html>