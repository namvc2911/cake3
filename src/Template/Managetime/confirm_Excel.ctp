<div class="container">
  <div class="row form-group" style="">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
      <ul class="nav nav-pills nav-justified thumbnail setup-panel">
        <li class="disabled"><a href="#step-1">
          <p class="list-group-item-text">Import Excel</p>
        </a></li>
        <li class="active"><a href="#step-2">
          <p class="list-group-item-text">Xác nhận nội dung</p>
        </a></li>
        <li class="disabled"><a href="#step-3">
          <p class="list-group-item-text">Kết thúc import</p>
        </a></li>
      </ul>
    </div>
    <div class="col-xs-2"></div>
      <div class="sw-container tab-content" style="min-height: 0px;">
      <div id="step-1" class="step-content" style="display: block;"></div>
      <div id="step-2" class="step-content" style="display: block;">
        <table class="table table-bordered" style="text-align: center;">
          <tr>
            <td>Mã nhân viên</td>
            <td>Tên nhân viên</td>
            <td>Xem chi tiết</td>
            <td>Trạng thái</td>
          </tr>  
          <?php foreach($manageTimeArrayResult as $manageTime): ?>
          <tr>
              <td><?= $manageTime->employee_code ?></td>
              <td><?= $manageTime->employee_name ?></td>
              <td><?= $this->Html->link('Xem',['action' => 'detail',$manageTime->employee_code]) ?></td>
              <td>Thành công</td>                    
          </tr>
          <?php endforeach; ?>
        </table>
        <div class="form-group">
          <p style="text-align: center;">
          <?= $this->Html->link('Trở về',array('controller' =>'ManageTime', 'action'=>'importExcel'), array('class' => 'btn btn-info', 'style'=>'color:black; background-color:white;')); ?>
          <?= $this->Html->link('Xác nhận',array('controller' =>'ManageTime', 'action'=>'insert'), array('class' => 'btn btn-info')); ?>
          </p>
        </div>
    </div>
    <div id="step-3" class="step-content" style="display: block;">
    </div>
    </div>
  </div>        
</div>