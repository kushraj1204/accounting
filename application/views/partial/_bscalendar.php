<style>
    .bsCalFoot {
        display: table-footer-group;
        font-size: 0.8em;
        color: #ccc;
        text-align: right;
    }
    .fc-day-grid-event .fc-content {
        text-align: center;
        white-space: normal !important;
        overflow: hidden;
    }
</style>
<div id="bscalendar"></div>
<script type="text/x-handlebars-template" id="calendar-hbs">
    <div id="bs-calendar" class="fc fc-unthemed fc-ltr">
        <div class="fc-toolbar fc-header-toolbar">
            <div class="fc-left">
                <div class="fc-button-group">
                    <button type="button" class="fc-prev-button fc-button fc-state-default fc-corner-left">
                        <span class="fc-icon fc-icon-left-single-arrow"></span>
                    </button>
                    <button type="button" class="fc-next-button fc-button fc-state-default">
                        <span class="fc-icon fc-icon-right-single-arrow"></span>
                    </button>
                    <button type="button"
                            class="fc-today-button fc-button fc-state-default fc-corner-right fc-state-hover">हालको महिना
                    </button>
                </div>
            </div>
            <div class="fc-right">
                <div id="bs_ad_months">{{bs_ad_months}}</div>
            </div>
            <div class="fc-center">
                <h2>{{info.bsMonthName}} {{info.bsYearText}}</h2>
            </div>
            <div class="fc-clear"></div>
        </div>
        <div class="fc-view-container" style="">
            <div class="fc-view fc-month-view fc-basic-view" style="">
                <table class="">
                    <thead class="fc-head">
                    <tr>
                        <td class="fc-head-container fc-widget-header">
                            <div class="fc-row fc-widget-header">
                                <table class="">
                                    <thead>
                                    <tr>
                                        <th class="fc-day-header fc-widget-header fc-sun">
                                            <span>आइत</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-mon">
                                            <span>सोम</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-tue">
                                            <span>मङ्गल</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-wed">
                                            <span>बुध</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-thu">
                                            <span>बिहि</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-fri">
                                            <span>शुक्र</span>
                                        </th>
                                        <th class="fc-day-header fc-widget-header fc-sat">
                                            <span>शनि</span>
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </td>
                    </tr>
                    </thead>
                    <tbody class="fc-body">
                    <tr>
                        <td class="fc-widget-content">
                            {{{calendar_body}}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/npcalendar/bscalendar.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#bscalendar").FullBSCalendar({
            template: Handlebars.compile($("#calendar-hbs").html()),
            events: <?php echo json_encode($events);?>
        });
    });
</script>
<div id="newTask" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title"><?php echo $this->lang->line('add_to_do'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <form role="form" id="addtodo_form" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label>
                            <input class="form-control" name="task_title" id="task-title">
                            <span class="text-danger"><?php echo form_error('title'); ?></span>

                        </div>


                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label>
                            <input type="hidden" name="task_date" id="task-date">
                            <input class="form-control" type="text" autocomplete="off" name="task_date_bs"
                                   id="task-date_bs">
                            <input class="form-control" type="hidden" name="eventid" id="taskid">
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div id="permission"><?php if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_add')) { ?>

                                    <input type="submit" class="btn btn-primary submit_addtask pull-right"
                                           value="<?php echo $this->lang->line('save'); ?>">
                                <?php } ?>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="newEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("Add New Event"); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <form role="form" id="addevent_form1" method="post" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                            <input class="form-control" name="title" id="input-field">
                            <span class="text-danger"><?php echo form_error('title'); ?></span>

                        </div>

                        <!--<div class="form-group col-md-12">
                            <label for="exampleInputEmail1">Image</label>
                            <input class="filestyle form-control" type='file' name='file' id="file" size='20'/>
                            <span class="text-danger"><?php /*echo form_error('title'); */?></span>

                        </div>-->

                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea name="description" class="form-control" id="desc-field"></textarea></div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Start date");?></label>
                            <input type="text" autocomplete="off" name="event_start_date"
                                   class="form-control event_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Start Time");?></label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" autocomplete="off" readonly="readonly" name="event_start_time"
                                       class="form-control event_time">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("End date");?></label>
                            <input type="text" autocomplete="off" name="event_end_date"
                                   class="form-control event_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("End Time");?></label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" autocomplete="off" readonly="readonly" name="event_end_time"
                                       class="form-control event_time">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Holiday");?></label> <br/>
                            <label class="radio-inline">

                                <input type="radio" name="is_holiday" value="1"><?php echo $this->lang->line("Yes");?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="is_holiday" checked value="0"><?php echo $this->lang->line("No");?>
                            </label>

                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('color'); ?></label>
                            <input type="hidden" name="eventcolor" autocomplete="off" placeholder="Event Color"
                                   id="event_color" class="form-control" value="#03a9f4">
                        </div>
                        <div class="form-group col-md-12">
                            <?php //print_r($event_colors)  ?>

                            <?php
                            $i = 0;
                            $colors = '';
                            foreach ($event_colors as $color) {
                                $color_selected_class = 'cpicker-small';
                                if ($i == 0) {
                                    $color_selected_class = 'cpicker-big';
                                }
                                $colors .= "<div class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
                                //   echo $colors ;
                                $i++;
                            }
                            echo '<div class="cpicker-wrapper">';
                            echo $colors;
                            echo '</div>';
                            ?>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('type'); ?></label>
                            <br/>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="public"
                                       id="public"><?php echo $this->lang->line('public'); ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="private" checked
                                       id="private"><?php echo $this->lang->line('private'); ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="sameforall"
                                       id="public"><?php echo $this->lang->line('all'); ?> <?php echo $role; ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="protected"
                                       id="public"><?php echo $this->lang->line('protected'); ?>
                            </label></div>
                        <?php if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_add')) { ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="submit" class="btn btn-primary submit_addevent pull-right"
                                       value="<?php echo $this->lang->line('save'); ?>"></div>
                        <?php } ?>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<div id="viewEventModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("View Event"); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <form role="form" method="post" id="updateevent_form1" enctype="multipart/form-data" action="">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                            <input class="form-control" name="title" placeholder="<?php echo $this->lang->line("Event Title");?>" id="event_title">
                        </div>
                        <!--<div class="form-group col-md-12">
                            <label for="exampleInputEmail1">Image</label>
                            <input class="filestyle form-control" type='file' name='file' id="file" size='20'/>
                        </div>-->
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('description'); ?></label>
                            <textarea name="description" class="form-control" placeholder="<?php echo $this->lang->line("Event Description");?>"
                                      id="event_desc"></textarea></div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Start date");?></label>
                            <input type="text" autocomplete="off" name="event_start_date"
                                   class="form-control event_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Start Time");?></label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" autocomplete="off" name="event_start_time"
                                       class="form-control event_time">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("End date");?></label>
                            <input type="text" autocomplete="off" name="event_end_date"
                                   class="form-control event_date">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("End Time");?></label>
                            <div class="input-group bootstrap-timepicker timepicker">
                                <input type="text" autocomplete="off" name="event_end_time"
                                       class="form-control event_time">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line("Holiday");?></label> <br/>
                            <label class="radio-inline">

                                <input type="radio" name="is_holiday" value="1"><?php echo $this->lang->line("Yes");?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="is_holiday" value="0"><?php echo $this->lang->line("No");?>
                            </label>

                        </div>

                        <input type="hidden" name="eventid" id="eventid">
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('color'); ?></label>
                            <input type="hidden" name="eventcolor" autocomplete="off" placeholder="<?php echo $this->lang->line("Event Color");?>"
                                   id="event_color_edit" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <?php //print_r($event_colors)  ?>

                            <?php
                            $i = 0;
                            $colors = '';
                            foreach ($event_colors as $color) {
                                $colorid = trim($color, "#");
                                // print_r($colorid);
                                $color_selected_class = 'cpicker-small';
                                if ($i == 0) {
                                    $color_selected_class = 'cpicker-big';
                                }
                                $colors .= "<div id=" . $colorid . " class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "; border-radius:100px'></div>";
                                //   echo $colors ;
                                $i++;
                            }
                            echo '<div class="cpicker-wrapper selectevent">';
                            echo $colors;
                            echo '</div>';
                            ?>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('event'); ?> <?php echo $this->lang->line('type'); ?></label><br/>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="public"
                                       id="public"><?php echo $this->lang->line('public'); ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="private"
                                       id="private"><?php echo $this->lang->line('private'); ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="sameforall"
                                       id="public"><?php echo $this->lang->line('all'); ?> <?php echo $role; ?>
                            </label>
                            <label class="radio-inline">

                                <input type="radio" name="event_type" value="protected"
                                       id="public"><?php echo $this->lang->line('protected'); ?>
                            </label>
                        </div>

                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                            <?php
                            if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_edit')) {
                                ?>
                                <input type="submit" class="btn btn-primary submit_update pull-right"
                                       value="<?php echo $this->lang->line('save'); ?>">
                            <?php } ?>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="margin-left: 10px;">
                            <?php
                            if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_delete')) {
                                ?>
                                <input type="button" id="delete_event" class="btn btn-primary submit_delete pull-right"
                                       value="<?php echo $this->lang->line('delete'); ?>">

                            <?php } ?>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Page specific script -->
<link rel="stylesheet" href="<?php echo base_url() ?>backend/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url() ?>backend/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script>
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

    function add_task() {
        $("#modal-title").html("<?php echo $this->lang->line('add_to_do');?>");
        $("#task-title").val('');
        $("#taskid").val('');

        $('#newTask').modal('show');
        $('#task-date_bs').nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true
        }).on('dateSelect', function (e) {
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);
        });

    }

    function view_event2(id) {
        $('.selectevent').find('.cpicker-big').removeClass('cpicker-big').addClass('cpicker-small');
        var base_url = '<?php echo base_url() ?>';
        if (typeof (id) == 'undefined') {
            return;
        }
        $.ajax({
            url: base_url + 'admin/calendar/view_event/' + id,
            type: 'POST',
            //data: '',
            dataType: "json",
            success: function (msg) {
                var modal = $('#viewEventModal');
                modal.find("#event_title").val(msg.event_title);
                modal.find("#event_desc").text(msg.event_description);
                modal.find('#eventid').val(id);
                modal.find("input[name='event_start_date']").val(msg.start_date_bs);
                modal.find("input[name='event_end_date']").val(msg.end_date_bs);
                modal.find("input[name='event_start_time']").val(msg.start_time);
                modal.find("input[name='event_end_time']").val(msg.end_time);
                if (msg.is_holiday == '1') {
                    modal.find('input:radio[name=is_holiday]')[0].checked = true;
                }
                else{
                    modal.find('input:radio[name=is_holiday]')[1].checked = true;
                }
                if (msg.event_type == 'public') {
                    modal.find('input:radio[name=event_type]')[0].checked = true;
                } else if (msg.event_type == 'private') {
                    modal.find('input:radio[name=event_type]')[1].checked = true;
                } else if (msg.event_type == 'sameforall') {
                    modal.find('input:radio[name=event_type]')[2].checked = true;
                } else if (msg.event_type == 'protected') {
                    modal.find('input:radio[name=event_type]')[3].checked = true;

                }
                modal.find("#event_color_edit").val(msg.event_color);
                modal.find("#delete_event").attr("onclick", "deleteevent(" + id + ",'Event')");

                // $("#28B8DA").removeClass('cpicker-big').addClass('cpicker-small');
                modal.find("#" + msg.colorid).removeClass('cpicker-small').addClass('cpicker-big');
                $('#viewEventModal').modal('show');
            }
        });


    }

    function edit_todo_task(eventid) {


        $.ajax({
            url: "<?php echo site_url("admin/calendar/gettaskbyid/") ?>" + eventid,
            type: "POST",
            data: {eventid: eventid},
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
                $("#modal-title").html("<?php echo $this->lang->line('to_do');?>");
                $("#task-title").val(res.event_title);
                $("#taskid").val(eventid);
                $("#task-date").val(new Date(res.start_date).toString("MM/dd/yyyy"));
                $('#task-date_bs').val(res.start_date_bs);
                $('#task-date_bs').nepaliDatePicker({
                    dateFormat: "%y-%m-%d",
                    closeOnDateSelect: true
                }).on('dateSelect', function (e) {
                    var id = e.target.id.replace("_bs", "");
                    var datePickerData = e.datePickerData;
                    var m = moment(datePickerData.adDate);
                    var adDate = m.format(date_format.toUpperCase());
                    $("#" + id).val(adDate);
                });
                $('#newTask').modal('show');
                $('#permission').html('<?php if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_edit')) { ?><input type="submit" class="btn btn-primary submit_addtask pull-right" value="<?php echo $this->lang->line('save'); ?>"><?php } ?>');

            }
        });
    }


    $(document).ready(function (e) {

        $('.event_date').nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true
        }).on('dateSelect', function (e) {
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);
        });
        $(".event_time").timepicker({
            showInputs: false,
            defaultTime: 'current',
            explicitMode: false,
            minuteStep: 1
        });

        $("#addevent_form1").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/calendar/saveeventbs") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));

        $("#updateevent_form1").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/calendar/saveeventbs") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res)
                {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));

        $("#addtodo_form").on('submit', (function (e) {

            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/calendar/addtodo") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {

                    if (res.status == "fail") {

                        var message = "";
                        $.each(res.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);

                    } else {

                        successMsg(res.message);

                        window.location.reload(true);
                    }
                }
            });

        }));

    });

    function complete_event(id, status) {

        $.ajax({
            url: "<?php echo site_url("admin/calendar/markcomplete/") ?>" + id,
            type: "POST",
            data: {id: id, active: status},
            dataType: 'json',

            success: function (res) {

                if (res.status == "fail") {

                    var message = "";
                    $.each(res.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);

                } else {

                    successMsg(res.message);

                    window.location.reload(true);
                }

            }

        });
    }

    function markcomplete(id) {

        $('#check' + id).change(function () {

            if (this.checked) {

                complete_event(id, 'yes');
            } else {

                complete_event(id, 'no');
            }

        });
    }


</script>