<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $payment->id ? $this->lang->line('view_payment') : $this->lang->line('view_payment'); ?>
        </h1>
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('payment_voucher'); ?>
                        </h3>

                    </div>
                    <div class="row box-body">
                        <?php echo form_open('account/payment/save_payment', array('name' => 'payment_form', 'id' => 'payment_form')); ?>
                        <input type="hidden" name="id" value="<?php echo set_value('id', $payment->id); ?>"/>
                        <div class="col-md-4 form-group">
                            <label><?php echo $this->lang->line('payment_no'); ?></label>
                            <input readonly type="text" name="payment_no"
                                   value="<?php echo set_value('payment_no', $payment->payment_no); ?>"
                                   class="form-control">
                        </div>
                        <div class="col-md-4 form-group">


                            <label><?php echo $this->lang->line('payment_date'); ?></label>
                            <?php $payment_date = isset($payment->payment_date) ? $this->customlib->formatDate($payment->payment_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>

                                <input readonly type="text" name="payment_date_bs"
                                       value="<?php echo set_value('payment_date_bs', $payment->payment_date_bs); ?>"
                                       class="form-control">
                            <?php } else {
                               ?>
                                <input readonly type="text" name="payment_date"
                                       value="<?php echo set_value('payment_date', $payment_date); ?>"
                                       class="form-control">

                            <?php } ?>


                        </div>

                        <div class="col-md-4 form-group">
                            <label><?php echo $this->lang->line('ref_no'); ?></label>
                            <input readonly type="text" name="ref_no"
                                   value="<?php echo set_value('ref_no', $payment->ref_no); ?>"
                                   class="form-control">

                        </div>

                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('pay_to'); ?></label>
                            <input readonly type="text" name="pay_to"
                                   value="<?php echo set_value('pay_to', $payment->name . '(' . $payment->code . ')'); ?>"
                                   class="form-control">
                        </div>


                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('payment_mode'); ?></label>
                            <input readonly type="text" name="payment_mode"
                                   value="<?php echo set_value('payment_mode', $payment->payment_mode); ?>"
                                   class="form-control">
                        </div>
                        <?php if($payment->payment_mode=='cheque') { ?>
                        <div class="col-md-2 form-group chequedate">


                            <label><?php echo $this->lang->line('cheque_date'); ?></label>
                            <?php $cheque_date = isset(json_decode($payment->payment_mode_details)->cheque_date) ? $this->customlib->formatDate(json_decode($payment->payment_mode_details)->cheque_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>
                                 <input readonly type="text" name="cheque_date_bs"
                                       value="<?php echo set_value('cheque_date_bs', json_decode($payment->payment_mode_details)->cheque_date_bs); ?>"
                                       class="form-control">

                            <?php } else {
                                ?>
                                <input readonly type="text" name="cheque_date"
                                       value="<?php echo set_value('paid_amount', $cheque_date); ?>"
                                       class="form-control">
                            <?php } ?>



                        </div>


                        <div class="col-md-2 form-group chequeno">
                            <label><?php echo $this->lang->line('cheque_no'); ?></label>

                            <input readonly type="text" name="cheque_no"
                                   value="<?php echo set_value('cheque_no', json_decode($payment->payment_mode_details)->cheque_no); ?>"
                                   class="form-control">

                        </div>

                        <div class="col-md-2 form-group bank">
                            <label><?php echo $this->lang->line('bank'); ?></label>
                            <input readonly type="text" name="bank"
                                   value="<?php echo set_value('bank', $payment->bankname); ?>"
                                   class="form-control">

                        </div>

                        <?php } ?>


                        <div class="col-md-8 form-group">

                                <label><?php echo $this->lang->line('selected_journal_for_payment'); ?></label>

                            <div id="duelist">
                                <table class="table table-bordered">
                                    <thead>
                                    <th class="table-primary"><?php echo $this->lang->line('journal_no'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('payable_amount'); ?> (<?php echo $this->currency; ?>)</th>
                                    <th class="table-primary"><?php echo $this->lang->line('due_date'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('payment_history'); ?></th>
                                    </thead>
                                    <tbody>
                                    <?php if ($relatedjournal) {
                                        foreach ($relatedjournal as $eachrelatedjournal) {
                                            ?>
                                            <tr>
                                                <td><?php echo $eachrelatedjournal->code; ?></td>
                                                <td><?php echo $eachrelatedjournal->narration; ?></td>
                                                <td class="dueamount"><?php  echo $this->accountlib->currencyFormat( $eachrelatedjournal->payableamount); ?></td>
                                                <td><?php echo $eachrelatedjournal->due_date; ?></td>
                                                <td><?php echo $eachrelatedjournal->pastpayments; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            <label><?php echo $this->lang->line('selected_journal_details'); ?></label>
                            <div id="selectedlist">
                                <table class="table table-bordered">
                                    <thead>
                                    <th class="table-primary"><?php echo $this->lang->line('journal_no'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
                                    <th class="table-primary "><?php echo $this->lang->line('debit_amount'); ?> (<?php echo $this->currency; ?>)</th>
                                    <th class="table-primary "><?php echo $this->lang->line('credit_amount'); ?> (<?php echo $this->currency; ?>)</th>
                                    </thead>

                                    <tbody>
                                    <?php if ($relatedjournaldetails) { ?>
                                        <?php foreach ($relatedjournaldetails as $eachjournal) {
                                            $newjcode = $eachjournal->journalcode;
                                            $style = "";
                                            if (isset($newjcode) && isset($oldjcode) && $newjcode != $oldjcode)
                                            {$style = "border-top: 1px solid #000;"; } ?>
                                            <tr class="selectedjournallist" style="<?php echo $style; ?>">
                                                <td>
                                                    <?php echo $eachjournal->journalcode; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->coa_title; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->coa_category; ?></td>
                                                <td>
                                                    <?php echo $this->accountlib->currencyFormat( $eachjournal->debit); ?></td>
                                                <td>
                                                    <?php echo $this->accountlib->currencyFormat( $eachjournal->credit); ?></td>
                                            </tr>
                                        <?php
                                            $oldjcode = $eachjournal->journalcode;
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><?php echo $this->lang->line('narration'); ?></label>
<!--                            <span class="form-control">--><?php //echo $payment->description; ?><!--</span>-->
                            <textarea name="narration" rows="5" required readonly
                                      class="form-control"><?php echo set_value('narration', $payment->description); ?></textarea>


                        </div>

                        <div class="col-md-4 form-group">
                            <table class="table">
                                <tbody>

                                <tr>
                                    <th><?php echo $this->lang->line('net_total'); ?> (<?php echo $this->currency; ?>)</th>
                                    <td><input readonly type="text"
                                               value="<?php echo $payment ? $this->accountlib->currencyFormat($payment->nettotal): 0; ?>" id="nettotal"
                                               name="nettotal"
                                               class="form-control"></td>
                                </tr>

                                <tr>
                                    <th><?php echo $this->lang->line('paid_amount'); ?> (<?php echo $this->currency; ?>)</th>
                                    <td><input readonly type="text"
                                               name="paid_amount"
                                               value="<?php echo set_value('paid_amount', $this->accountlib->currencyFormat($payment->paid_amount)); ?>"
                                               class="form-control">
                                     </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 form-group">
                            <input readonly type="checkbox" name="send_mail" value="1"
                                <?php if ($payment->send_email == '1') echo "checked"; ?>
                                   class="btn btn-primary pull-left btn-sm checkbox-toggle"> <?php echo $this->lang->line('sendmail'); ?>

                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<style>
    .form-control {
        border: 0;
    }
</style>


