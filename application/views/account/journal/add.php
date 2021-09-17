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
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add_journal'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo show_message();
                        } ?>
                        <?php echo form_open('account/journal/save_journal'); ?>
                        <div class="row">
                            <input type="hidden" name="id" value="<?php echo set_value('id', $journal->id); ?>"/>

                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('journal_no'); ?></label>
                                <?php echo form_error('code', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="code" name="code" class="form-control" readonly
                                       value="<?php echo $journal_id; ?>">
                            </div>
                            <div class="col-md-4 form-group journal_date_form">
                                <label><?php echo $this->lang->line('entry_date'); ?></label>
                                <?php $entry_date = $journal->id > 0 ? $this->customlib->formatDate($journal->entry_date) : ''; ?>
                                <?php if ($this->datechooser === 'bs') { ?>
                                    <?php echo form_error('entry_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                    <input type="text" id="entry_date_bs" name="entry_date_bs" class="form-control"
                                           readonly required
                                           value="<?php echo set_value('entry_date_bs', $journal->entry_date_bs); ?>">
                                    <input type="hidden" id="entry_date" name="entry_date" class="form-control"
                                           readonly
                                           value="<?php echo set_value('entry_date', $entry_date); ?>">
                                <?php } else {
                                    echo form_error('entry_date', '<span class="text-danger pull-right">', '</span>'); ?>
                                    <input type="text" id="entry_date" name="entry_date" class="form-control" readonly
                                           value="<?php echo set_value('entry_date', $entry_date); ?>">
                                    <input type="hidden" id="entry_date_bs" name="entry_date_bs"
                                           class="form-control" readonly
                                           value="<?php echo set_value('entry_date_bs', $journal->entry_date_bs); ?>">
                                <?php } ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('due_date'); ?></label> <small
                                        class="text-muted"><?php echo $this->lang->line('optional') ?></small>
                                <?php if (strtotime($journal->entry_date) > strtotime($journal->due_date) || $journal->id == 0) {
                                    $due_date = '';
                                    $due_date_bs = '';
                                } else {
                                    $due_date = $this->customlib->formatDate($journal->due_date);
                                    $due_date_bs = $journal->due_date_bs;
                                } ?>
                                <?php if ($this->datechooser === 'bs') { ?>
                                    <?php echo form_error('due_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                    <input type="text" id="due_date_bs" name="due_date_bs" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date_bs', $due_date_bs); ?>">
                                    <input type="hidden" id="due_date" name="due_date" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date', $due_date); ?>">
                                <?php } else { ?>
                                    <?php echo form_error('due_date', '<span class="text-danger pull-right">', '</span>'); ?>
                                    <input type="text" id="due_date" name="due_date" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date', $due_date); ?>">
                                    <input type="hidden" id="due_date_bs" name="due_date_bs" class="form-control"
                                           readonly required
                                           value="<?php echo set_value('due_date_bs', $due_date_bs); ?>">
                                <?php } ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('reference_no'); ?></label> <small
                                        class="text-muted"><?php echo $this->lang->line('optional') ?></small>
                                <?php echo form_error('reference_no', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="reference_no" name="reference_no" class="form-control"
                                       value="<?php echo set_value('reference_no', $journal->reference_no); ?>">
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
                                        <th width="20%"><?php echo $this->lang->line('category'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('debit_amount'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('credit_amount'); ?></th>
                                        <th width="5%"
                                            class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
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
                                                <td width="35%"><?php echo $journal_entry->coa_title; ?>
                                                    (<?php echo $journal_entry->code; ?>)
                                                </td>
                                                <td width="20%"><?php echo ucfirst($journal_entry->coa_category); ?></td>
                                                <td width="20%"><?php echo ($journal_entry->amount_type == 'debit') ? $this->accountlib->currencyFormat($journal_entry->amount) : ''; ?></td>
                                                <td width="20%"><?php echo ($journal_entry->amount_type == 'credit') ? $this->accountlib->currencyFormat($journal_entry->amount) : ''; ?></td>
                                                <td width="5%" class="mailbox-date no-print text">
                                                    <a href="#" class="btn btn-default btn-xs delete_entry"
                                                       data-toggle="tooltip" data-id="<?php echo $journal_entry->id; ?>"
                                                       title="" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td width="35%"><?php echo $this->lang->line('total'); ?></td>
                                        <td width="20%"></td>
                                        <td width="20%"><span
                                                    class="footer-debit-sum"><?php echo $total_debit > 0 ? $this->accountlib->currencyFormat($total_debit) : ''; ?></span>
                                        </td>
                                        <td width="20%"><span
                                                    class="footer-credit-sum"><?php echo $total_credit > 0 ? $this->accountlib->currencyFormat($total_credit) : ''; ?></span>
                                        </td>
                                        <td width="5%"></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <a href="#add_row" role="button"
                                   class="btn btn-secondary btn-sm add_row"
                                   data-toggle="tooltip" title="<?php echo $this->lang->line('add_row'); ?>"
                                   data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                            class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('narration'); ?></label>
                                <?php echo form_error('narration', '<span class="text-danger pull-right">', '</span>'); ?>
                                <textarea name="narration"
                                          class="form-control"><?php echo set_value('narration', $journal->narration); ?></textarea>
                            </div>
                        </div>
                        <div class="hidden-field"></div>
                        <?php if ($allow) { ?>
                            <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary pull-right btn-sm submit-form"> <?php echo $this->lang->line('save'); ?></button>
                            <?php echo form_close(); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    let lastID = 0; //maximum id from database
    let hide = true;
    let journal_entry = [];//contains all the entries from database
    let clickedTr = 0;
    $('document').ready(function () {

        <?php if($journal->id > 0 && count($journal_entries) > 0){?>
        <?php foreach($journal_entries as $journal_entry){?>
        var entry = {};
        entry.id = parseInt('<?php echo $journal_entry->id;?>');
        entry.coa_title = $.trim('<?php echo $journal_entry->coa_title;?>') + ' (' + $.trim('<?php echo $journal_entry->code;?>') + ')';
        entry.coa_category = $.trim('<?php echo ucfirst($journal_entry->coa_category);?>');
        entry.coa_type = '<?php echo ($journal_entry->coa_id != 0) ? "coa" : "personnel";?>';
        entry.coa_id = parseInt('<?php echo ($journal_entry->coa_id != 0) ? $journal_entry->coa_id : $journal_entry->personnel_id;?>');
        entry.amount = parseFloat('<?php echo $journal_entry->amount;?>');
        entry.amount_display = '<?php echo $this->accountlib->currencyFormat($journal_entry->amount);?>';
        entry.amount_type = '<?php echo $journal_entry->amount_type;?>';
        entry.debit = parseInt('<?php echo $journal_entry->amount_type == 'debit' ? 1 : 0;?>');
        entry.credit = parseInt('<?php echo $journal_entry->amount_type == 'credit' ? 1 : 0;?>');
        entry.is_new = 0;
        journal_entry.push(entry);
        <?php } ?>
        <?php } ?>

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        var due_date_duration = parseInt('<?php echo $this->accountSetting->due_date_duration;?>');
        <?php if($this->datechooser === 'bs'){?>

        $("#entry_date_bs").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true
        }).on('dateSelect', function (e) {
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);
            if ($("#due_date_bs").val() == '') {
                var due_date = moment(adDate).add('days', due_date_duration);
                var m = moment(due_date, date_format.toUpperCase());
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                due_date = due_date.format("YYYY-MM-DD");

                $('#due_date').val(m.format(date_format.toUpperCase()));
                $('#due_date_bs').val(bsdate);
            }
        });

        $("#due_date_bs").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true
        }).on('dateSelect', function (e) {
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);
            if ($("#entry_date_bs").val() == '') {
                var entry_date = moment(adDate).subtract('days', due_date_duration);
                var m = moment(entry_date, date_format.toUpperCase());
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                entry_date = entry_date.format("YYYY-MM-DD");

                $('#entry_date').val(entry_date);
                $('#entry_date_bs').val(bsdate);

            }
        });
        <?php }else{?>
        $('#entry_date').datepicker({
            format: date_format,
            autoclose: true,
        }).on('input change', function (e) {
            var id = e.target.id + "_bs";
            var val = e.target.value;
            var m = moment(val, date_format.toUpperCase());
            try {
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                $("#" + id).val(bsdate);
                if ($('#due_date').val() == '') {
                    var due_date = moment(val).add('days', due_date_duration);
                    var m = moment(due_date, date_format.toUpperCase());
                    var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                    var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                    $('#due_date').val(m.format(date_format.toUpperCase()));
                    $('#due_date_bs').val(bsdate);
                }
            } catch (e) {
                //
            }
        });
        $('#due_date').datepicker({
            format: date_format,
            autoclose: true,
        }).on('input change', function (e) {
            var id = e.target.id + "_bs";
            var val = e.target.value;
            var m = moment(val, date_format.toUpperCase());
            try {
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                $("#" + id).val(bsdate);
                if ($('#entry_date').val() == '') {
                    var entry_date = moment(val).subtract('days', due_date_duration);
                    var m = moment(entry_date, date_format.toUpperCase());
                    var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                    var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                    $('#entry_date').val(m.format(date_format.toUpperCase()));
                    $('#entry_date_bs').val(bsdate);
                }
            } catch (e) {
                //
            }
        });
        <?php } ?>

        $('.add_row').on('click', function (e) {
            e.preventDefault();
            renderEmptyRow(1);
        });

        //journal entry
        var empty_row_template = Handlebars.compile($('#empty_row_template').html());
        var form_row_template = Handlebars.compile($('#form_row_template').html());
        var value_row_template = Handlebars.compile($('#value_row_template').html());
        var lastID = $.isNumeric($('.editable-row:last-child').data('id')) ? $('.editable-row:last-child').data('id') : 0;

        if (journal_entry.length === 0) {
            renderEmptyRow(2);
        }

        $('body').on("click", function () {
            if (hide) {
                if ($('tr.selected-row').length > 0) {
                    var id = $('tr.selected-row').data('id');
                    var rowWithId = getRowWithID(id);
                    var row = {};
                    if (rowWithId >= 0) {
                        row = journal_entry[rowWithId];
                    } else {
                        row.id = id;
                        row.coa_category = '';
                    }
                    renderValuedRow(row);
                }
                calculateSum();
                renderValuedRowEntry();
                $('tr.editable-row').removeClass('selected-row');
            }
            hide = true;
        });

        $('body').on('click', '.editable-row td', function (e) {
            if ($(e.target).parent().hasClass('remove-entry')) {
                e.preventDefault();
                var id = $(this).closest('.editable-row').data('id');
                var rowWithId = getRowWithID(id);
                var confirmDelete = 1;
                if (rowWithId >= 0) {
                    confirmDelete = confirm('<?php echo $this->lang->line('delete_confirm') ?>');
                }
                if (confirmDelete) {
                    journal_entry = jQuery.grep(journal_entry, function (item) {
                        return parseInt(item.id) != parseInt(id);
                    });
                    $('.invoice-table tbody').find('[data-id="' + id + '"]').remove();
                    var countRow = $('.editable-row').length;
                    calculateSum();
                    if (countRow < 2) {
                        renderEmptyRow(1);
                    }
                }
                return false;
            }
            let tr = $(this).closest('tr.editable-row');
            var id = parseInt(tr.data('id'));
            if (tr.hasClass('selected-row')) {
                return false;
            }
            if (clickedTr != id) {
                var dataWithId = getRowWithID(clickedTr);
                $('.invoice-table').find('[data-id="' + clickedTr + '"]').removeClass('selected-row');
                if (dataWithId >= 0) {
                    renderValuedRowEntry();
                } else {
                    var data = {};
                    data.id = clickedTr;
                    data.coa_category = '';
                    renderValuedRow(data);
                }
                clickedTr = id;
                calculateSum();
                renderValuedRowEntry();
            }

            tr.toggleClass('selected-row');
            var data = {};
            data.id = id;
            clickedTr = data.id;
            data.coa_id = 0;
            data.coa_type = '';
            data.coa_title = '';
            data.coa_category = '';
            data.debit = 0;
            data.credit = 0;
            data.amount = 0;
            data.amount_display = currencyFormat(0, '<?php echo $currency_symbol?>', true);
            data.is_new = 1;

            if (journal_entry.length > 0) {
                var rowWithId = getRowWithID(id);
                if (rowWithId >= 0) {
                    data = journal_entry[rowWithId];
                    if (data.tax_rate > 0) {
                        data.readonly = 1;
                    }
                }
            }
            renderFormRow(data);
            var next = tr.next('.editable-row');
            if (next.length == 0) {
                renderEmptyRow(1);
            }
            hide = false;
        });

        function getRowWithID(id) {
            var index = journal_entry.map(function (item) {
                return parseInt(item.id);
            }).indexOf(parseInt(id));
            return index;
        }

        function renderEmptyRow(count) {
            for (var i = 0; i < count; i++) {
                lastID++;
                var template = empty_row_template({
                    data: lastID
                });
                $('.invoice-table tbody').append(template);
                //emptyRowClicked();
            }
        }

        function renderFormRow(row) {
            var template = form_row_template({
                data: row
            });
            $('.invoice-table tbody').find('[data-id="' + row.id + '"]').html(template);

            $coa_account = $('#coa_account' + row.id);
            var $row = $coa_account.closest('.editable-row');
            $coa_account.val(row.coa_type + ":" + row.coa_id);
            if (row.coa_id > 0) {
                if (row.debit > 0 && row.amount > 0) {
                    $row.find('input[name="credit[]"]').attr('readonly', 'readonly');
                } else if (row.credit > 0 && row.amount > 0) {
                    $row.find('input[name="debit[]"]').attr('readonly', 'readonly');
                }
            }
            $coa_account.select2({
                placeholder: "Select an option",
                allowClear: false,
                //theme: "classic",
            }).on('select2:select', function (e) {
                /*var data = e.params;*/
                var category = $coa_account.children("option:selected").data('category');
                $(this).closest('tr.editable-row').find('.subcategory').text(category);
                $row.find('input[name="debit[]"]').val('').removeAttr('readonly');
                $row.find('input[name="credit[]"]').val('').removeAttr('readonly');
                updateEntry(row.id);
            });

            updateChanges();
        }

        function updateEntry(id) {
            var element = $('#coa_account' + id);
            var $row = element.closest('.editable-row');
            var $val = element.val();
            if ($val) {
                $split = $val.split(':');
                var debit = $row.find('input[name="debit[]"]').val() !== '' ? parseFloat($row.find('input[name="debit[]"]').val()) : 0;
                var credit = $row.find('input[name="credit[]"]').val() !== '' ? parseFloat($row.find('input[name="credit[]"]').val()) : 0;

                var formData = {};
                formData.coa_id = parseInt($split[1]);
                formData.coa_type = $split[0];
                formData.coa_title = $.trim(element.children("option:selected").text());
                formData.coa_category = $.trim(element.children("option:selected").data('category'));
                formData.debit = 0;
                formData.credit = 0;
                formData.amount = 0;
                formData.amount_display = currencyFormat(0, '<?php echo $currency_symbol?>', true);
                if (formData.coa_id > 0) {
                    formData.debit = 1;
                    formData.credit = 1;
                    if (debit > 0) {
                        formData.credit = 0;
                        formData.amount = debit;
                        formData.amount_display = currencyFormat(debit, '<?php echo $currency_symbol?>', true);
                    } else if (credit > 0) {
                        formData.debit = 0;
                        formData.amount = credit;
                        formData.amount_display = currencyFormat(credit, '<?php echo $currency_symbol?>', true);
                    }
                }
                formData.amount_type = formData.debit >= 0 ? 'debit' : 'credit';
                formData.id = $row.find('input[name="id[]"]').val();
                formData.is_new = $row.find('input[name="is_new[]"]').val();
                var dataWithId = getRowWithID(id);
                if (dataWithId >= 0) {
                    journal_entry[dataWithId] = formData;
                } else {
                    journal_entry.push(formData);
                }
                calculateSum();
            }
        }

        function calculateSum() {
            var debitSum = 0;
            var creditSum = 0;
            $.each(journal_entry, function (index, item) {
                var debit = item.debit ? parseFloat(item.amount) : 0;
                var credit = item.credit ? parseFloat(item.amount) : 0;
                debitSum = debitSum + debit;
                creditSum = creditSum + credit;
            });
            $('.footer-debit-sum').text(currencyFormat(debitSum, '<?php echo $currency_symbol?>', true));
            $('.footer-credit-sum').text(currencyFormat(creditSum, '<?php echo $currency_symbol?>', true));
        }

        function updateChanges() {
            $('input[name="debit[]"]').on('change', function () {
                var id = $(this).closest('.editable-row').data('id');
                var element = $('#coa_account' + id);
                var $row = element.closest('.editable-row');
                if (element.val() !== '' && $(this).val() > 0) {
                    $row.find('input[name="credit[]"]').attr('readonly', 'readonly');
                }
                updateEntry(id);
            });
            $('input[name="debit[]"]').on('dblclick', function () {
                var id = $(this).closest('.editable-row').data('id');
                var element = $('#coa_account' + id);
                var $row = element.closest('.editable-row');

                let sumTillNow = 0;
                for (var i = 0; i < journal_entry.length; i++) {
                    var item = journal_entry[i];
                    var debit = item.debit ? parseFloat(item.amount) : 0;
                    var credit = item.credit ? parseFloat(item.amount) : 0;
                    if (item.id != parseInt(id)) {
                        sumTillNow += (debit - credit);
                    }
                }
                if (sumTillNow > 0) {
                    $row.find('input[name="credit[]"]').val(sumTillNow);
                    $row.find('input[name="debit[]"]').attr('readonly', 'readonly');
                } else {
                    $row.find('input[name="debit[]"]').val(-1 * sumTillNow);
                    $row.find('input[name="credit[]"]').attr('readonly', 'readonly');
                }
                updateEntry(id);
            });
            $('input[name="credit[]"]').on('change', function () {
                var id = $(this).closest('.editable-row').data('id');
                var element = $('#coa_account' + id);
                var $row = element.closest('.editable-row');
                if (element.val() !== '' && $(this).val() > 0) {
                    $row.find('input[name="debit[]"]').attr('readonly', 'readonly');
                }
                updateEntry(id);
            });
            $('input[name="credit[]"]').on('dblclick', function () {
                var id = $(this).closest('.editable-row').data('id');
                var element = $('#coa_account' + id);
                var $row = element.closest('.editable-row');

                let sumTillNow = 0;
                for (var i = 0; i < journal_entry.length; i++) {
                    var item = journal_entry[i];
                    var debit = item.debit ? parseFloat(item.amount) : 0;
                    var credit = item.credit ? parseFloat(item.amount) : 0;
                    if (item.id != parseInt(id)) {
                        sumTillNow += (debit - credit);
                    }
                }
                if (sumTillNow > 0) {
                    $row.find('input[name="credit[]"]').val(sumTillNow);
                    $row.find('input[name="debit[]"]').attr('readonly', 'readonly');
                } else {
                    $row.find('input[name="debit[]"]').val(-1 * sumTillNow);
                    $row.find('input[name="credit[]"]').attr('readonly', 'readonly');
                }
                updateEntry(id);
            });
        }

        function renderValuedRow(row) {
            var template = value_row_template({
                data: row
            });
            $('.invoice-table tbody').find('[data-id="' + row.id + '"]').html(template);
        }

        function renderValuedRowEntry() {
            $.each(journal_entry, function (index, row) {
                var template = value_row_template({
                    data: row
                });
                $('.invoice-table tbody').find('[data-id="' + row.id + '"]').html(template);
            });
        }

        $('form').on('submit', function (e) {

            let invoice_date = $('input[name=entry_date]').val();
            if (invoice_date == '') {
                if ($('.journal_date_form').find('.text-danger').length == 0) {
                    $('<span class="text-danger pull-right">Entry Date field is required</span>').insertAfter('.journal_date_form label');
                }
                return false;
                e.preventDefault();
            }
            $('.hidden-field').html('');
            var debitSum = 0;
            var creditSum = 0;
            $.each(journal_entry, function (index, item) {
                var debit = item.debit > 0 ? parseFloat(item.amount) : 0;
                var credit = item.credit > 0 ? parseFloat(item.amount) : 0;
                debitSum = debitSum + debit;
                creditSum = creditSum + credit;
                var amount = debit > 0 ? debit : credit;
                var amount_type = debit > 0 ? 'debit' : 'credit';
                var html = '<input type="hidden" name="coa_id[]" value="' + item.coa_id + '">';
                html += '<input type="hidden" name="coa_type[]" value="' + item.coa_type + '">';
                html += '<input type="hidden" name="amount[]" value="' + amount + '">';
                html += '<input type="hidden" name="amount_type[]" value="' + amount_type + '">';
                html += '<input type="hidden" name="is_new[]" value="' + item.is_new + '">';
                html += '<input type="hidden" name="entry_id[]" value="' + item.id + '">';
                $('.hidden-field').append(html);
            });
            if (debitSum !== creditSum || debitSum === 0 || creditSum === 0) {
                alert('<?php echo $this->lang->line('error_in_debit_amount_and_credit_amount'); ?>');
                e.preventDefault();
            }
            //e.preventDefault();
        });
    });

    function currencyFormat(num, symbol, space) {
        space = space ? " " : "";
        return symbol + space + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
</script>

<script id="form_row_template" type="text/x-handlebars-template">
    <td width="35%">
        <select id="coa_account{{data.id}}" name="coa_id[]" class="form-control coa_id">
            <option></option>
            <?php foreach ($coa_accounts as $account) { ?>
                <option value="<?php echo $account->type . ':' . $account->id; ?>"
                        data-category="<?php echo $account->category; ?>" data-rate="<?php echo $account->rate; ?>">
                    <?php echo $account->name; ?> (<?php echo $account->code; ?>)
                </option>
            <?php } ?>
        </select>
    </td>
    <td width="20%"><span class="subcategory">{{data.coa_category}}</span></td>
    <td width="20%">
        {{#if data.debit}}
        <input type="text" id="debit{{data.id}}" class="form-control rate" name="debit[]" value="{{data.amount}}"/>
        {{else}}
        <input type="text" id="debit{{data.id}}" class="form-control rate" name="debit[]" value="" readonly/>
        {{/if}}
    </td>
    <td width="20%">
        {{#if data.credit}}
        <input type="text" id="credit{{data.id}}" class="form-control rate" name="credit[]" value="{{data.amount}}"/>
        {{else}}
        <input type="text" id="credit{{data.id}}" class="form-control rate" name="credit[]" value="" readonly/>
        {{/if}}
    </td>
    <td width="5%">
        <input type="hidden" class="form-control" name="id[]" value="{{data.id}}">
        <input type="hidden" id="is_new{{data.id}}" class="form-control" name="is_new[]" value="{{data.is_new}}">
        <a href="#" class="btn btn-default btn-xs remove-entry" data-toggle="tooltip" title=""
           data-original-title="Delete">
            <i class="fa fa-remove"></i>
        </a>
    </td>
</script>

<script id="value_row_template" type="text/x-handlebars-template">
    <td width="35%">{{data.coa_title}}</td>
    <td width="20%"><span class="subcategory">{{data.coa_category}}</span></td>
    <td width="20%">{{#if data.debit}}{{data.amount_display}}{{/if}}</td>
    <td width="20%">{{#if data.credit}}{{data.amount_display}}{{/if}}</td>
    <td width="5%">
        <a href="#" class="btn btn-default btn-xs remove-entry" data-toggle="tooltip" title=""
           data-original-title="Delete">
            <i class="fa fa-remove"></i>
        </a>
    </td>
</script>

<script id="empty_row_template" type="text/x-handlebars-template">
    <tr data-id="{{data}}" class="editable-row">
        <td width="35%"></td>
        <td width="20%"><span class="subcategory"></span></td>
        <td width="20%"></td>
        <td width="20%"></td>
        <td width="5%">
            <a href="#" class="btn btn-default btn-xs remove-entry" data-toggle="tooltip" title=""
               data-original-title="Delete">
                <i class="fa fa-remove"></i>
            </a>
        </td>
    </tr>
</script>