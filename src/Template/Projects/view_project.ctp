<?php $this->assign('title', 'Chi tiết dự án: ' . $project->name); ?>
<section class="content-header">
    <h1>
        Chi tiết dự án
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li><?= $this->Html->link(' Danh sách dự án', ['action' => 'listProject'], ['escape' => false]) ?></li>
        <li class="active">Chi tiết dự án</li>
    </ol>
</section>
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h2 class="box-title">DỰ ÁN: <?= h($project->name) ?></h2>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <form class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ngày bắt đầu :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Ngày bắt đầu" value="<?= $project->start_date->format('Y-m-d') ?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ngày kết thúc :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Ngày kết thúc" value="<?php
                                   if ($project->end_date != NULL) {
                                   echo $project->end_date->format('Y-m-d');
                                   } else {
                                   echo 'Chưa có';
                                   }
                                   ?>" readonly="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Mô tả :</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="3" placeholder="Mô tả" readonly="true"><?= h($project->description) ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Trạng thái :</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Trạng thái" value="<?php
                                   if ($project->status == 1) {
                                   echo 'Chưa thực hiện';
                                   } elseif ($project->status == 2) {
                                   echo 'Đang thực hiện';
                                   } elseif ($project->status == 3) {
                                   echo 'Đã hoàn thành';
                                   } else {
                                   echo 'Đã xóa';
                                   }
                                   ?>" readonly="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Thành viên :</label>
                        <div class="col-sm-9">

                            <div class="form-control" style="background-color: #eee">
                                <?php
                                $i = 0;
                                $length = count($query->toArray());
                                foreach ($query as $row) {
                                    if ($row['is_leader'] == 0) {
                                        if (++$i <= $length && $i != 1)
                                            echo ", ";
                                        echo $this->Html->link($row['user']['fullname'], ['action' => 'viewByUserId', $row['user_id']]);
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nhóm trưởng :</label>
                        <div class="col-sm-9">
                            <div class="form-control" style="background-color: #eee">
                                <?php
                                $i = 0;
                                $length = count($query->toArray());
                                foreach ($query as $row) {
                                    if ($row['is_leader'] == 1) {
                                        if (++$i <= $length && $i != 1)
                                            echo ", ";
                                        echo $this->Html->link($row['user']['fullname'], ['action' => 'viewByUserId', $row['user_id']]);
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= $this->Html->link('Sửa dự án', ['action' => 'edit', $project->project_id], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <!-- /.box -->
</section>
