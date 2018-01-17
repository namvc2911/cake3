<?php $this->assign('title', 'Dự án tham gia'); ?>
<section class="content-header">
    <h1>
        Danh sách dự án tham gia
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
                    $this->Form->control('np',[
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
                    'default' => '-1',
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
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách dự án</h3>
        </div>

        <div class="box-body">
            <table class="table datatable table-hover table-bordered table-condensed" >
                <thead>
                    <tr role = "row" style=" color:white; background:#008B8B">
                        <th class="text-center">STT</th>
                        <th class="text-center">Tên dự án</th>
                        <th class="text-center">Ngày bắt đầu</th>
                        <th class="text-center">Ngày kết thúc</th>
                        <th class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($projects)):
                    $current_page = $this->Paginator->params()['page'];
                    $limit = $this->Paginator->params()['perPage'];
                    $k = ($current_page - 1) * $limit;
                    foreach ($projects as $project):
                    $k++;
                    ?>
                    <tr>
                        <td class="text-center"><?= $k ?> </td>
                        <td class = "text-center"> <?= $this->Html->link($project->name, ['action' => 'view', $project->project_id]) ?></td>
                        <td class="text-center"> <?= date("Y-m-d", strtotime($project->start_date));?></td>
                        <?php if ($project->end_date != null): ?>
                        <td class="text-center"><?= date("Y-m-d", strtotime($project->end_date));?></td>
                        <?php else: ?>
                        <td class="text-center">-</td>
                        <?php endif; ?>
                        <?php
                        $status_txt = '';
                        if ($project->status == 3)
                        $status_txt = "Đã hoàn thành";
                        elseif($project->status == 0)
                        $status_txt = "Đã xóa";
                        elseif($project->status == 2)
                        $status_txt = "Đang thực hiện";
                        else
                        $status_txt = "Chưa thực hiện";
                        ?>
                        <td class="text-center"><?= $status_txt ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($projects): ?>
        <div class="box-footer clearfix" >
            <ul class="pagination pagination-sm no-margin pull-right">
                <li>
                    <?= $this->Paginator->prev('<<') ?>
                </li>
                <li>
                    <?= $this->Paginator->numbers(array('modulus' => 5)) ?>
                </li>
                <li>
                    <?= $this->Paginator->next('>>') ?>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</section>