<?php $this->assign('title', 'Tạo dự án'); ?>
<section class="content-header">
    <h1>
        Tạo dự án
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li class="active">Tạo dự án</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Tạo dự án</h3>
        </div>
        <?= $this->Form->create($project, ['type' => 'post']) ?>
        <div class="box-body">
            <div class="form-group col-sm-offset-1 col-sm-10">
                <?=
                $this->Form->control('name', [
                    'type' => 'text',
                    'label' => 'Tên dự án*',
                    'class' => 'form-control',
                    'placeholder' => "Tên dự án",
                ])
                ?>
            </div>
            <div class="form-group col-sm-offset-1 col-sm-10">
                <?=
                $this->Form->control('description', [
                    'type' => 'textarea',
                    'label' => 'Mô tả',
                    'class' => 'form-control',
                    'placeholder' => "Mô tả",
                    'rows' => 3,
                ])
                ?>
            </div>
            <div class="form-group col-sm-offset-1 col-sm-5">
                <?=
                $this->Form->control('start_date', [
                    'type' => 'text',
                    'label' => 'Ngày bắt đầu*',
                    'class' => 'form-control pull-right',
                    'id' => "day-start",
                    'placeholder' => "Ngày bắt đầu",
                ])
                ?>
            </div>

            <div class="form-group col-sm-5">
                <?=
                $this->Form->control('end_date', [
                    'type' => 'text',
                    'label' => 'Ngày kết thúc',
                    'class' => 'form-control pull-right',
                    'id' => "day-end",
                    'placeholder' => "Ngày kết thúc",
                ])
                ?>
            </div>

            <div class="form-group col-sm-offset-1 col-sm-10">
                <?=
                $this->Form->control('status', ['type' => 'select', 'options' => array(
                        '1' => 'Chưa thực hiện',
                        '2' => 'Đang thực hiện'
                    ),
                    'class' => 'form-control', 'label' => 'Trạng thái', 'default' => 1]
                )
                ?>
            </div>

            <div class="form-group col-sm-3 col-sm-offset-1">
                <?=
                $this->Form->control('users',['type' => 'select', 'options' => $listuser,
                    'multiple' => true,'label' => 'Thành viên', 'id' => 'list_member', 'size' => 8, 'class' => 'form-control']);
                ?>
            </div>
            <div class="form-group col-sm-1">
                <div style="display: table;margin: 0 auto;">
                    <br><br>
                    <a style="font-size: 25px;"><i id="first_prev" class="fa fa-chevron-circle-left"></i></a>
                    <br>
                    <br>
                    <a style="font-size: 25px;"><i id="first_next" class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>
            <?= $this->Form->create($member_project, ['type' => 'post']) ?>
            <div class="form-group col-sm-3">
                <?=
                $this->Form->control('members',['type' => 'select',
                    'multiple' => true,'label' => 'Thành viên tham gia:', 'id' => 'join_member', 'size' => 8, 'class' => 'form-control']);
                ?>
            </div>
            <div class="form-group col-sm-1" >
                <div style="display: table;margin: 0 auto;">
                    <br><br>
                    <a style="font-size: 25px;"><i id="second_prev" class="fa fa-chevron-circle-left"></i></a>
                    <br>
                    <br>
                    <a style="font-size: 25px;"><i id="second_next" class="fa fa-chevron-circle-right"></i></a>
                </div>
            </div>

            <div class="form-group col-sm-2">
                <?=
                $this->Form->control('leader',['type' => 'select',
                    'multiple' => true,'label' => 'Nhóm trưởng:', 'id' => 'list_leader', 'size' => 8, 'class' => 'form-control']);
                ?>
            </div>
        </div>
        <div class="box-footer">
            <?= $this->Form->button('Tạo mới', ['class' => 'btn btn-info', 'onclick' => "selectAll('join_member');selectAll('list_leader');", 'div' => false]); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
</section>