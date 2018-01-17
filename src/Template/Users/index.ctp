<?php $this->assign('title', 'Danh sách nhân viên'); ?>
<section class="content-header">
    <h1>
       Danh sách nhân viên
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li class="active">Danh sách nhân viên</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Tìm kiếm</h3>
        </div>
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-horizontal']) ?>
        <div class="box-body">
            <div class="form-group">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-horizontal']) ?>
                <div class="date col-sm-3 col-sm-offset-1">
                    <?=
                    $this->Form->control('employee_code',[
                    'type' => 'text',
                    'label' => 'Mã nhân viên',
                    'class' => 'form-control pull-right',
                    'placeholder' => "Mã nhân viên",
                    'value' => isset($data['employee_code']) ? $data['employee_code'] : null
                    ])
                    ?>
                </div>

                <div class="date col-sm-4">
                    <?=
                    $this->Form->control('fullname',[
                    'type' => 'text',
                    'label' => 'Tên thành viên',
                    'class' => 'form-control pull-right',
                    'placeholder' => "Tên thành viên",
                    'value' => isset($data['fullname']) ? $data['fullname'] : null
                    ])
                    ?>
                </div>

                <div class="col-sm-1" style="margin-top: 25px">
                    <?= $this->Form->button('Tìm kiếm', ['class' => 'btn btn-info']); ?>
                </div>

                <?= $this->Html->link(__('Tạo mới'), ['action' => 'add'], ['class'=>'btn btn-success','style'=>'margin-top:25px']) ?>
                
                <?= $this->Form->end(); ?>
            </div>
        </div>
        <!--</form>-->
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách  thành viên</h3>
        </div>

        <div class="box-body">
            <table class="table datatable table-hover table-bordered table-condensed" >
                <thead>
                    <tr role = "row" style=" color:white; background:#008B8B">
                        <th class="text-center">Mã nhân viên</th>
                        <th class="text-center"><?= $this->Form->checkbox('select-all', ['id' => 'parent-checkbox', 'hiddenField' => false]); ?></th>
                        <th class="text-center">Nhóm</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Họ và Tên</th>
                        <th class="text-center">Khẩu Hiệu</th>
                        <th class="text-center">Vị Trí</th>
                        <th class="text-center">Thao Tác</th>
                    </tr>
                    <?php   foreach ($users as $user): ?>
                    <tr>
                        <td class="text-center"><?= $user->employee_code ?></td>
                        <td  class="text-center">
                            <input type="checkbox" class="child-checkbox" name="user[]" value= "<?= $user['user_id'] ?>"/>
                        </td>
                        <td class="text-center">
                            <?=
                            $this->Html->link(
                            $this->Text->truncate($user->groupID, 30, [
                            'ellipsis' => '...',
                            'exact' => true,
                            'html' => false,
                            ]), [
                            'action' => 'profile', $user->user_id
                            ])
                            ?>
                        </td>
                        <?php if ($user->company_email == null): ?>
                        <td class="text-center"> <?= $user->personal_email?></td>
                        <?php else: ?>
                        <td class="text-center"><?= $user->company_email?></td>
                        <?php endif; ?>
                        <td class="text-center">
                            <?=
                            $this->Html->link(
                            $this->Text->truncate($user->fullname, 30, [
                            'ellipsis' => '...',
                            'exact' => true,
                            'html' => false,
                            ]), [
                            'action' => 'profile', $user->user_id
                            ])
                            ?>
                        </td>
                        <td class = "text-center"> <?= $user->slogan ?></td>
                        <td class = "text-center">
                            <?php
                            $arrRole = [];
                            foreach ($user->roles as $roles) {
                            array_push($arrRole, $roles->value);
                            }
                            echo implode(", ", $arrRole);
                            ?>
                        </td>
                        <td class = "text-center">
                            <?= $this->Html->link('<i class="fa fa-edit fa-lg"></i> &nbsp;', ['action' => 'edit', $user->user_id], ['escape' => false]) ?>
                            | <?= $this->Form->postLink('&nbsp; <i class="fa fa-trash fa-lg"></i>', ['action' => 'delete', $user->user_id], ['confirm' => 'Bạn muốn xóa?','escape' => false]);?>
                        </td>

                    </tr>
                    <?php endforeach; ?>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                <?= $this->Paginator->first('<<') ?>
                <?= $this->Paginator->prev('<') ?>
                <?= $this->Paginator->numbers(array('modulus' => 4)); ?>
                <?= $this->Paginator->next('>') ?>
                <?= $this->Paginator->last('>>') ?>
            </ul>
        </div>
    </div>
    <!-- /.modal -->
</section>
