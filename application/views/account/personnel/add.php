<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-calculator"></i> <?php echo $this->lang->line('accounts'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus-circle"></i> <?php echo $personnel->id > 0 ? $this->lang->line('edit') : $this->lang->line('add'); ?> <?php echo $this->lang->line($type); ?>
                        </h3>

                    </div>


                    <div class="box-body">
                        <?php //echo validation_errors('<div class="alert alert-danger" role="alert">', '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'); ?>
                        <div class="row">
                            <?php echo form_open('account/personnel/save_personnel'); ?>
                            <input type="hidden" name="id" value="<?php echo set_value('id', $personnel->id); ?>"/>
                            <?php if ($type !== '') { ?>
                                <input type="hidden" id="type" name="type" class="form-control"
                                       value="<?php echo set_value('type', $type); ?>">
                            <?php } else { ?>
                                <div class="col-md-6 form-group">
                                    <label><?php echo $this->lang->line('Type'); ?></label>
                                    <small class="req">*</small>
                                    <?php echo form_error('type', '<span class="text-danger text-right">', '</span>'); ?>
                                    <select autofocus="" id="type" name="type" class="form-control">
                                        <option value="customer" <?php echo set_select('type', 'customer'); ?>><?php echo $this->lang->line('customer'); ?></option>
                                        <option value="supplier" <?php echo set_select('type', 'supplier'); ?>><?php echo $this->lang->line('supplier'); ?></option>
                                    </select>
                                </div>
                            <?php } ?>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <?php echo form_error('name', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="name" name="name" class="form-control" required
                                       value="<?php echo set_value('name', $personnel->name); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('email'); ?></label> <small class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</small>
                                <?php echo form_error('email', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?php echo set_value('email', $personnel->email); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('contact'); ?></label>
                                <?php echo form_error('contact', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="number" id="contact" name="contact" class="form-control" required
                                       value="<?php echo set_value('contact', $personnel->contact); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('code'); ?></label>
                                <?php echo form_error('code', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="code" name="code" class="form-control" required <?php echo ($personnel->parent_id != 0) ? 'readonly' : '';?>
                                       value="<?php echo set_value('code', $personnel->code); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('vat_/_pan'); ?></label> <small class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</small>
                                <?php echo form_error('pan', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="pan" name="pan" class="form-control"
                                       value="<?php echo set_value('pan', $personnel->pan); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('address'); ?></label>
                                <?php echo form_error('address', '<span class="text-danger pull-right">', '</span>'); ?>
                                <textarea name="address" required
                                          class="form-control"><?php echo set_value('address', $personnel->address); ?></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('balance'); ?></label>
                                <?php echo form_error('balance', '<span class="text-danger pull-right">', '</span>'); ?>
                                <input type="text" id="balance" name="balance" class="form-control" required
                                       value="<?php echo set_value('balance', $personnel->balance); ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('description'); ?></label> <small class="text-muted">(<?php echo $this->lang->line('optional'); ?>)</small>
                                <?php echo form_error('description', '<span class="text-danger pull-right">', '</span>'); ?>
                                <textarea name="description"
                                          class="form-control"><?php echo set_value('description', $personnel->description); ?></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('balance_type'); ?></label>
                                <?php echo form_error('balance_type', '<span class="text-danger pull-right">', '</span>'); ?>
                                <div class="form-check">
                                    <input type="radio" id="balance_type_debit" name="balance_type"
                                           class="form-check-input"
                                           value="debit" <?php echo $personnel->balance_type == 'debit' ? 'checked' : '';?>>
                                    <label class="form-check-label"
                                           for="balance_type_debit"><?php echo $this->lang->line('debit_balance'); ?></label>
                                    <input type="radio" id="balance_type_credit" name="balance_type"
                                           class="form-check-input"
                                           value="credit" <?php echo $personnel->balance_type == 'credit' ? 'checked' : '';?>>
                                    <label class="form-check-label"
                                           for="balance_type_credit"><?php echo $this->lang->line('credit_balance'); ?></label>
                                </div>

                            </div>
                            <div class="col-md-6 form-group">
                                <label><?php echo $this->lang->line('type'); ?></label>
                                <?php echo form_error('category', '<span class="text-danger pull-right">', '</span>'); ?>
                                <select id="category" name="category" class="form-control" <?php echo ($personnel->parent_id != 0) ? 'readonly style="pointer-events: none"' : '';?>>
                                    <option value="others" <?php echo $personnel->category == 'others' ? 'selected' : '';?>><?php echo $this->lang->line('others'); ?></option>
                                    <option value="student" <?php echo $personnel->category == 'student' ? 'selected' : '';?>><?php echo $this->lang->line('student'); ?></option>
                                    <option value="staff" <?php echo $personnel->category == 'staff' ? 'selected' : '';?>><?php echo $this->lang->line('staff'); ?></option>
                                </select>

                            </div>
                        </div>
                        <button type="submit" name="submit" value="submit"
                                class="btn btn-primary btn-sm checkbox-toggle"> <?php echo $this->lang->line('save'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

