<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$sum = 0; ?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('msg')) {
                    echo show_message();
                } ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('invoice'); ?>
                        </h3>
                    </div>
                    <div id="printcontent">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <div><?php echo $invoice->code; ?></div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('invoice_date'); ?></label>
                                    <div>
                                        <?php if ($this->datechooser === 'bs') {
                                            echo $invoice->invoice_date_bs;
                                        } else {
                                            echo $invoice_date;
                                        } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('due_date'); ?></label>
                                    <small class="text-muted">(Optional)</small>
                                    <?php if (strtotime($invoice->invoice_date) > strtotime($invoice->due_date)) {
                                        $due_date = '-';
                                        $due_date_bs = '-';
                                    } else {
                                        $due_date = $this->customlib->formatDate($invoice->due_date);
                                        $due_date_bs = $invoice->due_date_bs;
                                    } ?>
                                    <div>
                                        <?php if ($this->datechooser === 'bs') {
                                            echo $due_date_bs;
                                        } else {
                                            $due_date;
                                        } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('reference_no'); ?></label>
                                    <div><?php echo $invoice->reference_no != '' ? $invoice->reference_no : '-'; ?></div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('registered_no'); ?></label>
                                    <div><?php echo $invoice->registered_no != '' ? $invoice->registered_no : '-'; ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7">
                                    <label><?php echo $this->lang->line('customer_name'); ?></label>
                                    <div><?php echo $invoice->name; ?> (<?php echo $invoice->personnel_code; ?>)</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="invoice_entry_table"
                                           class="table invoice-table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="35%"><?php echo $this->lang->line('account_name'); ?></th>
                                            <th width="25%"><?php echo $this->lang->line('category'); ?></th>
                                            <th width="10%"><?php echo $this->lang->line('quantity'); ?></th>
                                            <th width="10%"><?php echo $this->lang->line('rate'); ?></th>
                                            <th width="20%"><?php echo $this->lang->line('amount'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($invoice->id > 0 && count($invoice_entries) > 0) { ?>
                                            <?php foreach ($invoice_entries as $invoice_entry) {
                                                $multiplier = $invoice_entry->balance_type == 'credit' ? 1 : -1;
                                                $sum = $sum + ($multiplier * $invoice_entry->rate * $invoice_entry->quantity); ?>
                                                <tr data-id="<?php echo $invoice_entry->id; ?>" class="editable-row">
                                                    <td width="35%"><?php echo $invoice_entry->coa_title; ?></td>
                                                    <td width="25%"><?php echo $invoice_entry->coa_category; ?></td>
                                                    <td width="10%"><?php echo $invoice_entry->quantity; ?></td>
                                                    <td width="10%"><?php echo $this->accountlib->currencyFormat($invoice_entry->rate); ?></td>
                                                    <td width="20%"><?php echo $this->accountlib->currencyFormat($invoice_entry->rate * $invoice_entry->quantity); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td width="35%">Total</td>
                                            <td width="25%"></td>
                                            <td width="10%"></td>
                                            <td width="10%"></td>
                                            <td width="20%"><span
                                                        class="footer-sum"><?php echo $sum > 0 ? $this->accountlib->currencyFormat($sum) : ''; ?></span>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <div><?php echo $invoice->description; ?></div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label><?php echo $this->lang->line('amount_due'); ?></label>
                                    <div><?php echo $this->accountlib->currencyFormat($sum); ?></div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo site_url('account/invoice/generatePDF/'. $invoice->id); ?>"
                                   class="btn btn-primary btn-sm">
                                    <?php echo $this->lang->line('generate_pdf') ?>
                                </a>
                                <a href="<?php echo site_url('account/invoice/sendMail/'. $invoice->id); ?>"
                                   class="btn btn-primary btn-sm">
                                    <?php echo $this->lang->line('Send Mail') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $(document).ready(function () {
        $('#export').on('click', function () {
            printData();
        });
    });

    function printData() {
// PRINT SCRIPTS
        var contents = $("#printcontent").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
//Create a new HTML document.
        frameDoc.document.write('<html><head><title>Receipt</title>');
        frameDoc.document.write('</head><body>');
//Append the external CSS file.
        frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>//backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>//backend/dist/css/style-main.css">');

//Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }

</script>