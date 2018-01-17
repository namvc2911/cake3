<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Thay đổi mật khẩu</h3>
        </div>
        <div class="box-body">
            <?= $this->Form->create($user, ['type' => 'post']) ?>
            <div class="box-body">
                
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('old_password', [
                    'type' => 'password',
                    'label' => 'Mật Khẩu Cũ *',
                    'class' => 'form-control',
                    'placeholder' => "Mật Khẩu",
                    ])
                    ?>
                </div>
                
                <div class="form-group col-sm-offset-1 col-sm-6">
                    <?=
                    $this->Form->control('password', [
                    'type' => 'password',
                    'label' => 'Mật Khẩu Mới *',
                    'class' => 'form-control',
                    'placeholder' => "Mật Khẩu",
                    ])
                    ?>
                </div>
                
            </div>
            
            
            <div class="box-body">
                
                <div class="form-group">
                    <div class=" col-sm-5 col-sm-offset-1 ">
                        <?php echo $this->Html->link('Quay lại',['controller'=>'users','action'=>'profile',$userLogin['user_id'],],['class'=>'btn btn-success']) ?>
                    </div>
                    <div class="col-sm-5 col-sm-offset-1">
                    <?= $this->Form->button(__('Lưu lại '), ['type' => "submit",'class' => 'btn btn-success']); ?>

                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </section>