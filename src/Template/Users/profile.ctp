<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
  User Profile
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <!-- <li><a href="#">Examples</a></li> -->
    <li class="active">User profile</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-3">
     
      <div class="box box-primary">
        <div class="box-body box-profile">
          
          <h3 class="profile-username text-center"><?php  echo $user_info['fullname'] ?></h3>
          <p class="text-muted text-center">Nick name: <?php echo $user_info['nickname'] ?></p>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>EMail Công ty: </b> <a class="pull-right"><?php echo $user_info['company_email'] ?></a>
            </li>
            <li class="list-group-item">
              <b>EMail Cá nhân: </b> <a class="pull-right"><?php echo $user_info['personal_email']; ?></a>
            </li>
            <li class="list-group-item">
              <b>Mã nhân viên: </b> <a class="pull-right"><?php echo $user_info['employee_code']; ?></a>

            </li>
            <li class="list-group-item">
              <b>CMND: </b> <a class="pull-right"><?php echo $user_info['identity_no']; ?></a>
            </li>
            <li class="list-group-item">
              <b>BirthDay: </b> <a class="pull-right"><?php echo $user_info['date_of_birth']; ?></a>
            </li>
          </ul>
          <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Thông tin cá nhân</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <strong><i class="fa fa-book margin-r-5"></i> Địa Chỉ: </strong>
          <p class="text-muted">
            <?php echo $user_info['address'] ?>
          </p>
          <hr>
          <strong><i class="fa fa-map-marker margin-r-5"></i> Slogan: </strong>
          <p class="text-muted"><?php echo $user_info['slogan'] ;

          ?></p>
          
          
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>

    <!-- /.col -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><?php echo $this->Html->link('Infomation',['controller'=>'users','action'=>'infomation']) ?></li>
          <?php

           if($user_info['username']=== $userLogin['username'] ){


           ?>
          <li><?php echo $this->Html->link('Change Infomation',['controller'=>'users','action'=>'change_infomation',$userLogin['user_id'],'onclick'=>'true']); ?></li>

              <li><?php echo $this->Html->link('Change Password',['controller'=>'users','action'=>'change_password',$userLogin['user_id'],'onclick'=>'true']) ?></li>
          
          <?php }?>
        </ul>
       
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->