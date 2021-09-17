<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Receipt</title>
    <style type="text/css">

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            max-width: 210mm;
            height: 297mm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 10pt;
            font-family: 'annapurna', serif;
        }

        header {
            padding: 1mm 0;
            margin-bottom: 1mm;
            border-bottom: 1px solid #AAAAAA;
        }

        h3 {
            font-size: 15pt;
        }

        h2.name {
            font-size: 30pt;
            font-weight: normal;
            margin: 0;
        }

        table {
            width: 100%;
            max-width: 210mm;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        table th {
            padding: 10px;
            background: #ffffff;
            text-align: center;
            border: 1px solid #000000;
        }

        table td {
            padding: 10px;
            background: #ffffff;
            text-align: center;
            border: 1px solid #000000;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
            padding: 2px;
        }

        .no {
            color: #555555;
            font-size: 11pt;
        }

        .qty {
            font-size: 11pt;
        }

        table tfoot td {
            padding: 5px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 10pt;
            white-space: nowrap;
            border-top: 1px solid #000000;
        }

        .pass {
            color: green;
        }

        .fail {
            color: red;
        }

        #signatures {
            margin-top: 60px;
        }
    </style>
</head>

<header class="clearfix">
    <table style="border:0;background: #ffffff !important;">
        <tr>
            <td style="background: #ffffff !important;text-align: left; margin-top: 8px;border: 0;">
                <img style="height:100px; "
                     src="<?php echo base_url(); ?>/uploads/school_content/logo/<?php echo $settings['image']; ?>">
            </td>
            <td style="background: #ffffff !important;float: right;
                        text-align: right;border: 0;">
                <h2 class="name"><?php echo $settings['name']; ?></h2>
                <div><?php echo $settings['address']; ?></div>
                <div><?php echo $settings['phone']; ?></div>
                <div><?php echo $settings['email']; ?></div>
            </td>
        </tr>
    </table>
</header>
<h3><?php echo $this->lang->line('cash_receipt'); ?></h3>
<div class="row box-body">
    <?php echo form_open('account/receipt/save_receipt', array('name' => 'receipt_form', 'id' => 'receipt_form')); ?>
    <input type="hidden" name="id"
           value="<?php echo set_value('id', $receipt->id); ?>"/>
    <div class="col-md-4 form-group">
        <label><?php echo $this->lang->line('receipt_no'); ?>: </label>
        <?php echo $receipt->receipt_no; ?>

    </div>
    <div class="col-md-4 form-group">

        <label><?php echo $this->lang->line('receipt_date'); ?>:</label>
        <?php $receipt_date = isset($receipt->receipt_date) ? $this->customlib->formatDate($receipt->receipt_date) : ''; ?>
        <?php if ($this->datechooser === 'bs') {
            echo $receipt->receipt_date_bs;
        } else {
            echo $receipt->receipt_date;
        } ?>


    </div>

    <div class="col-md-4 form-group">
        <label><?php echo $this->lang->line('ref_no'); ?>: </label>
        <?php echo $receipt->ref_no; ?>
    </div>

    <div class="col-md-3 form-group">
        <label><?php echo $this->lang->line('received_from'); ?>: </label>
        <?php echo $receipt->name . '(' . $receipt->code . ')'; ?>

    </div>

    <div class="col-md-3 form-group">
        <label><?php echo $this->lang->line('receipt_mode'); ?>: </label>
        <?php echo ucfirst($receipt->receipt_mode); ?>

    </div>

    <?php if ($receipt->receipt_mode == 'cheque') { ?>

        <div class="col-md-2 form-group chequedate">

            <label><?php echo $this->lang->line('cheque_date'); ?>: </label>
            <?php $cheque_date = isset(json_decode($receipt->receipt_mode_details)->cheque_date) ? $this->customlib->formatDate(json_decode($receipt->receipt_mode_details)->cheque_date) : ''; ?>
            <?php if ($this->datechooser === 'bs') {
                echo json_decode($receipt->receipt_mode_details)->cheque_date_bs;
            } else {
                echo $cheque_date;
                ?>

            <?php } ?>


        </div>

        <div class="col-md-2 form-group chequeno">
            <label><?php echo $this->lang->line('cheque_no'); ?>: </label>
            <?php echo json_decode($receipt->receipt_mode_details)->cheque_no; ?>

        </div>
        <div class="col-md-2 form-group bank">
            <label><?php echo $this->lang->line('bank'); ?>: </label>
            <?php echo $receipt->bankname; ?>
        </div>

    <?php } ?>


    <div class="col-md-8 form-group">
        <h4><b><?php echo $this->lang->line('payment_detail'); ?></b></h4>
        <div id="duelist">
            <table class="table ">

                <tbody>
                <tr>
                    <td style="text-align:center;"><?php echo $this->lang->line('entry_no'); ?></td>
                    <td style="text-align:center;"><?php echo $this->lang->line('description'); ?></td>
                    <td style="text-align:center;"><?php echo $this->lang->line('due_details'); ?></td>
                    <td style="text-align:center;"><?php echo $this->lang->line('due_amount'); ?>
                        (<?php echo $this->currency; ?>
                        )
                    </td>
                    <td style="text-align:center;"><?php echo $this->lang->line('due_date'); ?></td>
                </tr>
                <?php if (isset($relatedjournal)) { ?>
                    <?php foreach ($relatedjournal as $eachrelatedjournal) { ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $eachrelatedjournal->code; ?></td>
                            <td style="text-align:center;"><?php echo $eachrelatedjournal->narration; ?></td>

                            <?php if ($relatedjournaldetails) { ?>
                                <td>
                                    <table style="border: 0;">
                                        <?php foreach ($relatedjournaldetails as $eachjournal) {
                                            if ($eachrelatedjournal->code == $eachjournal->journalcode) { ?>
                                                <tr style="border: none;">
                                                    <td style="text-align:center; border-style: hidden;"><?php echo $eachjournal->coa_title; ?></td>
                                                    <td style="text-align:center;"><?php echo $this->accountlib->currencyFormat($eachjournal->debit); ?></td>
                                                    <td style="text-align:center;"><?php echo $this->accountlib->currencyFormat($eachjournal->credit); ?></td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </table>
                                </td>
                                <?php

                            }
                            ?>
                            <td style="text-align:center;"
                                class="dueamount"><?php echo $this->accountlib->currencyFormat($eachrelatedjournal->receivableamount); ?></td>
                            <td style="text-align:center;"><?php echo $eachrelatedjournal->due_date; ?></td>

                        </tr>
                    <?php } ?>
                <?php } ?>
                <?php if (isset($relatedinvoice)) { ?>
                    <?php foreach ($relatedinvoice as $eachrelatedinvoice) { ?>

                        <tr>
                            <td style="text-align:center;"><?php echo $eachrelatedinvoice->code; ?></td>
                            <td style="text-align:center;"><?php echo $eachrelatedinvoice->narration; ?></td>
                            <?php if ($relatedinvoicedetails) { ?>
                                <td>
                                    <table border="0" style="border: 0;">
                                        <?php
                                        foreach ($relatedinvoicedetails as $eachinvoice) {
                                            if ($eachrelatedinvoice->code == $eachinvoice->code) { ?>
                                                <tr>
                                                    <td style="border: 0;text-align:left;"><?php echo $eachinvoice->coa_title; ?></td>
                                                    <td style="border: 0;text-align:right;"><?php echo $this->accountlib->currencyFormat($eachinvoice->total); ?></td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </table>
                                </td>
                                <?php
                            }
                            ?>
                            <td style="text-align:center;"
                                class="dueamount"><?php echo $this->accountlib->currencyFormat($eachrelatedinvoice->receivableamount); ?></td>
                            <td style="text-align:center;"><?php echo $eachrelatedinvoice->due_date; ?></td>

                        </tr>
                    <?php } ?>

                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?php echo $this->lang->line('net_total'); ?></td>
                    <td>  <?php echo $this->accountlib->currencyFormat($receipt->nettotal); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>

                    <td><?php echo $this->lang->line('paid_amount'); ?></td>
                    <td>        <?php echo $this->accountlib->currencyFormat($receipt->received_amount); ?></td>
                    <td></td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>


    <div class="col-md-6 form-group">
        <label><?php echo $this->lang->line('narration'); ?>: </label>
        <?php echo $receipt->description; ?>
    </div>

</div>
<?php echo form_close(); ?>
<?php if($signature) { ?>
<div id="signatures" class="clearfix">
    <table cellpadding="0" cellspacing="0"
           style="width:100%; border:0; background-color: #ffffff; margin:0 0 2px 0;">
        <tr>
            <td style="font-size: .65em; font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <u><?php echo date('Y/m/d', time()); ?><u>
            </td>
            <td style="font-size: .65em; font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                &nbsp;
            </td>
            <td style="font-size: .65em; font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                ____________________
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <b><?php echo $this->lang->line('date'); ?></b></td>
            <td style="font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                &nbsp;
            </td>
            <td style="font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <b><?php echo $this->lang->line('accountant'); ?></b></td>
        </tr>
    </table>
</div>
<?php  } ?>
</body>

</html>
