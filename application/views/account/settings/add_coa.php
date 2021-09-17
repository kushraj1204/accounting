<script src="<?php echo base_url() . 'backend/js/jquery.chained.js' ?>" type="text/javascript"></script>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('account_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9 align-center">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $coa->id ? "" :  $this->lang->line('new')." " ;
                            echo $this->lang->line(strtolower($type)) ?>
                        </h3>

                    </div>
                    <form role="form" id="add_coa" action="<?php echo site_url('account/settings/save_coa') ?>"
                          class="form-horizontal" method="post">

                        <div class="box-body">
                            <?php if ($type == 'Charge/ Tax' || $coa->type == 5) { ?>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo "Rate"; ?></label><small class="req">*</small>
                                    <div class="col-md-7">
                                        <input required type="number" step="0.001" min="0" class="form-control"
                                               id="rate" name="rate"
                                               placeholder="Rate(in %)"
                                               value="<?php echo set_value('rate', $coa->rate); ?>">
                                        <span class="text text-danger rate_error"></span>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line(strtolower($type)) ?> <?php echo $this->lang->line('name') ; ?></label><small class="req">*</small>
                                <div class="col-md-9">
                                    <input required type="text" class="form-control" id="name" name="name"
                                           placeholder="Name"
                                           value="<?php echo set_value('name', $coa->name); ?>">
                                    <?php echo form_error('name', '<span class="text-danger pull-right">', '</span>'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line(strtolower($type)) . " ".$this->lang->line('category');; ?></label><small class="req">*</small>
                                <div class="col-md-5">
                                    <select required class="custom-select form-control" id="category" name="category">
                                        <option value="">--</option>
                                        <?php foreach ($categories as $category){?>
                                            <option value="<?php echo $category->id;?>" <?php echo $category->id == $coa->category ? "selected" : '';?>><?php echo $category->title;?></option>
                                        <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('category'); ?></span>
                                </div>
                            </div>
                            <?php if($this->level >= 4){?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line('sub_category_1'); ?></label><small class="req">*</small>
                                <div class="col-md-5">
                                    <select required class="custom-select form-control" id="subcategory1"
                                            name="subcategory1">
                                        <option value="">--</option>
                                        <?php foreach ($subcategories as $category){?>
                                            <option value="<?php echo $category->id;?>" data-chained="<?php echo $category->parent_id;?>" <?php echo $category->id == $coa->subcategory1 ? "selected" : '';?>><?php echo $category->title;?></option>
                                        <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('subcategory1'); ?></span>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if($this->level >= 5){?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line('sub_category_2'); ?></label><small class="req">*</small>
                                <div class="col-md-5">
                                    <select class="custom-select form-control" id="subcategory2"
                                            name="subcategory2">
                                        <option value="">--</option>
                                        <?php foreach ($subcategories as $category){?>
                                            <option value="<?php echo $category->id;?>" data-chained="<?php echo $category->parent_id;?>" <?php echo $category->id == $coa->subcategory2 ? "selected" : '';?>><?php echo $category->title;?></option>
                                        <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('subcategory2'); ?></span>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line(strtolower($type)) ?> <?php echo $this->lang->line('code') ?></label><small class="req">*</small>
                                <div class="col-md-9">
                                    <input required type="text" class="form-control" id="code" name="code"
                                           placeholder="Unique code"
                                           value="<?php echo set_value('code', $coa->code); ?>">
                                    <span class="text-danger"><?php echo form_error('code'); ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $this->lang->line(strtolower($type)) . " ".  $this->lang->line('description') ; ?>
                                </label>
                                <div class="col-md-9">
                                <textarea rows="5" class="form-control" id="description" name="description"
                                ><?php echo set_value('description', $coa->description); ?></textarea>
                                </div>
                            </div>


                            <?php  if(strtolower($type)=='assets'){  ?>
                            <div class="form-group">
                                <label for="is_bank" class="col-md-3 control-label"><?php echo $this->lang->line('is_bank'); ?>
                                </label>
                                <div class="col-md-1">
                                    <input type="checkbox" id="is_bank" name="is_bank"
                                           value="<?php echo isset($coa->is_bank) ? $coa->is_bank : 0; ?>"
                                        <?php if (isset($coa) && $coa->is_bank == 1) echo 'checked="checked"'; ?> >
                                    <span class="text text-danger status_error"></span>
                                </div>


                                <label for="is_default_bank" class="col-md-3 control-label"><?php echo $this->lang->line('default_bank'); ?>
                                </label>
                                <div class="col-md-1">
                                    <input type="checkbox" id="is_default_bank" name="is_default_bank"
                                           value="<?php echo isset($coa->is_defaultBank) ? $coa->is_defaultBank : 0; ?>"
                                        <?php if (isset($coa) && $coa->is_defaultBank == 1) echo 'checked="checked"'; ?> >
                                    <span class="text text-danger status_error"></span>
                                </div>
                            </div>


                            <?php }?>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" name="submit" value="submit"
                                            class="btn btn-primary pull-right btn-sm checkbox-toggle"> <?php echo $this->lang->line('save'); ?></button>

                                </div>
                            </div>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="type" value="<?php echo $coa->type ? $coa->type : $typeid; ?>">
                            <input type="hidden" name="id" value="<?php echo set_value('id', $coa->id); ?>">

                        </div>
                    </form>
                </div>
            </div>
        </div>


    </section>
</div>
<script>
    $(document).ready(function () {

        $("#subcategory1").chained("#category");
        $("#subcategory2").chained("#subcategory1");

        $('#status').change(function () {
            $(this).val($(this).attr('checked') ? '1' : '0');
        });


    });
</script>