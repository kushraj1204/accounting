<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $payment->id ? $this->lang->line('edit_payment') : $this->lang->line('add_payment'); ?>
        </h1>
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <?php if ($this->session->flashdata('msg')) {
                        echo show_message();
                    } ?>
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
                            <small class="req">*</small>
                            <input required type="text" readonly id="payment_no" name="payment_no" class="form-control"
                                   value="<?php echo set_value('payment_no', $payment_no ? $payment_no : $payment->payment_no); ?>">

                            <span class="text-danger"><?php echo form_error('payment_no'); ?></span>
                        </div>
                        <div class="col-md-4 form-group">


                            <label><?php echo $this->lang->line('payment_date'); ?></label>
                            <small class="req">*</small>
                            <?php $payment_date = isset($payment->payment_date) ? $this->customlib->formatDate($payment->payment_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>
                                <?php echo form_error('payment_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="payment_date_bs" name="payment_date_bs" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('payment_date_bs', $payment->payment_date_bs); ?>">
                                <input type="hidden" id="payment_date" name="payment_date" class="form-control"
                                       readonly
                                       value="<?php echo set_value('payment_date', $payment_date); ?>">
                            <?php } else {
                                echo form_error('payment_date', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="payment_date" name="payment_date" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('payment_date', $payment_date); ?>">
                                <input type="hidden" id="payment_date_bs" name="payment_date_bs"
                                       class="form-control" readonly
                                       value="<?php echo set_value('payment_date_bs', $payment->payment_date_bs); ?>">
                            <?php } ?>


                        </div>

                        <div class="col-md-4 form-group">
                            <label><?php echo $this->lang->line('ref_no'); ?></label>
                            <small class="req">*</small>
                            <input required type="text" id="ref_no" name="ref_no" class="form-control"
                                   value="<?php echo set_value('ref_no', $payment->ref_no); ?>">
                            <span class="text-danger"><?php echo form_error('ref_no'); ?></span>
                        </div>

                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('pay_to'); ?></label>
                            <small class="req"> *</small>
                            <select required name="pay_to" id="pay_to" class="form-control">
                                <?php if ($payment) { ?>
                                    <option value="<?php echo $payment->paid_to ?>"
                                    >
                                        <?php echo $payment->name . '(' . $payment->code . ')'; ?>
                                    </option>
                                <?php } else { ?>
                                    <option></option>
                                    <?php foreach ($suppliers as $supplier) { ?>
                                        <option value="<?php echo $supplier->id; ?>"
                                        >
                                            <?php echo $supplier->name . '(' . $supplier->code . ')'; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('pay_to'); ?></span>
                        </div>


                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('payment_mode'); ?></label>
                            <small class="req"> *</small>

                            <select required name="payment_mode" id="payment_mode" class="form-control">

                                <option value="cheque" <?php if ($payment->payment_mode == 'cheque') echo "selected"; ?>>
                                    Cheque
                                </option>
                                <option value="cash" <?php if ($payment->payment_mode == 'cash') echo "selected"; ?>>
                                    Cash
                                </option>

                            </select>
                            <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                        </div>

                        <div class="col-md-2 form-group chequedate">


                            <label><?php echo $this->lang->line('cheque_date'); ?></label>
                            <?php $cheque_date = isset(json_decode($payment->payment_mode_details)->cheque_date) ? $this->customlib->formatDate(json_decode($payment->payment_mode_details)->cheque_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>
                                <?php echo form_error('cheque_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="cheque_date_bs" name="cheque_date_bs" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('cheque_date_bs', json_decode($payment->payment_mode_details)->cheque_date_bs); ?>">
                                <input type="hidden" id="cheque_date" name="cheque_date" class="form-control"
                                       readonly
                                       value="<?php echo set_value('cheque_date', $cheque_date); ?>">
                            <?php } else {
                                echo form_error('cheque_date', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="cheque_date" name="cheque_date" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('cheque_date', $cheque_date); ?>">
                                <input type="hidden" id="cheque_date_bs" name="cheque_date_bs"
                                       class="form-control" readonly
                                       value="<?php echo set_value('cheque_date_bs', json_decode($payment->payment_mode_details)->cheque_date_bs); ?>">
                            <?php } ?>


                        </div>

                        <div class="col-md-2 form-group chequeno">
                            <label><?php echo $this->lang->line('cheque_no'); ?></label>
                            <small class="req"> *</small>
                            <input required type="text" id="cheque_no" name="cheque_no" class="form-control"
                                   value="<?php echo set_value('cheque_no', json_decode($payment->payment_mode_details)->cheque_no); ?>">
                            <span class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                        </div>

                        <div class="col-md-2 form-group bank">
                            <label><?php echo $this->lang->line('bank'); ?></label>
                            <small class="req"> *</small>
                            <select required name="bank" id="bank" class="form-control">
                                <?php if ($payment->asset_id) { ?>
                                    <option selected value="<?php echo $payment->asset_id; ?>"
                                    >
                                        <?php echo $payment->bankname; ?>
                                    </option>
                                <?php } ?>
                                <option></option>
                                <?php foreach ($banks as $bank) { ?>

                                    <option value="<?php echo $bank->id; ?>"
                                    >
                                        <?php echo $bank->name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('bank'); ?></span>
                        </div>

                        <div class="col-md-8 form-group form-check">
                            <input type="checkbox" class="form-check-input" id="advancePay" name="advancePay">
                            <label class="form-check-label" for="advancePay">Pay Advance</label>
                        </div>

                        <div class="col-md-8 form-group">
                            <?php if (!$payment) { ?>
                                <label><?php echo $this->lang->line('list_of_due_journals'); ?></label>
                            <?php } else { ?>
                                <label><?php echo $this->lang->line('selected_journal_for_payment'); ?></label>
                            <?php } ?>
                            <div id="duelist">
                                <table class="table table-bordered">
                                    <thead>
                                    <th class="table-primary"><?php echo $this->lang->line('journal_no'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('payable_amount'); ?>
                                        (<?php echo $this->currency; ?>)
                                    </th>
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
                                                <td class="dueamount"><?php echo $eachrelatedjournal->payableamount; ?></td>
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
                                    <th class="table-primary "><?php echo $this->lang->line('debit_amount'); ?>
                                        (<?php echo $this->currency; ?>)
                                    </th>
                                    <th class="table-primary "><?php echo $this->lang->line('credit_amount'); ?>
                                        (<?php echo $this->currency; ?>)
                                    </th>
                                    </thead>

                                    <tbody>
                                    <?php if ($relatedjournaldetails) { ?>
                                        <?php foreach ($relatedjournaldetails as $eachjournal) { ?>
                                            <tr class="selectedjournallist">
                                                <td>
                                                    <?php echo $eachjournal->journalcode; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->coa_title; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->coa_category; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->debit; ?></td>
                                                <td>
                                                    <?php echo $eachjournal->credit; ?></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label><?php echo $this->lang->line('narration'); ?></label>
                            <small class="req"> *</small>
                            <textarea name="narration" rows="5" required
                                      class="form-control"><?php echo set_value('narration', $payment->description); ?></textarea>
                            <span class="text-danger"><?php echo form_error('narration'); ?></span>
                        </div>

                        <div class="col-md-4 form-group">
                            <table class="table">
                                <tbody>

                                <tr>
                                    <th><?php echo $this->lang->line('net_total'); ?> (<?php echo $this->currency; ?>)
                                    </th>
                                    <td><input readonly type="text"
                                               value="<?php echo $payment ? $payment->nettotal : 0; ?>" id="nettotal"
                                               name="nettotal"
                                               class="form-control"></td>
                                </tr>

                                <tr>
                                    <th><?php echo $this->lang->line('paid_amount'); ?> (<?php echo $this->currency; ?>
                                        )
                                    </th>
                                    <td><input type="number" min="0" id="paid_amount" required
                                               name="paid_amount"
                                               value="<?php echo set_value('paid_amount', $payment->paid_amount); ?>"
                                               class="form-control">
                                        <span class="text-danger"><?php echo form_error('paid_amount'); ?></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="journal_ids"></div>

                        <div class="col-md-12 form-group">

                            <?php if ($allow) { ?>
                                <button type="submit" name="submit" value="submit"
                                        class="btn btn-primary pull-right btn-sm checkbox-toggle"> <?php echo $this->lang->line('save'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
</div>
</section>
</div>

<script id="duelist_handlebar" type="text/x-handlebars-template">
    <table class="table table-bordered">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('journal_no'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('payable_amount'); ?> (<?php echo $this->currency; ?>)
        </th>
        <th class="table-primary"><?php echo $this->lang->line('due_date'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('action'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('payment_history'); ?></th>
        </thead>
        <tbody>
        {{#each this}}
        <tr>
            <td>{{this.code}}</td>
            <td>{{this.narration}}</td>
            <td class="dueamount">{{this.payableamount}}</td>
            <td>{{this.due_date}}</td>
            <td class="mailbox-date no-print text ">
                <button value="{{this.id}}" type="button" data-value="{{this.amount}}"
                        class="btn-sm btn-success addtolist"><i class="fa fa-plus"></i>
                </button>
            </td>
            <td>{{{this.pastpayments}}}</td>
        </tr>
        {{/each}}

        </tbody>
    </table>
</script>

<script id="selectedlist_handlebar" type="text/x-handlebars-template">
    <table class="table table-bordered">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('journal_no'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
        <th class="table-primary "><?php echo $this->lang->line('debit_amount'); ?>(<?php echo $this->currency; ?>)</th>
        <th class="table-primary "><?php echo $this->lang->line('credit_amount'); ?> (<?php echo $this->currency; ?>)
        </th>
        </thead>
        <tbody>
        {{#each this}}
        <tr class="selectedjournallist">
            <td>{{this.code}}</td>
            <td>{{this.coa_title}}</td>
            <td>{{this.coa_category}}</td>
            <td>{{#if this.debit}}{{this.debit}}{{/if}}</td>
            <td>{{#if this.credit}}{{this.credit}}{{/if}}</td>
        </tr>
        {{/each}}
        </tbody>
    </table>


</script>


<script>
    var totalpayable = 0;
    var nettotal = 0;
    var pendingresult = new Array();
    var selectedids = new Array('0');
    var minamount = 0;
    <?php if(isset($minpayable)){ ?>
    minamount = parseFloat('<?php echo $minpayable; ?>');
    <?php } ?>
    $('document').ready(function () {
        var payAdvance = $('#advancePay').is(':checked');
        $('#advancePay').off('click').on("click", function () {
            payAdvance = $('#advancePay').is(':checked');
            console.log(payAdvance)
        });
        var mode = $('#payment_mode').val();
        if (mode == 'cash') {
            $('#cheque_no').prop('disabled', true);
            $('#bank').prop('disabled', true);
            $('#cheque_date').prop('disabled', true);
        }
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        <?php if($this->datechooser === 'bs'){?>
        var currentDate = new Date();
        var currentNepaliDate = calendarFunctions.getBsDateByAdDate(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
        var formattedNepaliDate = calendarFunctions.bsDateFormat("%y-%m-%d", currentNepaliDate.bsYear, currentNepaliDate.bsMonth, currentNepaliDate.bsDate);
        $("#payment_date_bs,#cheque_date_bs").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true,
            maxDate: formattedNepaliDate,
        }).on('dateSelect', function (e) {
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);
        });
        <?php }else{?>
        $('#payment_date,#cheque_date').datepicker({
            format: date_format,
            autoclose: true,
            endDate: 'NOW',
        }).on('input change', function (e) {
            var id = e.target.id + "_bs";
            var val = e.target.value;
            var m = moment(val, date_format.toUpperCase());
            try {
                var bsdateObj = calendarFunctions.getBsDateByAdDate(parseInt(m.format("YYYY")), parseInt(m.format("MM")), parseInt(m.format("DD")));
                var bsdate = calendarFunctions.bsDateFormat("%y-%m-%d", bsdateObj.bsYear, bsdateObj.bsMonth, bsdateObj.bsDate);
                $("#" + id).val(bsdate);
            } catch (e) {
                //
            }
        });
        <?php } ?>




        $('#payment_date, #cheque_date').datepicker({
            format: date_format,
            autoclose: true,
            endDate: 'NOW'
        });
        paymentModetoggle();
        $("#payment_form").submit(function (event) {
            var total = $('#total').val();
            var nettotal = $('#nettotal').val();
            var paidamt = $('#paid_amount').val();
            var tempmin = parseFloat(minamount);
            if (selectedids.length >= 2 && paidamt <= tempmin) {
                errorMsg("You must pay more than Rs." + tempmin + ' for this Journal selection');
                return false;
            }
            <?php if($minpayable){ ?>
            if (paidamt <= minamount) {
                errorMsg("You must pay more than Rs." + minamount);
                return false;
            }
            <?php }?>
            <?php if(!$payment->id){ ?>
            if (!(nettotal > 0)) {
                if (selectedids.length == 0 && !payAdvance) {
                    infoMsg("Please make selection for which payment is to be done from the list of due journals");
                    event.preventDefault();
                }
            }
            $(".journal_ids").empty();
            selectedids.forEach(function (entry) {
                $(".journal_ids").append("<input required type='hidden' name='journal_id[]' value='" + entry + "'>");
            });
            <?php } ?>
            if (paidamt <= 0) {
                errorMsg('Payment amount cannot be 0');
                return false;
            }

            if (parseFloat(paidamt) > parseFloat(nettotal) && !payAdvance) {
                errorMsg('Amount must not exceed the net total');
                return false;
            }
        });

        <?php if(!$payment){ ?>
        loadDueJournalList();
        <?php }?>

        $('#pay_to').select2({
            placeholder: "<?php echo $this->lang->line('pay_to'); ?>",
            allowClear: false,
        });
        $('#pay_to').on('change', function () {
            selectedids = new Array(0);
            $('#selectedlist').html('');
            loadDueJournalList();
        });
    });

    function loadDueJournalList() {
        var id = $('#pay_to').val();
        if (!id) {
            $('#duelist').html('');
            return false;
        }
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            id: id
        };
        $.ajax({
            url: '<?php echo site_url('account/payment/ajax_dueJournalList'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (result) {
                console.log(result);
                pendingresult = result.data;
                if (result.status == 'success') {
                    var handlebartemplatescript = Handlebars.compile($('#duelist_handlebar').html());
                    var htmltoload = handlebartemplatescript(result.data);
                    $('#duelist').html(htmltoload);
                    if (result.data.length < 1) {
                        computeAmount();
                    }
                    enableAddtoList();
                } else {
                }
            }
        });
    }

    function enableAddtoList() {
        $('.addtolist').on('click', function () {
            var id = this.value;
            var $this = $(this);
            var index = selectedids.indexOf(id);
            if (index == -1) {
                selectedids.push(id);
                $this.toggleClass('btn-success');
                $this.toggleClass('btn-danger');
                $this.html('<i class="fa fa-remove"></i>');
            } else {
                selectedids.splice(index, 1);
                $this.toggleClass('btn-success');
                $this.toggleClass('btn-danger');
                $this.html('<i class="fa fa-plus"></i>');
            }
            totalpayable = 0;
            minamount = 0;
            var maxvalue = 0;
            pendingresult.forEach(function (value) {
                if (selectedids.indexOf(value.id) != -1) {
                    if (parseFloat(value.payableamount) > parseFloat(maxvalue) || parseFloat(maxvalue) == 0) {
                        maxvalue = parseFloat(value.payableamount);
                    }
                    totalpayable = totalpayable + parseFloat(value.payableamount);
                }
            });
            minamount = totalpayable - maxvalue;
            computeAmount();
            reloadSelectedList();
        });
    }

    function reloadSelectedList() {
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            ids: selectedids,
            supplierid: $('#pay_to').val()
        };
        $.ajax({
            url: '<?php echo site_url('account/payment/ajax_JournalEntryList'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    $('#selectedlist').html('');
                    if (result.data) {
                        var handlebartemplatescript = Handlebars.compile($('#selectedlist_handlebar').html());
                        var htmltoload = handlebartemplatescript(result.data);
                        $('#selectedlist').html(htmltoload);
                    }
                } else {
                }
            }
        });
    }

    function computeAmount() {
        nettotal = parseFloat(totalpayable);
        if (isNaN(nettotal)) {
            nettotal = 0;
        }
        $('#nettotal').val(nettotal);
        $('#paid_amount').val(nettotal);
    }

    function paymentModetoggle() {
        $('#payment_mode').on('click', function (e) {
            var mode = this.value;
            if (mode == 'cheque') {
                $('#cheque_no').prop('disabled', false);
                $('#bank').prop('disabled', false);
                $('#cheque_date').prop('disabled', false);
                $('#cheque_date_bs').prop('disabled', false);

                $('.chequeno').show();
                $('.bank').show();
                $('.chequedate').show();
            } else {
                $('#cheque_no').prop('disabled', true);
                $('#bank').prop('disabled', true);
                $('#cheque_date').prop('disabled', true);
                $('#cheque_date_bs').prop('disabled', true);

                $('.chequeno').hide();
                $('.bank').hide();
                $('.chequedate').hide();

            }
        });
    }
</script>
