<div class="container">
        <div class="row form-group" style="">
            <div class="col-xs-2"></div>
            <div class="col-xs-8">
                <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="disabled"><a href="#step-1">
                    <p class="list-group-item-text">Import Excel</p>
                </a></li>
                <li class="disabled"><a href="#step-2">
                    <p class="list-group-item-text">Xác nhận nội dung</p>
                </a></li>
                <li class="active"><a href="#step-3">
                    <p class="list-group-item-text">Kết thúc import</p>
                </a></li>
                </ul>
            </div>
        </div>
        <div class="col-xs-2"></div>
        <div class="sw-container tab-content" style="min-height: 0px;">
            <div id="step-1" class="step-content" style="display: block;">  
            </div>
            <div id="step-2" class="step-content" style="display: block;">
            </div>
            <div id="step-3" class="step-content" style="display: block;text-align: center;width: 500px;margin:0 auto;">
                <div class="form-group">
                <h2 style="font-size: 20px;">Import thành công!</h2><br><br>
                <p>
                    <?= $this->Html->link('Quay lại trang chủ',array('controller' =>'Home', 'action'=>'index'), array('class' => 'btn btn-info')); ?>
                </p>
                </div>
            </div>  
        </div>        
</div>