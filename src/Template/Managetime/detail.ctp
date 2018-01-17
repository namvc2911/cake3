<div class="container" style="position: relative;">
            <div class="sw-container tab-content" style="min-height: 0px;">
                <div id="step-1" class="step-content" style="display: block;">
                </div>
                <div id="step-2" class="step-content" style="display: block;">
                    <table class="table table-bordered" style="text-align: center;">
                        <tr>
                            <td>Ngày làm việc</td>
                            <td>Thời gian bắt đầu</td>
                            <td>Thời gian kết thúc</td>
                        </tr>  
                        <?php foreach($manageTimeArrayResult as $manageTime): ?>
                        <tr>
                            <td><?= $manageTime->work_date ?></td>
                            <td><?= $manageTime->start_time ?></td>
                            <td><?= $manageTime->end_time ?></td>                   
                        </tr>
                        <?php endforeach; ?>
                    </table>
                  <div class="form-group">
                    <p style="text-align: center;">
                    <?= $this->Html->link('Trở về',array('controller' =>'ManageTime', 'action'=>'confirm_excel'), array('class' => 'btn btn-info', 'style'=>'color:black; background-color:white;')); ?>                    
                    </p>
                  </div>
                </div>
                <div id="step-3" class="step-content" style="display: block;">
                </div>
            </div>
        </div> 
</div>