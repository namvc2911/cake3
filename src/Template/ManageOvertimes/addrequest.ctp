<?php $this->assign('title', 'Tạo yêu cầu làm thêm (Thành viên)'); ?>
<section class="content-header">
    <h1>
        Tạo yêu cầu làm thêm < Vai trò thành viên >
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li><?= $this->Html->link(' Lịch làm thêm giờ', ['action' => 'calendar'], ['escape' => false]) ?></li>
        <li class="active">Tạo yêu cầu làm thêm < Vai trò thành viên ></li>
    </ol>
</section>
<section class="content">
    <?php echo $this->Form->create($add) ?>
    <div class="contain">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Tạo yêu cầu làm thêm</h3>
            </div>
            <div class="box-body">
                <div class="row">

                    <div class="col-sm-10 col-sm-offset-1">

                        <div class="form-group has-feedback">
                            <?=
                            $this->Form->control('project_id', [
                                'class' => 'form-control',
                                'placeholder' => 'Project id',
                                'type' => 'select',
                                'label' => "Tên dự án"])
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-1">
                            <?=
                            $this->Form->control('day_overtime', [
                                'id' => 'datepicker-2',
                                'class' => 'form-control pull-right',
                                'placeholder' => 'Ngày làm thêm',
                                'type' => 'text',
                                'label' => "Ngày làm thêm"])
                            ?>
                    </div>
                    <div class="col-sm-2">
                        <div class="bootstrap-timepicker">
                            <?=
                            $this->Form->control('start_at', [
                                'id' => 'timepicker_start',
                                'class' => 'form-control pull-right',
                                'placeholder' => 'Thời điểm bắt đầu',
                                'type' => 'text',
                                'label' => "Thời điểm bắt đầu"])
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="bootstrap-timepicker">
                            <?=
                            $this->Form->control('end_at', [
                                'id' => 'timepicker_end',
                                'class' => 'form-control pull-right',
                                'placeholder' => 'Thời điểm dự kiến kết thúc',
                                'type' => 'text',
                                'label' => "Thời điểm dự kiến kết thúc"])
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-2">
                            <?=
                            $this->Form->control('breaktime', [
                                'class' => 'form-control pull-right breaktime',
                                'placeholder' => 'Thời gian nghỉ',
                                'type' => 'number',
                                'min' => 0,
                                'label' => "Thời gian nghỉ (phút)"])
                            ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div>
                            <?=
                            $this->Form->control('reason', [
                                'class' => 'form-control',
                                'type' => 'textarea',
                                'label' => "Lí do"])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="row ">
                    <div class="col-sm-2 col-sm-offset-1 ">
                        <?= $this->Form->button('Tạo mới', ['class' => 'btn btn-info']) ?>
                    </div>
                </div> 
            </div>
        </div>
    </div> 
</section>

<?php echo $this->Form->end() ?>
 
