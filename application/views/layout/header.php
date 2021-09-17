<!DOCTYPE html>
<html <?php echo $this->customlib->getRTL(); ?>>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $this->customlib->getAppName(); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#424242" />
    <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">
    <?php
    $this->load->view('layout/theme');
    ?>
    <?php
    if ($this->customlib->getRTL() != "") {
    ?>
        <!-- Bootstrap 3.3.5 RTL -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css" />
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/AdminLTE-rtl.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />
    <?php
    }
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
    <!--file dropify-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
    <!--file nprogress-->
    <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">

    <!--print table-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!--print table mobile support-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
    <script src="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.js"></script>
    <script src="<?php echo base_url(); ?>backend/datepicker/date.js"></script>
    <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
    <!-- nepaliDatePicker -->
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/npdatepicker/nepaliDatePicker.min.css">
    <script src="<?php echo base_url(); ?>backend/npdatepicker/jquery.nepaliDatePicker.min.js"></script>
    <!-- fullCalendar -->
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">
    <script src="<?php echo base_url(); ?>backend/custom/handlebars.js"></script>
    <!-- select2 -->
    <link href="<?php echo base_url(); ?>backend/select2/select2.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>backend/select2/select2.min.js"></script>
    <script type="text/javascript">
        Handlebars.registerHelper('ifEquals', function(arg1, arg2, options) {
            return (arg1 == arg2) ? options.fn(this) : options.inverse(this);
        });
    </script>

    <script type="text/javascript">
        var baseurl = "<?php echo base_url(); ?>";
        var datechooser = "<?php echo $this->setting_model->getDatechooser(); ?>";
        var current_language = "<?php echo strtolower($this->config->item('language')); ?>";
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and me/
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->

</head>

<body class="hold-transition skin-blue fixed sidebar-mini">
    <div class="wrapper">
        <header class="main-header" id="alert">
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="col-md-5 col-sm-3 col-xs-5">
                    <span href="#" class="sidebar-session">
                        <?php echo  "Accounting System"; ?>
                    </span>
                </div>
                <div class="col-md-7 col-sm-9 col-xs-7">
                    <div class="pull-right">

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav headertopmenu">




                                <?php
                                $file = "";
                                $result = $this->customlib->getUserData();

                                $image = $result["image"];
                                $role = $result["user_type"];
                                $id = $result["id"];
                                if (!empty($image)) {

                                    $file = "uploads/staff_images/" . $image;
                                } else {

                                    $file = "uploads/student_images/no_image.png";
                                }
                                ?>
                                <li class="dropdown user-menu">
                                    <a class="dropdown-toggle" style="padding: 15px 13px;" data-toggle="dropdown" href="#" aria-expanded="false">
                                        <img src="<?php echo base_url() . $file; ?>" class="topuser-image" alt="User Image">
                                    </a>
                                    <ul class="dropdown-menu dropdown-user menuboxshadow">
                                        <li>
                                            <div class="sstopuser">
                                                <div class="ssuserleft">
                                                    <a href="#"><img src="<?php echo base_url() . $file; ?>" alt="User Image"></a>
                                                </div>

                                                <div class="sstopuser-test">
                                                    <h4 style="text-transform: capitalize;"><?php echo $this->customlib->getAdminSessionUserName(); ?></h4>
                                                    <h5><?php echo $role; ?></h5>
                                                    <!-- <div class="sspass pt15"><a class="pull-right" href="<?php //echo base_url()."admin/staff/profile/".$id         
                                                                                                                ?>"><i class="fa fa-user"></i> My Profile</a></div>   -->
                                                </div>

                                                <div class="divider"></div>
                                                <div class="sspass">
                                                    <a class="pl25" href="<?php echo base_url(); ?>admin/admin/changepass" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line("Change Password"); ?>"><i class="fa fa-key"></i><?php echo $this->lang->line('password'); ?></a> <a class="pull-right" href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out fa-fw"></i><?php echo $this->lang->line('logout'); ?></a>
                                                </div>
                                            </div>
                                            <!--./sstopuser-->
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <?php $this->load->view('layout/sidebar'); ?>