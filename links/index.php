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
    <link href="../assets/home/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../assets/home/assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="../assets/links/css/main.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../assets/links/js/main.js"></script>
</head>
<body class="fixed-left">
<div id="preloader"><div id="status"><div class="spinner"></div></div></div>
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
            </div> <!-- end sidebarinner -->
        </div> <!-- Left Sidebar End -->
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
                                                <div class="form-group">
                                                    <label for="region">地区</label>
                                                    <select class="form-control" id="region" name="region">
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">提交</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                            <p id="errorDetails"></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">确定</button>
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
                    </div>
                </div>
                <?php
                include_once('../assets/common/footer.php');
                ?>
                </div>
            </div>
        </div>
        </div>
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
        <script src="../assets/home/assets/plugins/skycons/skycons.min.js"></script>
        <script src="../assets/home/assets/plugins/raphael/raphael-min.js"></script>       
        <script src="../assets/home/assets/js/app.js"></script>
        <script async src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>
        <script src="../assets/links/js/count.js"></script>
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
        //复制js代码
        document.addEventListener("DOMContentLoaded", function () {
        var clipboardBtn = document.getElementById("btnjs");

        if (clipboardBtn) {
            clipboardBtn.addEventListener("click", function () {
            // 获取当前选择的链接的urltoken
            var urlToken = $("#urlToken").text();

            // 构建嵌入代码
            var embedCode =
                '<script src="<?php echo $siteUrl; ?>storage/monitor_script_' +urlToken +'.js"><\/script>';
            // 创建弹窗
            var modal = document.createElement("div");
            modal.className = "modal fade";
            modal.id = "myModal";
            modal.setAttribute("tabindex", "-1");
            modal.setAttribute("role", "dialog");
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
            $("#myModal").modal("show");

            // 复制代码到剪贴板
            navigator.clipboard
                .writeText(embedCode)
                .then(function () {
                // 复制成功后的操作
                })
                .catch(function (err) {
                console.error("复制失败：", err);
                });
            });
        } else {
            console.error('找不到具有ID "btnjs" 的按钮元素。');
        }
        });
        </script>

    </body>

</html>