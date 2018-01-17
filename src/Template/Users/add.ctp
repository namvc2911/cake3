<?php $this->assign('title', 'Thêm thành viên'); ?>
<section class="content-header">
    <h1>
        Thêm Thành Viên
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li class="active">Thêm Thành Viên</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Thêm Thành Viên</h3>
        </div>
        <div class="box-body">
            <?= $this->Form->create($user, ['type' => 'post']) ?>
            <div class="box-body">
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('username', [
                    'type' => 'text',
                    'label' => 'Tên Đăng Nhập*',
                    'class' => 'form-control',
                    'placeholder' => "Tên Đăng Nhập",
                    ])
                    ?>
                </div>
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('password', [
                    'type' => 'password',
                    'label' => 'Mật Khẩu*',
                    'class' => 'form-control',
                    'placeholder' => "Mật Khẩu",
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('fullname', [
                    'type' => 'text',
                    'label' => 'Tên Đầy Đủ*',
                    'class' => 'form-control',
                    'placeholder' => "Tên Đầy Đủ",
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('nickname', [
                    'type' => 'text',
                    'label' => 'Nickname',
                    'class' => 'form-control',
                    'placeholder' => 'Nickname',
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('personal_email', [
                    'type' => 'text',
                    'label' => 'Email*',
                    'class' => 'form-control',
                    'placeholder' => 'Email cá nhân',
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('employee_code', [
                    'type' => 'text',
                    'label' => 'Mã Nhân Viên',
                    'class' => 'form-control',
                    'placeholder' => 'Mã Nhân Viên',
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('company_email', [
                    'type' => 'text',
                    'label' => 'Email công ty',
                    'class' => 'form-control',
                    'placeholder' => "Email công ty",
                    ])
                    ?>
                </div>
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('image', [
                    'type' => 'file',
                    'label' => 'Ảnh',
                    'class' => 'form-control',
                    
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('gender', ['type' => 'select', 'options' => array(
                    '0' => 'Nam',
                    '1' => 'Nữ',
                    '2'=>'Other'
                    ),
                    'class' => 'form-control', 'label' => 'Giới tính*', 'default' => 2]);
                    ?>
                </div>

                <div class="col-sm-6 col-sm-offset-1">
                    <?=
                    $this->Form->control('date_of_birth', [
                    'id' => 'datepicker',
                    'class' => 'form-control pull-right',
                    'placeholder' => 'Ngày sinh',
                    'type' => 'text',
                    'label' => "Ngày sinh*"])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('identity_no', [
                    'type' => 'text',
                    'label' => 'Số CMND*',
                    'class' => 'form-control',
                    'placeholder' => 'Số CMND',
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('address', [
                    'type' => 'text',
                    'label' => 'Địa chỉ',
                    'class' => 'form-control',
                    'placeholder' => 'Địa chỉ'
                    ])
                    ?>
                </div>

                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('slogan', [
                    'type' => 'textarea',
                    'label' => 'Khẩu Hiệu',
                    'class' => 'form-control',
                    ])
                    ?>
                </div>

                <div class="col-sm-6 col-sm-offset-1">
                    <?=
                    $this->Form->control('trial_date', [
                    'id' => 'datepicker-3',
                    'type' => 'text',
                    'class' => 'form-control pull-right',
                    'placeholder' => 'Ngày thử việc',
                    'label' => "Ngày thử việc"])
                    ?>
                </div>

                <div class="form-group  col-sm-6 col-sm-offset-1">
                    <?=
                    $this->Form->control('official_date', [
                    'id' => 'datepicker-2',
                    'type' => 'text',
                    'class' => 'form-control pull-right',
                    'placeholder' => 'Ngày chính thức',
                    'label' => "Ngày chính thức"])
                    ?>

                </div>
            </div>

            <?php
            foreach($opt as $kye => $value){
            $opt_key[] = $kye;
            $opt_value[] = $value;
            }

            $count=count($opt);
            for($i=0; $i<$count; $i++){
            ?>
            <div class="box-body">
                <div class="col-sm-6 col-sm-offset-1">
                    <?= $this->Form->control('UserMeta.' . $i . '.meta_value', [
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => $opt_value[$i],
                    'id'=>'list_value',
                    ]);
                    ?>
                </div>
            </div>

            <div class="col-sm-2">
                <?= $this->Form->control('UserMeta.' . $i . '.meta_key', [
                'type' => 'hide',
                'value'=> $opt_key[$i],
                ]);
                ?>
            </div>

            <?php } ?>

            <div class=" box-body">
                <div class="form-group col-sm-2 col-sm-offset-1">
                    <?=
                    $this->Form->control(' ',['type' => 'select', 'options' => $roles,
                    'multiple' => true,'label' => 'Vị trí', 'size' => 6,'id' => 'list_member','class' => 'form-control']);
                    ?>
                </div>
                <div class="form-group col-sm-2">
                    <div style="display: table;margin: 0 auto;">
                        <br><br>
                        <a style="font-size: 25px;"><i id="first_prev" class="fa fa-chevron-circle-left"></i></a>
                        <br>
                        <br>
                        <a style="font-size: 25px;"><i id="first_next" class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
                <?= $this->Form->create($roleUser, ['type' => 'post']) ?>
                <div class="form-group col-sm-2">
                    <?=
                    $this->Form->control('userRoles',['type' => 'select',
                    'multiple' => true,'label' => 'Vị trí', 'id' => 'join_member', 'size' => 6, 'class' => 'form-control']);
                    ?>
                </div>
            </div>

            <div class="box-body">
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('group_id', ['type' => 'select', 'options' => array(
                    '1' => 'Administrator',
                    '2' => 'PHP',
                    '3'=>'IOS',
                    '4'=>'JAVA',
                    '5'=>'BrSE',
                    '6'=>'TTS'
                    ),
                    'class' => 'form-control', 'label' => 'Nhóm','empty'=>'-- Chọn --']
                    )
                    ?>
                </div>

                <div class="form-group">
                    <div class=" col-sm-5 col-sm-offset-1 ">
                        <input type="button" class="btn btn-info" value="Quay lại" onclick="history.back(-1)" />
                    </div>
                    <div class="col-sm-5 ">
                        <?= $this->Form->button('Lưu lại', ['class' => 'btn btn-info', 'onclick' => "selectAll('join_member');", 'div' => false]); ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
</section>