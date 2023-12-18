<?php
include './config.php'; // 替换为实际的 config.php 文件路径
?>

<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="utf-8" />
    <title>
        <?php echo $siteName; ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $siteName; ?> - <?php echo $siteInfo; ?>"  >
    <meta name="keywords" content="Count Api">
    <meta content="Shreethemes" name="author">
    <!-- favicon -->
    <link href="../assets/index/images/wawcloud_icon.svg" rel="shortcut icon">
    <!-- Bootstrap -->
    <link href="../assets/index/css/bootstrap.min.css" rel="stylesheet">
    <!-- Slider -->
    <link href="../assets/index/css/owl.carousel.min.css" rel="stylesheet">
    <link href="../assets/index/css/owl.theme.default.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="../assets/index/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Css -->
    <link href="../assets/index/css/main.css" rel="stylesheet">
    <link href="../assets/index/css/style.css" rel="stylesheet">
    <link href="../assets/index/css/colors/default.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/mdui/1.0.1/css/mdui.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar STart -->
    <header id="topnav" class="defaultscroll sticky">
        <div class="container">
            <!-- Logo container-->
            <div>
                <a class="logo" href="/">
                    <img src="<?php echo $logoimagedark; ?>" class="l-dark" height="27" alt="">
                    <img src="<?php echo $logoimagelight; ?>" class="l-light" height="27" alt="">
                </a>
            </div>

            <div class="buy-button">
                <a href="home/login.php" class="text-dark h6 mr-3 login">登陆</a>
                <a href="home/register.php" target="_blank" class="btn btn-primary">立刻注册</a>
            </div><!--end login button-->
            <!-- End Logo container-->
            <div class="menu-extras">
                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>

            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu nav-light">
                    <li class="buy-button">
                        <a href="/home/#" class="text-dark h6 mr-3 login">主页</a>
                    </li>

                    <ul class="navigation-menu nav-light">
                        <li class="buy-button">
                            <a href="/api/" class="text-dark h6 mr-3 login">API 文档</a>
                    </ul>
                    </li>

                    <ul class="navigation-menu nav-light">
                        <li class="buy-button">
                            <a href="/qa/" class="text-dark h6 mr-3 login">Q & A</a>
                    </ul>
                    </li>

                    <ul class="navigation-menu nav-light">
                        <li class="buy-button">
                            <a href="/contact/" class="text-dark h6 mr-3 login">联系我们</a>
                        </li>
                    </ul>
                </ul><!--end navigation menu-->
            </div><!--end navigation-->
        </div><!--end container-->
    </header><!--end header-->
    <section class="bg-half-260 d-table w-100 bg-primary"
        style="background: url('https://api.miaomc.cn/image/get') top center;" id="home">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <h4 class="heading text-white mb-3">
                            <?php echo $siteName; ?> -
                            <?php echo $siteInfo; ?>
                        </h4>
                        <p class="text-white-50 para-desc mx-auto mb-0">Count Api，做你最好的网站监控者</p>

                        <div class="mt-4">
                            <a href="/home/register/" class="btn btn-primary mx-1">立刻注册</a>
                            <a href="/home/login/" class="btn btn-light mx-1">立即登陆</a>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <div class="container mt-100 mt-60">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="mr-lg-5">
                    <img src="../assets/index/images/features/1.png" class="img-fluid" alt="">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2 mt-sm-0 pt-sm-0">
                <div class="ml-lg-5">
                    <div
                        class="media services serv-primary rounded align-items-center p-4 bg-light position-relative overflow-hidden">
                        <span class="h1 icon2 text-primary">
                            <i class="uil uil-swatchbook"></i>
                        </span>
                        <div class="media-body content ml-3">
                            <h5>增强安全性</h5>
                            <p class="para text-muted mb-0">监控服务器大部分使用有防御的BGP线路,并且在物理方面有多重防御</p>
                        </div>
                        <div class="big-icon">
                            <i class="uil uil-swatchbook"></i>
                        </div>
                    </div>

                    <div
                        class="media services serv-primary rounded align-items-center p-4 bg-light mt-4 position-relative overflow-hidden">
                        <span class="h1 icon2 text-primary">
                            <i class="uil uil-tachometer-fast-alt"></i>
                        </span>
                        <div class="media-body content ml-3">
                            <h5>高性能并发</h5>
                            <p class="para text-muted mb-0">监控服务器大部分采用Intel Xeon高性能CPU,保证其服务器的稳定运行</p>
                        </div>
                        <div class="big-icon">
                            <i class="uil uil-tachometer-fast-alt"></i>
                        </div>
                    </div>

                    <div
                        class="media services serv-primary rounded align-items-center p-4 bg-light mt-4 position-relative overflow-hidden">
                        <span class="h1 icon2 text-primary">
                            <i class="uil uil-user-check"></i>
                        </span>
                        <div class="media-body content ml-3">
                            <h5>稳定的售后支持</h5>
                            <p class="para text-muted mb-0">我们提供7*12小时的售后服务,并且有相关的交流群反馈</p>
                        </div>
                        <div class="big-icon">
                            <i class="uil uil-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-100 mt-60">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-12 order-1 order-md-2">
                <div class="ml-lg-5">
                    <img src="../assets/index/images/features/2.png" class="img-fluid" alt="">
                </div>
            </div><!--end col-->
            <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2 mt-sm-0 pt-sm-0 order-2 order-md-1">
                <div class="section-title mr-lg-5">
                    <h4 class="title mb-3">快速反应 <br> 和安全的服务器</h4>
                    <p class="text-muted para-desc mx-auto mb-0">我们的支持团队随时准备为您提供最佳解决方案。</p>

                    <ul class="list-unstyled text-muted mt-3">
                        <li class="my-2"><i data-feather="check-circle"
                                class="fea icon-ex-md text-primary mr-2"></i>稳定与安全始终是您最关心的问题，我们的所有产品均为优质的机房，确保为您提供超高在线率
                        </li>
                        <li class="my-2"><i data-feather="check-circle"
                                class="fea icon-ex-md text-primary mr-2"></i>我们拥有强大的技术支持团队，如果您有任何疑问，只需新建工单，便有专门的客服与您联系
                        </li>
                        <li class="my-2"><i data-feather="check-circle"
                                class="fea icon-ex-md text-primary mr-2"></i>精细打磨的控制面板，高度集成的一键式操作为您带来最为易用的体验，抛弃繁琐操作</li>
                    </ul>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    </section><!--end section-->
    <div class="container-fluid mt-100 mt-60">
        <div class="rounded-pill bg-primary py-5 px-4">
            <div class="row py-md-5 py-4">
                <div class="container">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="section-title">
                                <h6 class="text-white mb-3">基于
                                    <?php echo $siteName; ?>
                                </h6>
                                <h2 class="text-white mb-0">全自主开发的监控平台</h2>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-6 col-md-6 col-12 mt-4 pt-2 mt-sm-0 pt-sm-0">
                            <ul class="list-unstyled mb-0 ml-lg-5">
                                <li class="text-white-50 my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-arrow-right-circle fea icon-ex-md text-white mr-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 16 16 12 12 8"></polyline>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>稳定快速
                                </li>
                                <li class="text-white-50 my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-arrow-right-circle fea icon-ex-md text-white mr-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 16 16 12 12 8"></polyline>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>不拖累网站加载速度
                                </li>
                                <li class="text-white-50 my-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-arrow-right-circle fea icon-ex-md text-white mr-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 16 16 12 12 8"></polyline>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>最安全的数据保护可靠性
                                </li>
                            </ul>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </div><!--end row-->
        </div><!--end div-->
    </div><!--end container-->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-center mb-4 pb-2">
                        <h4 class="title mb-3">关于人们的意见</h4>
                        <p class="text-muted para-desc mx-auto mb-0">我们的客户爱我们，您也一样<br>下面是随机选择的客户推荐</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row justify-content-center">
                <div class="col-lg-7 mt-4">
                    <div id="customer-testi" class="owl-carousel owl-theme">
                        <div class="card border-0 text-center client-bar m-2">
                            <div class="card-body content rounded-pill  px-4 py-5 shadow position-relative">
                                <i class="mdi mdi-format-quote-open icons text-primary"></i>
                                <p class="text-muted mb-0">占位一下下，还在想</p>
                            </div>
                            <img src="https://m1.miaomc.cn/uploads/20220205_ce6857117bc3a.jpg"
                                class="avatar avatar-md-md mt-4 testi-img rounded-circle shadow mx-auto" alt="">
                            <h6 class="text-primary mt-2 mb-0">筱笙月</h6>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->

        <div class="container mt-100 mt-60">
            <div class="row align-items-center mb-4 pb-2">
                <div class="col-md-6">
                    <div class="section-title">
                        <h4 class="title mb-md-0 mb-4">大部分的问题都可以在这里得到解答 <br> 例如:</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12 mt-4 pt-2">
                    <div class="media">
                        <i data-feather="help-circle" class="fea icon-ex-md text-primary mr-2 mt-1"></i>
                        <div class="media-body">
                            <h5 class="mt-0">咱们的 <span class="text-primary">Count Api</span> 是怎么运行的</h5>
                            <p class="answer text-muted mb-0">使用js代码记录网站的访问，IP，地区</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-6 col-12 mt-4 pt-2">
                    <div class="media">
                        <i data-feather="help-circle" class="fea icon-ex-md text-primary mr-2 mt-1"></i>
                        <div class="media-body">
                            <h5 class="mt-0"> 注册账户的主要流程是什么？</h5>
                            <p class="answer text-muted mb-0">可以在首页上进行注册的操作,在注册界面提供自己的邮箱等联系方式即可注册成功</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-6 col-12 mt-4 pt-2">
                    <div class="media">
                        <i data-feather="help-circle" class="fea icon-ex-md text-primary mr-2 mt-1"></i>
                        <div class="media-body">
                            <h5 class="mt-0"> 如何进行无限制的网站监控数量？</h5>
                            <p class="answer text-muted mb-0">购买网站监控数量套餐,并且不违反Count Api的相关用户协议和隐私政策即可享受不限流量的体验</p>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-6 col-12 mt-4 pt-2">
                    <div class="media">
                        <i data-feather="help-circle" class="fea icon-ex-md text-primary mr-2 mt-1"></i>
                        <div class="media-body">
                            <h5 class="mt-0">我的<span class="text-primary">Count Api</span> 账户是否安全</h5>
                            <p class="answer text-muted mb-0">
                                网站全程使用SSL加密,并且保证不会泄露用户的相关数据等,对服务器进行了加密保护等措施,来保证您的账号达到最安全的情况</p>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row mt-5 pt-4 justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title">
                        <h4 class="title mb-3">有疑问 ？ 保持联系！</h4>
                        <p class="text-muted para-desc mx-auto">借助权威的数字设计平台，创建、协作并将您的想法转化为令人难以置信的产品。</p>
                        <a href="javascript:void(0)" class="btn btn-primary mt-4"><i class="mdi mdi-phone"></i> 联系我们</a>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <footer class="footer bg-footer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="section-title text-md-center">
                        <h4 class="text-light mb-0">取得联系！</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row mt-3 justify-content-center">
                <div class="col-md-4 mt-4 pt-2">
                    <div class="text-md-center">
                        <div class="icon">
                            <i data-feather="mail" class="fea icon-md text-light"></i>
                        </div>
                        <div class="content mt-2">
                            <h5 class="title text-light">邮箱</h5>
                            <a href="mailto:znc15@tcbwork.com" class="text-foot">***@*****.**</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-4 pt-2">
                    <div class="text-md-center">
                        <div class="icon">
                            <i data-feather="phone" class="fea icon-md text-light"></i>
                        </div>
                        <div class="content mt-2">
                            <h5 class="title text-light">联系电话</h5>
                            <a href="tel:+152534-468-854" class="text-foot">+86 *******</a>
                        </div>
                    </div>
                </div><!--end col-->

                <div class="col-md-4 mt-4 pt-2">
                    <div class="text-md-center">
                        <div class="icon">
                            <i data-feather="map-pin" class="fea icon-md text-light"></i>
                        </div>
                        <div class="content mt-2">
                            <h5 class="title text-light">地区</h5>
                            <a href="https://map.baidu.com/poi/%E5%8F%A4%E4%BA%AD%E6%9D%91/@13171865.128562689,3598937.0456269113,14.46z?uid=2f6c356432036ff5a2046410&ugc_type=3&ugc_ver=1&device_ratio=2&compat=1&pcevaname=pc4.1&newfrom=zhuzhan_webmap&querytype=detailConInfo&da_src=shareurl"
                                class="video-play-icon text-foot">在百度地图查看</a>
                        </div>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!--Footer -->
        <div class="container mt-5">
            <div class="footer-bar">
                <div class="row mt-5">
                    <div class="col-lg-4 col-md-12">
                        <a class="logo-footer h4 mouse-down text-light" href="#home">
                            <img src="https://cdn.img.lgdl.lol/uploads/2022/08/03/62ea13b403ad3.png" height="16" alt="">
                        </a>
                        <p class="mt-4 text-foot">在我们完全冗余,并且稳定的 <br> 高性能云平台上部署您的服务基础架构，<br>并从其高可靠性、安全性和企业功能集中受益。
                        </p>

                    </div>

                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-4 col-12 mt-4 pt-2 mt-lg-0 pt-lg-0">
                                <h5 class="text-light footer-head font-weight-normal mb-0">关于我们</h5>
                                <ul class="list-unstyled footer-list mt-4">
                                    <li><a href="privacy.html" class="text-foot"><i
                                                class="mdi mdi-chevron-right mr-1"></i>隐私协议</a></li>
                                    <li><a href="terms.html" class="text-foot"><i
                                                class="mdi mdi-chevron-right mr-1"></i>服务条款</a></li>
                                    <li><a href="faqs.html" class="text-foot"><i
                                                class="mdi mdi-chevron-right mr-1"></i>问&答</a></li>
                                    <li><a href="contact.html" class="text-foot"><i
                                                class="mdi mdi-chevron-right mr-1"></i>联系我们</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!--end footer-->
    <footer class="footer footer-line bg-footer">
        <div class="container">
            <div class="row text-center">
                <div class="col-sm-6 col-md-7">
                    <div class="text-sm-left">
                        <p class="mb-0 text-foot">© 2021-2023
                            <?php echo $siteName; ?>. Design with <i class="mdi mdi-heart text-danger">
                            </i> by <a href="https://www.tcbmc.cc/" target="_blank" class="text-reset">TCB Work</a>.
                        </p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </footer><!--end footer-->
    <a href="#" class="btn btn-icon btn-soft-primary back-to-top"><i data-feather="arrow-up" class="icons"></i></a>
    <!-- Back to top -->

    <!-- javascript -->
    <script src="../assets/index/js/jquery.min.js"></script>
    <script src="../assets/index/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/index/js/scrollspy.min.js"></script>
    <!-- SLIDER -->
    <script src="../assets/index/js/owl.carousel.min.js"></script>
    <script src="../assets/index/js/owl.init.js"></script>
    <!-- Icons -->
    <script src="../assets/index/js/feather.min.js"></script>
    <!-- Main Js -->
    <script src="../assets/index/js/app.js"></script>
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

</body>

</html>