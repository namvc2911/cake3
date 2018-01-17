<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->element('head_login') ?>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <b>BBS</b>Movertime
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Đăng nhập để truy cập hệ thống</p>
                <?= $this->Flash->render() ?>

                <?= $this->fetch('content') ?>
            </div>
        </div>
        <?php
        // jQuery
        echo $this->Html->script('jquery.min');

        // Bootstrap Core JavaScript
        echo $this->Html->script('bootstrap.min');

        echo $this->Html->script('icheck.min');
        ?>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>