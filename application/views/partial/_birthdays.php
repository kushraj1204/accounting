<style>
    .users-list>li {
        width: 150px;
    }
    .users-list > li img {
        border-radius: 50%;
        max-width: 120px;
        height: 120px;
    }
</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $this->lang->line("Who has birthday today?");?></h3>
    </div>
    <div class="box-body">
        <?php
        if (count($birthdays) > 0):
            ?>
            <ul class="users-list clearfix">
                <?php
                foreach ($birthdays as $birthday):
                    ?>
                    <li>
                        <img src="<?php echo base_url($birthday['image']); ?>"
                             alt="<?php echo $birthday['firstname']; ?>">
                        <span class="users-list-name"><?php echo $birthday['firstname'] . ' ' . $birthday['lastname']; ?></span>
                        <span class="users-list-date"><?php echo $birthday['class'] . ' ' . $birthday['section']; ?></span>
                    </li>
                <?php
                endforeach;
                ?>
            </ul>
        <?php
        else:
            echo $this->lang->line("No one has birthday today");
        endif;
        ?>
    </div>
</div>