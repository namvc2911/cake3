<?= $this->Html->css('Groups.css') ?>
<div class="row" style="">
  <div class="col-md-6"><h3>Tạo nhóm</h3></div>
  <div class="col-md-6"><p align="right" style="margin-top: 28px;"><!-- <a href=""><i class="fa fa-home" aria-hidden="true"></i> Trang chủ</a> --><?php echo $this->Html->link('Trang Chủ','<i class="fa fa-home" aria-hidden="true"></i>',['controller'=>'home','action'=>'index']) ?>  &gt;  <?php echo $this->Html->link('Tạo Nhóm',['controller'=>'groups','action'=>'add']) ?></p>
  </div>
</div>
<section class="content">
	<div id="listGroup">
		<div class="box-header with-border">
			<h3 class="box-title">Tạo nhóm</h3>
		</div>
		<?php $this->Form->create($userGroupTable,['type'=>'post']); ?>
			
			<div class="box-body">
			<div class="form-group col-sm-offset-1 col-sm-10">
				<div class="input text">
				
					<?=
                    $this->Form->control('name', [
                    'type' => 'text',
                    'label' => 'Tên Nhóm*',
                    'class' => 'form-control',
                    'placeholder' => "Tên nhóm",
                    ])
                    ?>
				</div>            
			</div>
			<div class="form-group col-sm-offset-1 col-sm-10">
				<div class="input textarea">
					  <?=
                $this->Form->control('description', [
                    'type' => 'textarea',
                    'label' => 'Mô tả',
                    'class' => 'form-control',
                    'placeholder' => "Mô tả",
                    'rows' => 3,
                ])
                ?>
					
				</div>            
			</div>
			<div class="form-group col-sm-offset-1 col-sm-10">
				<div class="input textarea">
					<?=
                $this->Form->control('slogan', [
                    'type' => 'textarea',
                    'label' => 'Khẩu hiệu',
                    'class' => 'form-control',
                    'placeholder' => "Khẩu hiệu",
                    'rows' => 3,
                ])
                ?>
					
				</div>            
			</div>
			<div class="form-group col-sm-2 col-sm-offset-1">
				<div class="input select">
				<?=
                $this->Form->control('users',['type' => 'select', 'options' => $list_user,
                    'multiple' => true,'label' => 'Thành viên', 'id' => 'list_member', 'size' => 8, 'class' => 'form-control']);
                ?>
					
					
				</div>            
			</div>
			<div class="form-group col-sm-1" style="padding:0;width: 70px;">
				<div style="display: table;margin: 0 auto;">
					<br><br>
					<a style="font-size: 25px;"><i id="first_back" class="fa fa-chevron-circle-left"></i></a>
					<br>
					<br>
					<a style="font-size: 25px;"><i id="first_go" class="fa fa-chevron-circle-right"></i></a>
				</div>
			</div>
			<div style="display:none;"><input type="hidden" name="_method" value="POST"></div>            
			<div class="form-group col-sm-2">
				<div class="input select">
					<?=
                $this->Form->control('members',['type' => 'select',
                    'multiple' => true,'label' => 'Thành viên tham gia:', 'id' => 'join_member', 'size' => 8, 'class' => 'form-control']);
                ?>
					<!-- <label for="join_member">Thành viên tham gia</label>
					<input type="hidden" name="members" value="">
					<select name="members[]" multiple="multiple" id="join_member" size="8" class="form-control"></select> -->
				</div>            
			</div>
			<div class="form-group col-sm-1" style="padding:0;width: 70px;">
				<div style="display: table;margin: 0 auto;">
					<br><br>
					<a style="font-size: 25px;"><i id="second_back" class="fa fa-chevron-circle-left"></i></a>
					<br>
					<br>
					<a style="font-size: 25px;"><i id="second_go" class="fa fa-chevron-circle-right"></i></a>
				</div>
			</div>
			<div class="form-group col-sm-2">
				<div class="input select">
					 <?=
                $this->Form->control('sub_leader',['type' => 'select',
                    'multiple' => true,'label' => 'Sub Leader:', 'id' => 'sub_leader', 'size' => 8, 'class' => 'form-control']);
                ?>
					
				</div>            
			</div>
			<div class="form-group col-sm-1" style="padding:0;width: 70px;">
				<div style="display: table;margin: 0 auto;">
					<br><br>
					<a style="font-size: 25px;"><i id="third_back" class="fa fa-chevron-circle-left"></i></a>
					<br>
					<br>
					<a style="font-size: 25px;"><i id="third_go" class="fa fa-chevron-circle-right"></i></a>
				</div>
			</div>
			<div class="form-group col-sm-2">
				<div class="input select">
					 <?=
                $this->Form->control('leader',['type' => 'select',
                    'multiple' => true,'label' => 'Leader:', 'id' => 'leader', 'size' => 8, 'class' => 'form-control']);
                ?>
					
				</div>            
			</div>
			<div class="box-footer col-sm-6">
				 <input type="button" class="btn btn-info" value="Quay lại" onclick="history.back(-1)" /> 

                      <?= $this->Form->button('Tạo mới', ['class' => 'btn btn-info', 'onclick' => "selectAll('join_member');selectAll('list_leader');", 'div' => false]); ?>
                      
				
				<?= $this->Flash->render(); ?>
			</div>
		<?php $this->Form->end() ?>
	</div>
</section>

    
