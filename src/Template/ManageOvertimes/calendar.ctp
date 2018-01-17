
<!DOCTYPE html>

<html>
    <head>
        <meta charset='utf-8'>

        <?= $this->Html->css('bootstrap.min') ?>
        <?= $this->Html->css('font-awesome.min') ?>
        <?= $this->Html->css('ionicons.min') ?>
        <?= $this->Html->css('AdminLTE.min') ?>
        <?= $this->Html->css('_all-skins.min') ?>
        <?= $this->Html->css('fullcalendar.min') ?>
        <?= $this->Html->css('fullcalendar.print.min', ['media' => 'print']) ?>

        <?= $this->Html->script('jquery.min') ?>

        <?= $this->Html->script('bootstrap.min') ?>

        <?= $this->Html->script('jquery.slimscroll.min') ?>

        <?= $this->Html->script('fastclick') ?>

        <?= $this->Html->script('adminlte.min') ?>

        <?= $this->Html->script('moment.min') ?>

        <?= $this->Html->script('fullcalendar.min') ?>

        <?= $this->Html->script('jquery-ui.min') ?>

        <?= $this->Html->script('demo') ?>

        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>

        <style>

            #calendar {
                max-width: 900px;
                margin: 0 auto;
            }
            #addrequest{
                text-align: center;
                text-decoration : underline;
                color: red;
            }

        </style>
    </head>
    <body>
        <?php $this->assign('title', 'Làm thêm giờ'); ?>
        <section class="content-header">
            <h1>
                Lịch làm thêm giờ
            </h1>
            <ol class="breadcrumb">
                <li><?= $this->Html->link('<i class="fa fa fa-home"></i> Trang chủ', '/', ['escape' => false]) ?></li>
                <li><?= $this->Html->link(' Yêu cầu làm thêm', ['action' => 'addrequest'], ['escape' => false]) ?></li>
                <li class="active">Lịch làm thêm giờ</li>
            </ol>
        </section>
        <section class="content">

            <?php if (!empty($allObj)): ?>
                <div class="box">
                    <div id='calendar'></div> 
                </div>
                <script type="text/javascript">

                    $(document).ready(function () {
                        var a = '<?php echo $allObj ?>';
                        var holidayObj = $.parseJSON(a);
                        $('#calendar').fullCalendar({
                            header: {
                                left: null,
                                center: 'title',
                            },
                            editable: false,
                            navLinks: true,
                            events: [],
                            eventRender: function (event, element) {
                                element.qtip({
                                    content: {
                                        title: {text: "Dự án "+ event.project},
                                        text: '' + event.time_start + '<br>' + event.reason +'<br>'+ event.approve_status
                                    },
                                    show: {solo: true},
                                    hide: { when: 'mouseover', delay: 100 },
                                    position: {
                                        my: 'center right',  // Position my top left...
                                        at: 'center left', // at the bottom right of...
                                    },
                                    style: {
                                        width: 200,
                                        padding: 5,
                                        color: 'black',
                                        textAlign: 'left',
                                        border: {
                                            width: 1,
                                            radius: 3
                                        },
                                        //tip: 'topRight',

                                        classes: {
                                            tooltip: 'ui-widget',
                                            tip: 'ui-widget',
                                            title: 'ui-widget-header',
                                            content: 'ui-widget-content'
                                        }
                                    }
                                });
                            }
                        });

                        $.each(holidayObj, function (i, item) {
                            user = item.name;
                            date = item.date;
                            project = item.project;
                            reason = item.reason;
                            sttapprover = item.sttapprover;

                            var sttapprover_txt = '';
                            if (sttapprover == 1) {
                                sttapprover_txt = "Được duyệt bởi: " + item.approver;
                                reason_txt = "* Lý do: " + reason.substring(0,50) + ".";
                                time_txt = "* Thời gian: " + item.start_at + " - " + item.end_at;

                                var eventObject = {
                                    title: project + ": " + user + " làm thêm" + "\n Lý do: " + reason + "\n" + sttapprover_txt,
                                    start: date,
                                    project: project,
                                    reason: reason_txt,
                                    time_start: time_txt,
                                    approve_status: "* Được duyệt bởi: " + item.approver + ".",
                                    color: '#00a65a',
                                    allDay: true
                                };
                            } else if (sttapprover == 2)   {
                                sttapprover_txt = "Từ chối bởi: " + item.approver;
                                deny_reason_txt = "* Lý do: " + reason.substring(0,50) + ".<br>" + "* Lý do từ chối: " + item.deny_reason.substring(0,50) +".";
                                time_txt = "* Thời gian: " + item.start_at + " - " + item.end_at;
                                
                                var eventObject = {
                                    title: project + ": " + user + " làm thêm" + "\n Lý do: " + reason + "\n" + sttapprover_txt + "\n" +
                                            "Lý do từ chối: " + item.deny_reason + "",
                                    start: date,
                                    project: project,
                                    time_start: time_txt,
                                    reason: deny_reason_txt,
                                    approve_status: "* Từ chối bởi: " + item.approver + ".",
                                    color: '#dd4b39',
                                    allDay: true
                                };
                            } else {
                                sttapprover_txt = "Chưa được phê duyệt";
                                reason_txt = "* Lý do: " + reason.substring(0,50)+".";
                                time_txt = "* Thời gian: " + item.start_at + " - " + item.end_at;
                                
                                var eventObject = {
                                    title: project + ": " + user + " làm thêm" + "\n Lý do: " + reason + "\n" + sttapprover_txt,
                                    project: project,
                                    start: date,
                                    time_start: time_txt,
                                    reason: reason_txt,
                                    approve_status: "* Chưa được phê duyệt" + ".",
                                    color: '#EEAD0E',
                                    allDay: true
                                };
                            }
                            $('#calendar').fullCalendar('renderEvent', eventObject, true);

                        });
                    });

                </script>
            <?php endif; ?>
        </section>
    </body>
</html>


