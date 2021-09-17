<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('Accounts'); ?></h1>
    </section>
    <!--    --><?php //echo "<pre>";print_r($receipts); echo "</pre>"; 
    ?>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <?php if ($this->session->flashdata('msg')) {
                        echo show_message();
                    } ?>
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('receipts'); ?></h3>
                        <small class="pull-right">
                            <a href="<?php echo base_url(); ?>account/receipt/add_receipt"
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_receipt'); ?></a>
                        </small>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('receipts'); ?></div>
                            <table class="table table-bordered ">
                                <thead>
                                <input type="hidden" id="fromTime" value="">
                                <input type="hidden" id="toTime" value="">
                                <input type="hidden" id="paymentMedium" value="any">
                                <?php if ($this->datechooser == 'bs') { ?>
                                    <th class="table-primary"><input
                                                placeholder="<?php echo $this->lang->line('From Date'); ?>" type="text"
                                                id="mindate_bs" name="mindate_bs"></th>
                                    <th class="table-primary"><input
                                                placeholder="<?php echo $this->lang->line('To Date'); ?>" type="text"
                                                id="maxdate_bs" name="maxdate_bs"></th>
                                <?php } else { ?>
                                    <th class="table-primary"><input
                                                placeholder="<?php echo $this->lang->line('From Date'); ?>" type="text"
                                                id="mindate" name="mindate"></th>
                                    <th class="table-primary"><input
                                                placeholder="<?php echo $this->lang->line('To Date'); ?>" type="text"
                                                id="maxdate" name="maxdate"></th>
                                <?php } ?>
                                <th>
                                    <div class="form-group">
                                        <select class="form-control" id="paymentMethod">
                                            <option value='any'><?php echo $this->lang->line('payment_modes'); ?>
                                                (<?php echo $this->lang->line('any'); ?>)
                                            </option>
                                            <option value='cash'>Cash</option>
                                            <option value='cheque'>Cheque</option>
                                            <option value='prabhupay'>PrabhuPay</option>
                                        </select>
                                    </div>
                                </th>
                                </thead>
                            </table>

                            <table id="receiptList" class="table table-bordered ">
                                <thead>
                                <th class="table-primary"><?php echo $this->lang->line('receipt_date'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('received_from'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('receipt_no'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('receipt_mode'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('description'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('amount'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('actions'); ?></th>
                                </thead>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {


    });
</script>


<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function Popup(data) {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }


    $("#print_div").click(function () {
        Popup($('#bklist').html());
    });


    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        var startDate = new Date();
        var enTime = startDate.toISOString().replace(/T.*Z/, '');
        startDate.setMonth(startDate.getMonth() - 1);
        var stTime = startDate.toISOString().replace(/T.*Z/, '');
        var paymentMode = 'any';

        $('#receiptList').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {"bSearchable": true, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4, 5]},
                {className: "mailbox-date no-print text text-right", "targets": [6]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/receipt/receiptList',
                "data":
                    function (data) {
                        data.mode = $('#paymentMedium').val();
                        data.from = $('#fromTime').val();
                        data.to = $('#toTime').val();
                    },
            },
            'columns':
                [
                    {data: 'receipt_date'},
                    {data: 'received_from'},
                    {data: 'receipt_no'},
                    {data: 'receipt_mode'},
                    {data: 'description'},
                    {data: 'amount'},
                    {data: 'action'},
                ],

        })
        ;
        additionInDataTable();
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        $('.deleteReceipt').off('click').on('click', function () {
            var response = confirm('Are you sure you want to delete this item? This might pose adverse effects on your statements.');
            if (!response) {
                return false;
            }
        });

        //$.fn.dataTable.ext.search.push(
        //    function (settings, data, dataIndex) {
        //        <?php //if ($this->datechooser == 'bs') { ?>
        //        var mindate = $('#mindate_bs').val();
        //        var maxdate = $('#maxdate_bs').val();
        //        <?php //} else { ?>
        //        var mindate = $('#mindate').val();
        //        var maxdate = $('#maxdate').val();
        //        <?php //} ?>
        //        if (!mindate) {
        //            mindate = '01/01/1970';
        //        }
        //        mindate = new Date(mindate);
        //        if (!maxdate) {
        //            maxdate = '12/30/9999';
        //        }
        //        maxdate = new Date(maxdate);
        //        var thedate = new Date(data[0]);
        //        var thepaymentMode = data[3];
        //        var paymentMode = $('#paymentMethod').val();
        //        if ((mindate <= thedate && thedate <= maxdate)) {
        //            if (paymentMode == 'all') {
        //                return true;
        //            } else {
        //                if (thepaymentMode.toLowerCase() == paymentMode.toLowerCase()) {
        //                    return true;
        //                }
        //                return false;
        //            }
        //        }
        //        return false;
        //    }
        //);

        <?php if ($this->datechooser == 'bs') { ?>
        $("#mindate_bs").nepaliDatePicker({
            dateFormat: "%m/%d/%y",
            closeOnDateSelect: true,
        }).on('dateSelect', function (e) {
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            stTime = m.toISOString().replace(/T.*Z/, '');
            console.log(stTime)
            $('#fromTime').val(stTime)
            $("#mindate_bs").trigger("change");
        });
        $("#maxdate_bs").nepaliDatePicker({
            dateFormat: "%m/%d/%y",
            closeOnDateSelect: true,
        }).on('dateSelect', function (e) {
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            enTime = m.toISOString().replace(/T.*Z/, '');
            console.log(enTime);
            $('#toTime').val(enTime)
            $("#maxdate_bs").trigger("change");
        });
        <?php } else { ?>
        $('#mindate,#maxdate').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
        });
        <?php } ?>


        $('#mindate, #maxdate').on('change', function (event) {
            stTime = $('#mindate').val();
            $('#fromTime').val(stTime)
            enTime = $('#maxdate').val();
            $('#toTime').val(enTime)
            $('#receiptList').DataTable().draw();
        });
        $('#mindate_bs, #maxdate_bs').on('change', function (event) {
            $('#receiptList').DataTable().draw();
            // $('#receiptList').DataTable().ajax.reload();
        });
        $('#paymentMethod').on('change', function () {
            paymentMode = $('#paymentMethod').val();
            $('#paymentMedium').val(paymentMode)
            $('#receiptList').DataTable().draw();
        });

    });

    function additionInDataTable() {
        // $('#mindate, #maxdate').on('change', function (event) {
        //     console.log($('#mindate').val())
        //     $('#receiptList').DataTable().draw();
        // });
        // $('#mindate_bs, #maxdate_bs').on('change', function (event) {
        //     $('#receiptList').DataTable().draw();
        // });
        // $('#paymentMethod').on('change', function () {
        //     $('#receiptList').DataTable().draw();
        // });
    }

</script>