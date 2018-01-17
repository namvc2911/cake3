<div class="container" style="position: relative;">
        <div class="row" style="">
          <div class="col-md-6"><h3>Import bảng chấm công</h3></div>
          <div class="col-md-6"><p align="right" style="margin-top: 28px;"><a href="#"><img src="home.png"> Trang chủ</a>  &gt;  <a href="#">Import bảng chấm công</a></p>
          </div>
        </div>
        <br>
        <div class="row form-group" style="margin-bottom: 10px;">
          <div class="col-xs-2"></div>
          <div class="col-xs-8">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
              <li class="active"><a href="#step-1">
                <p class="list-group-item-text">Import Excel</p>
              </a></li>
              <li class="disabled"><a href="#step-2">
                <p class="list-group-item-text">Xác nhận nội dung</p>
              </a></li>
              <li class="disabled"><a href="#step-3">
                <p class="list-group-item-text">Kết thúc import</p>
              </a></li>
            </ul>
          </div>
          <div class="col-xs-2"></div>
        </div>
        <div>
          <div class="col-xs-2"></div>
          <div class="col-xs-8 sw-container tab-content" style="padding: 0px;height: 70px;">
            <div id="step-1" class="step-content" style="padding: 5px;">
              <?= $this->Flash->render(); ?>
              <table style="border: 1px solid #d2d6de;background-color: #fff;width: 100%">
                <tr>
                  <td style="border: 1px solid #d2d6de;padding-left: 10px;">File Excel</td>
                  <td style="padding-left: 10px; height: 88px;"><?php 
                  echo $this->Form->create('',array('type' => 'file','url'=>array('controller'=>'ManageTime','action'=>'importExcel')));
                  echo $this->Form->input('importFile', ['type' => 'file']);
                  echo $this->Form->button('Import',['class' => 'btn btn-info','style' => 'position: absolute;left: 0px; right: 0px;top: 150px;width: 88px; margin: 0px auto;', 'type' => 'submit']);
                  echo $this->Form->end();       
                  ?></td>   
                </tr>   
              </table>
            </div>
            <div class="col-xs-2"></div>
            <div id="step-2" class="step-content" style="display: block;">
            </div>
            <div id="step-3" class="step-content" style="display: block;">
            </div>
          </div>
          <div class="col-xs-2"></div>
        </div>
</div>
