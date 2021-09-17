<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line($type . 's'); ?></h1>

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">

                        <small class="pull-right">
                            <a href="<?php echo base_url(); ?>account/personnel/add_<?php echo $type; ?>"
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_' . $type); ?> </a>
                        </small>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('customer_/_supplier_list'); ?></div>
                            <?php if ($this->session->flashdata('msg')) {
                                echo show_message();
                            } ?>
                            <table id="personnelList" class="table table-striped table-bordered table-hover" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('#'); ?></th>
                                    <th><?php echo $this->lang->line('name'); ?></th>
                                    <!--<th><?php /*echo $this->lang->line('type'); */ ?></th>-->
                                    <th><?php echo $this->lang->line('code'); ?></th>
                                    <th><?php echo $this->lang->line('email'); ?></th>
                                    <th><?php echo $this->lang->line('contact'); ?></th>
                                    <th><?php echo $this->lang->line('closing_balance'); ?></th>
                                    <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                                </thead>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        var type="<?php echo $type; ?>";
        $('#personnelList').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {"bSearchable": true, "aTargets": [0, 1, 2, 3, 4, 5, 6]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4, 5]},
                {className: "mailbox-date no-print text text-right", "targets": [6]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/personnel/personnelList',
                "data" : {
                    "type" : type,
                },
            },

            'columns': [
                {data: 'count'},
                {data: 'name'},
                {data: 'code'},
                {data: 'email'},
                {data: 'contact'},
                {data: 'closing_balance'},
                {data: 'action'},
            ],

        });

    });
</script>