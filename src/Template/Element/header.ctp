<body class="hold-transition skin-blue fixed sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <?= $this->Html->link('<span class="logo-mini"><b>B</b>M</span><span class="logo-lg"><b>BBS</b>Movertime</span>', '/', ['class' => 'logo' ,'escape' => false]) ?>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs"><i class="fa fa-user fa-fw"></i><?= $userLogin['username'] ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <?= $this->Html->image('img-user.png', ['alt' => 'User Image', 'class' => "img-circle"]) ?>
                                    <p>
                                        <?= $userLogin['fullname'] ?>
                                        <small><?= $userLogin['nickname'] ?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <?=
                                        $this->Html->link('Đăng xuất', [
                                        'controller' => 'Users', 'action' => 'logout'
                                        ], [
                                        'class' => 'btn btn-default btn-flat'
                                        ]
                                        )
                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">DANH MỤC</li>
                    <li><?= $this->Html->link('<i class="fa fa fa-home"></i> <span>Trang chủ</span>', '/', ['escape' => false]) ?></li>

                    <?php if ($userLogin['role'] != "MEMBERS"): ?>
                    <li><?=
                        $this->Html->link('<i class="fa fa-plus"></i> <span>Tạo mới dự án</span>', [
                        'controller' => 'Projects', 'action' => 'add'], ['escape' => false])
                        ?></li>
                    <li><?=
                        $this->Html->link('<i class="fa fa-book"></i> <span>Danh sách tất cả dự án</span>', [
                        'controller' => 'Projects', 'action' => 'listProject'], ['escape' => false])
                        ?></li>
                    <?php endif; ?>


                    <li><?= $this->Html->link('<i class="fa fa-folder"></i> <span>Danh sách dự án tham gia</span>', ['controller' => 'Projects', 'action' => 'index'], ['escape' => false]) ?></li>

                    <li><?= $this->Html->link('<i class="fa fa-calendar"></i> <span>Lịch làm thêm</span>', ['controller' => 'ManageOvertimes', 'action' => 'calendar'], ['escape' => false]) ?></li>
                    <?php if ($userLogin['role'] != "MEMBERS"): ?>
                    <li class="treeview">
                        <a href="">
                            <i class="fa fa-clock-o"></i> <span>Danh sách làm thêm giờ </span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">

                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Tất cả thành viên</span>', ['controller' => 'Overtimes', 'action' => 'index'], ['escape' => false]) ?></li>

                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Của bản thân</span>', ['controller' => 'Overtimes', 'action' => 'listOvertime'], ['escape' => false]) ?></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li><?= $this->Html->link('<i class="fa fa-clock-o"></i> <span>Danh sách làm thêm giờ </span>', ['controller' => 'Overtimes', 'action' => 'listOvertime'], ['escape' => false]) ?></li>
                    <?php endif; ?>
                    <li class="treeview">
                        <a href="">
                            <i class="fa fa-clock-o"></i> <span>Tạo yêu cầu làm thêm </span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Vai trò thành viên</span>', ['controller' => 'ManageOvertimes', 'action' => 'addrequest'], ['escape' => false]) ?></li>
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Vai trò nhóm trưởng</span>', ['controller' => 'ManageOvertimes', 'action' => 'addrequestbyleader'], ['escape' => false]) ?></li>
                        </ul>
                    </li>
                    <li class="treeview">

                        <a href="">
                            <i class="fa fa-clock-o"></i> <span>Phê duyệt làm thêm </span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Chưa phê duyệt</span>', ['controller' => 'Overtimes','action' => 'index1'], ['escape' => false]) ?></li>
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Đã phê duyệt</span>', ['controller' => 'Overtimes', 'action' => 'index2'], ['escape' => false]) ?></li>
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Đã từ chối</span>', ['controller' => 'Overtimes', 'action' => 'listDenyOvertime'], ['escape' => false]) ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="">
                            <i class="fa fa-clock-o"></i> <span>Quản lý thời gian</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Import bảng chấm công</span>', ['controller' => 'Managetime', 'action' => 'importExcel'], ['escape' => false]) ?></li>

                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Import bảng chấm công</span>', ['controller' => 'Managetime', 'action' => 'deleteTemp'], ['escape' => false]) ?></li>

                        </ul>
                    </li>
                     <li class="treeview">
                        <a href="">
                            <i class="fa fa-clock-o"></i> <span>Danh Sách Nhân Viên</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Danh Sách</span>', ['controller' => 'users', 'action' => 'index'], ['escape' => false]) ?></li>
                             <li><?= $this->Html->link('<i class="fa fa-circle-o"></i> <span>Thêm Nhân Viên</span>', ['controller' => 'users', 'action' => 'add'], ['escape' => false]) ?></li>
                        </ul>
                    </li>
                    <li>
                        <?= $this->Html->link('<i class="fa fa-users" aria-hidden="true"></i> <span>Nhóm</span>', ['controller' => 'Groups', 'action' => 'listGroup'], ['escape' => false]) ?>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- =============================================== -->

