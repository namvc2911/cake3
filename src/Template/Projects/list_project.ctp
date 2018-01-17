<?php $this->assign('title', 'Danh sách dự án'); ?>
<section class="content-header">
    <h1>
        Danh sách dự án
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
        <li class="active">Danh sách dự án</li>
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
                <div class="date col-sm-4 col-sm-offset-1">
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
                <div class="date col-sm-2">
                    <?=
                    $this->Form->control('s', [
                            'type' => 'select', 'options' => array(
                            '-1' => 'Tất cả',
                            '4' => 'Đã xóa',
                            '1' => 'Chưa thực hiện',
                            '2' => 'Đang thực hiện',
                            '3' => 'Đã hoàn thành'
                            ),
                            'label'=>'Trạng thái dự án',
                            'class' => 'form-control pull-right',
                            'default' => -1,
                            'placeholder' => "Trạng thái dự án",
                            'value' => isset($data['s']) ? $data['s'] : null
                    ])
                    ?>        
                </div>      
                <div class="date col-sm-2">
                    <?=
                    $this->Form->control('ds', [
                        'type' => 'text',
                        'label' => 'Ngày bắt đầu',
                        'class' => 'form-control pull-right',
                        'id' => "day-start",
                        'placeholder' => "Ngày bắt đầu",
                        'value' => isset($data['ds']) ? $data['ds'] : null
                    ])
                    ?>
                </div>
                <div class="date col-sm-2">
                    <?=
                    $this->Form->control('de', [
                        'type' => 'text',
                        'label' => 'Ngày kết thúc',
                        'class' => 'form-control pull-right',
                        'id' => "day-end",
                        'placeholder' => "Ngày kết thúc",
                        'value' => isset($data['de']) ? $data['de'] : null
                    ])
                    ?>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?= $this->Form->button('Tìm kiếm', ['class' => 'btn btn-info']); ?>
            </div>
            <!-- /.box-footer -->
        </div>
        <!--</form>-->
        <?= $this->Form->end(); ?>
    </div>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách dự án</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Tên dự án</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Ngày bắt đầu</th>
                    <th class="text-center">Ngày kết thúc</th>
                    <th>Mô tả</th>
                    <th>Nhóm trưởng</th>
                    <?php if ($userLogin['role'] != 'MEMBERS'): ?>
                    <th>Thao tác</th>
                    <?php endif; ?>
                </tr>
                <?php
                $current_page = $this->Paginator->params()['page'];
                $limit = $this->Paginator->params()['perPage'];
                $count = ($current_page - 1) * $limit;
                ?>
                <?php foreach ($projects as $key => $project): ?>
                <tr>
                    <td><?= ++$count ?></td>
                    <td>
                        <?=
                        $this->Html->link(
                        $this->Text->truncate($project->name, 30, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ]), [
                        'action' => 'viewProject', $project->project_id
                        ])
                        ?>
                    </td>
                    <?php if ($project->status == 0): ?>
                    <td class="text-center">Đã xóa</td>
                    <?php elseif ($project->status == 1): ?>
                    <td class="text-center">Chưa thực hiện</td>
                    <?php elseif ($project->status == 2): ?>
                    <td class="text-center">Đang thực hiện</td>
                    <?php elseif ($project->status == 3): ?>
                    <td class="text-center">Đã hoàn thành</td>
                    <?php else : ?>
                    <td class="text-center">Unknown</td>
                    <?php endif; ?>
                    <?php if ($project->start_date != NULL): ?>
                    <td class="text-center"><?= $project->start_date->format('Y-m-d') ?></td>
                    <?php else: ?>
                    <td class="text-center">NULL</td>
                    <?php endif; ?>
                    <?php if ($project->end_date != NULL): ?>
                    <td class="text-center"><?= $project->end_date->format('Y-m-d') ?></td>
                    <?php else: ?>
                    <td class="text-center">-</td>
                    <?php endif; ?>
                    <td>
                        <?=
                        $this->Text->truncate($project->description, 50, [
                        'ellipsis' => '...',
                        'exact' => true,
                        'html' => false,
                        ])
                        ?>
                    </td>
                    <td>
                        <?php
                        $arrLeader = [];
                        foreach ($project->users as $leader) {
                        array_push($arrLeader, $leader->fullname);
                        }
                        echo implode(", ", $arrLeader);
                        ?>
                    </td>
                    <?php if ($userLogin['role'] != 'MEMBERS'): ?>
                    <td><?= $this->Html->link('<i class="fa fa-edit fa-lg"></i> &nbsp;', ['action' => 'edit', $project->project_id], ['escape' => false]) ?>
                        | <?php
                        //$this->Form->postLink('&nbsp; <i class="fa fa-trash fa-lg"></i>', ['action' => 'deleteProject', $project->project_id], ['confirm' => 'Bạn muốn xóa?','escape' => false]);
                        echo $this->Html->link('&nbsp; <i class="fa fa-trash fa-lg"></i>', '#', [
                        'data-toggle' => 'modal',
                        'data-target' => "#ConfirmDelete",
                        'data-action' => \Cake\Routing\Router::url(
                        ['action' => 'deleteProject', $project->project_id]
                        ),
                        'escape' => false
                        ], false);
                        ?>
                    </td>
                    <?php else: ?>
                    <td></td>
                    <?php endif; ?>

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

    <div class="modal fade" id="ConfirmDelete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Xóa dự án</h4>
                </div>
                <div class="modal-body">
                    Bạn muốn xóa dự án này?
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
