<aside class="main-sidebar" id="alert2">
    <a href="<?php echo base_url(); ?>account/settings/general" class="logo">
        <span class="logo-lg"><img src="<?php echo base_url() ?>uploads/school_content/logo/header/<?php echo $this->setting_model->getHeaderImage(); ?>" alt="<?php echo $this->customlib->getAppName() ?>" style="height: 28px; width: 164px;" /></span>
    </a>

    <section class="sidebar" id="sibe-box">
        <?php $this->load->view('layout/top_sidemenu'); ?>
        <ul class="sidebar-menu verttop">
            <?php


            ?>
            <li class="treeview <?php echo set_Topmenu('Accounts'); ?>">
                <a href="#">
                    <i class="fa fa-calculator"></i> <span><?php echo $this->lang->line('accounts'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php
                    ?>
                    <li class="<?php echo set_Submenu('account/personnel/import'); ?>"><a href="<?php echo base_url(); ?>account/personnel/import"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('import_personnel'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/personnel/customers'); ?>"><a href="<?php echo base_url(); ?>account/personnel/customers"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('customers'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/personnel/suppliers'); ?>"><a href="<?php echo base_url(); ?>account/personnel/suppliers"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('suppliers'); ?>
                        </a>
                    </li>
                    <?php
                    ?>
                    <li class="<?php echo set_Submenu('account/invoice/list'); ?>">
                        <a href="<?php echo base_url(); ?>account/invoice">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('invoice'); ?>
                        </a>
                    </li>
                    <?php


                    ?>
                    <li class="<?php echo set_Submenu('account/receipt'); ?>"><a href="<?php echo base_url(); ?>account/receipt"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('receipts'); ?>
                        </a>
                    </li>
                    <?php
                    ?>
                    <li class="<?php echo set_Submenu('account/journal/list'); ?>">
                        <a href="<?php echo base_url(); ?>account/journal">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('journal'); ?>
                        </a>
                    </li>
                    <?php
                    ?>
                    <li class="<?php echo set_Submenu('account/payment'); ?>"><a href="<?php echo base_url(); ?>account/payment"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('payments'); ?>
                        </a>
                    </li>
                    <?php
                    ?>
                    <li class="<?php echo set_Submenu('account/ledger/assets'); ?>">
                        <a href="<?php echo base_url(); ?>account/ledger/assets">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('assets_ledger'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/ledger/liabilities'); ?>">
                        <a href="<?php echo base_url(); ?>account/ledger/liabilities">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('liabilities_ledger'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/ledger/incomes'); ?>">
                        <a href="<?php echo base_url(); ?>account/ledger/incomes">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('incomes_ledger'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/ledger/expenses'); ?>">
                        <a href="<?php echo base_url(); ?>account/ledger/expenses">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('expenses_ledger'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/trial_balance'); ?>">
                        <a href="<?php echo base_url(); ?>account/trial_balance">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('trial_balance'); ?>
                        </a>
                    </li>
                    <li style="overflow-wrap: break-word" class="income_statement_visible <?php echo set_Submenu('account/income_statement'); ?>">
                        <a href="<?php echo base_url(); ?>account/income_statement">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('income_statement'); ?>
                        </a>
                    </li>
                    <li style="overflow-wrap: break-word" class="receipt_and_payment_visible <?php echo set_Submenu('account/receiptAndPayment'); ?>">
                        <a href="<?php echo base_url(); ?>account/receiptAndPayment">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('receipt_and_payment'); ?>
                        </a>
                    </li>
                    <li class="<?php echo set_Submenu('account/balance_sheet'); ?>">
                        <a href="<?php echo base_url(); ?>account/balance_sheet">
                            <i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('balance_sheet'); ?>
                        </a>
                    </li>
                </ul>
            </li>
            <?php


            ?>

            <?php
            ?>
            <li class="treeview <?php echo set_Topmenu('Account_Settings'); ?>">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span><?php echo $this->lang->line('account_settings'); ?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php
                    if ($this->rbac->hasPrivilege('account_general_setting', 'can_view')) { ?>
                        <li class="<?php echo set_Submenu('account/settings/import'); ?>"><a href="<?php echo base_url(); ?>account/settings/import"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('import_account_heads'); ?>
                            </a>
                        </li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('account_general_setting', 'can_view')) { ?>
                        <li class="<?php echo set_Submenu('account/settings/general'); ?>"><a href="<?php echo base_url(); ?>account/settings/general"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('general_setting'); ?>
                            </a>
                        </li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('account_categories', 'can_view')) { ?>
                        <li class="<?php echo set_Submenu('account/categories'); ?>"><a href="<?php echo base_url(); ?>account/categories"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('categories'); ?>
                            </a>
                        </li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('account_chart_of_accounts', 'can_view')) { ?>
                        <li class="<?php echo set_Submenu('account/settings/chart'); ?>"><a href="<?php echo base_url(); ?>account/settings/chart"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('chart_of_accounts'); ?>
                            </a>
                        </li>
                    <?php
                    }
                    if ($this->rbac->hasPrivilege('account_general_setting', 'can_view')) { ?>
                        <li class="<?php echo set_Submenu('account/settings/financialyear'); ?>"><a href="<?php echo base_url(); ?>account/settings/financialyear"><i class="fa fa-angle-double-right"></i> <?php echo $this->lang->line('financial_year'); ?>
                            </a>
                        </li>
                    <?php
                    }
                    //account settings
                    ?>
                </ul>
            </li>
            <?php ?>
            <?php  ?>
            <?php  ?>


        </ul>
    </section>
</aside>

<script type="text/javascript">
    $(document).ready(function() {
        $(".income_statement_visible").hide();
        $(".receipt_and_payment_visible").hide();
        $.ajax({
            url: "<?php echo base_url(); ?>" + "/account/settings/getSchoolType",
            type: "get",
            success: function(data) {
                let type = JSON.parse(data);
                if (type == 1) {
                    $(".income_statement_visible").show();
                }
                if (type == 2) {
                    $(".receipt_and_payment_visible").show();
                }
            }
        });
    });
</script>