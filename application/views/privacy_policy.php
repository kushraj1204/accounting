<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $settings['name']; ?></title>
    <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <?php
    $this->load->view('layout/theme');
    ?>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <span class="sidebar-session"><?php echo $settings['name']; ?></span>
                </div>
            </div>
            <!-- /.container-fluid -->
        </nav>
    </header>
    <div class="jumbotron">
        <div class="container">
            <h1>Privacy Policy</h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>We at <?php echo $_SERVER['HTTP_HOST']; ?> take due care and we are committed to protect your
                    personal information. This privacy policy tells you how we use personal information collected at
                    this site. Please read this privacy policy before using the site or submitting any personal
                    information. By using the site, you are accepting the practices described in this privacy policy. We
                    reserve the right to change the policy at any point of time and its your responsibility to keep
                    checking the privacy policy. By visiting and using the site you confirm that you are aware of the
                    privacy policy.</p>
            </div>
            <div class="col-md-12">
                <h2>Collection of Information</h2>
                <p>We collect personal information like names, postal addresses, email addresses, mobile no., DOB etc.
                    which are required to provide you normal service. This information is only used to fulfill your
                    specific request, unless you give us
                    permission to use it in another manner. We don’t sell or distribute your personal information to any
                    third party until unless there is consent from you. We don’t store any Credit card information on
                    our website. The user enters the card details on the third party Payment gateway provider. We should
                    not be held responsible in any manner for card information provided on the third party website.</p>
            </div>
            <div class="col-md-12">
                <h2>Cookie/Tracking Technology</h2>
                <p>The Site may use cookie and tracking technology depending on the features offered. Cookie and
                    tracking technology are useful for gathering information such as browser type and operating system,
                    tracking the number of visitors to the Site, and understanding how visitors use the Site. Cookies
                    can also help customize the Site for visitors.</p>
            </div>
            <div class="col-md-12">
                <h2>Distribution of Information</h2>
                <p>We may share information with governmental agencies or other companies assisting us in fraud
                    prevention or investigation. We may do so when:</p>
                <ol>
                    <li>Permitted or required by law; or,</li>
                    <li>Trying to protect against or prevent actual or potential fraud or unauthorized transactions;
                        or,
                    </li>
                    <li>Investigating fraud which has already taken place. The information is not provided to these
                        companies for marketing purposes. We will also use your personal information to keep you posted
                        about your transactions, payments related queries/alerts and promotional activities on our
                        products and services.
                    </li>
                </ol>
            </div>
            <div class="col-md-12">
                <h2>Commitment to Data Security</h2>
                <p>Your personally identifiable information is kept secure. Only authorized employees have
                    access to this information.</p>
            </div>
            <div class="col-md-12">
                <h2>Privacy Contact Information</h2>
                <p>If you have any questions, concerns, or
                    comments about our privacy policy you may contact us by e-mail at <?php echo $settings['email']; ?></p>
            </div>
        </div>
    </div>
    <footer class="main-footer">
        &copy; <?php echo date('Y'); ?>
        <?php echo $settings['name']; ?> <?php echo $this->customlib->getAppVersion(); ?>
    </footer>
</div>
</body>
</html>