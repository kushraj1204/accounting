<?php
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
                                    class="fa fa-users"></i> <?php echo $this->lang->line('account_ledger_for') . ' ' . $personnel->name . " (" . ucfirst($personneltype) . ")" ?>
                        </h3>

                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <select id="financial_year" class="form-control">
                                    <?php foreach ($financial_years as $financial_year) { ?>
                                        <?php if ($this->datechooser == 'bs') { ?>
                                            <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                    value="<?php echo $financial_year->id ?>"><?php echo $financial_year->year_starts_bs ?>
                                                - <?php echo $financial_year->year_ends_bs ?></option>
                                        <?php } else { ?>
                                            <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                    value="<?php echo $financial_year->id ?>"><?php echo $financial_year->year_starts ?>
                                                - <?php echo $financial_year->year_ends ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('personnelLedger'); ?></div>
                            <?php if ($this->session->flashdata('msg')) {
                                echo show_message();
                            } ?>
                            <table id="personnelLedgerTable" class="table table-striped table-bordered table-hover "
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('accounts'); ?></th>
                                    <th><?php echo $this->lang->line('narration'); ?></th>
                                    <th><?php echo $this->lang->line('source'); ?></th>
                                    <th><?php echo $this->lang->line('debit'); ?></th>
                                    <th><?php echo $this->lang->line('credit'); ?></th>
                                    <th><?php echo $this->lang->line('balance'); ?></th>
                                    <th><?php echo $this->lang->line('action'); ?></th>
                                </tr>
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


<script id="openingdata_handlebar" type="text/x-handlebars-template">
    <tr>
        <td>{{this.date}}</td>
        <td>{{this.balancetype}}</td>
        <td>{{this.balanceaction}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo $currency_symbol ?>{{this.amount}}</td>
        <td></td>
    </tr>
</script>


<script>
    var lastrow = '';
    var type = '<?php echo $personneltype; ?>';
    $(document).ready(function () {
        $('#personnelLedgerTable').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7]},
                {"bSearchable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4, 5, 6]},
                {className: "mailbox-date no-print text text-right", "targets": [7]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/personnel/personnelLedgerList',
                "data":
                    function (data) {
                        data.financial_year = "<?php echo $selectedYear; ?>";
                        data.id = "<?php echo $id;?>"
                    },
            },
            'columns': [
                {data: 'date'},
                {data: 'accounts'},
                {data: 'narration'},
                {data: 'source'},
                {data: 'debit'},
                {data: 'credit'},
                {data: 'balance'},
                {data: 'action'},
            ],

        });
        $('#financial_year').on('change', function () {
            window.location = '<?php echo base_url(); ?>account/personnel/ledger/<?php echo $personnel->id; ?>/' + $(this).val();
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        customdatatableinitialize();
        additionInDataTable();

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                <?php if ($this->datechooser == 'bs') { ?>
                var mindate = $('#mindate_bs').val();
                var maxdate = $('#maxdate_bs').val();
                <?php } else { ?>
                var mindate = $('#mindate').val();
                var maxdate = $('#maxdate').val();
                <?php } ?>
                if (!mindate) {
                    mindate = '01/01/1970';
                }
                mindate = new Date(mindate);
                if (!maxdate) {
                    maxdate = '12/30/9999';
                }
                maxdate = new Date(maxdate);
                var thedate = new Date(data[0]);
                if ((mindate <= thedate && thedate <= maxdate)) {
                    return true;
                }
                return false;

            }
        );

        <?php if ($this->datechooser === 'bs') { ?>
        $("#mindate_bs,#maxdate_bs").nepaliDatePicker({
            dateFormat: "%m/%d/%y",
            closeOnDateSelect: true,
        }).on('dateSelect', function (e) {
            $("#mindate_bs").trigger("change");
        });
        <?php } else { ?>
        $('#mindate,#maxdate').datepicker({
            format: date_format,
            autoclose: true,
        });
        <?php } ?>
    });

    function additionInDataTable() {
        $('#mindate_bs,#maxdate_bs,#maxdate,#mindate').on('change', function () {
            var selecteddate = this.value;
            $('.exampledatatable').DataTable().draw();
            addrow(selecteddate);

        });
    }

    function addrow(selecteddate) {
        var firstrow = $('.exampledatatable').DataTable().rows({
            filter: 'applied'
        }).data()[0];
        lastrow = $('.exampledatatable').DataTable().row(':last').data();
        if (firstrow) {
            var debit = firstrow[4];
            debit = debit.replace("Rs.", "");
            if (debit == '-') {
                debit = 0;
            }
            var credit = firstrow[5];
            credit = credit.replace("Rs.", "");
            if (credit == '-') {
                credit = 0;
            }
            var amount = firstrow[6];
            amount = amount.replace("Rs.", "");
            openingamount = 0;
            if (type == 'customer') {
                if (amount) {
                    amount = amount.replace(/\D/g, '');
                }
                if (debit) {
                    debit = debit.replace(/\D/g, '');
                }
                if (credit) {
                    credit = credit.replace(/\D/g, '');
                }
                var openingamount = parseFloat(amount) - parseFloat(debit) + parseFloat(credit);
            }
            if (type == 'supplier') {
                var openingamount = parseFloat(amount) - parseFloat(credit) + parseFloat(debit);
            }
            var data = {
                'date': '',
                'amount': openingamount,
                'balancetype': 'Opening Balance',
                'balanceaction': ''
            }

            var handlebartemplatescript = Handlebars.compile($('#openingdata_handlebar').html());
            var htmltoload = handlebartemplatescript(data);
            $("#maintablebody").prepend(htmltoload);
        }

        var closingamount = lastrow[6];
        if (closingamount) {
            closingamount = closingamount.replace(/\D/g, '');
        }
        var data = {
            'date': '',
            'amount': closingamount,
            'balancetype': 'Closing Balance',
            'balanceaction': '',
        }

        var handlebartemplatescript = Handlebars.compile($('#openingdata_handlebar').html());
        var htmltoload = handlebartemplatescript(data);
        $("#maintablebody").append(htmltoload);


    }

    function customdatatableinitialize() {
        $('.exampledatatable').DataTable({
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