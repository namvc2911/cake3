<?php $this->assign('title', 'Thông tin dự án: '. $projects->name); ?>
<section class="content-header">
    <h1>
        Thông tin dự án
    </h1>
    <ol class="breadcrumb">
        <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>

        <li class="active">Thông tin dự án</li>
    </ol>
</section>

<section class="content">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Thông tin dự án</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr  role = "row" style="color: white; background: #008B8B">
                        <th class = "text-center" >Tên dự án</th>
                        <th class = "text-center" >Thành viên</th>
                        <th class = "text-center" >Trưởng nhóm</th>
                    </tr>
                </thead>
                <tbody>
                    <tr >
                        <td class = "text-center" ><?= $projects->name ?></td>
                        <td class = "text-center" >
                            <?php
                            foreach ($users as $user) {
                                $members[] = $user->fullname;
                                $member = implode(', ', $members);
                            }
                            echo $member;
                            ?> 
                        </td>
                        <td class = "text-center" >
                            <?php
                            if($users1){
                                foreach ($users1 as $user1) {
                                    $leaders[] = $user1->fullname;
                                    $leader = implode(', ', $leaders);
                                }
                                echo $leader;
                            } else {
                                echo "";
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>