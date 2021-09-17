<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('account_head_import'); ?> Preview</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" style="padding:5px;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Type: <?php echo ucfirst($type); ?></h3>

                        <?php
                        if (!empty($data)):
                            ?>
                            <div class="pull-right box-tools">
                                <form method="post" onsubmit="return confirm('Are you sure?');"
                                      action="<?php echo site_url('account/settings/import_confirm') ?>">
                                    <input type="hidden" name="data" value='<?php echo json_encode($data); ?>'>
                                    <input type="hidden" name="type" value='<?php echo $typeKey; ?>'>
                                    <button class="btn btn-primary btn-sm"><?php echo $this->lang->line('confirm_import'); ?></button>
                                </form>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <div>  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                        <br/>
                        1. <?php echo $this->lang->line('person_import_preview_note_1'); ?><br/>
                        2. <?php echo $this->lang->line('person_import_preview_note_2'); ?><br/>
                        3. <?php echo $this->lang->line('person_import_preview_note_3'); ?><br/>
                        4. <?php echo $this->lang->line('person_import_preview_note_4'); ?><br/>

                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <?php foreach ($fields as $key => $value) {

                                        $add = "<span class=text-red>*</span>";
                                      if($value=='error' || $value=='error_cause'){
                                          $add='';
                                      }
                                        //                                        ?>
                                        <th><?php echo $add . "<span>" . $this->lang->line($value) . "</span>"; ?></th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($data as $datum):
                                    $color = '#AFFADC';
                                    if ($datum['error'] == 1) {
                                        $color = '#F78383';
                                    }
                                    ?>
                                    <tr style="background-color: <?php echo $color ?>">
                                        <?php
                                        foreach ($datum as $key => $value):
                                            ?>

                                            <td><?php echo $value; ?></td>
                                        <?php
                                        endforeach;
                                        ?>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>