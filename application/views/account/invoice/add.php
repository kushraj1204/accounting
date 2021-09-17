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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add_invoice'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo show_message();
                        } ?>
                        <?php echo form_open('account/invoice/save_invoice'); ?>
                        <div class="row">
                            <input type="hidden" name="id" value="<?php echo set_value('id', $invoice->id); ?>"/>

                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('invoice_number'); ?></label>
                                <?php echo form_error('code'); ?>
                                <input type="text" id="code" name="code" class="form-control" readonly required
                                       value="<?php echo $invoice_id; ?>">
                            </div>
                            <div class="col-md-4 form-group invoice_date_form">
                                <label><?php echo $this->lang->line('invoice_date'); ?></label>
                                <?php $invoice_date = $invoice->id > 0 ? $this->customlib->formatDate($invoice->invoice_date) : ''; ?>
                                <?php if ($this->datechooser === 'bs') { ?>
                                    <?php echo form_error('invoice_date_bs'); ?>
                                    <input type="text" id="invoice_date_bs" name="invoice_date_bs" class="form-control"
                                           readonly required
                                           value="<?php echo set_value('invoice_date_bs', $invoice->invoice_date_bs); ?>">
                                    <input type="hidden" id="invoice_date" name="invoice_date" class="form-control"
                                           readonly
                                           value="<?php echo set_value('invoice_date', $invoice_date); ?>">
                                <?php } else {
                                    echo form_error('invoice_date'); ?>
                                    <input type="text" id="invoice_date" name="invoice_date" class="form-control"
                                           readonly required
                                           value="<?php echo set_value('invoice_date', $invoice_date); ?>">
                                    <input type="hidden" id="invoice_date_bs" name="invoice_date_bs"
                                           class="form-control" readonly
                                           value="<?php echo set_value('invoice_date_bs', $invoice->invoice_date_bs); ?>">
                                <?php } ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('due_date'); ?></label>
                                <small class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</small>
                                <?php if (strtotime($invoice->invoice_date) > strtotime($invoice->due_date) || $invoice->id == 0) {
                                    $due_date = '';
                                    $due_date_bs = '';
                                } else {
                                    $due_date = $this->customlib->formatDate($invoice->due_date);
                                    $due_date_bs = $invoice->due_date_bs;
                                } ?>
                                <?php if ($this->datechooser === 'bs') { ?>
                                    <?php echo form_error('due_date_bs'); ?>
                                    <input type="text" id="due_date_bs" name="due_date_bs" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date_bs', $due_date_bs); ?>">
                                    <input type="hidden" id="due_date" name="due_date" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date', $due_date); ?>">
                                <?php } else { ?>
                                    <?php echo form_error('due_date'); ?>
                                    <input type="text" id="due_date" name="due_date" class="form-control" readonly
                                           required
                                           value="<?php echo set_value('due_date', $due_date); ?>">
                                    <input type="hidden" id="due_date_bs" name="due_date_bs" class="form-control"
                                           readonly required
                                           value="<?php echo set_value('due_date_bs', $due_date_bs); ?>">
                                <?php } ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('reference_no'); ?></label>
                                <small
                                        class="text-muted">(<?php echo $this->lang->line('optional'); ?>)
                                </small>
                                <?php echo form_error('reference_no'); ?>
                                <input type="text" id="reference_no" name="reference_no" class="form-control"
                                       value="<?php echo set_value('reference_no', $invoice->reference_no); ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label><?php echo $this->lang->line('registered_no'); ?></label>
                                <small
                                        class="text-muted">(<?php echo $this->lang->line('optional'); ?>)
                                </small>
                                <input type="text" id="reference_no" name="registered_no" class="form-control"
                                       value="<?php echo set_value('registered_no', $invoice->registered_no); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-7">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label><?php echo $this->lang->line('customer_name'); ?></label>
                                        <?php echo form_error('customer_id'); ?>
                                        <select name="customer_id" id="customer_id" class="form-control" required>
                                            <option></option>
                                            <?php foreach ($customers as $customer) { ?>
                                                <option value="<?php echo $customer->id; ?>" <?php echo $invoice->customer_id == $customer->id ? 'selected' : ''; ?>
                                                        data-type="<?php echo $customer->category; ?>">
                                                    <?php echo $customer->name; ?> (<?php echo $customer->code; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if ($this->rbac->hasPrivilege('account_personnel', 'can_add')) { ?>
                                            <button type="button"
                                                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"
                                                    class="btn btn-primary btn-sm openBtn"><?php echo $this->lang->line('add'); ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="invoice_entry_table"
                                       class="table invoice-table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="20%"><?php echo $this->lang->line('account_name'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('category'); ?></th>
                                        <th width="10%"><?php echo $this->lang->line('quantity'); ?></th>
                                        <th width="10%"><?php echo $this->lang->line('rate'); ?></th>
                                        <th width="20%"><?php echo $this->lang->line('amount'); ?></th>
                                        <!--<th width="10%"><?php /*echo $this->lang->line('tax'); */ ?></th>-->
                                        <th width="5%"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($invoice->id > 0 && count($invoice_entries) > 0) { ?>
                                        <?php foreach ($invoice_entries as $invoice_entry) {
                                            $multiplier = $invoice_entry->balance_type == 'credit' ? 1 : -1;
                                            $sum = $sum + ($multiplier * $invoice_entry->rate * $invoice_entry->quantity); ?>
                                            <tr data-id="<?php echo $invoice_entry->id; ?>" class="editable-row">
                                                <td width="35%"><?php echo $invoice_entry->coa_title; ?>
                                                    (<?php echo $invoice_entry->coa_code; ?>)
                                                </td>
                                                <td width="20%"><?php echo $invoice_entry->coa_category; ?></td>
                                                <td width="10%"><?php echo $invoice_entry->quantity; ?></td>
                                                <td width="10%"><?php echo $this->accountlib->currencyFormat($invoice_entry->rate); ?></td>
                                                <td width="20%"><?php echo $this->accountlib->currencyFormat($invoice_entry->rate * $invoice_entry->quantity); ?></td>
                                                <!--<td width="10%"><?php /*echo $invoice_entry->tax == 1 ? 'Yes' : 'No'; */ ?></td>-->
                                                <td width="5%">
                                                    <a href="#" class="btn btn-default btn-xs remove-entry"
                                                       data-toggle="tooltip" title="" data-original-title="Delete">
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
                                        <td width="10%"></td>
                                        <td width="10%"></td>
                                        <td width="20%"><span
                                                    class="footer-sum"><?php echo $sum > 0 ? $this->accountlib->currencyFormat($sum) : ''; ?></span>
                                        </td>
                                        <!--<td width="10%"></td>-->
                                        <td width="5%"></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                <a href="#add_row" role="button"
                                   class="btn btn-secondary btn-sm add_row"
                                   data-toggle="tooltip" title="<?php echo $this->lang->line('add'); ?>"
                                   data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                            class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('description'); ?></label>
                                <?php echo form_error('description'); ?>
                                <textarea name="description" class="form-control" rows="5"
                                          required="required"><?php echo set_value('description', $invoice->description); ?></textarea>
                            </div>
                            <div class="col-md-3 form-group">
                                <label><?php echo $this->lang->line('amount_due'); ?></label>
                                <?php echo form_error('amount_due'); ?>
                                <input type="text" id="amount_due" name="amount_due" class="form-control" readonly
                                       value="<?php echo $this->accountlib->currencyFormat($sum); ?>">
                            </div>
                        </div>
                        <div class="hidden-field" style="display: none"></div>
                        <input type="hidden" name="personnel_type"
                               value="<?php echo set_value('personnel_type', $invoice->personnel_type); ?>">
                        <?php if ($invoice->fee_id == 0) { ?>
                            <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary pull-right btn-sm submit-form"> <?php echo $this->lang->line('save'); ?></button>
                        <?php } ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    (function () {
        'use strict';
        window.addEventListener('load', function () {
// Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByTagName('form');
// Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    let lastID = 0; //maximum id from database
    let hide = true;
    let invoice_entry = [];//contains all the entries from database
    let clickedTr = 0;
    $('document').ready(function () {
        <?php if($invoice->id > 0 && count($invoice_entries) > 0){?>
        <?php foreach($invoice_entries as $invoice_entry){?>
        var entry = {};
        entry.id = parseInt('<?php echo $invoice_entry->id;?>');
        entry.coa_title = $.trim('<?php echo $invoice_entry->coa_title;?>') + ' (' + $.trim('<?php echo $invoice_entry->coa_code;?>') + ')';
        entry.coa_category = $.trim('<?php echo $invoice_entry->coa_category;?>');
        entry.coa_type = '<?php echo ($invoice_entry->coa_id != 0) ? "coa" : "personnel";?>';
        entry.coa_id = parseInt('<?php echo ($invoice_entry->coa_id != 0) ? $invoice_entry->coa_id : $invoice_entry->personnel_id;?>');
        entry.quantity = parseFloat('<?php echo $invoice_entry->quantity;?>').toFixed(2);
        entry.rate = parseFloat('<?php echo $invoice_entry->rate;?>');
        entry.rate_display = '<?php echo $this->accountlib->currencyFormat($invoice_entry->rate);?>';
        entry.amount = (entry.quantity * entry.rate);
        entry.amount_display = '<?php echo $this->accountlib->currencyFormat($invoice_entry->rate * $invoice_entry->quantity);?>';
        entry.tax = parseInt(<?php echo $invoice_entry->tax;?>);
        entry.tax_rate = parseInt(<?php echo $invoice_entry->tax_rate;?>);
        entry.tax_amount = parseInt(<?php echo $invoice_entry->tax_amount;?>);
        entry.tax_text = entry.tax === 1 ? 'Yes' : 'No';
        entry.is_new = 0;
        entry.parent_type = parseInt('<?php echo $invoice_entry->coa_type;?>');
        invoice_entry.push(entry);
        <?php } ?>
        <?php } ?>
        $('#customer_id').select2({
            placeholder: "<?php echo $this->lang->line('select_customer'); ?>",
            allowClear: false,
            //theme: "classic",
        }).on('select2:select', function (e) {
            /*var data = e.params;*/
            var category = $(this).children("option:selected").data('type');
            console.log(category.toLowerCase());
            $('input[name="personnel_type"]').val(category.toLowerCase());
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        var due_date_duration = parseInt('<?php echo $this->accountSetting->due_date_duration;?>');
        <?php if($this->datechooser === 'bs'){?>

        $("#invoice_date_bs").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
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
            if ($("#invoice_date_bs").val() == '') {
                var entry_date = moment(adDate).subtract('days', due_date_duration);
                var m = moment(entry_date, date_format.toUpperCase());
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                entry_date = entry_date.format("YYYY-MM-DD");

                $('#invoice_date').val(entry_date);
                $('#invoice_date_bs').val(bsdate);

            }
        });
        <?php }else{?>
        $('#invoice_date').datepicker({
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
                if ($('#invoice_date').val() == '') {
                    var entry_date = moment(val).subtract('days', due_date_duration);
                    var m = moment(entry_date, date_format.toUpperCase());
                    var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                    var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                    $('#invoice_date').val(m.format(date_format.toUpperCase()));
                    $('#invoice_date_bs').val(bsdate);
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

        //invoice entry
        var empty_row_template = Handlebars.compile($('#empty_row_template').html());
        var form_row_template = Handlebars.compile($('#form_row_template').html());
        var value_row_template = Handlebars.compile($('#value_row_template').html());
        var lastID = $.isNumeric($('.editable-row:last-child').data('id')) ? $('.editable-row:last-child').data('id') : 0;

        if (invoice_entry.length === 0) {
            renderEmptyRow(2);
        }

        $('body').on("click", function () {
            if (hide) {
                if ($('tr.selected-row').length > 0) {
                    var id = $('tr.selected-row').data('id');
                    var rowWithId = getRowWithID(id);
                    var row = {};
                    if (rowWithId >= 0) {
                        row = invoice_entry[rowWithId];
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
                    invoice_entry = jQuery.grep(invoice_entry, function (item) {
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
            data.quantity = 1;
            data.quantity = data.quantity.toFixed(2);
            data.rate = 0;
            data.rate_display = currencyFormat(0, '<?php echo $currency_symbol?>', true);
            data.amount = 0;
            data.amount_display = currencyFormat(0, '<?php echo $currency_symbol?>', true);
            data.checked = '';
            data.tax = 0;
            data.tax_rate = 0;
            data.parent_type = 0;
            data.is_new = 1;
            if (invoice_entry.length > 0) {
                var rowWithId = getRowWithID(id);
                if (rowWithId >= 0) {
                    data = invoice_entry[rowWithId];
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
            var index = invoice_entry.map(function (item) {
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
            $row.find('select[name="tax[]"]').val(parseInt(row.tax));
            if (parseInt(row.tax_rate) > 0) {
                $row.find('input[name="quantity[]"]').attr('readonly', 'readonly');
                $row.find('input[name="rate[]"]').attr('readonly', 'readonly');
                $row.find('select[name="tax[]"]').attr('readonly', 'readonly');
            }
            $coa_account.select2({
                placeholder: "Select an option",
                allowClear: false,
                //theme: "classic",
            }).on('select2:select', function (e) {
                /*var data = e.params;*/
                var category = $coa_account.children("option:selected").data('category');
                $(this).closest('tr.editable-row').find('.subcategory').text(category);
                var tax_rate = $coa_account.children("option:selected").data('rate');
                if (parseFloat(tax_rate) > 0) {
                    $row.find('input[name="quantity[]"]').attr('readonly', 'readonly');
                    $row.find('input[name="rate[]"]').attr('readonly', 'readonly');
                    $row.find('select[name="tax[]"]').attr('readonly', 'readonly');
                } else {
                    $row.find('input[name="quantity[]"]').val(1).removeAttr('readonly');
                    $row.find('input[name="rate[]"]').val(0).removeAttr('readonly');
                    $row.find('select[name="tax[]"]').val(0).removeAttr('readonly');
                }
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
                var formData = {};
                formData.coa_id = parseInt($split[1]);
                formData.coa_type = $split[0];
                formData.coa_title = $.trim(element.children("option:selected").text());
                formData.coa_category = $.trim(element.children("option:selected").data('category'));
                formData.tax = parseInt($row.find('select[name="tax[]"]').val());
                formData.tax_text = $row.find('select[name="tax[]"]').children("option:selected").text();
                formData.quantity = parseFloat($row.find('input[name="quantity[]"]').val()).toFixed(2);
                formData.rate = parseFloat($row.find('input[name="rate[]"]').val());
                formData.rate_display = currencyFormat(formData.rate, '<?php echo $currency_symbol?>', true);
                formData.amount = parseFloat(formData.quantity) * parseFloat(formData.rate);
                formData.amount_display = currencyFormat(formData.amount, '<?php echo $currency_symbol?>', true);
                formData.tax_rate = parseFloat($.trim(element.children("option:selected").data('rate')));
                formData.id = $row.find('input[name="id[]"]').val();
                formData.is_new = $row.find('input[name="is_new[]"]').val();
                formData.parent_type = parseInt(element.children("option:selected").data('parent_type'));
                var dataWithId = getRowWithID(id);
                if (dataWithId >= 0) {
                    invoice_entry[dataWithId] = formData;
                } else {
                    invoice_entry.push(formData);
                }
                calculateSum();
            }
        }

        function calculateSum() {
            var sum = 0;
            var sumTillNow = 0;
            $.each(invoice_entry, function (index, item) {
                var element = $('#coa_account' + item.id);
                var $row = element.closest('.editable-row');

                if (parseInt(item.tax) === 1 && parseFloat(item.tax_rate) === 0) {
                    sumTillNow = sumTillNow + parseFloat(item.amount);
                }
                if (parseFloat(item.tax_rate) > 0) {
                    var amt = parseFloat(item.tax_rate) * sumTillNow / 100;
                    invoice_entry[index].quantity = 1;
                    invoice_entry[index].quantity = invoice_entry[index].quantity.toFixed(2);
                    invoice_entry[index].rate = amt;
                    sumTillNow = 0;
                    $row.find('input[name="tax[]"]').val(0).prop('readonly', true);
                    $row.find('input[name="quantity[]"]').val(item.quantity);
                    $row.find('input[name="rate[]"]').val(item.rate);
                }
                invoice_entry[index].amount = parseFloat(item.quantity) * parseFloat(item.rate);
                invoice_entry[index].amount_display = currencyFormat(invoice_entry[index].amount, '<?php echo $currency_symbol?>', true);
                $row.find('.row-amount').text(currencyFormat(item.amount, '<?php echo $currency_symbol?>', true));
                var multiplier = getMultiplier(invoice_entry[index].parent_type);
                sum = sum + (multiplier * parseFloat(item.amount));
            });
            $('.footer-sum').text(currencyFormat(sum, '<?php echo $currency_symbol?>', true));
            $('#amount_due').val(currencyFormat(sum, '<?php echo $currency_symbol?>', true));
        }

        function getMultiplier($parent_type) {
            var multiplier;
            switch ($parent_type) {
                case 4:
                    multiplier = -1;
                    break;
                default:
                    multiplier = 1;
                    break;
            }
            return multiplier;
        }

        function updateChanges() {
            $('input[name="quantity[]"]').on('change', function () {
                var id = $(this).closest('.editable-row').data('id');
                updateEntry(id);
            });
            $('input[name="rate[]"]').on('change', function () {
                var id = $(this).closest('.editable-row').data('id');
                updateEntry(id);
            });
            $('select[name="tax[]"]').on('change', function () {
                var id = $(this).closest('.editable-row').data('id');
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
            $.each(invoice_entry, function (index, row) {
                var template = value_row_template({
                    data: row
                });
                $('.invoice-table tbody').find('[data-id="' + row.id + '"]').html(template);
            });
        }

        $('form').on('submit', function (e) {
            $('.hidden-field').html('');
            let invoice_date = $('input[name=invoice_date]').val();
            if(invoice_date == ''){
                if($('.invoice_date_form').find('.text-danger').length == 0){
                    $('<span class="text-danger pull-right">Invoice Date field is required</span>').insertAfter('.invoice_date_form label');
                }
                return false;
                e.preventDefault();
            }
            $.each(invoice_entry, function (index, item) {
                var html = '<input type="hidden" name="coa_id[]" value="' + item.coa_id + '">';
                html += '<input type="hidden" name="coa_type[]" value="' + item.coa_type + '">';
                html += '<input type="hidden" name="quantity[]" value="' + item.quantity + '">';
                html += '<input type="hidden" name="rate[]" value="' + item.rate + '">';
                //html += '<input type="hidden" name="tax[]" value="' + item.tax + '">';
                //html += '<input type="hidden" name="tax_rate[]" value="' + item.tax_rate + '">';
                html += '<input type="hidden" name="is_new[]" value="' + item.is_new + '">';
                html += '<input type="hidden" name="entry_id[]" value="' + item.id + '">';
                $('.hidden-field').append(html);
            });
            //e.preventDefault();
        });

        $('.openBtn').on('click', function () {
            var $this = $(this);
            $this.button('loading');
            $('.modal-body').load('<?php echo base_url();?>account/personnel/add_personnel_form', function () {
                $('#myModal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
                //$(document).find('#type').val('customer');
            });
            $this.button('reset');
        });

        $(document).on('click', '.form-submit', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.button('loading');
            $.ajax({
                url: '<?php echo site_url("account/personnel/save_personnel_form") ?>',
                type: 'post',
                data: $('#personnel_form').serialize() + '&type=customer',
                dataType: 'json',
                success: function (data) {
                    if (!data.success) {
                        var message = "";
                        $.each(data.message, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        var option = '<option value="' + data.personnel.id + '">' + data.personnel.name + ' (' + data.personnel.code + ')</option>';
                        $(document).find('#customer_id').append(option);
                        $("#customer_id option[value=" + data.personnel.id + "]").attr('selected', 'selected');
                        $('#customer_id').select2({
                            placeholder: "<?php echo $this->lang->line('select_customer'); ?>",
                            allowClear: false,
                            //theme: "classic",
                        });
                        successMsg(data.message);
                        $(document).find('.modal-close').trigger('click');
                    }

                    $this.button('reset');
                }
            });
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
                        data-category="<?php echo $account->category; ?>"
                        data-parent_type="<?php echo $account->parent_type; ?>">
                    <?php echo $account->name; ?> (<?php echo $account->code; ?>)
                </option>
            <?php } ?>
        </select>
    </td>
    <td width="20%"><span class="subcategory">{{data.coa_category}}</span></td>
    <td width="10%">
        {{#if data.readonly}}
        <input type="text" id="quantity{{data.id}}" class="form-control quantity" name="quantity[]"
               value="{{data.quantity}}" readonly="readonly"/>
        {{else}}
        <input type="text" id="quantity{{data.id}}" class="form-control quantity" name="quantity[]"
               value="{{data.quantity}}"/>
        {{/if}}
    </td>
    <td width="10%">
        {{#if data.readonly}}
        <input type="text" id="rate{{data.id}}" class="form-control rate" name="rate[]" value="{{data.rate}}"
               readonly="readonly"/>
        {{else}}
        <input type="text" id="rate{{data.id}}" class="form-control rate" name="rate[]" value="{{data.rate}}"/>
        {{/if}}
    </td>
    <td width="20%"><span class="row-amount">{{data.amount}}</span></td>
    <!--<td width="10%">
        {{#if data.readonly}}
        <select id="tax{{data.id}}" name="tax[]" class="form-control tax" readonly="readonly">
            <option value="1">Yes</option>
            <option value="0" selected>No</option>
        </select>
        {{else}}
        <select id="tax{{data.id}}" name="tax[]" class="form-control tax">
            <option value="1">Yes</option>
            <option value="0" selected>No</option>
        </select>
        {{/if}}
    </td>-->
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
    <td width="10%">{{data.quantity}}</td>
    <td width="10%">{{data.rate_display}}</td>
    <td width="20%"><span class="row-amount">{{data.amount_display}}</span></td>
    <!--<td width="10%">{{data.tax_text}}</td>-->
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
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="20%"><span class="row-amount"></span></td>
        <!--<td width="10%"></td>-->
        <td width="5%">
            <a href="#" class="btn btn-default btn-xs remove-entry" data-toggle="tooltip" title=""
               data-original-title="Delete">
                <i class="fa fa-remove"></i>
            </a>
        </td>
    </tr>
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Customer</h4>
            </div>
            <div class="modal-body">
                <!--content here-->
            </div>
        </div>
    </div>
</div>