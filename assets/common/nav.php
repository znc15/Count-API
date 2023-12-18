<div class="topbar">
    <nav class="navbar-custom">
        <ul class="list-inline float-right mb-0">
            <li class="list-inline-item dropdown notification-list hide-phone">
                <a class="nav-link dropdown-toggle arrow-none waves-effect text-white" href="#">
                    Chinese/中文 <img src="../assets/home/assets/images/flags/chinese_flag.jpg" class="ml-2" height="16"
                        alt="" />
                </a>
            </li>
            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $qq_number; ?>&s=100" alt="user"
                        class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <div class="dropdown-item noti-title">
                        <h5>欢迎，
                            <?php echo $is_admin ? '管理员' : '普通用户'; ?>
                        </h5>
                    </div>
                    <a class="dropdown-item" href="../settings/"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
                        个人信息</a>
                    <a class="dropdown-item" href="../settings/"><i class="mdi mdi-settings m-r-5 text-muted"></i>
                        设置</a>
                    <?php
                    // 根据后端代码获取的$is_admin的值来决定是否显示“后台”和“管理员设置”项目
                    if ($is_admin == 1) {
                        echo '<a class="dropdown-item" href="../admin/"><i class="mdi mdi-key-variant m-r-5 text-muted"></i> 后台面板</a>';
                    }
                    ?>
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