<ul class="sessionul fixedmenu">
    <?php

    ?>
    <li class="removehover">
        <!--            <a data-toggle="modal" data-target="#sessionModal">--><?php //echo $this->lang->line('current_session') . ": " . ($this->setting_model->getDatechooser() == 'bs'?$this->setting_model->getCurrentSessionNameBS():$this->setting_model->getCurrentSessionName()); 
                                                                                ?>
        <!--<i class="fa fa-pencil pull-right"></i></a>-->
        <a href="javascript:void()">
            <script>
                document.write(new Date().toLocaleDateString("en-US"))
            </script>
        </a>
    </li>

    <li class="dropdown">
        <a class="dropdown-toggle drop5" data-toggle="dropdown" href="#" aria-expanded="false">
            <?php echo $this->lang->line('accounting'); ?> <span class="glyphicon glyphicon-th pull-right"></span>
        </a>

    </li>
</ul>