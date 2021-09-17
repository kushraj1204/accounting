<?php
$settings = $this->accountlib->getAccountSetting();
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calculator"></i> <?php echo $this->lang->line('Accounts'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i
                                    class="fa fa-book"></i> <?php echo $this->lang->line('invoices'); ?></h3>
                        <small class="pull-right">
                            <a href="<?php echo base_url(); ?>account/invoice/add_invoice"
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_invoice'); ?></a>
                        </small>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('invoice_list'); ?></div>
                            <?php if ($this->session->flashdata('msg')) {
                                echo show_message();
                            } ?>
                            <table id="invoiceTable" class="table table-striped table-bordered table-hover "
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('#'); ?></th>
                                    <th><?php echo $this->lang->line('created_date'); ?></th>
                                    <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                    <th><?php echo $this->lang->line('customer_code'); ?></th>
                                    <th><?php echo $this->lang->line('customer_name'); ?></th>
                                    <th><?php echo $this->lang->line('amount'); ?></th>
                                    <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                                </thead>

                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#postdate').datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true
        });
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>


<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    function Popup(data) {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
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
        $('#invoiceTable').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {"bSearchable": true, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4, 5]},
                {className: "mailbox-date no-print text text-right", "targets": [6]},
            ],
            'pageLength': 40,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/invoice/invoiceList'
            },
            'columns': [
                {data: 'count'},
                {data: 'created_date'},
                {data: 'invoice_no'},
                {data: 'customer_code'},
                {data: 'customer_name'},
                {data: 'amount'},
                {data: 'action'},
            ],

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
    });
</script>