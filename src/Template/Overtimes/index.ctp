<?php $this->assign('title', 'Làm thêm giờ'); ?>
<section class="content-header">
    <h1>
        Làm thêm giờ
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ</a>', '/', ['escape' => false]) ?></li>
        <li class="active">Làm thêm giờ</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Tìm kiếm</h3>
        </div>
        <?= $this->Form->create($overtimes, ['class' => 'form-horizontal']) ?>
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-1">
                    <?php
                    $arrUser = array();
                    if ($userLogin['role'] != 'MEMBERS') {
                    foreach ($users as $user) {
                    $arrUser[-1] = 'Tất cả';
                    $arrUser[$user->user_id] = $user->fullname;
                    }

                    echo $this->Form->control('user', [
                    'type' => 'select',
                    'options' => $arrUser,
                    'class' => 'form-control',
                    'label' => 'Thành viên',
                    'default' => -1,
                    'readonly'
                    ]);
                    } else {
                    echo $this->Form->control('userName', [
                    'type' => 'text',
                    'value' => $userLogin['fullname'],
                    'class' => 'form-control',
                    'label' => 'Thành viên',
                    'readonly'
                    ]);
                    echo $this->Form->control('user', [
                    'type' => 'hidden',
                    'value' => $userLogin['user_id'],
                    'class' => 'form-control',
                    ]);
                    }


                    ?>
                </div>
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('approve_status', ['type' => 'select', 'options' => array(
                    '-1' => 'Tất cả',
                    '0' => 'Chưa được duyệt',
                    '1' => 'Đã được duyệt',
                    '2' => 'Đã từ chối'
                    ),
                    'class' => 'form-control', 'label' => 'Trạng Thái', 'default' => '-1']
                    )
                    ?>
                </div>
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('month', ['type' => 'select', 'options' => array(
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
                    'class' => 'form-control', 'label' => 'Tháng', 'default' => $currentMonth]
                    )
                    ?>
                </div>
                <div class="col-sm-2">
                    <?=
                    $this->Form->control('year', ['type' => 'select', 'options' => $arrYear,
                    'class' => 'form-control', 'label' => 'Năm', 'default' => $currentYear]
                    )
                    ?>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= $this->Form->button('Tìm kiếm', ['class' => 'btn btn-info']) ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>
    <!-- /.box -->
    <?php
    unset($arrUser[-1]);
    ?>
    <div class="box">
        <div class="box-header">
            <h3 class="box-title col-sm-11" style="margin-top: 5px;">Danh sách làm thêm</h3>
            <?php //$this->Form->button('Xuất CSV', ['type' => 'button', 'class' => 'btn btn-info btn-sm col-xs-1']) ?>
            <?=
            $this->Html->link(
            $this->Form->button('Xuất CSV', ['type' => 'button', 'class' => 'btn btn-info btn-sm col-xs-1'])
            , '#', [
            'data-toggle' => 'modal',
            'id' => 'export-csv-link',
            'data-target' => "#export-csv",
            'escape' => false
            ], false);
            ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-condensed">
                <tr>
                    <th>Ngày</th>
                    <th>Người thực hiện</th>
                    <th>Tên dự án</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>T/gian nghỉ</th>
                    <th>Lý do</th>
                    <th>Trạng thái</th>
                    <th>Lý do từ chối</th>
                    <th>Người phê duyệt</th>
                </tr>
                <!-- Row[0] is Date of Month  -->
                <!-- Row[1] is Information of Overtime  -->
                <?php foreach ($result as $row): ?>
                <!-- Different weekend with normal day by pink color -->
                <!-- Weekend -->
                <?php
                if (date('D', strtotime($row[0])) == 'Sat' ||
                date('D', strtotime($row[0])) == 'Sun'):
                ?>
                <tr style="background-color: #FFDFDD">
                    <td><?= $row[0] ?></td>
                    <?php if ($row[1]): ?>
                    <?php
                    // Count number of overtime in day
                    $numElement = count($row[1]);
                    $i = 0;
                    ?>
                    <?php foreach ($row[1] as $key => $overtime): ?>
                    <td><?= $overtime->created_by ?> </td>
                    <td><?= $overtime->name_project ?></td>
                    <td><?= $overtime->start_at ?></td>
                    <td><?= $overtime->end_at ?></td>
                    <td><?= $overtime->breaktime ?></td>
                    <td><?php
                        $reason = str_replace(array("\r", "\n", ". . "), '. ', $overtime->reason);

                        echo $this->Text->truncate($reason, 50, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ])
                        ?>
                    </td>
                    <?php if ($overtime->approve_status == 1): ?>
                    <td>Đã được duyệt</td>
                    <?php elseif ($overtime->approve_status == 2): ?>
                    <td>Bị từ chối</td>
                    <?php else: ?>
                    <td>Chưa được duyệt</td>
                    <?php endif; ?>
                    <?php if (isset($overtime->deny_reason) || !empty($overtime->deny_reason)): ?>
                    <td><?php
                        $reason = str_replace(array("\r", "\n", ". . "), '. ', $overtime->deny_reason);

                        echo $this->Text->truncate($reason, 50, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ])
                        ?></td>
                    <?php else: ?>
                    <td> - </td>
                    <?php endif; ?>
                    <?php
                    if ($overtime->name_approver != null) {
                    echo "<td>" . $overtime->name_approver . "</td>";
                    } else {
                    echo "<td>-</td>";
                    }
                    ?>
                    <?php
                    //Set color for overtime 2nd in day
                    if (++$i != $numElement)
                    echo "</tr><tr style='background-color: #FFDFDD'><td></td>";
                    ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <?php endif; ?>
                </tr>
                <!-- Normal day -->
                <?php else: ?>
                <tr>
                    <td><?= $row[0] ?></td>
                    <?php if ($row[1]): ?>
                    <?php
                    // Count number of overtime in day
                    $numElement = count($row[1]);
                    $i = 0;
                    ?>
                    <?php foreach ($row[1] as $key => $overtime): ?>
                    <td><?= $overtime->created_by ?> </td>
                    <td><?= $overtime->name_project ?></td>
                    <td><?= $overtime->start_at ?></td>
                    <td><?= $overtime->end_at ?></td>
                    <td><?= $overtime->breaktime ?></td>
                    <td><?php
                        $reason = str_replace(array("\r", "\n", ". . "), '. ', $overtime->reason);

                        echo $this->Text->truncate($reason, 50, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ])
                        ?>
                    </td>
                    <?php if ($overtime->approve_status == 1): ?>
                    <td>Đã được duyệt</td>
                    <?php elseif ($overtime->approve_status == 2): ?>
                    <td>Bị từ chối</td>
                    <?php else: ?>
                    <td>Chưa được duyệt</td>
                    <?php endif; ?>
                    <?php if (isset($overtime->deny_reason) || !empty($overtime->deny_reason)): ?>
                    <td><?php
                        $reason = str_replace(array("\r", "\n", ". . "), '. ', $overtime->deny_reason);

                        echo $this->Text->truncate($reason, 50, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ])
                        ?></td>
                    <?php else: ?>
                    <td> - </td>
                    <?php endif; ?>
                    <?php
                    if ($overtime->name_approver != null) {
                    echo "<td>" . $overtime->name_approver . "</td>";
                    } else {
                    echo "<td>-</td>";
                    }
                    ?>
                    <?php
                    if (++$i != $numElement)
                    echo "</tr><tr><td></td>";
                    ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <td> - </td>
                    <?php endif; ?>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                <tr>
                    <th>Ngày</th>
                    <th>Người thực hiện</th>
                    <th>Tên dự án</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>T/gian nghỉ</th>
                    <th>Lý do</th>
                    <th>Trạng thái</th>
                    <th>Lý do từ chối</th>
                    <th>Người phê duyệt</th>
                </tr>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
</section>

<div class="modal fade" id="export-csv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close hide-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Xuất file CSV</h4>
            </div>
            <?= $this->Form->create(null, ['url' => ['controller' => 'Overtimes', 'action' => 'exportCSV']]) ?>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5" id="modal-user">
                            <label for="user">Thành viên</label>
                            <?php
                            echo $this->Form->control('user', [
                            'type' => 'select',
                            'options' => $arrUser,
                            'id' => 'multi-user',
                            'class' => 'form-control',
                            'label' => false,
                            'multiple' => true,
                            ]);
                            ?>
                        </div>

                        <div class="col-sm-4">
                            <?=
                            $this->Form->control('month', ['type' => 'select', 'options' => array(
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
                            'class' => 'form-control', 'id' => 'month-modal', 'label' => 'Tháng', 'default' => $currentMonth]
                            )
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?=
                            $this->Form->control('year', ['type' => 'select', 'options' => $arrYear,
                            'class' => 'form-control', 'id' => 'year-modal', 'label' => 'Năm', 'default' => $currentYear]
                            )
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= $this->Form->button('Xuất file', ['type' => 'button', 'id' => 'export-csv-submit', 'class' => 'btn btn-info pull-left']); ?>
                <button type="button" class="btn btn-default pull-left hide-modal" data-dismiss="modal">Hủy</button>
            </div>
            <?= $this->Form->end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

