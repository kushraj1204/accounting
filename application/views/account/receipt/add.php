<div class="content-wrapper" style="min-height: 946px;">

    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $receipt->id ? $this->lang->line('edit_receipt') : $this->lang->line('add_receipt'); ?>
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
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('receipt_voucher'); ?>
                        </h3>

                    </div>
                    <div class="row box-body">
                        <?php echo form_open('account/receipt/save_receipt', array('name' => 'receipt_form', 'id' => 'receipt_form')); ?>
                        <input type="hidden" name="id" value="<?php echo set_value('id', $receipt->id); ?>"/>
                        <div class="col-md-4 form-group">
                            <label><?php echo $this->lang->line('receipt_no'); ?></label>
                            <small class="req">*</small>
                            <input required type="text" readonly id="receipt_no" name="receipt_no" class="form-control"
                                   value="<?php echo set_value('receipt_no', $receipt_no ? $receipt_no : $receipt->receipt_no); ?>">

                            <span class="text-danger"><?php echo form_error('receipt_no'); ?></span>
                        </div>
                        <div class="col-md-4 form-group">

                            <label><?php echo $this->lang->line('receipt_date'); ?></label>
                            <small class="req">*</small>
                            <?php $receipt_date = isset($receipt->receipt_date) ? $this->customlib->formatDate($receipt->receipt_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>
                                <?php echo form_error('receipt_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="receipt_date_bs" name="receipt_date_bs" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('receipt_date_bs', $receipt->receipt_date_bs); ?>">
                                <input type="hidden" id="receipt_date" name="receipt_date" class="form-control"
                                       readonly
                                       value="<?php echo set_value('receipt_date', $receipt_date); ?>">
                            <?php } else {
                                echo form_error('receipt_date', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="receipt_date" name="receipt_date" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('receipt_date', $receipt_date); ?>">
                                <input type="hidden" id="receipt_date_bs" name="receipt_date_bs"
                                       class="form-control" readonly
                                       value="<?php echo set_value('receipt_date_bs', $payment->receipt_date_bs); ?>">
                            <?php } ?>


                        </div>

                        <div class="col-md-4 form-group">
                            <label><?php echo $this->lang->line('ref_no'); ?></label>
                            <small class="req">*</small>
                            <input required type="text" id="ref_no" name="ref_no" class="form-control"
                                   value="<?php echo set_value('ref_no', $receipt->ref_no); ?>">
                            <span class="text-danger"><?php echo form_error('ref_no'); ?></span>
                        </div>

                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('receive_from'); ?></label>
                            <small class="req"> *</small>
                            <select required name="receive_from" id="receive_from" class="form-control">
                                <?php if ($receipt) { ?>
                                    <option value="<?php echo $receipt->received_from ?>"
                                    >
                                        <?php echo $receipt->name . '(' . $receipt->code . ')'; ?>
                                    </option>
                                <?php } else { ?>
                                    <option></option>
                                    <?php foreach ($customers as $customer) { ?>
                                        <option value="<?php echo $customer->id; ?>"
                                        >
                                            <?php echo $customer->name . '(' . $customer->code . ')'; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('receive_from'); ?></span>
                        </div>

                        <div class="col-md-3 form-group">
                            <label><?php echo $this->lang->line('receipt_mode'); ?></label>
                            <small class="req"> *</small>

                            <select required name="receipt_mode" id="receipt_mode" class="form-control">

                                <option value="cheque" <?php if ($receipt->receipt_mode == 'cheque') echo "selected"; ?>>
                                    Cheque
                                </option>
                                <option value="cash" <?php if ($receipt->receipt_mode == 'cash') echo "selected"; ?>>
                                    Cash
                                </option>

                            </select>
                            <span class="text-danger"><?php echo form_error('receipt_mode'); ?></span>
                        </div>
                        <div class="col-md-2 form-group chequedate">

                            <label><?php echo $this->lang->line('cheque_date'); ?></label>
                            <?php $cheque_date = isset(json_decode($receipt->receipt_mode_details)->cheque_date) ? $this->customlib->formatDate(json_decode($receipt->receipt_mode_details)->cheque_date) : ''; ?>
                            <?php if ($this->datechooser === 'bs') { ?>
                                <?php echo form_error('cheque_date_bs', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="cheque_date_bs" name="cheque_date_bs" class="form-control"
                                       readonly required
                                       value="<?php echo set_value('cheque_date_bs', json_decode($receipt->receipt_mode_details)->cheque_date_bs); ?>">
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
                                       value="<?php echo set_value('cheque_date_bs', json_decode($receipt->receipt_mode_details)->cheque_date_bs); ?>">
                            <?php } ?>

                            <!--                            <label>-->
                            <?php //echo $this->lang->line('cheque_date'); ?><!--</label>-->
                            <!--                            <small class="req"> *</small>-->
                            <!--                            <input required type="text" id="cheque_date" name="cheque_date" class="form-control"-->
                            <!--                                   value="-->
                            <?php //echo set_value('cheque_date', isset($receipt->cheque_date) ? $this->customlib->formatDate($receipt->cheque_date) : ''); ?><!--">-->
                            <!---->
                            <!--                            <span class="text-danger">-->
                            <?php //echo form_error('cheque_date'); ?><!--</span>-->

                        </div>

                        <div class="col-md-2 form-group chequeno">
                            <label><?php echo $this->lang->line('cheque_no'); ?></label>
                            <small class="req"> *</small>
                            <input required type="text" id="cheque_no" name="cheque_no" class="form-control"
                                   value="<?php echo set_value('cheque_no', json_decode($receipt->receipt_mode_details)->cheque_no); ?>">
                            <span class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                        </div>

                        <div class="col-md-2 form-group bank">
                            <label><?php echo $this->lang->line('bank'); ?></label>
                            <small class="req"> *</small>
                            <select required name="bank" id="bank" class="form-control">
                                <?php if ($receipt->receipt_mode == 'cheque') { ?>
                                    <option selected value="<?php echo $receipt->asset_id; ?>"
                                    >
                                        <?php echo $receipt->bankname; ?>
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
                            <?php if (!$receipt) { ?>
                                <label><?php echo $this->lang->line('due_receipts'); ?></label>
                            <?php } else { ?>
                                <label><?php echo $this->lang->line('selected_due_receipt'); ?></label>
                            <?php } ?>
                            <div id="duelist">
                                <table class="table table-bordered">
                                    <thead>
                                    <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
                                    <th class="table-primary"><?php echo $this->lang->line('receivable_amount'); ?>
                                        (<?php echo $this->currency; ?>)
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
                                                <td class="dueamount"><?php echo $eachrelatedjournal->receivableamount; ?></td>
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
                                                <td class="dueamount"><?php echo $eachrelatedinvoice->receivableamount; ?></td>
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
                                            (<?php echo $this->currency; ?>)
                                        </th>
                                        <th class="table-primary "><?php echo $this->lang->line('credit_amount'); ?>
                                            (<?php echo $this->currency; ?>)
                                        </th>
                                        </thead>

                                        <tbody>

                                        <?php foreach ($relatedjournaldetails as $eachjournal) { ?>
                                            <tr class="selectedjournallist">
                                                <td><?php echo $eachjournal->journalcode; ?></td>
                                                <td><?php echo $eachjournal->coa_title; ?></td>
                                                <td><?php echo $eachjournal->coa_category; ?></td>
                                                <td><?php echo $eachjournal->debit; ?></td>
                                                <td><?php echo $eachjournal->credit; ?></td>
                                            </tr>
                                        <?php } ?>

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
                                        <th class="table-primary"><?php echo $this->lang->line('rate'); ?>
                                            (<?php echo $this->currency; ?>)
                                        </th>
                                        <th class="table-primary "><?php echo $this->lang->line('total'); ?>
                                            (<?php echo $this->currency; ?>)
                                        </th>
                                        </thead>

                                        <tbody>

                                        <?php foreach ($relatedinvoicedetails as $eachinvoice) { ?>
                                            <tr class="selectedjournallist">
                                                <td><?php echo $eachinvoice->code; ?></td>
                                                <td><?php echo $eachinvoice->coa_title; ?></td>
                                                <td><?php echo $eachinvoice->coa_category; ?></td>
                                                <td><?php echo $eachinvoice->quantity; ?></td>
                                                <td><?php echo $eachinvoice->rate; ?></td>
                                                <td><?php echo $eachinvoice->total; ?></td>
                                            </tr>
                                        <?php } ?>

                                        </tbody>
                                    </table>
                                <?php } ?>

                            </div>
                            <div id="selectedlistjournal"></div>
                            <div id="selectedlistinvoice"></div>

                        </div>
                        <div class="col-md-6 form-group">
                            <label><?php echo $this->lang->line('narration'); ?></label>
                            <small class="req"> *</small>
                            <textarea name="narration" rows="5" required
                                      class="form-control"><?php echo set_value('narration', $receipt->description); ?></textarea>
                            <span class="text-danger"><?php echo form_error('narration'); ?></span>
                        </div>

                        <div class="col-md-4 form-group">
                            <table class="table">
                                <tbody>

                                <tr>
                                    <th><?php echo $this->lang->line('net_total'); ?> (<?php echo $this->currency; ?>)
                                    </th>
                                    <td><input readonly type="text"
                                               value="<?php echo $receipt ? $receipt->nettotal : 0; ?>" id="nettotal"
                                               name="nettotal"
                                               class="form-control"></td>
                                </tr>

                                <tr>
                                    <th><?php echo $this->lang->line('paid_amount'); ?> (<?php echo $this->currency; ?>
                                        )
                                    </th>
                                    <td><input type="number" min="0" id="paid_amount" required
                                               name="paid_amount"
                                               value="<?php echo set_value('paid_amount', $receipt->received_amount); ?>"
                                               class="form-control">
                                        <span class="text-danger"><?php echo form_error('paid_amount'); ?></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="journal_ids"></div>
                        <div class="invoice_ids"></div>

                        <div class="col-md-12 form-group">

                            <input type="hidden" id="type" name="type"
                                   value="<?php echo set_value('type', $receipt->type); ?>">
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

<style>
    .btn:focus {
        outline: none;
    }
</style>

<script id="duelist_handlebar" type="text/x-handlebars-template">
    <table class="table table-bordered">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('narration'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('receivable_amount'); ?> (<?php echo $this->currency; ?>
            )
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
                <button value="{{this.id}}" type="button" data-value="{{this.amount}}" data-type="{{this.dueType}}"
                        class="btn-sm btn-success addtolist"><i class="fa fa-plus"></i>
                </button>
            </td>
            <td>{{{this.pastpayments}}}</td>
        </tr>
        {{/each}}

        </tbody>
    </table>
</script>

<script id="selectedlist_handlebar_journal" type="text/x-handlebars-template">
    <?php echo $this->lang->line('journal_details'); ?>
    <table class="table table-bordered">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
        <th class="table-primary "><?php echo $this->lang->line('debit_amount'); ?> (<?php echo $this->currency; ?>)
        </th>
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
        <hr>
        {{/each}}
        </tbody>
    </table>
</script>

<script id="selectedlist_handlebar_invoice" type="text/x-handlebars-template">
    <?php echo $this->lang->line('invoice_details'); ?>
    <table class="table table-bordered">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('entry_no'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('account'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('sub_account'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('quantity'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('rate'); ?> (<?php echo $this->currency; ?>)</th>
        <th class="table-primary "><?php echo $this->lang->line('total'); ?> (<?php echo $this->currency; ?>)</th>
        </thead>
        <tbody>
        {{#each this}}
        <tr class="selectedinvoicelist">
            <td>{{this.code}}</td>
            <td>{{this.coa_title}}</td>
            <td>{{this.coa_category}}</td>
            <td>{{this.quantity}}</td>
            <td>{{this.rate}}</td>
            <td>{{this.amount}}</td>
        </tr>
        {{/each}}
        </tbody>
    </table>
</script>


<script>
    var totalpayable = 0;
    var nettotal = 0;
    var pendingresult = new Array();
    var selectedjournalids = new Array();
    var selectedinvoiceids = new Array();
    var minamount = 0;
    <?php if(isset($minpayable)){ ?>
    minamount = parseFloat('<?php echo $minpayable; ?>');
    <?php } ?>

    $('document').ready(function () {

        var mode = $('#receipt_mode').val();
        if (mode == 'cash') {
            $('#cheque_no').prop('disabled', true);
            $('#bank').prop('disabled', true);
            $('#cheque_date').prop('disabled', true);
        }

        var payAdvance = $('#advancePay').is(':checked');
        $('#advancePay').off('click').on("click", function () {
            payAdvance = $('#advancePay').is(':checked');
            console.log(payAdvance)
        });
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';


        <?php if($this->datechooser === 'bs'){?>
        var currentDate = new Date();
        var currentNepaliDate = calendarFunctions.getBsDateByAdDate(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
        var formattedNepaliDate = calendarFunctions.bsDateFormat("%y-%m-%d", currentNepaliDate.bsYear, currentNepaliDate.bsMonth, currentNepaliDate.bsDate);
        $("#receipt_date_bs,#cheque_date_bs").nepaliDatePicker({
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
        $('#receipt_date,#cheque_date').datepicker({
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



        $('#receipt_date, #cheque_date').datepicker({
            format: date_format,
            autoclose: true,
            endDate: 'NOW'
        });

        receiptModetoggle();
        $("#receipt_form").submit(function (event) {
            var total = $('#total').val();
            var nettotal = $('#nettotal').val();
            var paidamt = $('#paid_amount').val();
            var templength = selectedjournalids.length + selectedinvoiceids.length;
            var tempmin = parseFloat(minamount);
            if (templength >= 2 && paidamt <= tempmin) {
                errorMsg("You must pay more than Rs." + tempmin + ' for this selection');
                return false;
            }
            <?php if($minpayable){ ?>
            if (paidamt <= minamount) {
                errorMsg("You must pay more than Rs." + minamount);
                return false;
            }
            <?php }?>


            <?php if(!$receipt->id){ ?>
            if (!(nettotal > 0)) {
                if (selectedjournalids.length == 0 && selectedinvoiceids.length == 0 && !payAdvance) {
                    infoMsg("Please make selection for which receipt is to be done from due list");
                    event.preventDefault();
                }
            }
            $(".journal_ids").empty();
            selectedjournalids.forEach(function (entry) {
                $(".journal_ids").append("<input required type='hidden' name='journal_id[]' value='" + entry + "'>");
            });

            $(".invoice_ids").empty();
            selectedinvoiceids.forEach(function (entry) {
                $(".invoice_ids").append("<input required type='hidden' name='invoice_id[]' value='" + entry + "'>");
            });
            // return false;
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

        <?php if(!$receipt){ ?>
        loadDueJournalList();
        <?php }?>
        $('#receive_from').select2({
            placeholder: "<?php echo $this->lang->line('receive_from'); ?>",
            allowClear: false,
        });
        $('#receive_from').on('change', function () {
            totalpayable = '';
            computeAmount();
            selectedjournalids = new Array(0);
            selectedinvoiceids = new Array(0);
            $('#selectedlist').html('');
            loadDueJournalList();
        });
    });

    function loadDueJournalList() {
        var id = $('#receive_from').val();
        if (!id) {
            $('#duelist').html('');
            return false;
        }
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            id: id
        };
        $.ajax({
            url: '<?php echo site_url('account/receipt/ajax_dueList'); ?>',
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
        $('.addtolist').off('click').on('click', function () {
            var id = this.value;
            var type = $(this).data('type');
            var $this = $(this);
            if (type == 'Journal') {
                var index = selectedjournalids.indexOf(id);
            }
            if (type == 'Invoice') {
                var index = selectedinvoiceids.indexOf(id);
            }
            if (index == -1) {
                if (type == 'Journal') {
                    selectedjournalids.push(id);
                }
                if (type == 'Invoice') {
                    selectedinvoiceids.push(id);
                }
                $this.toggleClass('btn-success');
                $this.toggleClass('btn-danger');
                $this.html('<i class="fa fa-remove"></i>');
            } else {

                if (type == 'Journal') {
                    selectedjournalids.splice(index, 1);
                }
                if (type == 'Invoice') {
                    selectedinvoiceids.splice(index, 1);
                }
                $this.toggleClass('btn-success');
                $this.toggleClass('btn-danger');
                $this.html('<i class="fa fa-plus"></i>');
            }
            totalpayable = 0;
            minamount = 0;
            var maxvalue = 0;
            pendingresult.forEach(function (value) {
                if ((selectedjournalids.indexOf(value.id) != -1 && value.dueType == 'Journal') || (selectedinvoiceids.indexOf(value.id) != -1 && value.dueType == 'Invoice')) {
                    if (parseFloat(value.payableamount) > parseFloat(maxvalue) || parseFloat(maxvalue) == 0) {
                        maxvalue = parseFloat(value.payableamount);
                    }
                    totalpayable = totalpayable + parseFloat(value.payableamount);
                }
            });
            minamount = totalpayable - maxvalue;
            computeAmount();
            reloadSelectedList(type);
        });
    }

    function reloadSelectedList(type) {
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            journalids: selectedjournalids,
            invoiceids: selectedinvoiceids,
            customerid: $('#receive_from').val(),
        };
        $.ajax({
            url: '<?php echo site_url('account/receipt/ajax_EntryListDetails'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    $('#selectedlistjournal').html('');
                    $('#selectedlistinvoice').html('');
                    if (result.journaldata) {
                        var handlebartemplatescript = Handlebars.compile($('#selectedlist_handlebar_journal').html());
                        var htmltoload = handlebartemplatescript(result.journaldata);
                        $('#selectedlistjournal').html(htmltoload);
                    }
                    if (result.invoicedata) {
                        var handlebartemplatescript = Handlebars.compile($('#selectedlist_handlebar_invoice').html());
                        var htmltoload = handlebartemplatescript(result.invoicedata);
                        $('#selectedlistinvoice').html(htmltoload);
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

    function receiptModetoggle() {
        $('#receipt_mode').on('click', function (e) {
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