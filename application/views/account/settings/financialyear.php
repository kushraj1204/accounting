<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $this->lang->line('financial_year'); ?></h1>
    </section>


    <!-- Main content -->
    <section class="content">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary" id="bklist">

                    <div class="box-header ptbnull">
                        <?php if($this->session->flashdata('msg')){
                            echo show_message();
                        }?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <!-- Check all button -->
                            <div class="pull-right">
                            </div><!-- /.pull-right -->
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('financial_year'); ?></div>


                            <table class="table table-bordered example">
                                <thead>
                                <th class="table-primary"><?php echo $this->lang->line('id'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('financial_year_start'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('financial_year_end'); ?></th>
                                <th class="table-primary"><?php echo $this->lang->line('actions'); ?></th>
                                </thead>
                                <tbody>

                                <?php $i=0; foreach ($financialyearlist as $financialyear) { $i++;?>

                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $date_system == 1 ? $financialyear->year_starts : $financialyear->year_starts_bs ?></td>
                                        <td><?php echo $date_system == 1 ? $financialyear->year_ends : $financialyear->year_ends_bs ?></td>
                                       <td class="mailbox-date no-print text ">
                                            <?php if ($this->rbac->hasPrivilege('account_receipts', 'can_view') && $financialyear->is_current)  { ?>
                                                <a role="button" data-id=<?php echo $financialyear->id; ?>
                                                href="<?php echo base_url(); ?>account/Settings/closeFinancialYear/<?php echo $financialyear->id; ?>"
                                                   class="btn btn-default btn-xs  closeyear"
                                                   title="<?php echo $this->lang->line('close_year'); ?>" onclick="return confirm(<?php echo $this->lang->line('are_you_sure_to_close_the_financial_year'); ?>)">
                                                    <i class="fa fa-unlock-alt"></i>
                                                </a>
                                            <?php }
                                            else{?>
                                                <a role="button" data-id=<?php echo $financialyear->id; ?>
                                                href=""
                                                   class="btn btn-default btn-xs "
                                                   title="<?php echo $this->lang->line('year_closed'); ?>">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                            <?php }
                                         ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


