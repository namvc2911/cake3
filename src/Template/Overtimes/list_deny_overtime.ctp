<?php $this->assign('title', 'Danh sách làm thêm đã từ chối'); ?>
<section class="content-header">
    <h1>
        Làm thêm giờ đã từ chối
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ</a>', '/', ['escape' => false]) ?></li>
        <li class="active">Làm thêm giờ (Đã từ chối)</li>
    </ol>
</section>
<section class="content">

    <!-- /.box -->

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách làm thêm đã từ chối</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped">
                <tr>
                    <th>Dự án</th>
                    <th>Thành viên</th>
                    <th>Ngày làm</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>T/gian nghỉ</th>
                    <th>Lý do</th>
                    <th>Lý do từ chối</th>
                </tr>

                <?php
                if (!empty($result)) {

                    foreach ($result as $row):
                        ?>

                        <tr>
                            <td><?= $row['name_project']; ?></td>
                            <td><?= $row['name_member']; ?></td>
                            <td><?= $row['day_of_overtime']->format('Y-m-d'); ?> </td>
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
                            <td><?php
                                $deny_reason = str_replace(array("\r", "\n", ". . "), '. ', $row['deny_reason']);

                                echo $this->Text->truncate($deny_reason, 50, [
                                    'ellipsis' => '...',
                                    'exact' => true,
                                    'html' => false,
                                ])
                                ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php } ?>
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
</section>
