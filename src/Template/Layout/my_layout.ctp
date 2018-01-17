<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->element('head') ?>
    </head>
    <body>

        <?= $this->element('header') ?>

        <div class="content-wrapper">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>

        <?php
        // jQuery
        echo $this->Html->script('jquery.min');

        // Bootstrap Core JavaScript
        echo $this->Html->script('bootstrap.min');

        echo $this->Html->script('jquery.slimscroll.min');

        echo $this->Html->script('fastclick');

        echo $this->Html->script('adminlte.min');

        echo $this->Html->script('moment.min');

        echo $this->Html->script('fullcalendar.min');

        echo $this->Html->script('jquery-ui.min');

        echo $this->Html->script('jquery.qtip.min');

        echo $this->Html->script('demo');

        echo $this->Html->script('bootstrap-timepicker');

        echo $this->Html->script('bootstrap-datepicker.min');

        echo $this->Html->script('bootstrap-datetimepicker.min');

        echo $this->Html->script('bootstrap-multiselect');
        ?>
        <script>
            $(function () {

                // Format Datepicker
                $('#day-start').datepicker({
                    autoclose: true,
                    orientation: "bottom",
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                });

                $('#day-end').datepicker({
                    autoclose: true,
                    orientation: "bottom",
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                });

                $('#day-start').datepicker().on('changeDate', function (e) {
                    $('#day-end').datepicker('setStartDate', $('#day-start').val());
                    $("#day-end").focus();
                });

                $('#datepicker-2').datepicker({
                    autoclose: false,
                    format: 'yyyy-mm-dd',
                    orientation: "bottom",
                    todayHighlight: true,
                })

                $('#datepicker-3').datepicker({
                    autoclose: false,
                    format: 'yyyy-mm-dd',
                    orientation: "bottom",
                    todayHighlight: true,
                })

                //Time Start And Time End Create Overtime
                $('#timepicker_start').datetimepicker({
                    format: 'HH:mm',
                    minDate: moment('00:00', 'HH:mm'),
                    maxDate: moment('24:00', 'HH:mm'),
                    useCurrent: false
                });
                $('#timepicker_end').datetimepicker({
                    format: 'HH:mm',
                    minDate: moment('00:00', 'HH:mm'),
                    maxDate: moment('24:00', 'HH:mm'),
                    useCurrent: false
                });
                $("#timepicker_start").on("dp.change", function (e) {
                    $('#timepicker_end').data("DateTimePicker").minDate(e.date);
                });
                $("#timepicker_end").on("dp.change", function (e) {
                    $('#timepicker_start').data("DateTimePicker").maxDate(e.date);
                });

                //Remove zero leading input number
                $('.breaktime').change(function (e) {
                    var number = $(this).val();
                    number = number.replace(/^0+/, '');
                    $(this).val(number);
                });


                //Send action to modal delete
                $('#ConfirmDelete').on('show.bs.modal', function (e) {
                    $(this).find('form').attr('action', $(e.relatedTarget).data('action'));
                });

                //Clear Data popup modal
                $('.close-deny-overtime').on('click', function (e) {
                    var $t = $(this),
                            target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

                    $(target)
                            .find("input,textarea,select")
                            .val('')
                            .end()
                            .find("input[type=checkbox], input[type=radio]")
                            .prop("checked", "")
                            .end();
                });
            })
            // Select all member element when create and fix project
            function selectAll(id)
            {
                selectBox = document.getElementById(id);

                console.log(selectBox);

                for (var i = 0; i < selectBox.options.length; i++)
                {
                    selectBox.options[i].selected = true;
                }
            }
            //Format Date Javascript
            function getFormattedDate(date) {
                var year = date.getFullYear();

                var month = (1 + date.getMonth()).toString();
                month = month.length > 1 ? month : '0' + month;

                var day = date.getDate().toString();
                day = day.length > 1 ? day : '0' + day;

                return year + '-' + month + '/' + day;
            }
            $(document).ready(function () {
                // Move Member Create and Fix Project
                $('#first_next').click(function () {
                    var $options = $("#list_member option:selected").clone();
                    $("#list_member option:selected").remove();
                    $('#join_member').append($options);
                });

                $('#first_prev').click(function () {
                    var $options = $("#join_member option:selected").clone();
                    $("#join_member option:selected").remove();
                    $('#list_member').append($options);
                });

                $('#second_next').click(function () {
                    var $options = $("#join_member option:selected").clone();
                    $("#join_member option:selected").remove();
                    $('#list_leader').append($options);
                });

                $('#second_prev').click(function () {
                    var $options = $("#list_leader option:selected").clone();
                    $("#list_leader option:selected").remove();
                    $('#join_member').append($options);
                });

                $('#first_go').click(function () {
                    var $options = $("#list_member option:selected").clone();
                    $("#list_member option:selected").remove();
                    $('#join_member').append($options);
                });

                $('#first_back').click(function () {
                    var $options = $("#join_member option:selected").clone();
                    $("#join_member option:selected").remove();
                    $('#list_member').append($options);
                });

                $('#second_go').click(function () {
                    var $options = $("#join_member option:selected").clone();
                    $("#join_member option:selected").remove();
                    $('#sub_leader').append($options);
                });

                $('#second_back').click(function () {
                    var $options = $("#sub_leader option:selected").clone();
                    $("#sub_leader option:selected").remove();
                    $('#join_member').append($options);
                });
                 $('#third_go').click(function () {
                    var $options = $("#sub_leader option:selected").clone();
                    $("#sub_leader option:selected").remove();
                    $('#leader').append($options);
                });

                $('#third_back').click(function () {
                    var $options = $("#leader option:selected").clone();
                    $("#leader option:selected").remove();
                    $('#sub_leader').append($options);
                });

                //Check all approve overtime
                $('#parent-checkbox').click(function () {
                    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
                });

                $('.child-checkbox').click(function () {
                    if ($('.child-checkbox :checked').length == $('.child-checkbox').length) {
                        $('#parent-checkbox').prop('checked', true);
                    } else {
                        $('#parent-checkbox').prop('checked', false);
                    }
                });

                $(".child-checkbox").change(function () {
                    if ($('.child-checkbox:checked').length == $('.child-checkbox').length) {
                        $('#parent-checkbox').prop('checked', true);
                    }
                });

                //Load member of project in Create Overtime By Leader
                $('#manage_project').change(function () {
                    var project_id = $("#manage_project option:selected").val();
                    $.ajax({url: "ManageOvertimes/getMemberProject",
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            project_id: project_id
                        },
                        success: function (result) {
                            var html = '';
                            html += '<div class="form-group has-feedback" id="ajax_userProject">';
                            html += '<div class="input select">';
                            html += '<label for="user-id">Thành viên dự án</label>';
                            html += '<select name="user_id" class="form-control" placeholder="User id" id="user-id">';
                            $.each(result, function (key, item) {
                                html += '<option value="';
                                html += item['user_id'];
                                html += '">';
                                html += item['name_user'];
                                html += '</option>';
                            });
                            html += '</select>';
                            html += '</div>';
                            html += '</div>';

                            $('#ajax_userProject').html(html);
                        }});
                });
                // Put id overtime to modal popup & delete old error message
                $('.deny-button').click(function () {
                    var overtime_id = $(this).data('id');
                    $(".deny-submit").attr("id", overtime_id);
                    $("label[for='deny-reason']").removeAttr('style');
                    $("div[style='color:red']").remove();
                });

                //Set multi select export csv
                $('#multi-user').multiselect({
                    includeSelectAllOption: true,
                    numberDisplayed: 2,
                    selectAllText: 'Tất cả',
                    nSelectedText: 'người đã chọn',
                    nonSelectedText: 'Chưa chọn',
                    allSelectedText: 'Tất cả',
                    buttonWidth: '220px',
                });

                $('#export-csv-submit').click(function () {
                    if ($('select#multi-user option:selected').length == 0) {
                        if ($('.error-message').length == false) {
                            $("#modal-user").find("div[class='input select']").after("<div style='color:red' class='error-message'>Hãy chọn thành viên</div>");
                        }
                    } else {
                        $('#export-csv-submit').attr('type', 'submit');
                        $('#export-csv-submit').click();
                    }
                });

                // Load Table After Deny-Success
                $('.deny-submit').click(function () {
                    var overtime_id = $(this).attr('id');
                    var deny_reason = $('.deny-reason-text').val();

                    if (deny_reason != '' && deny_reason != null) {
                        $.ajax({url: "Overtimes/denyOvertime",
                            dataType: 'json',
                            type: 'POST',
                            data: {
                                overtime_id: overtime_id,
                                deny_reason: deny_reason,
                            },
                            success: function (result) {
                                $("tr").remove('#' + result);
                            }
                        });
                        //Exit modal and check number of row to disable button submit
                        $('#deny-modal').modal('toggle');
                        if ($("#overtime-not-approved-table tr").length < 3) {
                            $(".btn-approve").prop("disabled", true);
                        }
                    } else {
                        //Message not empty deny reason
                        $("label[for='deny-reason']").css('color', 'red');
                        if ($('.error-message').length == false) {
                            $("div[class='modal-body']").find("textarea").after("<div style='color:red' class='error-message'>Hãy nhập lý do từ chối</div>");
                        }
                    }
                });
            });
        </script>

    </body>
    <script>
        $(function () {
            // Format datepicker
            $('#datepicker').datepicker({

                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                rule: {
                    datepicker: {
                        required: true,
                    },
                    agree: "required"

                },
                messages: {
                    datepicker: {
                        required: "Hãy nhập ngày làm thêm giờ"
                    }
                }

            });
        });
    </script>
</html>
