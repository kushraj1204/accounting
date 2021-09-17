<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('personnel_information'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i
                                    class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="pull-right box-tools">
                            <a class="btn btn-primary btn-sm" href="<?php echo site_url('account/personnel/exportformat') ?>">
                                <i class="fa fa-download"></i> <?php echo $this->lang->line('dl_sample_import'); ?>

                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <div>  <?php echo show_message() ?> </div> <?php } ?>
                        <br/>
                        1. <?php echo $this->lang->line('person_import_note_1') ?><br/>
                        2. <?php echo $this->lang->line('person_import_note_2') ?><br/>
                        3. <?php echo $this->lang->line('person_import_note_3') ?><br/>
                        4. <?php echo $this->lang->line('person_import_note_4') ?>

                        <hr/>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sampledata">
                            <thead>
                            <?php $add = "<span class=text-red>*</span>"; ?>
                            <tr>
                                <?php foreach ($fields as $key => $value) {

                                    $add = "<span class=text-red>*</span>"; ?>

                                    <th><?php echo $add . "<span>" . $this->lang->line($value) . "</span>"; ?></th>
                                <?php } ?>


                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <?php foreach ($fields as $key => $value) {
                                    ?>
                                    <td><?php echo $this->lang->line('sample_data'); ?></td>
                                <?php } ?>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <hr/>
                    <form action="<?php echo site_url('account/personnel/importPersonnel') ?>" id="employeeform" name="employeeform"
                          method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small
                                                class="req"> *</small>
                                        <div><input required class="filestyle form-control" type='file' name='fileupload'
                                                    id="fileupload"
                                                    size='20'/>
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('type'); ?></label><small class="req"> *</small>
                                        <select  id="type" name="type" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($types as $key=>$type) {
                                                ?>
                                                <option value="<?php echo $type ?>"><?php echo $type ?></option>
                                                <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('type'); ?></span>
                                    </div></div>
                                <div class="col-md-6"></div>
                                <div class="col-md-6 pt20">
                                    <button type="submit"
                                            class="btn btn-info pull-right"><?php echo $this->lang->line('import_personnel'); ?></button>
                                </div>

                            </div>
                        </div>


                    </form>

                    <div>


                    </div>
                </div>
    </section>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function () {
        $("#sampledata").DataTable({
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false,
        });

        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>