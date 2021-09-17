<!-- Main content -->
<section class="content">
    <?php echo form_open('account/personnel/save_personnel_form', array('id' => 'personnel_form')); ?>
    <div class="row">
        <input type="hidden" id="type" name="type" class="form-control">
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('name'); ?></label>
            <input type="text" id="name" name="name" class="form-control">
            <span class="text-danger"><?php echo form_error('name'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('email'); ?></label> <small class="text-muted"><?php echo $this->lang->line('optional'); ?></small>
            <input type="email" id="email" name="email" class="form-control">
            <span class="text-danger"><?php echo form_error('email'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('contact'); ?></label>
            <input type="number" id="contact" name="contact" class="form-control">
            <span class="text-danger"><?php echo form_error('contact'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('code'); ?></label>
            <input type="text" id="code" name="code" class="form-control">
            <span class="text-danger"><?php echo form_error('code'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('vat_/_pan'); ?></label>
            <input type="text" id="pan" name="pan" class="form-control">
            <span class="text-danger"><?php echo form_error('pan'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('address'); ?>
            <textarea name="address" class="form-control"></textarea>
            <span class="text-danger"><?php echo form_error('address'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('balance'); ?></label>
            <input type="text" id="balance" name="balance" class="form-control">
            <span class="text-danger"><?php echo form_error('balance'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('description'); ?></label> <small class="text-muted"><?php echo $this->lang->line('optional'); ?></small>
            <textarea name="description"
                      class="form-control"></textarea>
            <span class="text-danger"><?php echo form_error('description'); ?></span>
        </div>
        <div class="col-md-6 form-group">
            <label><?php echo $this->lang->line('balance_type'); ?></label>
            <div class="form-check">
                <input type="radio" id="balance_type_debit" name="balance_type"
                       class="form-check-input"
                       value="<?php echo $this->lang->line('debit'); ?>" checked>
                <label class="form-check-label"
                       for="balance_type_debit"><?php echo $this->lang->line('debit_balance'); ?></label>
                <input type="radio" id="balance_type_credit" name="balance_type"
                       class="form-check-input"
                       value="<?php echo $this->lang->line('credit'); ?>">
                <label class="form-check-label"
                       for="balance_type_credit"><?php echo $this->lang->line('credit_balance'); ?></label>
            </div>

            <span class="text-danger"><?php echo form_error('balance_type'); ?></span>
        </div>
    </div>
    <button type="submit" name="submit" value="submit"
            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"
            class="btn btn-primary btn-sm form-submit"> <?php echo $this->lang->line('save'); ?></button>
    <?php echo form_close(); ?>
</section>
