<?php $this->assign('title', 'Trang chủ'); ?>
<section class="content-header">
    <h1>
        Trang chủ
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa fa-home"></i> Trang chủ</li>
    </ol>
</section>
<section class="content">
   <!--  <?php if ($userLogin['role'] != 'MEMBERS'): ?> -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="info-box">
                <?= $this->Html->link('<span class="info-box-icon bg-aqua"><i class="fa fa-bars"></i></span>', ['controller' => 'Projects', 'action' => 'listProject'], ['escape' => false]) ?>

                <div class="info-box-content">
                    <span class="info-box-text">Tổng số dự án</span>
                    <span class="info-box-number"><?= $totalProject ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="info-box">
                <?= $this->Html->link('<span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>', ['controller' => 'Projects', 'action' => 'listProject', 's' => 3], ['escape' => false]) ?>

                <div class="info-box-content">
                    <span class="info-box-text">Dự án đã hoàn thành</span>
                    <span class="info-box-number"><?= $completedProject ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="info-box">
                <?= $this->Html->link('<span class="info-box-icon bg-yellow"><i class="fa fa-info-circle"></i></span>', ['controller' => 'Projects', 'action' => 'listProject', 's' => 2], ['escape' => false]) ?>

                <div class="info-box-content">
                    <span class="info-box-text">Dự án đang thực hiện</span>
                    <span class="info-box-number"><?= $completingProject ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="info-box">
                <?= $this->Html->link('<span class="info-box-icon bg-red"><i class="fa fa-exclamation-circle"></i></span>', ['controller' => 'Projects', 'action' => 'listProject', 's' => 1], ['escape' => false]) ?>

                <div class="info-box-content">
                    <span class="info-box-text">Dự án chưa thực hiện</span>
                    <span class="info-box-number"><?= $uncompleteProject ?></span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= $totalProjectUser ?></h3>

                    <p>Số dự án tham gia</p>
                </div>
                <div class="icon">
                    <i class="ion ion-navicon-round"></i>
                </div>
                <?=
                $this->Html->link('Xem thêm <i class="fa fa-arrow-circle-right"></i>', [
                'controller' => 'Projects', 'action' => 'index'], ['class' => 'small-box-footer', 'escape' => false])
                ?>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= $completedProjectUser ?></h3>

                    <p>Số dự án đã hoàn thành</p>
                </div>
                <div class="icon">
                    <i class="ion ion-checkmark-round"></i>
                </div>
                <?=
                $this->Html->link('Xem thêm <i class="fa fa-arrow-circle-right"></i>', [
                'controller' => 'Projects', 'action' => 'index','s' => 3], ['class' => 'small-box-footer', 'escape' => false])
                ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $completingProjectUser ?></h3>

                    <p>Số dự án đang thực hiện</p>
                </div>
                <div class="icon">
                    <i class="ion ion-information"></i>
                </div>
                <?=
                $this->Html->link('Xem thêm <i class="fa fa-arrow-circle-right"></i>', [
                'controller' => 'Projects', 'action' => 'index','s' => 2], ['class' => 'small-box-footer', 'escape' => false])
                ?>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $uncompleteProjectUser ?></h3>

                    <p>Số dự án chưa thực hiện</p>
                </div>
                <div class="icon">
                    <i class="ion ion-alert"></i>
                </div>
                <?=
                $this->Html->link('Xem thêm <i class="fa fa-arrow-circle-right"></i>', [
                'controller' => 'Projects', 'action' => 'index','s' => 1], ['class' => 'small-box-footer', 'escape' => false])
                ?>
            </div>
        </div>
</section>
