<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calculator"></i> <?php echo $this->lang->line('Accounts'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-book"></i>
                            <?php echo $this->lang->line($type . '_ledger'); ?>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <select id="financial_year" class="form-control">
                                    <?php foreach ($financial_years as $financial_year) { ?>
                                        <?php if ($this->datechooser == 'bs') { ?>
                                            <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                    value="<?php echo $financial_year->id ?>"><?php echo $financial_year->id == 1 ? $this->opening_balance_date : $financial_year->year_starts_bs ?>
                                                - <?php echo $financial_year->year_ends_bs ?></option>
                                        <?php } else { ?>
                                            <option <?php echo $financial_year->id == $selectedYear ? 'selected' : ''; ?>
                                                    value="<?php echo $financial_year->id ?>"><?php echo $financial_year->id == 1 ? $this->opening_balance_date : $financial_year->year_starts ?>
                                                - <?php echo $financial_year->year_ends ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line($type . '_ledger'); ?></div>
                            <table id="coaLedgerList" class="table table-striped table-bordered table-hover "
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?php echo $this->lang->line($type . '_title'); ?></th>
                                    <th><?php echo $this->lang->line('code'); ?></th>
                                    <th><?php echo $this->lang->line('category'); ?></th>
                                    <th><?php echo $this->lang->line('sub_category_1'); ?></th>
                                    <th><?php echo $this->lang->line('sub_category_2'); ?></th>
                                    <th><?php echo $this->lang->line('closing_balance'); ?></th>
                                    <th class="no-print text text-right"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                                </thead>

                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script type="text/javascript">
    $(document).ready(function () {
        $('#financial_year').on('change', function () {
            window.location = '<?php echo base_url(); ?>account/ledger/<?php echo strtolower($type);?>/' + $(this).val();
        });
        $('#coaLedgerList').DataTable({
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
                'url': '<?=base_url()?>/account/ledger/coaLedgerList',
                "data":
                    function (data) {
                        data.type = "<?php echo $type;?>";
                        data.financial_year = <?php echo $selectedYear; ?>;
                    },
            },
            'columns':
                [
                    {data: 'name'},
                    {data: 'code'},
                    {data: 'categoryName'},
                    {data: 'subCategory1Name'},
                    {data: 'subCategory2Name'},
                    {data: 'balance'},
                    {data: 'action'},
                ],

        })
        ;
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

    });
</script>