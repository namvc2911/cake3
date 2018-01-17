<?php $this->assign('title', 'Phê duyệt làm thêm'); ?>
<section class="content-header">
    <h1>
        Phê duyệt yêu cầu làm thêm
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ</a>', '/', ['escape' => false]) ?></li>
        <li class="active">Phê duyệt yêu cầu làm thêm</li>
    </ol>
</section>
<section class="content">
    <!-- /.box -->

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách làm thêm chưa phê duyệt</h3>
        </div>
        <!-- /.box-header -->
        <?= $this->Form->create(null, ['class' => 'form-horizontal']) ?>
        <div class="box-body no-padding">
            <table class="table table-striped" id="overtime-not-approved-table" >
                <tr>
                    <th style="width: 50px"><?= $this->Form->checkbox('select-all', ['id' => 'parent-checkbox', 'hiddenField' => false]); ?></th>
                    <th>Dự án</th>
                    <th>Thành viên</th>
                    <th>Ngày làm</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>T/gian nghỉ</th>
                    <th>Lý do</th>
                    <th></th>

                </tr>

                <?php if ($result != null): ?>
                    <?php foreach ($result as $row): ?>
                        <tr id="<?= $row['manage_overtime_id'] ?>" class="check-row">
                            <td>
                                <input type="checkbox" class="child-checkbox" name="manage_overtime[]" value= "<?= $row['manage_overtime_id'] ?>"/>
                            </td>
                            <td><?= $row['name_project']; ?></td>
                            <td><?= $row['name_member']; ?> </td>
                            <td><?= $row['day_of_overtime']->format('Y-m-d'); ?> </td>
                            <td><?= $row['start_at']; ?> </td>
                            <td><?= $row['end_at']; ?> </td>
                            <td><?= $row['breaktime'] ?></td>
                            <td>
                                <?php
                                $reason = str_replace(array("\r", "\n", ". . "), '. ', $row['reason']);

                                echo $this->Text->truncate($reason, 50, [
                                    'ellipsis' => '...',
                                    'exact' => true,
                                    'html' => false,
                                ]);
                                ?>
                            </td>
                            <td><?=
                                $this->Form->button('Từ chối', [
                                    'type' => 'button',
                                    'class' => 'btn btn-block btn-danger btn-sm deny-button',
                                    'data-toggle' => 'modal',
                                    'data-target' => "#deny-modal",
                                    'data-id' => $row['manage_overtime_id']
                                ])
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </table>
        </div>

        <div class="box-footer">
            <?php
            if ($result != null)
                echo $this->Form->button('Xác nhận', ['class' => 'btn btn-info btn-approve']);
            else
                echo $this->Form->button('Xác nhận', ['class' => 'btn btn-info btn-approve', 'disabled' => true]);
            ?>
            <ul class="pagination pagination-sm no-margin pull-right">
                <?= $this->Paginator->first('<<') ?>
                <?= $this->Paginator->prev('<') ?>
                <?= $this->Paginator->numbers(array('modulus' => 4)); ?>
                <?= $this->Paginator->next('>') ?>
                <?= $this->Paginator->last('>>') ?>
            </ul>
        </div>

        <?= $this->Form->end(); ?>
        <!-- /.box-body -->
    </div>

    <div class="modal fade" id="deny-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-deny-overtime" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Từ chối làm thêm giờ</h4>
                </div>
                <div class="modal-body">
                    <?=
                    $this->Form->control('deny_reason', [
                        'class' => 'form-control deny-reason-text',
                        'type' => 'textarea',
                        'label' => "Lý do từ chối*"])
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left close-deny-overtime" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger deny-submit" id="">Từ chối</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>
