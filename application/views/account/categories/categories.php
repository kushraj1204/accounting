<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('account_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('categories'); ?>
                </h3>
                <small class="pull-right">
                    <a href="<?php echo base_url(); ?>account/categories/add_category" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_category'); ?></a>
                </small>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php if($this->session->flashdata('msg')){
                        echo show_message();
                    }?>
                    <table class="table table-striped table-bordered table-hover example"
                           cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><?php echo $this->lang->line('#'); ?></th>
                            <th><?php echo $this->lang->line('category'); ?></th>
                            <th><?php echo $this->lang->line('parent'); ?></th>
                            <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($categories)) {
                            $count = 1;
                            foreach ($categories as $item) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $count; ?>
                                    </td>
                                    <td>
                                        <?php echo str_repeat(' - ', $item->level - 1) . $item->title; ?>
                                    </td>
                                    <td><?php echo $item->parent_title == '' ? '- - - -' : $item->parent_title; ?></td>
                                    <td class="pull-right">
                                    <td class="mailbox-date pull-right">
                                        <?php
                                        if ($this->rbac->hasPrivilege('section', 'can_edit')) {
                                            ?>
                                            <a href="<?php echo base_url(); ?>account/categories/edit/<?php echo $item->id ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php
                                        }
                                        if ($this->rbac->hasPrivilege('section', 'can_delete') && $item->parent_id > 0 && $item->deletable == 1) {
                                            ?>
                                            <a href="<?php echo base_url(); ?>account/categories/delete/<?php echo $item->id ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('all_associated_categories_will_be_deleted.') . ' ' . $this->lang->line('delete_confirm') ?>');">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>

                                <?php
                                $count++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>