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
                            <i class="fa fa-money"></i>
                            <?php echo $this->lang->line('receipt_and_payment_account'); ?>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('balance_sheet'); ?></div>
                            <div class="row">
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
                                    <th class="grey-column"><?php echo $this->lang->line('particulars'); ?></th>
                                    <th class="green-column"><?php echo $this->lang->line('current_year') . ' (' . $financial_years[$selectedYear]->display . ')'; ?>
                                        <br><?php echo $this->lang->line('balance') . ' (' . $currency_symbol . ')'; ?>
                                    </th>
                                    <th class="orange-column"><?php echo $this->lang->line('last_year') ?><?php echo $selectedYear > 1 ? ' (' . $financial_years[$selectedYear - 1]->display . ')' : ''; ?>
                                        <br><?php echo $this->lang->line('balance') . ' (' . $currency_symbol . ')'; ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $count = 1;
                                $arrayshifted = 0;
                                $totalbalance = 0;
                                $totalopeningbalance = 0;

                                foreach ($parentCategories as $index => $parent) {
                                    if ($index == 4 && $arrayshifted == 0) {
                                        array_shift($categories);
                                        array_push($categories, (object)array('id' => 0, 'title' => 'Cash and Banks'));
                                        $arrayshifted = 1;
                                    }
                                    ?>
                                    <?php if (!isset($coa[$index])) continue; ?>
                                    <?php
                                    $totalbalance += $coa[$index]['balance'];
                                    $totalopeningbalance += $coa[$index]['openingbalance'];

                                    ?>
                                    <tr class="category-parent">
                                        <td class="grey-column"><h5><?php echo $parent; ?></h5></td>
                                        <td class="green-column"></td>
                                        <td class="orange-column"></td>
                                    </tr>
                                    <?php foreach ($categories as $category) { ?>
                                        <?php if (!isset($coa[$index][$category->id])) continue; ?>
                                        <?php
                                        $categorybalance = $coa[$index][$category->id]['balance'];
                                        $categoryopeningbalance = $coa[$index][$category->id]['openingbalance'];

                                        ?>
                                        <tr class="category-row">
                                            <td class="grey-column"><u><?php echo $category->title; ?></u></td>
                                            <td class="green-column"><?php echo $categorybalance ? '<u>' . $this->accountlib->currencyFormat($categorybalance, false) . '</u>' : '0' ?></td>
                                            <td class="orange-column"><?php echo $categoryopeningbalance ? '<u>' . $this->accountlib->currencyFormat($categoryopeningbalance, false) . '</u>' : '0' ?></td>
                                        </tr>
                                        <?php foreach ($coa[$index][$category->id] as $item) { ?>
                                            <?php if (!isset($item->name)) continue; ?>
                                            <?php //if (!isset($item->balance) && !isset($item->openingbalance)) continue; ?>
                                            <tr>
                                                <td class="grey-column">
                                                    <?php echo $item->name; ?>
                                                    <a href="<?php echo base_url(); ?>account/ledger/detail/<?php echo $item->id ?>/<?php echo $selectedYear ?>"
                                                       class="btn btn-default btn-xs" data-toggle="tooltip"
                                                       target="_blank"
                                                       title="<?php echo $this->lang->line('view'); ?>">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                                <td class="green-column"><?php echo $item->balance ? $this->accountlib->currencyFormat($item->balance, false) : '0' ?></td>
                                                <td class="orange-column"><?php echo $item->opening_balance ? $this->accountlib->currencyFormat($item->opening_balance, false) : '0' ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>

                                    <tr class="category-parent">
                                        <td class="grey-column"><h5><?php echo $this->lang->line('grand_total') ?></h5></td>
                                        <td class="green-column"
                                            style="text-decoration: underline double;"><?php echo $totalbalance ? '<u>' . $this->accountlib->currencyFormat($totalbalance, false) . '</u>' : '0' ?></td>
                                        <td class="orange-column"
                                            style="text-decoration: underline double;"><?php echo $totalopeningbalance ? '<u>' . $this->accountlib->currencyFormat($totalopeningbalance, false) . '</u>' : '0' ?></td>

                                    </tr>
                                    <?php if($index == 3) {?>
                                        <tr class="category-parent">
                                            <td  class="grey-column"><h5>
                                                <?php echo $profitAmount>0?$this->lang->line('surplus'): $this->lang->line('deficit'); ?>
                                                </h5>
                                            </td>
                                            <td style="text-decoration: underline double;" class="green-column"><?php echo $profitAmount ? $this->accountlib->currencyFormat(abs($profitAmount), false) : '0' ?></td>
                                            <td style="text-decoration: underline double;" class="orange-column"><?php echo $profitAmount ? $this->accountlib->currencyFormat(abs($profitAmountPrev), false) : '0' ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                    $totalbalance = 0;
                                    $totalopeningbalance = 0;
                                }
                                ?>

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
        customDatatableInitialize();
        $('#financial_year').on('change', function () {
            window.location = '<?php echo base_url(); ?>account/receiptAndPayment/index/' + $(this).val();
        });
    });

    function customDatatableInitialize() {
        var table = $('.no-sorting-table').DataTable({
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
        $('.no-sorting-table').addClass('with-border-table');

        toggleColumns(table);

        $('#toggle-transaction').on('change', function () {
            toggleColumns(table);
        });
        $('#toggle-balances').on('change', function () {
            toggleColumns(table);
        });
    }

    function toggleColumns(table) {
        //toggle transactions

        //toggle opening balances
        if ($('#toggle-balances').is(":checked")) {
            table.column(2).visible(true);
        } else {
            table.column(2).visible(false);
        }
    }
</script>