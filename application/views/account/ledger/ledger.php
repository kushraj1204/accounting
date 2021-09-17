<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calculator"></i> <?php echo $this->lang->line('Accounts'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-book"></i>
                            <?php echo $this->lang->line($type) . ' ' . $this->lang->line('ledger'); ?>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <h4><?php echo $asset->name; ?> - <?php echo $asset->code; ?>
                            : <?php echo $this->lang->line('accounting_details') ?></h4>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('assets_ledger'); ?></div>
                            <table id="coaLedgerList" class="table table-striped table-bordered table-hover "
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                <tr>
                                    <th><?php echo $this->lang->line('account_name'); ?></th>
                                    <th><?php echo $this->lang->line('category'); ?></th>
                                    <th><?php echo $this->lang->line('sub_category_1'); ?></th>
                                    <th><?php echo $this->lang->line('sub_category_2'); ?></th>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('debit_amount'); ?></th>
                                    <th><?php echo $this->lang->line('credit_amount'); ?></th>
                                    <th><?php echo $this->lang->line('total_balance'); ?></th>
                                    <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                                </tr>
                                </thead>

                            </table>
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
    $(document).ready(function () {
        $('#coaLedgerList').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8]},
                {"bSearchable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4, 5, 6, 7]},
                {className: "mailbox-date no-print text text-right", "targets": [8]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/ledger/ledgerDetailList',
                "data":
                    function (data) {
                        data.id = "11"
                    },
            },
            'columns': [
                {data: 'name'},
                {data: 'categoryName'},
                {data: 'subCategory1Name'},
                {data: 'subCategory2Name'},
                {data: 'date'},
                {data: 'debit'},
                {data: 'credit'},
                {data: 'sum'},
                {data: 'action'},
            ],

        });

        customDatatableInitialize();
        /*$('.example').dataTable( {
            "ordering": false
        } );*/
    });

    function customDatatableInitialize() {
        $('.ledgerTable').DataTable({
            "aaSorting": [],
            "ordering": false,
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: 'true',
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    orientation: ($('.download_label').attr('data-orientation') || 'portrait'),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    },
                    customize: function (doc) {
                        //console.log(doc);
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });

    }
</script>