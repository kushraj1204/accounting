<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Invoice</title>
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
<h3>Invoice</h3>

    <div id="printcontent">
        <div class="box-body">
            <div class="row"><label><?php echo $this->lang->line('invoice_number'); ?>: </label>



                <?php echo $invoice->code; ?>
                </div>
                <div class="col-md-4 form-group">
                    <label><?php echo $this->lang->line('invoice_date'); ?>: </label>

                        <?php if ($this->datechooser === 'bs') {
                            echo $invoice->invoice_date_bs;
                        } else {
                            echo $invoice_date;
                        } ?>
                </div>
                <div class="col-md-4 form-group">
                    <label><?php echo $this->lang->line('due_date'); ?>: </label>
                    <?php if (strtotime($invoice->invoice_date) > strtotime($invoice->due_date)) {
                        $due_date = '-';
                        $due_date_bs = '-';
                    } else {
                        $due_date = $this->customlib->formatDate($invoice->due_date);
                        $due_date_bs = $invoice->due_date_bs;
                    } ?>
                        <?php if ($this->datechooser === 'bs') {
                            echo $due_date_bs;
                        } else {
                            echo $due_date;
                        } ?>
                </div>
                <div class="col-md-4 form-group">
                    <label><?php echo $this->lang->line('reference_no'); ?>: </label>
                    <?php echo $invoice->reference_no != '' ? $invoice->reference_no : '-'; ?>
                </div>
                <div class="col-md-4 form-group">
                    <label><?php echo $this->lang->line('registered_no'); ?>: </label>
                    <?php echo $invoice->registered_no != '' ? $invoice->registered_no : '-'; ?>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-7">
                    <label><?php echo $this->lang->line('customer_name'); ?>: </label>
                   <?php echo $invoice->name; ?> (<?php echo $invoice->personnel_code; ?>)
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <table
                           class="table " cellspacing="0" width="50%" style="width: 50%;">
                        <thead>
                        <tr>
                            <th width="35%" style="text-align: center"><?php echo $this->lang->line('account_name'); ?></th>
                            <th width="15%" style="text-align: center"><?php echo $this->lang->line('amount'); ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($invoice->id > 0 && count($invoice_entries) > 0) { ?>
                            <?php foreach ($invoice_entries as $invoice_entry) {
                                $multiplier = $invoice_entry->balance_type == 'credit' ? 1 : -1;
                                $sum = $sum + ($multiplier * $invoice_entry->rate * $invoice_entry->quantity); ?>
                                <tr data-id="<?php echo $invoice_entry->id; ?>" class="editable-row">
                                    <td width="35%" style="text-align: center"><?php echo $invoice_entry->coa_title; ?></td>
                                    <td width="15%" style="text-align: center"><?php echo $this->accountlib->currencyFormat($invoice_entry->rate * $invoice_entry->quantity); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td width="35%" style="text-align: center">Total</td>
                            <td width="15%" style="text-align: center"><span
                                    class="footer-sum"><?php echo $sum > 0 ? $this->accountlib->currencyFormat($sum) : ''; ?></span>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label><?php echo $this->lang->line('description'); ?>: </label>
                    <?php echo $invoice->description; ?>
                </div>
                <div class="col-md-3 form-group">
                    <label><?php echo $this->lang->line('amount_due'); ?>: </label>
                    <?php echo $this->accountlib->currencyFormat($sum); ?>
                </div>
            </div>
        </div>
    </div>
<?php if($signature) { ?>
<div id="signatures" class="clearfix">
    <table cellpadding="0" cellspacing="0"
           style="width:100%; border:0; background-color: #ffffff; margin:0 0 2px 0;">
        <tr>
            <td style="font-size: .65em; font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <u><?php echo date('Y/m/d',time()); ?><u>
            </td>

            <td style="font-size: .65em; font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                ____________________
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <b>Date</b></td>

            <td style="font-weight:bold;text-align:center; background-color: #ffffff; border: 0;">
                <b>Accountant</b></td>
        </tr>
    </table>
</div>
<?php  } ?>
</body>

</html>
