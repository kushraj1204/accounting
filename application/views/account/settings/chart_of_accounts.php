<!--<script src="--><?php //echo base_url() . 'backend/js/pagination.min.js' ?><!--" type="text/javascript"></script>-->
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">


        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('account_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom coalist">
                    <?php $selected_coa = 1;

                    ?>
                    <ul class="nav nav-tabs">
                        <li class="pull-left header"><i
                                    class="fa fa-list-ul"></i> <?php echo $this->lang->line('chart_of_account'); ?></li>
                        <li<?php echo ($selected_coa == '' || $selected_coa == 1) ? ' class="active"' : ''; ?>>
                            <a href="#tab_assets" data-toggle="tab"><?php echo $this->lang->line('assets') ?></a>
                        </li>
                        <li<?php echo ($selected_coa == 2) ? ' class="active"' : ''; ?>>
                            <a href="#tab_liabilities"
                               data-toggle="tab"><?php echo $this->lang->line('liabilities') ?></a>
                        </li>
                        <li<?php echo ($selected_coa == 3) ? ' class="active"' : ''; ?>>
                            <a href="#tab_incomes" data-toggle="tab"><?php echo $this->lang->line('incomes') ?></a>
                        </li>
                        <li<?php echo ($selected_coa == 4) ? ' class="active"' : ''; ?>>
                            <a href="#tab_expenses" data-toggle="tab"><?php echo $this->lang->line('expenses') ?></a>
                        </li>
                        <li<?php echo ($selected_coa == 5) ? ' class="active"' : ''; ?>>
                            <a href="#tab_charges_and_taxes"
                               data-toggle="tab"><?php echo $this->lang->line('equity') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <?php if ($this->session->flashdata('msg')) {
                            echo show_message();
                        } ?>

                        <div class="tab-pane<?php echo ($selected_coa == 1 || !isset($selected_coa)) ? ' active' : ''; ?> table-responsive"
                             id="tab_assets">
                            <div class="download_label"><?php echo $this->lang->line('assets'); ?></div>

                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>account/settings/add_coa/assets"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i><?php echo $this->lang->line('add_assets'); ?></a>
                            </small>
                        </div>
                        <div class="tab-pane<?php echo ($selected_coa == 2) ? ' active' : ''; ?> table-responsive"
                             id="tab_liabilities">
                            <div class="download_label"><?php echo $this->lang->line('liability'); ?></div>

                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>account/settings/add_coa/liabilities"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i><?php echo $this->lang->line('add_liabilities'); ?></a>
                            </small>
                        </div>
                        <div class="tab-pane<?php echo ($selected_coa == 3) ? ' active' : ''; ?> table-responsive"
                             id="tab_incomes">
                            <div class="download_label"><?php echo $this->lang->line('income'); ?></div>

                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>account/settings/add_coa/income"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i><?php echo $this->lang->line('add_income'); ?></a>
                            </small>
                        </div>
                        <div class="tab-pane<?php echo ($selected_coa == 4) ? ' active' : ''; ?> table-responsive"
                             id="tab_expenses">
                            <div class="download_label"><?php echo $this->lang->line('expense'); ?></div>

                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>account/settings/add_coa/expense"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_expense'); ?> </a>
                            </small>
                        </div>
                        <div class="tab-pane<?php echo ($selected_coa == 5) ? ' active' : ''; ?> table-responsive"
                             id="tab_charges_and_taxes">
                            <div class="download_label"><?php echo $this->lang->line('equity'); ?></div>
                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>account/settings/add_coa/equity"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_equity'); ?> </a>
                            </small>
                        </div>

                        <!--                        <div id="COAData"></div>-->
                        <div class="box-body">
                            <div class="table-responsive mailbox-messages">
                                <div class="download_label"><?php echo $this->lang->line('coa_list'); ?></div>
                                <table id="coaList" class="table table-striped table-bordered table-hover "
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('sub_category_1'); ?></th>
                                        <th><?php echo $this->lang->line('sub_category_2'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                    </thead>

                                </table><!-- /.table -->
                            </div><!-- /.mail-box-messages -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script id="COAlisting_handlebar" type="text/x-handlebars-template">
    <table class="table table-bordered example">
        <thead>
        <tr>
            <th><?php echo $this->lang->line('name'); ?></th>
            <th><?php echo $this->lang->line('category'); ?></th>
            <?php if ($this->level >= 4) { ?>
                <th><?php echo $this->lang->line('sub_category_1'); ?></th>
            <?php } ?>
            <?php if ($this->level >= 5) { ?>
                <th><?php echo $this->lang->line('sub_category_2'); ?></th>
            <?php } ?>
            <th><?php echo $this->lang->line('code'); ?></th>
            <th><?php echo $this->lang->line('action'); ?></th>
        </tr>
        </thead>
        <tbody>

        {{#each this}}
        <tr>
            <td>{{name}}</td>
            <td>{{categoryname}}</td>
            <?php if ($this->level >= 4) { ?>
                <td>{{subcategory1name}}</td>
            <?php } ?>
            <?php if ($this->level >= 5) { ?>
                <td>{{subcategory2name}}</td>
            <?php } ?>
            <td>{{code}}</td>
            <td class="mailbox-date no-print text ">
                <?php if ($this->rbac->hasPrivilege('account_chart_of_accounts', 'can_edit')) { ?>
                    <a href="<?php echo base_url(); ?>account/settings/edit_coa/{{id}}"
                       class="btn btn-default btn-xs" data-toggle="tooltip"
                       title="<?php echo $this->lang->line('edit'); ?>">
                        <i class="fa fa-pencil"></i>
                    </a>
                <?php }
                if ($this->rbac->hasPrivilege('account_chart_of_accounts', 'can_delete')) { ?>
                    {{#if deletable}}
                    <a href="<?php echo base_url(); ?>account/settings/delete_coa/{{id}}"
                       class="btn btn-default btn-xs deletecoa" data-toggle="tooltip"
                       title="<?php echo $this->lang->line('delete'); ?>">
                        <i class="fa fa-remove"></i>
                    </a>
                    {{/if}}
                <?php } ?>
            </td>
        </tr>
        {{/each}}
        </tbody>
    </table>
</script>

<script id="pagination_handlebar" type="text/x-handlebars-template">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1"> << </a>
            </li>
            {{#times this }}
            <li class="page-item"><a class="page-link" href="#">{{this}}</a></li>
            {{/times}}
            <li class="page-item disabled">
                <a class="page-link" href="#"> >> </a>
            </li>
        </ul>
    </nav>
</script>

<script type="text/javascript">

    $(document).ready(function () {
        // loadCOAList();
        $('#coaList').DataTable({
            "aoColumnDefs": [
                {"bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5]},
                {"bSearchable": true, "aTargets": [0, 1, 2, 3, 4, 5]},
                {className: "mailbox-name", "targets": [0, 1, 2, 3, 4]},
                {className: "mailbox-date no-print text text-right", "targets": [5]},
            ],
            'pageLength': 100,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?=base_url()?>/account/settings/coaList',
                "data":
                    function (data) {
                        data.type = $('.coalist .nav-tabs li.active a').attr('href');
                    },
            },
            'columns': [
                {data: 'name'},
                {data: 'categoryname'},
                {data: 'subcategory1name'},
                {data: 'subcategory2name'},
                {data: 'code'},
                {data: 'action'},
            ],

        });

        deleteCOA();
        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');
            if (checked) {
                if (!confirm('Are you sure you active account?')) {
                    $(this).removeAttr('checked');
                } else {
                    var status = "yes";
                    changeStatus(rowid, status, role);
                }
            } else if (!confirm('Are you sure you deactive account?')) {
                $(this).prop("checked", true);
            } else {
                var status = "no";
                changeStatus(rowid, status, role);

            }
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // loadCOAList();
            $('#coaList').DataTable().draw();
        });
    });

    function loadCOAList() {
        var target = $('.coalist .nav-tabs li.active a').attr('href');
        var dataJson = {
            ['<?php echo $this->security->get_csrf_token_name(); ?>']: '<?php echo $this->security->get_csrf_hash(); ?>',
            target: target
        };
        $.ajax({
            url: '<?php echo site_url('account/settings/ajax_getCOAListing'); ?>',
            type: 'POST',
            data: dataJson,
            dataType: 'json',
            success: function (data) {
                var handlebartemplatescript = Handlebars.compile($('#COAlisting_handlebar').html());
                var htmltoload = handlebartemplatescript(data.result);
                $('#COAData').html(htmltoload);
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
                                //console.log(doc);
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
                deleteCOA();
            }
        });
    }

    function deleteCOA() {
        $("body").on("click", "#coaList tbody tr .deletecoa", function (e) {
            e.preventDefault();
            var r = confirm('Are you sure you want to delete this item?');
            if (r == true) {
                deleteCOAItem($(this).attr('href'));
            }
        });
    }

    function deleteCOAItem(url) {
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
                    $('#coaList').DataTable().draw();
                    // loadCOAList();
                } else {
                    errorMsg(result.status);
                }
            }
        });
    }

    function changeStatus(rowid, status, role) {
        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/users/changeStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.status);
            }
        });
    }
</script>