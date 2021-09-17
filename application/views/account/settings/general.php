<script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('account_settings'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('account_info') != '') { ?>
            <p class="alert alert-info"><?php echo $this->session->flashdata('account_info'); ?></p>
        <?php } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_4"
                                              data-toggle="tab"><?php echo $this->lang->line('general'); ?></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('financial_year'); ?></a>
                        </li>
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('opening_balances'); ?></a>
                        </li>
                        <li class="pull-left header">
                            <i class="fa fa-gear"></i> <?php echo $this->lang->line('account_settings'); ?>
                        </li>
                    </ul>
                    <div class="tab-content pb0">

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">

                            <div class="nav-tabs-custom openingbalances">

                                <ul class="nav nav-tabs pull-right">

                                    <li class="active openingbalance"><a href="#tab_customers"
                                                                         data-toggle="tab"><?php echo $this->lang->line('customer_balances'); ?></a>
                                    </li>
                                    <li class="openingbalance"><a href="#tab_suppliers"
                                                                  data-toggle="tab"><?php echo $this->lang->line('supplier_balances'); ?></a>
                                    </li>
                                    <li class="openingbalance"><a href="#tab_assets"
                                                                  data-toggle="tab"><?php echo $this->lang->line('asset_balances'); ?></a>
                                    </li>
                                    <li class="openingbalance"><a href="#tab_liabilities"
                                                                  data-toggle="tab"><?php echo $this->lang->line('liability_balances'); ?></a>
                                    </li>
                                    <li class="openingbalance"><a href="#tab_equity"
                                                                  data-toggle="tab"><?php echo $this->lang->line('equity_balances'); ?></a>
                                    </li>
                                    <li class="pull-left" style="margin: 3px 5px 0">
                                        <button data-toggle="modal" data-target="#addOpeningBalance"
                                                class="btn btn-primary btn-sm">
                                            <i class="fa fa-plus"></i><?php echo $this->lang->line('add_opening_balance'); ?>
                                        </button>
                                    </li>


                                </ul>


                            </div>
                            <!--                            <div id="openingBalanceData">-->
                            <!--                            </div>-->
                            <div class="box-body">
                                <div class="table-responsive mailbox-messages">
                                    <div class="download_label"><?php echo $this->lang->line('opening_balances'); ?></div>
                                    <?php if ($this->session->flashdata('msg')) {
                                        echo show_message();
                                    } ?>
                                    <table id="openingBalancesList"
                                           class="table table-striped table-bordered table-hover "
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th class="table-primary"><?php echo $this->lang->line('name'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('code'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('debit'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('credit'); ?></th>
                                            <th class="table-primary"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                        </thead>

                                    </table><!-- /.table -->
                                </div><!-- /.mail-box-messages -->
                            </div>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <h4><?php echo $this->lang->line('financial_year'); ?></h4>
                            <form role="form" id="datesystem"
                                  action="<?php echo site_url('account/Settings/saveDateSettings') ?>"
                                  class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-7">

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('date_system'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control" id="date_system"
                                                                name="date_system">
                                                            <option value="1" <?php if ($settings->date_system == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('english_ad'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->date_system == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('nepali_bs'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger yeartype_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('year_starts_month'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <input type="number" min="1" max="12" class="form-control"
                                                               data-toggle="tooltip" data-placement="top"
                                                               title="Fiscal year starting Month"
                                                               id="year_start" name="year_start"
                                                               placeholder=" 1 for January (in AD)"
                                                               value="<?php echo set_value('year_start', $settings->year_start > 0 ? $settings->year_start : 1); ?>"
                                                        >

                                                        <span class="text text-danger year_start_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('year_ends_month'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <input type="number" min="1" max="12" class="form-control"
                                                               data-toggle="tooltip" data-placement="top"
                                                               readonly
                                                               title="Fiscal year ending Month"
                                                               id="year_end" name="year_end"
                                                               placeholder=" 1 for January(in AD)"
                                                               value="<?php echo set_value('year_end', $settings->year_end > 0 ? $settings->year_end : 12); ?>"
                                                        >

                                                        <span class="text text-danger year_end_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('opening_balance_date'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <!--                                                        <span class="text-danger opening_balance_date_error">Opening Balance Date must be stated</span>-->
                                                        <?php $opening_balance_date = $settings->opening_balance_date ? $this->customlib->formatDate($settings->opening_balance_date) : ''; ?>
                                                        <?php if ($this->datechooser === 'bs') { ?>
                                                            <input type="text" id="opening_balance_date_bs"
                                                                   name="opening_balance_date_bs" class="form-control"
                                                                   readonly required
                                                                   value="<?php echo set_value('opening_balance_date_bs', $settings->opening_balance_date_bs); ?>">
                                                            <input type="hidden" id="opening_balance_date"
                                                                   name="opening_balance_date" class="form-control"
                                                                   readonly
                                                                   value="<?php echo set_value('opening_balance_date', $opening_balance_date); ?>">
                                                        <?php } else {
                                                            ?>
                                                            <input type="text" id="opening_balance_date"
                                                                   name="opening_balance_date" class="form-control"
                                                                   readonly
                                                                   value="<?php echo set_value('opening_balance_date', $opening_balance_date); ?>">
                                                            <input type="hidden" id="opening_balance_date_bs"
                                                                   name="opening_balance_date_bs"
                                                                   class="form-control" readonly
                                                                   value="<?php echo set_value('opening_balance_date_bs', $settings->opening_balance_date_bs); ?>">
                                                        <?php } ?>


                                                        <!--                                                        --><?php //if ($this->datechooser === 'bs') {
                                                        //                                                            $opening_balance_date = $settings->opening_balance_date ? $settings->opening_balance_date : ''; ?>
                                                        <!--                                                        --><?php //} else {
                                                        //                                                            $opening_balance_date = $settings->opening_balance_date ? $this->customlib->formatDate($settings->opening_balance_date) : ''; ?>
                                                        <!--                                                        --><?php //} ?>
                                                        <!---->
                                                        <!--                                                        <input type="text" id="opening_balance_date"-->
                                                        <!--                                                               name="opening_balance_date" class="form-control"-->
                                                        <!--                                                               readonly-->
                                                        <!--                                                               value="-->
                                                        <?php //echo set_value('opening_balance_date', $opening_balance_date); ?><!--">-->
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <?php if (!$settings->is_year_saved || !$settings->opening_balance_date) { ?>
                                    <div class="box-footer">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                               value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="submit"
                                                class="btn btn-primary pull-right col-md-offset-3 datesystem_save"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane active" id="tab_4">
                            <h4><?php echo $this->lang->line('general_account_settings'); ?></h4>
                            <form role="form" id="generalsettings"
                                  action="<?php echo site_url('account/Settings/saveGeneralSettings') ?>"
                                  class="form-horizontal" method="post">
                                <div class="box-body minheight149">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('system_type'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control" id="system_type"
                                                                name="system_type" <?php echo $settings->is_settings_saved ? 'readonly' : ''; ?>
                                                                style="pointer-events: <?php echo $settings->is_settings_saved ? 'none' : ''; ?>">

                                                            <option value="1" <?php if ($settings->system_type == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('business_oriented'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->system_type == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('service_oriented'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger system_type_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('round_to'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control" id="round_to"
                                                                name="round_to" <?php echo $settings->is_settings_saved ? 'readonly' : ''; ?>
                                                                style="pointer-events: <?php echo $settings->is_settings_saved ? 'none' : ''; ?>">
                                                            <option value="1" <?php if ($settings->round_to == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('2_decimal'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->round_to == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('round_up'); ?>
                                                            </option>
                                                            <option value="3" <?php if ($settings->round_to == 3) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('round_down'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger round_to_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('allow_general_payments_edit'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control"
                                                                id="allow_payment_edit"
                                                                name="allow_payment_edit">
                                                            <option value="0" <?php if ($settings->allow_payment_edit == 0) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('no'); ?>
                                                            </option>
                                                            <option value="1" <?php if ($settings->allow_payment_edit == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('admin_only'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->allow_payment_edit == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('30_days'); ?>
                                                            </option>
                                                            <option value="3" <?php if ($settings->allow_payment_edit == 3) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('1_financial_year'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger allow_general_payments_edit_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('allow_general_receipts_edit'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control"
                                                                id="allow_receipt_edit"
                                                                name="allow_receipt_edit">
                                                            <option value="0" <?php if ($settings->allow_receipt_edit == 0) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('no'); ?>
                                                            </option>
                                                            <option value="1" <?php if ($settings->allow_receipt_edit == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('admin_only'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->allow_receipt_edit == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('30_days'); ?>
                                                            </option>
                                                            <option value="3" <?php if ($settings->allow_receipt_edit == 3) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('1_financial_year'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger allow_general_receipts_edit_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('allow_journal_entries_edit'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <select class="custom-select form-control"
                                                                id="allow_journal_edit"
                                                                name="allow_journal_edit">
                                                            <option value="0" <?php if ($settings->allow_journal_edit == 0) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('no'); ?>
                                                            </option>
                                                            <option value="1" <?php if ($settings->allow_journal_edit == 1) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('admin_only'); ?>
                                                            </option>
                                                            <option value="2" <?php if ($settings->allow_journal_edit == 2) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('30_days'); ?>
                                                            </option>
                                                            <option value="3" <?php if ($settings->allow_journal_edit == 3) echo "selected"; ?>>
                                                                <?php echo $this->lang->line('1_financial_year'); ?>
                                                            </option>
                                                        </select>
                                                        <span class="text text-danger allow_journal_entries_edit_error"></span>
                                                    </div>
                                                </div>

                                                <!--                                                <div class="form-group">-->
                                                <!--                                                    <label class="col-sm-6 control-label">--><?php //echo $this->lang->line('invoice_generation_on'); ?>
                                                <!--                                                    </label>-->
                                                <!--                                                    <div class="col-sm-6">-->
                                                <!--                                                        <select class="custom-select form-control"-->
                                                <!--                                                                id="invoice_generation_on" name="invoice_generation_on">-->
                                                <!--                                                            <option value="1" -->
                                                <?php //if ($settings->invoice_generation_on == 1) echo "selected"; ?><!--
<!--                                                                Beginning of month-->
                                                <!--                                                            </option>-->
                                                <!--                                                            <option value="2" -->
                                                <?php //if ($settings->invoice_generation_on == 2) echo "selected"; ?><!--
<!--                                                                End of Month-->
                                                <!--                                                            </option>-->
                                                <!--                                                        </select>-->
                                                <!--                                                        <span class="text text-danger invoice_generation_on_error"></span>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>
                                                                                                <input type="hidden" name="invoice_generation_on" value="1"></input>-->

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('maximum_level_of_categories'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <?php $level = $settings->level > 0 ? $settings->level : 4; ?>
                                                        <input type="number" class="form-control" name="level" min="3"
                                                               max="5" value="<?php echo set_value('level', $level); ?>"
                                                               required <?php echo $settings->is_settings_saved ? 'readonly' : ''; ?>>
                                                        <span class="text text-danger level"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('due_date_duration_(days)'); ?>
                                                    </label>
                                                    <div class="col-sm-6">
                                                        <?php $due_date_duration = $settings->due_date_duration > 0 ? $settings->due_date_duration : 7; ?>
                                                        <input type="number" class="form-control"
                                                               name="due_date_duration" min="1"
                                                               value="<?php echo set_value('due_date_duration', $due_date_duration); ?>"
                                                               required>
                                                        <span class="text text-danger level"></span>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('invoice_number_format'); ?>
                                                    </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="prefix" class="form-control"
                                                               id="invoice_prefix" name="invoice_prefix"
                                                               value="<?php echo set_value('invoice_prefix', $settings->invoice_prefix); ?>">
                                                        <span class="text text-danger invoice_prefix_error"></span>

                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Check to enable prefix" type="checkbox"
                                                               id="use_invoice_prefix" name="use_invoice_prefix"
                                                               value="1"
                                                            <?php if ($settings->use_invoice_prefix == 1) echo "checked"; ?>

                                                        >

                                                    </div>

                                                    <div class="col-sm-2">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Starting number" type="text"
                                                               placeholder="invoice start" class="form-control"
                                                               id="invoice_start" name="invoice_start"
                                                               value="<?php echo set_value('invoice_start', $settings->invoice_start); ?>">
                                                        <span class="text text-danger invoice_start_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('journal_number_format'); ?>
                                                    </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="prefix " class="form-control"
                                                               id="journal_prefix" name="journal_prefix"
                                                               value="<?php echo set_value('journal_prefix', $settings->journal_prefix); ?>">

                                                        <span class="text text-danger journal_prefix_error"></span>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Check to enable prefix" type="checkbox"
                                                               id="use_journal_prefix" name="use_journal_prefix"
                                                               value="1"
                                                            <?php if ($settings->use_journal_prefix == 1) echo "checked"; ?>>

                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Starting number" type="text"
                                                               placeholder="Journal start" class="form-control"
                                                               id="journal_start" name="journal_start"
                                                               value="<?php echo set_value('journal_start', $settings->journal_start); ?>">

                                                        <span class="text text-danger journal_start_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('general_payment_number_format'); ?>
                                                    </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="prefix" class="form-control"
                                                               id="general_payment_prefix" name="general_payment_prefix"
                                                               value="<?php echo set_value('general_payment_prefix', $settings->general_payment_prefix); ?>">

                                                        <span class="text text-danger general_payment_prefix_error"></span>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Check to enable prefix" type="checkbox"
                                                               id="use_general_payment_prefix"
                                                               name="use_general_payment_prefix"
                                                               value="1"
                                                            <?php if ($settings->use_general_payment_prefix == 1) echo "checked"; ?>>

                                                    </div>

                                                    <div class="col-sm-2">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Starting number" type="text"
                                                               placeholder="Payment start" class="form-control"
                                                               id="general_payment_start" name="general_payment_start"
                                                               value="<?php echo set_value('general_payment_start', $settings->general_payment_start); ?>">

                                                        <span class="text text-danger general_payment_start_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('general_receipt_number_format'); ?>
                                                    </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="prefix" class="form-control"
                                                               id="general_receipt_prefix" name="general_receipt_prefix"
                                                               value="<?php echo set_value('general_receipt_prefix', $settings->general_receipt_prefix); ?>">

                                                        <span class="text text-danger general_receipt_prefix_error"></span>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Check to enable prefix" type="checkbox"
                                                               id="use_general_receipt_prefix"
                                                               name="use_general_receipt_prefix"
                                                               value="1"
                                                            <?php if ($settings->use_general_receipt_prefix == 1) echo "checked"; ?>
                                                        >
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Starting number" type="text"
                                                               placeholder="Invoice start" class="form-control"
                                                               id="general_receipt_start" name="general_receipt_start"
                                                               value="<?php echo set_value('general_receipt_start', $settings->general_receipt_start); ?>">

                                                        <span class="text text-danger general_receipt_start_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-6 control-label"><?php echo $this->lang->line('cash_receipt_number_format'); ?>
                                                    </label>
                                                    <div class="col-sm-3">
                                                        <input type="text" placeholder="prefix" class="form-control"
                                                               id="cash_receipt_prefix" name="cash_receipt_prefix"
                                                               value="<?php echo set_value('cash_receipt_prefix', $settings->cash_receipt_prefix); ?>">

                                                        <span class="text text-danger cash_receipt_prefix_error"></span>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Check to enable prefix" type="checkbox"
                                                               id="use_cash_receipt_prefix"
                                                               name="use_cash_receipt_prefix"
                                                               value="1"
                                                            <?php if ($settings->use_cash_receipt_prefix == 1) echo "checked"; ?>
                                                        >
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <input data-toggle="tooltip" data-placement="top"
                                                               title="Starting number" type="text"
                                                               placeholder="Entry start" class="form-control"
                                                               name="cash_receipt_start" id="cash_receipt_start"
                                                               value="<?php echo set_value('cash_receipt_start', $settings->cash_receipt_start); ?>">

                                                        <span class="text text-danger cash_receipt_start_error"></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                           value="<?= $this->security->get_csrf_hash(); ?>">

                                    <?php if ($settings->is_settings_saved == 0) { ?>
                                        <button type="submit"
                                                class="btn btn-primary pull-right col-md-offset-3 generalsettings_save"
                                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="addOpeningBalance" tabindex="-1" role="dialog" aria-labelledby="addOpeningBalance">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"
                    id="myaddModalLabel">                                            <?php echo $this->lang->line('add_opening_balance'); ?></h4>
            </div>
            <div class="modal-body openingBalanceModalBody">
                <form name="openingBalanceForm" id="openingBalanceForm" method="post" class="form-horizontal"
                      action="<?php echo site_url('account/openingBalance/ajax_addOpeningBalance') ?>"
                      enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('type'); ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <select required class="custom-select form-control" id="balance_for"
                                            name="balance_for">
                                        <option value="customer">
                                            <?php echo $this->lang->line('customer'); ?>
                                        </option>
                                        <option value="supplier">
                                            <?php echo $this->lang->line('supplier'); ?>
                                        </option>
                                        <option value="asset">
                                            <?php echo $this->lang->line('asset'); ?>
                                        </option>
                                        <option value="liability">
                                            <?php echo $this->lang->line('liability'); ?>
                                        </option>
                                        <option value="income">
                                            <?php echo $this->lang->line('income'); ?>
                                        </option>
                                        <option value="expense">
                                            <?php echo $this->lang->line('expense'); ?>
                                        </option>
                                        <option value="equity">
                                            <?php echo $this->lang->line('equity'); ?>
                                        </option>
                                    </select>
                                    <span class="text text-danger type_error"></span>
                                </div>
                            </div>

                            <div class="form-group" id="dynamicheading">
                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('heading'); ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <select required class="custom-select form-control" id="heading"
                                            name="heading">
                                    </select>
                                    <span class="text text-danger type_error"></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('amount'); ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <input required type="number" min="0.001" step="0.001" class="form-control"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Balance"
                                           id="balance" name="balance">
                                    <span class="text text-danger year_start_error"></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label ">
                                    <input type="radio" id="balance_type_debit" name="balance_type"
                                           value="debit"
                                        <?php
                                        if (set_value('balance_type', $personnel->balance_type) == 'debit' || set_value('balance_type', $personnel->balance_type) == '') {
                                            echo "checked";
                                        }
                                        ?>><?php echo $this->lang->line('debit_balance'); ?>

                                </label>
                                <label class="col-sm-4 control-label  ">
                                    <input type="radio" id="balance_type_credit" name="balance_type"
                                           value="credit"
                                        <?php
                                        if (set_value('balance_type', $personnel->balance_type) == 'credit') {
                                            echo "checked";
                                        }
                                        ?>><?php echo $this->lang->line('credit_balance'); ?>
                                </label>
                            </div>
                            <div>
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                       value="<?= $this->security->get_csrf_hash(); ?>">
                                <button type="submit" id="openingbalance_save"
                                        class="btn btn-primary pull-right btn-sm col-md-offset-3 openingbalance_save"
                                        data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editOpeningBalance" data-id="0" tabindex="-1" role="dialog"
     aria-labelledby="editOpeningBalance">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myeditModalLabel">Edit Opening Balance</h4>
            </div>
            <div class="modal-body openingBalanceModalBody">
                <!-- ==== -->
                <form name="editOpeningBalanceForm" id="editopeningBalanceForm" method="post" class="form-horizontal"
                      action="<?php echo site_url('account/openingBalance/ajax_editOpeningBalance') ?>"
                      enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo "Type"; ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <select required class="custom-select form-control" id="balance_for_edit"
                                            name="balance_for_edit">
                                        <div></div>
                                    </select>
                                    <span class="text text-danger type_error"></span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $this->lang->line('heading'); ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <select required class="custom-select form-control" id="heading_edit"
                                            name="heading_edit">

                                    </select>
                                    <span class="text text-danger type_error"></span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label"> <?php echo $this->lang->line('balance'); ?>
                                </label>
                                <small class="req">
                                    *
                                </small>
                                <div class="col-sm-6">
                                    <input required type="number" min="0.001" step="0.001" class="form-control"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Balance"
                                           id="balance_edit" name="balance_edit"
                                    >

                                    <span class="text text-danger year_start_error"></span>
                                </div>
                            </div>


                            <div class=" form-group">
                                <label class="col-sm-4 control-label ">
                                    <input type="radio" id="balance_type_debit_edit" name="balance_type_edit"
                                           value="debit"
                                    ><?php echo $this->lang->line('debit_balance'); ?>
                                </label>
                                <label class="col-sm-4 control-label">
                                    <input type="radio" id="balance_type_credit_edit" name="balance_type_edit"
                                           value="credit"
                                    ><?php echo $this->lang->line('credit_balance'); ?>
                                </label>
                            </div>
                            <div>
                                <input type="hidden" name="id_edit" id="id_edit" value="0">
                                <input type="hidden" name="personnel_id" id="personnel_id" value="0">
                                <input type="hidden" name="coa_id" id="coa_id" value="0">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <button type="submit" id="editopeningbalance_save"
                                        class="btn btn-primary btn-sm pull-right col-md-offset-3 openingbalance_save"
                                        data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script id="openingBalance_handlebar" type="text/x-handlebars-template">
    <table class="table table-bordered example">
        <thead>
        <th class="table-primary"><?php echo $this->lang->line('name'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('code'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('debit'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('credit'); ?></th>
        <th class="table-primary"><?php echo $this->lang->line('action'); ?></th>
        </thead>
        <tbody>

        {{#each this}}

        <tr>
            <td>{{this.name}}</td>
            <td>{{this.code}}</td>
            <td>{{this.debit}}</td>
            <td>{{this.credit}}</td>
            <td class="mailbox-date no-print text ">

                <?php if ($this->rbac->hasPrivilege('account_opening_balances', 'can_edit') && $this->financial_year == 1) { ?>
                    <a role="button" data-id={{this.id}} data-toggle="modal" data-target="#editOpeningBalance"
                       class="btn btn-default btn-xs editbutton" data-toggle="tooltip"
                       title="<?php echo $this->lang->line('edit'); ?>">
                        <i class="fa fa-pencil"></i>
                    </a>
                <?php }
                if ($this->rbac->hasPrivilege('account_opening_balances', 'can_delete') && $this->financial_year == 1) { ?>
                    <a href="<?php echo base_url(); ?>account/openingBalance/deleteOpeningBalance/{{this.id}}"
                       class="btn btn-default btn-xs deleteopeningbalance" data-toggle="tooltip"
                       title="<?php echo $this->lang->line('delete'); ?>">
                        <i class="fa fa-remove"></i>
                    </a>
                <?php } ?>

            </td>
        </tr>
        {{/each}}

        </tbody>
    </table>

</script>

<script id="edit_type_handlebar" type="text/x-handlebars-template">
    <select required class="custom-select form-control" id="balance_for"
            name="balance_for">
        <div></div>
    </select>
</script>


<script id="heading_handlebar" type="text/x-handlebars-template">
    <label class="col-sm-4 control-label"><?php echo $this->lang->line('heading'); ?>
    </label>
    <small class="req">
        *
    </small>
    <div class="col-sm-6">
        <select required class="custom-select form-control" id="heading"
                name="heading">
            {{#each this}}
            <option value="{{this.id}}">{{this.name}}({{this.code}})</option>
            {{/each}}
        </select>
        <span class="text text-danger type_error"></span>
    </div>
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.opening_balance_date_error').hide();
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#openingBalancesList tbody tr .editbutton').off('click').on('click', function (e) {
            console.log($(this).data("id"), 'herer');
            $("#editOpeningBalance").data("id", $(this).data("id"));
            $('#editOpeningBalance').modal('show');
        });
        $("body").on("click", "#openingBalancesList tbody tr .editbutton", function (e) {
            e.preventDefault();
            $("#editOpeningBalance").data("id", $(this).data("id"));
            $('#editOpeningBalance').modal('show');
        });

        deleteData();
        $('#openingBalancesList').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4]},
                {"bSearchable": true, "aTargets": [0, 1, 2, 3, 4]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3]},
                {className: "mailbox-date no-print text text-right", "targets": [4]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/openingBalance/openingBalancesList',
                "data":
                    function (data) {
                        data.type = $(".openingbalances ul li.active a").attr("href");
                    },
            },
            'columns': [
                {data: 'name'},
                {data: 'code'},
                {data: 'debit'},
                {data: 'credit'},
                {data: 'action'},
            ],
        });


        // loadOpeningBalanceList();
        $('#year_start').on('change', function () {
            var year_start = $('#year_start').val();
            if (year_start > 12 || year_start < 1) {
                $('#year_start').val('12');
            }
            year_start = $('#year_start').val();
            var year_end = parseInt(year_start) - 1;
            if (year_end < 1) {
                year_end = 12
            }
            $('#year_end').val(year_end);
        });

        // $('.datesystem_save').on('click', function () {
        //     var opening_balance_date = $('#opening_balance_date').val();
        //     if (!opening_balance_date) {
        //         $('.opening_balance_date_error').show();
        //         return false;
        //     }
        // });


        <?php if($this->datechooser === 'bs'){?>

        $("#opening_balance_date_bs").nepaliDatePicker({
            dateFormat: "%y-%m-%d",
            closeOnDateSelect: true
        }).on('dateSelect', function (e) {
            $('.opening_balance_date_error').hide();
            var id = e.target.id.replace("_bs", "");
            var datePickerData = e.datePickerData;
            var m = moment(datePickerData.adDate);
            var adDate = m.format(date_format.toUpperCase());
            $("#" + id).val(adDate);

        });

        <?php }else{?>
        $('#opening_balance_date').datepicker({
            format: date_format,
            autoclose: true,
        }).on('input change', function (e) {
            $('.opening_balance_date_error').hide();
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




    });

    $('li.openingbalance>a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");// activated tab
        // loadOpeningBalanceList(target);
        $('#openingBalancesList').DataTable().draw();
    });


    $('#balance_for').on('change', function () {
        loadheadings(this.value);
    });
    $('#addOpeningBalance').on('shown.bs.modal', function (e) {
        loadheadings($('#balance_for').val());
    })


    function loadOpeningBalanceList(target) {
        target = target || "#tab_customers";
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            target: target
        };
        $.ajax({
            url: '<?php echo site_url('account/openingBalance/ajax_getOpeningBalances'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    var handlebartemplatescript = Handlebars.compile($('#openingBalance_handlebar').html());
                    var htmltoload = handlebartemplatescript(result.data);
                    $('#openingBalanceData').html(htmltoload);
                    renderTable();
                    editData();
                    deleteData();

                } else {

                }


            }
        });

    }

    function renderTable() {

        $('.example').DataTable({
            "aaSorting": [],

            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: 'true',
            dom: "Bfrtip",
            buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',

                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    orientation: ($('.download_label').attr('data-orientation') || 'portrait'),
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    },
                    customize: function (doc) {

                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: ':visible:not(.axnCol)'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    }

    function loadheadings(selectedvalue) {
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            balance_for: selectedvalue
        };
        $.ajax({
            url: '<?php echo site_url('account/openingBalance/ajax_getHeadings'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (result) {
                $('#dynamicheading').html('');
                if (result.status == 'success') {
                    var handlebartemplatescript = Handlebars.compile($('#heading_handlebar').html());
                    var htmltoload = handlebartemplatescript(result.data);
                    $('#dynamicheading').html(htmltoload);

                } else {

                }

            }
        });
    }

    $('#openingBalanceForm').on('submit', function (e) {
        e.preventDefault();
        $('#openingbalance_save').attr("disabled", true);
        addOpeningBalance();
    });

    function addOpeningBalance() {
        var url = $("#openingBalanceForm").attr("action");
        $.ajax({
            url: url,
            type: 'POST',
            data: $("#openingBalanceForm").serialize(),
            dataType: 'json',
            success: function (result) {
                if (Number.isInteger(result.data)) {
                    $('#myaddModalLabel').html('Record added Successfully');
                    var data = $(".openingbalances ul li.active a").attr("href");
                    setTimeout(function () {
                        $('#addOpeningBalance').modal('toggle');
                        $('#openingBalancesList').DataTable().draw();
                        successMsg(result.status);
                    }, 1000);
                } else {
                    errorMsg(result.status);
                }

            }
        });
    }

    $("#addOpeningBalance").on("hidden.bs.modal", function () {
        $(".modal-body1").html("");
        $('#myaddModalLabel').html('Add Opening Balance');
        $("#balance").val('');
        $('#openingbalance_save').attr("disabled", false);
    });

    function editData() {
        $(".editbutton").on('click', function (e) {
            e.preventDefault();
            console.log($(this).data("id"), 'herer');
            $("#editOpeningBalance").data("id", $(this).data("id"));
        });
    }

    function deleteData() {
        $("body").on("click", "#openingBalancesList tbody tr .deleteopeningbalance", function (e) {
            e.preventDefault();
            var r = confirm('Are you sure you want to delete this item?');
            if (r == true) {
                deleteOpeningBalance($(this).attr('href'));
            }
        });
    }

    function deleteOpeningBalance(url) {
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
        };
        $.ajax({
            type: "POST",
            url: url,
            data: dataJson,
            dataType: "json",
            success: function (result) {
                if (result.status == 'success') {
                    successMsg(result.status);
                    var data = $(".openingbalances ul li.active a").attr("href");
                    $('#openingBalancesList').DataTable().draw();
                } else {
                    errorMsg(result.status);
                }
            }
        });
    }


    $('#editOpeningBalance').on('shown.bs.modal', function (e) {
        loadEditData($(this).data("id"));
    })

    function loadEditData(id) {
        var url = '<?php echo site_url('account/openingBalance/ajax_getEditItem');  ?>';
        $.ajax({
            url: url,
            type: 'POST',
            data: {'id': id},
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    var data = result.data;
                    $('#balance_for_edit').html('<option value="' + data.type + '">' + data.type + '</option>');
                    $('#heading_edit').html('<option value="' + data.headingid + '">' + data.name + '(' + data.code + ')</option>');
                    $('#balance_edit').val(data.balance);
                    $('#id_edit').val(data.id);
                    $('#personnel_id').val(data.personnel_id);
                    $('#coa_id').val(data.coa_id);
                    if ((data.balance_type == 'debit')) {
                        $('#balance_type_credit_edit').prop('checked', false);
                        $('#balance_type_debit_edit').prop('checked', true);
                    }
                    if ((data.balance_type == 'credit')) {
                        $('#balance_type_debit_edit').prop('checked', false);
                        $('#balance_type_credit_edit').prop('checked', true);
                    }
                    // successMsg(result.msg);
                } else {
                    // errorMsg(result.msg);
                }
            }
        });
    }

    $('#editopeningBalanceForm').on('submit', function (e) {
        e.preventDefault();
        $('#editopeningbalance_save').attr("disabled", true);
        updateOpeningBalance();
    });

    function updateOpeningBalance() {
        var url = $("#editopeningBalanceForm").attr("action");
        $.ajax({
            url: url,
            type: 'POST',
            data: $("#editopeningBalanceForm").serialize(),
            dataType: 'json',
            success: function (result) {
                if (result.status == 'success') {
                    $('#myeditModalLabel').html('Updated Successfully');
                    var data = $(".openingbalances ul li.active a").attr("href");
                    setTimeout(function () {
                        $('#editOpeningBalance').modal('toggle');
                        $('#openingBalancesList').DataTable().draw();
                        successMsg(result.status);
                    }, 1000);
                } else {
                    errorMsg(result.status);
                }
            }
        });
    }

    $("#editOpeningBalance").on("hidden.bs.modal", function () {
        $("#balance_edit").val('');
        $('#myeditModalLabel').html('Edit Opening Balance');
        $('#editopeningbalance_save').attr("disabled", false);
    });


</script>


