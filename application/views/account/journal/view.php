<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('journal'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('journal_number'); ?></label>
                                <div><?php echo $journal->code; ?></div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('entry_date'); ?></label>
                                <div>
                                    <?php if ($this->datechooser === 'bs') {
                                        echo $journal->entry_date_bs;
                                    } else {
                                        echo $entry_date;
                                    } ?>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('due_date'); ?></label>
                                <?php if (strtotime($journal->entry_date) > strtotime($journal->due_date)) {
                                    $due_date = '-';
                                    $due_date_bs = '-';
                                } else {
                                    $due_date = $this->customlib->formatDate($journal->due_date);
                                    $due_date_bs = $journal->due_date_bs;
                                } ?>
                                <div>
                                    <?php if ($this->datechooser === 'bs') {
                                        echo $due_date_bs;
                                    } else {
                                        echo $due_date;
                                    } ?>
                                </div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('reference_no'); ?></label>
                                <div><?php echo $journal->reference_no != '' ? $journal->reference_no : '-'; ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="journal_entry_table"
                                       class="table invoice-table" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th width="35%"><?php echo $this->lang->line('account_name'); ?></th>
                                        <th width="25%"><?php echo $this->lang->line('category'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('debit_amount'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('credit_amount'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($journal_entries) {
                                        $total_debit = $total_credit = 0;
                                        ?>
                                        <?php foreach ($journal_entries as $journal_entry) {
                                            $total_debit += ($journal_entry->amount_type == 'debit') ? $journal_entry->amount : 0;
                                            $total_credit += ($journal_entry->amount_type == 'credit') ? $journal_entry->amount : 0;
                                            ?>
                                            <tr data-id="<?php echo $journal_entry->id; ?>" class="editable-row">
                                                <td width="35%"><?php echo $journal_entry->coa_title; ?> (<?php echo $journal_entry->code; ?>)</td>
                                                <td width="25%"><?php echo ucfirst($journal_entry->coa_category); ?></td>
                                                <td width="20%"><?php echo ($journal_entry->amount_type == 'debit') ? $this->accountlib->currencyFormat($journal_entry->amount) : ''; ?></td>
                                                <td width="20%"><?php echo ($journal_entry->amount_type == 'credit') ? $this->accountlib->currencyFormat($journal_entry->amount) : ''; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td width="35%">Total</td>
                                        <td width="25%"></td>
                                        <td width="20%"><span class="footer-debit-sum"><?php echo $total_debit > 0 ? $this->accountlib->currencyFormat($total_debit) : '';?></span></td>
                                        <td width="20%"><span class="footer-credit-sum"><?php echo $total_credit > 0 ? $this->accountlib->currencyFormat($total_credit) : '';?></span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('narration'); ?></label>
                                <div><?php echo $journal->narration; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>