<style>
    .product-info {
        margin-left: 0 !important;
    }
    .product-description {
        color: #181818 !important;
    }
    .product-description > .fa {
        margin-right: 5px;
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><?php echo $title;?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sales and Marketing</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?php
                            foreach ($team['sales'] as $s):
                                ?>
                                <li class="item">
                                    <div class="product-info">
                                        <span class="product-title"><?php echo $s['name']; ?></span>
                                        <span class="product-description"><?php echo $s['post']; ?></span>
                                        <span class="product-description">Contact No.: <?php echo $s['phone']; ?></span>
                                        <span class="product-description"><?php echo $s['email']; ?></span>
                                    </div>
                                </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Support and IT</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <?php
                            foreach ($team['support'] as $s):
                                ?>
                                <li class="item">
                                    <div class="product-info">
                                        <span class="product-title"><?php echo $s['name']; ?></span>
                                        <span class="product-description"><?php echo $s['post']; ?></span>
                                        <span class="product-description">Contact No.: <?php echo $s['phone']; ?></span>
                                        <span class="product-description"><?php echo $s['email']; ?></span>
                                    </div>
                                </li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>