<?php
$accordion = buildAccordionCategory($categories, 0, $form->parent_lft); ?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('account_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 align-center">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add_category'); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php echo form_open('account/categories/save_category'); ?>
                        <input type="hidden" name="id" value="<?php echo $form->id; ?>"/>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parent'); ?></label> <small class="req">*</small>
                            <?php echo form_error('parent_id', '<span class="text-danger pull-right">', '</span>'); ?>
                            <!--<select autofocus="" id="parent_id" name="parent_id" class="form-control">
                                <option value="0"><?php /*echo $this->lang->line('parent_category'); */?></option>
                                <?php /*foreach ($categories as $category) { */?>
                                    <option value="<?php /*echo $category->id */?>" <?php /*if ($form->parent_id == $category->id) echo "selected=selected" */?>><?php /*echo str_repeat(' - ', $category->level - 1) . $category->title */?></option>
                                    <?php
/*                                    $count++;
                                }
                                */?>
                            </select>-->
                            <div class="category-accordion">
                                <?php echo $accordion; ?>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('title'); ?></label> <small class="req"> *</small>
                                <?php echo form_error('title', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="title" name="title" class="form-control"
                                       value="<?php echo $form->title; ?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="search" value="search_full"
                                        class="btn btn-primary pull-right btn-sm checkbox-toggle"> <?php echo $this->lang->line('save'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.check-category').on('click', function () {
            let $this = $(this);
            let $input = $this.closest('label').find('input[name="parent_id"]');
            $input.prop('checked', true);
            let val = $input.val();
            collapseElement($this, val);
        });
        $('.toggle-accordion').on('click', function () {
            let $this = $(this);
            let val = $this.val();
            collapseElement($this, val);
        });
        function collapseElement($this, val){
            let $panel = $this.closest('.panel');
            let parent = $panel.data('parent');
            let element = $('#collapse-' + val);
            $('[data-parent=' + parent + ']').each(function () {
                $(this).find('.panel-collapse').not('#collapse-' + val).collapse('hide');
            });
            element.collapse('show');
        }
    });
</script>

<style type="text/css">
    .category-accordion .panel{
        margin-bottom: 0;
        background-color: #fff;
        border: none;
        /* border-radius: 0px; */
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .category-accordion input[type="radio"]{
        margin-right: 5px;
    }
</style>