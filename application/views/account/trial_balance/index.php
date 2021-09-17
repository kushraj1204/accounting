<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
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
                        <h3 class="box-title titlefix">
                            <i class="fa fa-book"></i>
                            <?php echo $this->lang->line('trial_balance'); ?>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('trial_balance'); ?></div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <input type="checkbox" class="form-check-input" id="toggle-transaction" checked>
                                    <label class="form-check-label"
                                           for="toggle-transaction"><?php echo $this->lang->line('show_transactions'); ?></label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <input type="checkbox" class="form-check-input" id="toggle-balances" checked>
                                    <label class="form-check-label"
                                           for="toggle-balances"><?php echo $this->lang->line('show_opening_balances'); ?></label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <select id="financial_year" class="form-control">
                                        <?php foreach ($financial_years as $financial_year) { ?>
                                            <?php if ($this->datechooser == 'bs') { ?>
                                                <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                        value="<?php echo $financial_year->id ?>"><?php echo $financial_year->id==1?$this->opening_balance_date:$financial_year->year_starts_bs ?>
                                                    - <?php echo $financial_year->year_ends_bs ?></option>
                                            <?php } else { ?>
                                                <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                        value="<?php echo $financial_year->id ?>"><?php echo $financial_year->id==1?$this->opening_balance_date:$financial_year->year_starts ?>
                                                    - <?php echo $financial_year->year_ends ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <table id="example" class="table table-striped table-bordered table-hover no-sorting-table"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th class="grey-column"
                                        rowspan="2"><?php echo $this->lang->line('particulars'); ?></th>
                                    <th class="green-column"
                                        colspan="2"><?php echo $financial_years[$selectedYear]->display; ?></th>
                                    <th class="blue-column"
                                        colspan="2"><?php echo $this->lang->line('transactions'); ?></th>
                                    <th class="orange-column"
                                        colspan="2"><?php echo $selectedYear > 1 ? $financial_years[$selectedYear - 1]->display : $this->lang->line('opening_balance'); ?></th>

                                </tr>
                                <tr>
                                    <td class="green-column"><?php echo $this->lang->line('debit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>
                                    <td class="green-column"><?php echo $this->lang->line('credit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>
                                    <td class="blue-column"><?php echo $this->lang->line('debit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>
                                    <td class="blue-column"><?php echo $this->lang->line('credit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>
                                    <td class="orange-column"><?php echo $this->lang->line('debit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>
                                    <td class="orange-column"><?php echo $this->lang->line('credit'); ?>
                                        (<?php echo $currency_symbol; ?>)
                                    </td>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                $totalDebit = 0;
                                $totalCredit = 0;
                                $totalOpeningDebit = 0;
                                $totalOpeningCredit = 0;
                                $sumTransactionDebit = 0;
                                $sumTransactionCredit = 0;
                                foreach ($parentCategories as $index => $parent) { ?>
                                    <?php if (!isset($coa[$index]['debitTotal']) && !isset($coa[$index]['creditTotal'])) continue; ?>
                                    <?php
                                    /*$totalDebit += $coa[$index]['debitTotal'];
                                    $totalCredit += $coa[$index]['creditTotal'];
                                    $totalOpeningDebit += $coa[$index]['openingDebitTotal'];
                                    $totalOpeningCredit += $coa[$index]['openingCreditTotal'];*/
                                    ?>
                                    <tr class="category-parent">
                                        <td class="grey-column"><h5><?php echo $parent; ?></h5></td>
                                        <td class="green-column"></td>
                                        <td class="green-column"></td>
                                        <td class="blue-column"></td>
                                        <td class="blue-column"></td>
                                        <td class="orange-column"></td>
                                        <td class="orange-column"></td>
                                    </tr>
                                    <?php foreach ($categories as $category) { ?>
                                        <?php if (!isset($coa[$index][$category->id]['debitTotal']) && !isset($coa[$index][$category->id]['creditTotal'])) continue; ?>
                                        <?php
                                        $categoryDebitTotal = $coa[$index][$category->id]['debitTotal'];
                                        $categoryCreditTotal = $coa[$index][$category->id]['creditTotal'];
                                        $categoryOpeningDebitTotal = $coa[$index][$category->id]['openingDebitTotal'];
                                        $categoryOpeningCreditTotal = $coa[$index][$category->id]['openingCreditTotal'];
                                        $categoryTransactionDebitTotal = $coa[$index][$category->id]['transactionDebitTotal'];
                                        $categoryTransactionCreditTotal = $coa[$index][$category->id]['transactionCreditTotal'];
                                        ?>
                                        <tr class="category-row">
                                            <td class="grey-column" ><u><?php echo $category->title; ?></u></td>
                                            <td class="green-column"><?php echo $categoryDebitTotal ? '<u>' . $this->accountlib->currencyFormat($categoryDebitTotal, false) . '</u>' : '' ?></td>
                                            <td class="green-column"><?php echo $categoryCreditTotal ? '<u>' . $this->accountlib->currencyFormat($categoryCreditTotal, false) . '</u>' : '' ?></td>
                                            <td class="blue-column"><?php echo $categoryTransactionDebitTotal > 0 ? '<u>' . $this->accountlib->currencyFormat($categoryTransactionDebitTotal, false) . '</u>' : '' ?></td>
                                            <td class="blue-column"><?php echo $categoryTransactionCreditTotal > 0 ? '<u>' . $this->accountlib->currencyFormat($categoryTransactionCreditTotal, false) . '</u>' : '' ?></td>
                                            <td class="orange-column"><?php echo $categoryOpeningDebitTotal ? '<u>' . $this->accountlib->currencyFormat($categoryOpeningDebitTotal, false) . '</u>' : '' ?></td>
                                            <td class="orange-column"><?php echo $categoryOpeningCreditTotal ? '<u>' . $this->accountlib->currencyFormat($categoryOpeningCreditTotal, false) . '</u>' : '' ?></td>
                                        </tr>
                                        <?php foreach ($coa[$index][$category->id] as $item) { ?>
                                            <?php if (!isset($item->name)) continue; ?>
                                            <?php //if (!isset($item->amount) && !isset($item->opening_balance)) continue; ?>
                                            <?php $totalDebit += $item->debit;
                                            $totalCredit += $item->credit;

                                            $sumTransactionDebit += $item->coaDebitSum;
                                            $sumTransactionCredit += $item->coaCreditSum;
                                            $totalOpeningDebit += strtolower($item->opening_balance_type) == 'debit' ? $item->opening_balance : 0;
                                            $totalOpeningCredit += strtolower($item->opening_balance_type) == 'credit' ? $item->opening_balance : 0; ?>
                                            <tr id="<?php echo $item->id ?>">
                                                <td class="grey-column">
                                                    <?php echo $item->name; ?>
                                                    <a href="<?php echo $item->link ?>"
                                                       class="btn btn-default btn-xs" data-toggle="tooltip"
                                                       target="_blank"
                                                       title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <?php if ($item->name == 'Receivables' ) { ?>
                                                        <a class="pull-right">
                                                            <i class="fa fa-chevron-down " id="receivablecollapse" ></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ( $item->name == 'Payables') { ?>
                                                        <a class="pull-right">
                                                            <i class="fa fa-chevron-down " id="payablecollapse" ></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                                <td class="green-column"><?php echo $item->debit > 0 ? $this->accountlib->currencyFormat($item->debit, false) : '' ?></td>
                                                <td class="green-column"><?php echo $item->credit > 0 ? $this->accountlib->currencyFormat($item->credit, false) : '' ?></td>
                                                <td class="blue-column"><?php echo $item->coaDebitSum > 0 ? $this->accountlib->currencyFormat($item->coaDebitSum, false) : '' ?></td>
                                                <td class="blue-column"><?php echo $item->coaCreditSum > 0 ? $this->accountlib->currencyFormat($item->coaCreditSum, false) : '' ?></td>
                                                <td class="orange-column"><?php echo ($item->opening_balance_type == 'debit' && $item->opening_balance > 0) ? $this->accountlib->currencyFormat($item->opening_balance, false) : '' ?></td>
                                                <td class="orange-column"><?php echo ($item->opening_balance_type == 'credit' && $item->opening_balance > 0) ? $this->accountlib->currencyFormat($item->opening_balance, false) : '' ?></td>
                                            </tr>
                                            <?php
                                            if ($item->name == $this->lang->line('receivables')) {
                                                foreach ($customerDetail as $eachcustomer) {
                                                    if($eachcustomer->debitTotal <=0 && $eachcustomer->creditTotal<=0 && $eachcustomer->transactionDebitTotal<=0 &&
                                                        $eachcustomer->transactionCreditTotal <=0 && $eachcustomer->openingDebitTotal<=0 && $eachcustomer->openingCreditTotal<=0
                                                    )
                                                        continue;
                                                    ?>

                                                    <tr class='collapseCustomer'>
                                                        <td class="grey-column">
                                                            <?php echo $eachcustomer->name; ?>
                                                            <a href="<?php echo base_url() . 'account/personnel/ledger/'.$eachcustomer->id; ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip"
                                                               target="_blank"
                                                               title="<?php echo $this->lang->line('view'); ?>">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                        <td class="green-column"><?php echo $eachcustomer->debitTotal > 0 ? $this->accountlib->currencyFormat($eachcustomer->debitTotal, false) : '' ?></td>
                                                        <td class="green-column"><?php echo $eachcustomer->creditTotal > 0 ? $this->accountlib->currencyFormat($eachcustomer->creditTotal, false) : '' ?></td>
                                                        <td class="blue-column"><?php echo $eachcustomer->transactionDebitTotal > 0 ? $this->accountlib->currencyFormat($eachcustomer->transactionDebitTotal, false) : '' ?></td>
                                                        <td class="blue-column"><?php echo $eachcustomer->transactionCreditTotal > 0 ? $this->accountlib->currencyFormat($eachcustomer->transactionCreditTotal, false) : '' ?></td>
                                                        <td class="orange-column"><?php echo ( $eachcustomer->openingDebitTotal > 0) ? $this->accountlib->currencyFormat($eachcustomer->openingDebitTotal, false) : '' ?></td>
                                                        <td class="orange-column"><?php echo ($eachcustomer->openingCreditTotal > 0) ? $this->accountlib->currencyFormat($eachcustomer->openingCreditTotal, false) : '' ?></td>
                                                    </tr>

                                                <?php }
                                            } ?>
                                            <?php
                                            if ($item->name === $this->lang->line('payables')) {
                                                foreach ($supplierDetail as $eachsupplier) {
                                                    if($eachsupplier->debitTotal <=0 && $eachsupplier->creditTotal<=0 && $eachsupplier->transactionDebitTotal<=0 &&
                                                        $eachsupplier->transactionCreditTotal <=0 && $eachsupplier->openingDebitTotal<=0 && $eachsupplier->openingCreditTotal<=0
                                                    )
                                                        continue;
                                                    ?>
                                                    <tr class='collapseSupplier'>
                                                        <td class="grey-column">
                                                            <?php echo $eachsupplier->name; ?>
                                                            <a href="<?php echo base_url() . 'account/personnel/ledger/'.$eachsupplier->id; ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip"
                                                               target="_blank"
                                                               title="<?php echo $this->lang->line('view'); ?>">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                        <td class="green-column"><?php echo $eachsupplier->debitTotal > 0 ? $this->accountlib->currencyFormat($eachsupplier->debitTotal, false) : '' ?></td>
                                                        <td class="green-column"><?php echo $eachsupplier->creditTotal > 0 ? $this->accountlib->currencyFormat($eachsupplier->creditTotal, false) : '' ?></td>
                                                        <td class="blue-column"><?php echo $eachsupplier->transactionDebitTotal > 0 ? $this->accountlib->currencyFormat($eachsupplier->transactionDebitTotal, false) : '' ?></td>
                                                        <td class="blue-column"><?php echo $eachsupplier->transactionCreditTotal > 0 ? $this->accountlib->currencyFormat($eachsupplier->transactionCreditTotal, false) : '' ?></td>
                                                        <td class="orange-column"><?php echo ( $eachsupplier->openingDebitTotal > 0) ? $this->accountlib->currencyFormat($eachsupplier->openingDebitTotal, false) : '' ?></td>
                                                        <td class="orange-column"><?php echo ($eachsupplier->openingCreditTotal > 0) ? $this->accountlib->currencyFormat($eachsupplier->openingCreditTotal, false) : '' ?></td>
                                                    </tr>

                                                <?php }
                                            } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($totalDebit != 0 || $totalCredit != 0 || $sumTransactionDebit != 0 || $sumTransactionCredit != 0 || $totalOpeningDebit != 0 || $totalOpeningCredit != 0) { ?>
                                    <tr style="border-top: solid 1px #3c3c3c">
                                        <td class="grey-column">
                                            <strong><?php echo $this->lang->line('grand_total'); ?></strong></td>
                                        <td class="green-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($totalDebit, false); ?></strong>
                                        </td>
                                        <td class="green-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($totalCredit, false); ?></strong>
                                        </td>
                                        <td class="blue-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($sumTransactionDebit, false); ?></strong>
                                        </td>
                                        <td class="blue-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($sumTransactionCredit, false); ?></strong>
                                        </td>
                                        <td class="orange-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($totalOpeningDebit, false); ?></strong>
                                        </td>
                                        <td class="orange-column">
                                            <strong><?php echo $this->accountlib->currencyFormat($totalOpeningCredit, false); ?></strong>
                                        </td>
                                    </tr>
                                <?php } ?>


                                <?php /*if(($totalDebit != $totalCredit) || ($sumTransactionDebit != $sumTransactionCredit) || ($totalOpeningDebit != $totalOpeningCredit)){
                                    $suspense_amount = $totalCredit - $totalDebit;
                                    $suspense_transaction_amount = $sumTransactionCredit - $sumTransactionDebit;
                                    $suspense_opening_amount = $totalOpeningCredit - $totalOpeningDebit;*/ ?><!--
                                    <tr>
                                        <td class="grey-column"><strong><?php /*echo $this->lang->line('suspense_account'); */ ?></strong></td>
                                        <td class="green-column"><?php /*echo $suspense_amount > 0 ? '<strong>' . $this->accountlib->currencyFormat($suspense_amount, false) . '</strong>' : ''; */ ?></td>
                                        <td class="green-column"><?php /*echo $suspense_amount < 0 ? '<strong>' . $this->accountlib->currencyFormat(-1 * $suspense_amount, false) . '</strong>' : ''; */ ?></td>
                                        <td class="blue-column"><?php /*echo $suspense_transaction_amount > 0 ? '<strong>' . $this->accountlib->currencyFormat($suspense_transaction_amount, false) . '</strong>' : ''; */ ?></td>
                                        <td class="blue-column"><?php /*echo $suspense_transaction_amount < 0 ? '<strong>' . $this->accountlib->currencyFormat(-1 * $suspense_transaction_amount, false) . '</strong>' : ''; */ ?></td>
                                        <td class="orange-column"><?php /*echo $suspense_opening_amount > 0 ? '<strong>' . $this->accountlib->currencyFormat($suspense_opening_amount, false) . '</strong>' : ''; */ ?></td>
                                        <td class="orange-column"><?php /*echo $suspense_opening_amount < 0 ? '<strong>' . $this->accountlib->currencyFormat(-1 * $suspense_opening_amount, false) . '</strong>' : ''; */ ?></td>
                                    </tr>
                                --><?php /*} */ ?>
                                </tbody>
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
        $('.collapseSupplier').hide();
        $('.collapseCustomer').hide();
        customDatatableInitialize();
        $('#financial_year').on('change', function () {
            window.location = '<?php echo base_url(); ?>account/trial_balance/index/' + $(this).val();
        });

        $('#payablecollapse').on('click',function(e){
            $('.collapseSupplier').toggle();
        })

        $('#receivablecollapse').on('click',function(e){
            $('.collapseCustomer').toggle();
        })

        $('.fa-chevron-down').on('click', function (e) {
            //console.log('clicked', this.id);
            //e.preventDefault();
            //var dataJson = {
            //    ['<?php //echo $this->security->get_csrf_token_name(); ?>//']: '<?php //echo $this->security->get_csrf_hash(); ?>//',
            //    id: this.id,
            //    financial_year:<?php //echo $selectedYear; ?>
            //};
            //$.ajax({
            //    url: '<?php //echo site_url('account/ledger/detailAjax'); ?>//',
            //    type: 'POST',
            //    data: dataJson,
            //    dataType: 'json',
            //    success: function (data) {
            //        console.log(data);
            //    }
            //});


        })


    });

    function customDatatableInitialize() {
        var table = $('.no-sorting-table').DataTable({
            "aaSorting": [],
            "ordering": false,
            "paging": false,
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
        $('.no-sorting-table').addClass('with-border-table');
        toggleColumns(table);

        $('#toggle-transaction').on('change', function () {
            toggleColumns(table);
        });
        $('#toggle-balances').on('change', function () {
            toggleColumns(table);
        });


        //$('#example tbody').on('click', 'tr a .fa-chevron-down', function () {
        //    count = 0;
        //    if (!this.id) {
        //        console.log('no id');
        //        return
        //    }
        //
        //    var currentPage = table.page();
        //    count++;
        //    var parenttr = ($(this).parent().parent().parent())
        //    var index = table.row(($(this).parent().parent().parent())).index();
        //    var dataJson = {
        //        ['<?php //echo $this->security->get_csrf_token_name(); ?>//']: '<?php //echo $this->security->get_csrf_hash(); ?>//',
        //        id: this.id,
        //        financial_year:<?php //echo $selectedYear; ?>
        //    };
        //
        //    console.log('index',index)
        //    console.log('id',this.id);
        //    console.log('financial year',<?php //echo $selectedYear; ?>//)
        //    $.ajax({
        //        url: '<?php //echo site_url('account/ledger/detailAjax'); ?>//',
        //        type: 'POST',
        //        data: dataJson,
        //        dataType: 'json',
        //        success: function (data) {
        //            console.log(data);
        //            $(this).parent().parent().parent().addClass('childShown');
        //            // (table.row(($(this).parent().parent().parent()))).addClass('childShown');
        //            data.logs.forEach(element => {
        //                table.row.add([element.name, element.transaction_amount, '', '', '', '', '']).node().id='randomIdIplaced';
        //                //row always added to the end data is pulled up but the row class and id remains as it is
        //                var rowCount = table.data().length - 1;
        //                var insertedRow = table.row(rowCount).data()
        //                var tempRow
        //                console.log('index', index);
        //                for (var i = rowCount; i > index + 1; i--) {
        //                    tempRow = table.row(i - 1).data();
        //                    table.row(i - 1).data();
        //                    table.row(i).data(tempRow);
        //                    table.row(i - 1).data(insertedRow);
        //
        //                }
        //                table.page(currentPage).draw(true);
        //
        //            });
        //            }
        //    });
        //
        //});

        //$('#example tbody').on('click', 'tr', function () {
        //    var tr = $(this)
        //    var row = table.row(tr);
        //
        //    if (row.child.isShown()) {
        //        row.child.hide();
        //        tr.removeClass('shown');
        //    } else {
        //        var dataJson = {
        //            ['<?php //echo $this->security->get_csrf_token_name(); ?>//']: '<?php //echo $this->security->get_csrf_hash(); ?>//',
        //            id: this.id,
        //            financial_year:<?php //echo $selectedYear; ?>
        //        };
        //        $.ajax({
        //            url: '<?php //echo site_url('account/ledger/detailAjax'); ?>//',
        //            type: 'POST',
        //            data: dataJson,
        //            dataType: 'json',
        //            success: function (data) {
        //                console.log(data)
        //                var objstring='';
        //                data.logs.forEach(element => {
        //                    objstring=objstring+"<tr class=\"even\" role=\"row\">" +
        //                    "<td class=\"grey-column\">"+element.name+"</td>" +
        //                    "<td class=\"green-column\"></td>" +
        //                    "<td class=\"green-column\"></td>" +
        //                    "<td class=\"blue-column\">" + element.transaction_amount + "</td>" +
        //                    "<td class=\"blue-column\"></td>" +
        //                    "<td class=\"orange-column\"></td>" +
        //                    "<td class=\"orange-column\"></td>" +
        //                    "</tr>";
        //                    });
        //                row.child($(objstring)).show();
        //
        //            }
        //        });
        //        tr.addClass('shown');
        //    }
        //});


    }

    function toggleColumns(table) {
        //toggle transactions
        if ($('#toggle-transaction').is(":checked")) {
            table.column(3).visible(true);
            table.column(4).visible(true);
        } else {
            table.column(3).visible(false);
            table.column(4).visible(false);
        }

        //toggle opening balances
        if ($('#toggle-balances').is(":checked")) {
            table.column(5).visible(true);
            table.column(6).visible(true);
        } else {
            table.column(5).visible(false);
            table.column(6).visible(false);
        }
    }
</script>