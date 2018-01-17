<?= $this->Html->css('Groups.css') ?>
<div class="row">
  <div class="col-md-6"><h3>Nhóm</h3></div>
  <div class="col-md-6"><p align="right" style="margin-top: 28px;"><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Trang chủ</a>  &gt;  <a href="#">Nhóm</a></p>
  </div>
</div>
<div id="listGroup" class="step-content">
  	<div class="button">
	  	<div class="col-md-6"><h4>Danh sách các nhóm</h4></div>
	  	<div class="col-md-6 add"><p><?= $this->Html->link('Thêm mới',array('controller' => 'Groups', 'action' => 'add'),array('class' => 'btn btn-info', 'style'=>'color:#fff;')) ?></p></div>
	</div>
	<div class="margin">
		<table class="table">
		  	<thead>
			    <tr class="table-row">
			      <th>Nhóm</th>
			      <th>Leader</th>
			      <th>Sub Leader</th>
			      <th>Khẩu hiệu</th>   
			    </tr>
		    </thead>
		    <tbody>
		    	<?php foreach ($group_leader as $list) { ?>    	
		    	<tr>
			      	<td><?php echo $list['name']; ?></td>
			      	<td><?php echo $list['UsersTable1']['fullname']; ?></td>
			      	<td><?php echo $list['sub_leader']; ?></td>
			      	<td><?php echo $list['slogan']; ?></td>
		    	</tr>
		    	<?php
		    	}
		    	?> 
		    </tbody>
  		</table>
  		<div class="box-footer clearfix pull-right"  >
  			<ul class="pagination pagination-sm no-margin pull-right">
            <?= $this->Paginator->first('<<') ?>
                <?= $this->Paginator->prev('<') ?>
                <?= $this->Paginator->numbers(array('modulus' => 4)); ?>
                <?= $this->Paginator->next('>') ?>
                <?= $this->Paginator->last('>>') ?>
          </ul>
        </div>
	</div>
	
        
</div>


