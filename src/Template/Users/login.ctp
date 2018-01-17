<?php $this->assign('title', 'Đăng nhập'); ?>
<?= $this->Form->create() ?>
    <div class="form-group has-feedback">
        <?= $this->Form->control('username', ['class' => 'form-control', 'placeholder' => 'Tên tài khoản', 'type' => 'text', 'label' => false]) ?>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <?= $this->Form->control('password', ['class' => 'form-control', 'placeholder' => 'Mật khẩu', 'type' => 'password', 'label' => false]) ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-xs-8">
        </div>
        <!-- /.col -->
        <div class="col-xs-12">
             <?= $this->Form->button(__('Đăng nhập'), ['type' => "submit",'class' => 'btn btn-primary btn-block btn-flat']); ?>

        </div>
       
        <!-- /.col -->
    </div>
<!--</form>-->
<?= $this->Form->end() ?>
