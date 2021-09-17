<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $receipt->id ? $this->lang->line('view_receipt') : $this->lang->line('view_receipt'); ?>
        </h1>
    </section>


    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('receipt_voucher'); ?>

                        </h3>

                    </div>

                    <div id="printcontent">
                        <div class="box-body">
                            <div class="row">
                                <input type="hidden" name="id"
                                       value="<?php echo set_value('id', $receipt->id); ?>"/>
                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('receipt_no'); ?></label>
                                    <input readonly type="text" class="form-control" name="receipt_no"
                                           value="<?php echo set_value('receipt_no', $receipt->receipt_no); ?>">

                                </div>
                                <div class="col-md-4 form-group">

                                    <label><?php echo $this->lang->line('receipt_date'); ?></label>
                                    <?php $receipt_date = isset($receipt->receipt_date) ? $this->customlib->formatDate($receipt->receipt_date) : ''; ?>
                                    <?php if ($this->datechooser === 'bs') { ?>
                                        <input type="text" readonly class="form-control" name="receipt_date_bs"
                                               readonly
                                               value="<?php echo set_value('receipt_date_bs', $receipt->receipt_date_bs); ?>">

                                    <?php } else { ?>
                                        <input type="text" class="form-control" name="receipt_date"
                                               readonly
                                               value="<?php echo set_value('receipt_date', $receipt->receipt_date); ?>">
                                    <?php } ?>


                                </div>

                                <div class="col-md-4 form-group">
                                    <label><?php echo $this->lang->line('ref_no'); ?></label>
                                    <input readonly type="text" class="form-control" name="ref_no"
                                           value="<?php echo set_value('ref_no', $receipt->ref_no); ?>">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label><?php echo $this->lang->line('receive_from'); ?></label>

                                    <input readonly type="text" class="form-control" name="receive_from"
                                           value="<?php echo set_value('receive_from', $receipt->name . '(' . $receipt->code . ')'); ?>">

                                </div>

                                <div class="col-md-3 form-group">
                                    <label><?php echo $this->lang->line('receipt_mode'); ?></label>
                                    <input readonly type="text" class="form-control" name="receipt_mode"
                                           value="<?php echo set_value('receipt_mode', $receipt->receipt_mode); ?>">


                                </div>

                                <?php if ($receipt->receipt_mode == 'cheque') { ?>
                                    <div class="col-md-2 form-group chequedate">

                                        <label><?php echo $this->lang->line('cheque_date'); ?></label>
                                        <?php $cheque_date = isset(json_decode($receipt->receipt_mode_details)->cheque_date) ? $this->customlib->formatDate(json_decode($receipt->receipt_mode_details)->cheque_date) : ''; ?>
                                        <?php if ($this->datechooser === 'bs') { ?>
                                            <input type="text" readonly name="cheque_date_bs" class="form-control"
                                                   value="<?php echo set_value('cheque_date_bs', json_decode($receipt->receipt_mode_details)->cheque_date_bs); ?>">

                                        <?php } else {
                                            ?>
                                            <input type="text" id="cheque_date" name="cheque_date"
                                                   class="form-control"
                                                   readonly
                                                   value="<?php echo set_value('cheque_date', json_decode($receipt->receipt_mode_details)->cheque_date); ?>">
                                        <?php } ?>


                                    </div>

                                    <div class="col-md-2 form-group chequeno">
                                        <label><?php echo $this->lang->line('cheque_no'); ?></label>
                                        <input readonly type="text" name="cheque_no" class="form-control"
                                               value="<?php echo set_value('cheque_no', json_decode($receipt->receipt_mode_details)->cheque_no); ?>">
                                    </div>
                                    <div class="col-md-2 form-group bank">
                                        <label><?php echo $this->lang->line('bank'); ?></label>
                                        <input readonly type="text" name="bank" class="form-control"
                                               value="<?php echo set_value('bank', $receipt->bankname); ?>">
                                    </div>

                                <?php } ?>

                                <?php if ($receipt->receipt_mode == 'Prabhupay') { ?>

                                    <div class="col-md-2 form-group TransactionId">
                                        <label><?php echo $this->lang->line('TransactionId'); ?></label>
                                        <input readonly type="text" name="TransactionId" class="form-control"
                                               value="<?php echo set_value('TransactionId', json_decode($receipt->receipt_mode_details)->TransactionId); ?>">
                                    </div>
                                    <div class="col-md-2 form-group ">

                                    </div>

                                    <div class="col-md-2 form-group ">

                                    </div>


                                <?php } ?>

                                <div class="col-md-8 form-group">
                                    <label><?php echo $this->lang->line('selected_due_receipt'); ?></label>
                                    <div id="duelist">
                                        <table class="table table-bordered">
                                            <thead>
                                            <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('receivable_amount'); ?>
                                                (<?php echo $this->currency; ?>
                                                )
                                            </th>
                                            <th class="table-primary"><?php echo $this->lang->line('due_date'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('payment_history'); ?></th>
                                            </thead>
                                            <tbody>
                                            <?php if (isset($relatedjournal)) { ?>
                                                <?php foreach ($relatedjournal as $eachrelatedjournal) { ?>
                                                    <tr>
                                                        <td><?php echo $eachrelatedjournal->code; ?></td>
                                                        <td><?php echo $eachrelatedjournal->narration; ?></td>
                                                        <td class="dueamount"><?php echo $this->accountlib->currencyFormat($eachrelatedjournal->receivableamount); ?></td>
                                                        <td><?php echo $eachrelatedjournal->due_date; ?></td>
                                                        <td><?php echo $eachrelatedjournal->pastreceipts; ?></td>

                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (isset($relatedinvoice)) { ?>
                                                <?php foreach ($relatedinvoice as $eachrelatedinvoice) { ?>
                                                    <tr>
                                                        <td><?php echo $eachrelatedinvoice->code; ?></td>
                                                        <td><?php echo $eachrelatedinvoice->narration; ?></td>
                                                        <td class="dueamount"><?php echo $this->accountlib->currencyFormat($eachrelatedinvoice->receivableamount); ?></td>
                                                        <td><?php echo $eachrelatedinvoice->due_date; ?></td>
                                                        <td><?php echo $eachrelatedinvoice->pastreceipts; ?></td>

                                                    </tr>
                                                <?php } ?>

                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="col-md-12 form-group">
                                    <label><?php echo $this->lang->line('selected_due_details'); ?></label>
                                    <div id="selectedlist">

                                        <?php if ($relatedjournaldetails) { ?>
                                            <?php echo $this->lang->line('journal_details'); ?>
                                            <table class="table table-bordered">
                                                <thead>

                                                <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
                                                <th class="table-primary "><?php echo $this->lang->line('debit_amount'); ?>
                                                    (<?php echo $this->currency; ?>
                                                    )
                                                </th>
                                                <th class="table-primary "><?php echo $this->lang->line('credit_amount'); ?>
                                                    (<?php echo $this->currency; ?>
                                                    )
                                                </th>
                                                </thead>

                                                <tbody>

                                                <?php foreach ($relatedjournaldetails as $eachjournal) {
                                                    $newjcode = $eachjournal->journalcode;
                                                    $style = "";
                                                    if (isset($newjcode) && isset($oldjcode) && $newjcode != $oldjcode) {
                                                        $style = "border-top: 1px solid #000;";
                                                    } ?>
                                                    <tr class="selectedjournallist" style="<?php echo $style; ?>">
                                                        <td><?php echo $eachjournal->journalcode; ?></td>
                                                        <td><?php echo $eachjournal->coa_title; ?></td>
                                                        <td><?php echo $eachjournal->coa_category; ?></td>
                                                        <td><?php echo $this->accountlib->currencyFormat($eachjournal->debit); ?></td>
                                                        <td><?php echo $this->accountlib->currencyFormat($eachjournal->credit); ?></td>
                                                    </tr>
                                                    <?php
                                                    $oldjcode = $eachjournal->journalcode;
                                                } ?>

                                                </tbody>
                                            </table>
                                        <?php } ?>



                                        <?php if ($relatedinvoicedetails) { ?>
                                            <?php echo $this->lang->line('invoice_details'); ?>
                                            <table class="table table-bordered">
                                                <thead>
                                                <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('quantity'); ?></th>
                                                <th class="table-primary"><?php echo $this->lang->line('rate'); ?> (<?php echo $this->currency; ?>)</th>
                                                <th class="table-primary "><?php echo $this->lang->line('total'); ?> (<?php echo $this->currency; ?>)
                                                </th>
                                                </thead>

                                                <tbody>

                                                <?php foreach ($relatedinvoicedetails as $eachinvoice) {
                                                    $newicode = $eachinvoice->code;
                                                    $style = "";
                                                    if (isset($newicode) && isset($oldicode) && $newicode != $oldicode) {
                                                        $style = "border-top: 1px solid #000;";
                                                    } ?>
                                                    <tr class="selectedjournallist" style="<?php echo $style; ?>">

                                                        <td><?php echo $eachinvoice->code; ?></td>
                                                        <td><?php echo $eachinvoice->coa_title; ?></td>
                                                        <td><?php echo $eachinvoice->coa_category; ?></td>
                                                        <td><?php echo $eachinvoice->quantity; ?></td>
                                                        <td><?php echo $this->accountlib->currencyFormat($eachinvoice->rate); ?></td>
                                                        <td><?php echo $this->accountlib->currencyFormat($eachinvoice->total); ?></td>
                                                    </tr>
                                                    <?php
                                                    $oldicode = $eachinvoice->code;
                                                } ?>

                                                </tbody>
                                            </table>
                                        <?php } ?>

                                    </div>

                                </div>
                                <div class="col-md-6 form-group">
                                    <label><?php echo $this->lang->line('narration'); ?></label>
                                    <textarea readonly name="narration" rows="5" required
                                              class="form-control"><?php echo set_value('narration', $receipt->description); ?></textarea>
                                </div>

                                <div class="col-md-4 form-group">
                                    <table class="table">
                                        <tbody>

                                        <tr>
                                            <th><?php echo $this->lang->line('net_total'); ?> (<?php echo $this->currency; ?>)</th>
                                            <td><input readonly type="text"
                                                       value="<?php echo $this->accountlib->currencyFormat($receipt->nettotal); ?>"
                                                       name="nettotal"
                                                       class="form-control"></td>
                                        </tr>

                                        <tr>
                                            <th><?php echo $this->lang->line('paid_amount'); ?> (<?php echo $this->currency; ?>)</th>
                                            <td><input readonly type="text"
                                                       name="paid_amount"
                                                       value="<?php echo set_value('paid_amount', $this->accountlib->currencyFormat($receipt->received_amount)) ?>"
                                                       class="form-control">
                                                <span class="text-danger"><?php echo form_error('paid_amount'); ?></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo site_url('account/receipt/generatePDF/' . $receipt->id); ?>"
                                   class="btn btn-primary btn-sm">
                                    <?php echo $this->lang->line('generate_pdf'); ?>
                                </a>
                                <a href="<?php echo site_url('account/receipt/sendMail/' . $receipt->id); ?>"
                                   class="btn btn-primary btn-sm">
                                    <?php echo $this->lang->line('send_mail'); ?>
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
        frameDoc.document.write('<html><head><title>Invoice</title>');
        frameDoc.document.write('</head><body>');
//Append the external CSS file.
        frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>/backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>/backend/dist/css/style-main.css">');

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
