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
    <title>
        <?php echo $siteName; ?> - 个人设置
    </title>
    <meta content="<?php echo $siteName; ?> - 个人设置" name="description" />
    <meta content="<?php echo $siteName; ?> - 个人设置" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo $iconimage; ?>">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">

</head>


<body class="fixed-left">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="wrapper">
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
                        <li class="menu-title">版本</li>
                        <li>
                            <a href="https://github.com" class="waves-effect"> <i
                                    class="mdi mdi-arrow-up-bold"></i><span> 当前版本：0.1.0-beta </span></a>
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
                                    <a class="dropdown-item" href="../settings/"><i
                                            class="mdi mdi-settings m-r-5 text-muted"></i> 设置</a>
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
                <!-- Top Bar End -->

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
                                            <li class="breadcrumb-item active">个人设置</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">个人设置</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title end breadcrumb -->
                        <div class="row">
                            <div class="col-md-4 col-xl-2">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class=" text-center">
                                            <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $qq_number; ?>&s=100"
                                                alt="" class="rounded-circle img-thumbnail w-50">
                                            <h2 class="font-16">
                                                <?php echo $username; ?>
                                            </h2>
                                            <a class="text-muted font-14">用户ID：
                                                <?php echo $id; ?>
                                            </a><br>
                                            <a class="text-muted font-14">组别：
                                                <?php echo $is_admin ? '管理员' : '普通用户'; ?>
                                            </a><br>
                                            <a class="text-muted font-14">
                                                <?php echo $email; ?>
                                            </a><br>
                                            <a class="text-muted font-14">
                                                <?php echo $reg_date; ?>
                                            </a><br>
                                            <ul class="list-unstyled list-inline mb-0 mt-3">
                                                <li class="list-inline-item"><a href="#"><i
                                                            class="ti-facebook text-primary"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-xl-5">
                                <div class="card bg-white m-b-30">
                                    <div class="card-body">
                                        <div class="general-label">
                                            <form id="changePasswordForm">
                                                <div class="form-group">
                                                    <div style="padding-bottom: 15px;">
                                                        <h3>修改密码</h3>
                                                    </div>
                                                </div>
                                                <form id="changePasswordForm">
                                                    <div class="form-group">
                                                        <label for="new_password">新密码</label>
                                                        <input type="password" class="form-control"
                                                            id="exampleInputEmail1" placeholder="8-32位"
                                                            name="new_password" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="confirm_password">重复新密码</label>
                                                        <input type="password" class="form-control"
                                                            id="exampleInputPassword1" placeholder="8-32位"
                                                            name="confirm_password" required>
                                                    </div>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="changePassword()">提交</button>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-5">
                            <div class="card bg-white m-b-30">
                                    <div class="card-body">
                                        <div class="general-label">
                                                <div class="form-group">
                                                    <div style="padding-bottom: 15px;">
                                                        <h3>修改信息</h3>
                                                    </div>
                                                </div>
                                                <form id="updateForm">
                                                    <div class="form-group">
                                                        <label for="newUsername">新用户名</label>
                                                        <input type="text" class="form-control" id="newUsername" name="newUsername" placeholder="5-15位" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="newEmail">新邮箱</label>
                                                        <input type="email" class="form-control" id="newEmail" name="newEmail" placeholder="如果不需要修改，重复输入旧邮箱即可" required>
                                                    </div>
                                                    <button type="button" class="btn btn-primary" onclick="updateUserInfo()">提交</button>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xl-4">
                                <div class="mini-stat clearfix bg-white">
                                    <div class="row align-items-center">
                                        <div class="col-2">
                                            <span class="mini-stat-icon bg-light"><i
                                                    class="mdi mdi-security text-warning"></i></span>
                                        </div>
                                        <div class="col-10 text-left">
                                            <h4 class="counter text-dark m-0 pb-1">API 密钥</h4>
                                            <h6>请管理好自己的密钥和UrlToken，请勿泄露</h6>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-primary waves-effect waves-light"
                                                    data-toggle="modal" data-animation="bounce"
                                                    data-target=".bs-example-modal-center">点击查看API</button>
                                                <div class="modal fade bs-example-modal-center" tabindex="-1"
                                                    role="dialog" aria-labelledby="mySmallModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">API 密钥
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>当前用户UserToken：</p>
                                                                <p>
                                                                    <?php echo $token; ?>
                                                                </p>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->
                <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="resultModalLabel">密码更改结果</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- 这里将显示密码更新的结果消息 -->
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refreshPage()">关闭</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">提示</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modalContent">
                                <!-- 这里显示更新成功或失败的消息 -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refreshPage()">关闭</button>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                © 2023
                <?php echo $siteName; ?> | Design by Mannatthemes | Power By TCB Work
            </footer>

            </div>
            <!-- End Right content here -->

        </div>
        <!-- 添加一个模态框 -->

        <!-- END wrapper -->

        <script>
            function changePassword() {
                $.ajax({
                    url: 'change_password.php',
                    method: 'POST',
                    data: $('#changePasswordForm').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        $('#resultModal .modal-body').html(response.message);
                        $('#resultModal').modal('show');
                    },
                    error: function () {
                        alert("错误：无法与服务器通信。");
                    }
                });
            }
            function refreshPage() {
            location.reload();
        }
        </script>
        <script>
            function updateUserInfo() {
                // 获取用户输入的新用户名和新邮箱
                var newUsername = document.getElementById('newUsername').value;
                var newEmail = document.getElementById('newEmail').value;

                // 创建一个FormData对象，用于将数据发送到服务器
                var formData = new FormData();
                formData.append('newUsername', newUsername);
                formData.append('newEmail', newEmail);

                // 发送POST请求到update_info.php
                fetch('update_info.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // 使用Bootstrap模态框显示成功或失败消息
                    var modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = data;
                    $('#myModal').modal('show');
                })
                .catch(error => console.error('Error:', error));
            }

            function refreshPage() {
                location.reload(true); // 刷新页面
            }
        </script>
        <!-- jQuery  -->
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

        <!-- App js -->
        <script src="assets/js/app.js"></script>

</body>

</html>