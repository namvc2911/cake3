<?php $this->assign('title', 'Làm thêm giờ'); ?>
<section class="content-header">
    <h1>
        Yêu cầu làm thêm của <b><i><?= $userLogin['fullname'] ?></i></b>
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ</a>', '/', ['escape' => false]) ?></li>
        <li class="active">Danh sách làm thêm của bản thân</li>
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
                <div class="col-sm-4 col-sm-offset-1">
                    <?=
                    $this->Form->control('np', [
                        'type' => 'text',
                        'label' => 'Tên dự án',
                        'class' => 'form-control pull-right',
                        'placeholder' => "Tên dự án",
                        'value' => isset($data['np']) ? $data['np'] : null
                    ])
                    ?>
                </div> 
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('s', ['type' => 'select', 'options' => array(
                            '-1' => 'Tất cả',
                            '1' => 'Chưa phê duyệt',
                            '2' => 'Chấp nhận',
                            '3' => 'Không chấp nhận'
                        ),
                        'class' => 'form-control', 'label' => 'Trạng Thái', 'default' => '-1',
                        'value' => isset($data['s']) ? $data['s'] : null]
                    )
                    ?>
                </div>
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('m', ['type' => 'select', 'options' => array(
                            '-1' => 'Tất cả',
                            '1' => 'Tháng 1',
                            '2' => 'Tháng 2',
                            '3' => 'Tháng 3',
                            '4' => 'Tháng 4',
                            '5' => 'Tháng 5',
                            '6' => 'Tháng 6',
                            '7' => 'Tháng 7',
                            '8' => 'Tháng 8',
                            '9' => 'Tháng 9',
                            '10' => 'Tháng 10',
                            '11' => 'Tháng 11',
                            '12' => 'Tháng 12',
                        ),
                        'class' => 'form-control', 'label' => 'Tháng', 'default' => $currentMonth,
                        'value' => isset($data['m']) ? $data['m'] : null]
                    )
                    ?>
                </div>
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('y', ['type' => 'select', 'options' => $arrYear,
                        'class' => 'form-control', 'label' => 'Năm', 'default' => $currentYear,
                        'value' => isset($data['y']) ? $data['y'] : null]
                    )
                    ?>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= $this->Form->button('Tìm kiếm', ['class' => 'btn btn btn-info']) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
    <!-- /.box -->

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách yêu cầu làm thêm</h3>
        </div>

        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped">
                <tr>
                    <th>Ngày làm</th>
                    <th>Dự án</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>T/gian nghỉ</th>
                    <th>Lý do</th>
                    <th>Trạng thái</th>
                    <th>Người phê duyệt</th>
                    <th>Lý do từ chối</th>
                    <th></th>
                </tr>

                <?php if (!empty($result)): ?>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= $row['day_of_overtime']->format('Y-m-d'); ?> </td>
                            <td><?= $row['name_project']; ?></td>
                            <td><?= $row['start_at'] ?></td>
                            <td><?= $row['end_at'] ?></td>
                            <td><?= $row['breaktime'] ?></td>
                            <td><?php
                                $reason = str_replace(array("\r", "\n", ". . "), '. ', $row['reason']);

                                echo $this->Text->truncate($reason, 50, [
                                    'ellipsis' => '...',
                                    'exact' => true,
                                    'html' => false,
                                ])
                                ?></td>
                            <?php if ($row['approve_status'] == 0): ?>
                                <td>Chưa phê duyệt</td>
                            <?php elseif ($row['approve_status'] == 1): ?>
                                <td>Chấp nhận</td>
                            <?php else: ?>
                                <td>Không chấp nhận</td>
                            <?php endif; ?>
                            <td><?= $row['name_approver'] ?></td>    
                            <?php if (isset($row['deny_reason']) || !empty($row['deny_reason'])): ?> 
                                <td><?php
                                    $reason = str_replace(array("\r", "\n", ". . "), '. ', $row['deny_reason']);

                                    echo $this->Text->truncate($reason, 50, [
                                        'ellipsis' => '...',
                                        'exact' => true,
                                        'html' => false,
                                    ])
                                    ?></td>
                            <?php else: ?>
                                <td>-</td>
                            <?php endif; ?>
                            <td><?php if ($row['approve_status'] == 0): ?>
                                    <?=
                                    $this->Html->link('<i class="fa fa-edit fa-lg"></i> &nbsp;', ['action' => 'editOvertime', $row['manage_overtime_id']], ['escape' => false])
                                    ?>
                                    |
                                <?php else: ?>
                                    <?= '&nbsp; &nbsp; &nbsp;' ?>
                                <?php endif; ?>
                                <?=
                                $this->Html->link('&nbsp; <i class="fa fa-trash fa-lg"></i>', '#', [
                                    'data-toggle' => 'modal',
                                    'data-target' => "#ConfirmDelete",
                                    'data-action' => \Cake\Routing\Router::url(
                                            ['action' => 'deleteOvertime', $row['manage_overtime_id']]
                                    ),
                                    'escape' => false
                                        ], false);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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

    <div class="modal fade" id="ConfirmDelete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Xóa làm thêm giờ</h4>
                </div>
                <div class="modal-body">
                    Bạn muốn xóa đơn làm thêm giờ này?
                </div>
                <div class="modal-footer">
                    <?= $this->Form->postLink('Xóa', ['action' => 'delete'], ['class' => 'btn btn-danger pull-left'], false); ?>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Hủy</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>
