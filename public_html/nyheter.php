<?php
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include_once './src/db/dbConnection.php';
include_once './src/db/dbConfig.php';
try {
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
} catch (PDOException $e) {
    return $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>C4 SPRÅKPARTNER AB - Nyheter</title>

    <meta name="keywords" content=""/>
    <meta name="description" content="">
    <meta name="author" content="C4 SPRÅKPARTNER AB">

    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Web Fonts  -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light"
          rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/simple-line-icons/css/simple-line-icons.min.css">
    <link rel="stylesheet" href="vendor/owl.carousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="vendor/owl.carousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.min.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/theme-elements.css">
    <link rel="stylesheet" href="css/theme-animate.css">

    <!-- Current Page CSS -->
    <link rel="stylesheet" href="vendor/rs-plugin/css/settings.css" media="screen">
    <link rel="stylesheet" href="vendor/rs-plugin/css/layers.css" media="screen">
    <link rel="stylesheet" href="vendor/rs-plugin/css/navigation.css" media="screen">
    <link rel="stylesheet" href="vendor/circle-flip-slideshow/css/component.css" media="screen">

    <!-- Skin CSS -->
    <link rel="stylesheet" href="css/skins/default.css">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <!-- Head Libs -->
    <script src="vendor/modernizr/modernizr.min.js"></script>

</head>
<body>

<div class="body">
    <!--========================================================
                        HEADER
    =========================================================-->
    <?php include './partials/shared/header.php'; ?>
    <!--========================================================
                              CONTENT
    =========================================================-->
    <div role="main" class="main">
        <section class="page-header page-header-light p-sm">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <h2 class="heading-quaternary m-sm">Om oss </h2>
                    </div>
                    <div class="col-md-10">
                        <?php include 'subHeader.php'; ?>
                    </div>
                </div>

            </div>
        </section>
        <div class="container">
            <div class="row mt-xlg">
                <div class="col-md-12">
                    <?php
                    $statement = $con->prepare('SELECT * FROM t_newsLetter WHERE n_ID=:id');
                    $statement->bindParam(':id', $_GET['id']);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    $row = null;
                    if ($statement->rowCount() > 0) {
                        $row = $statement->fetch();
                        $date = new DateTime($row->n_time);
                    }
                    ?>
                    <h1><?php echo $row->n_title ?></h1>
                    <hr />
                    <p><span class="glyphicon glyphicon-time"></span> Publicerad den <?php echo $date->format('Y-m-d H:i:s'); ?></p>
                    <hr />
                    <div>
                        <?php echo $row->n_text; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--========================================================
                              FOOTER
    =========================================================-->
    <?php include './partials/shared/footer.html'; ?>

</div>

<!-- Vendor -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/jquery.appear/jquery.appear.min.js"></script>
<script src="vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="vendor/jquery-cookie/jquery-cookie.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/common/common.min.js"></script>
<script src="vendor/jquery.validation/jquery.validation.min.js"></script>
<script src="vendor/jq-validate/additional-methods.min.js"></script>
<script src="vendor/jquery.stellar/jquery.stellar.min.js"></script>
<script src="vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<script src="vendor/jquery.gmap/jquery.gmap.min.js"></script>
<script src="vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
<script src="vendor/isotope/jquery.isotope.min.js"></script>
<script src="vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="vendor/vide/vide.min.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

<!-- Current Page Vendor and Views -->
<script src="vendor/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="vendor/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="vendor/circle-flip-slideshow/js/jquery.flipshow.min.js"></script>
<script src="js/views/view.home.js"></script>


<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>

<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
<!-- form wizard -->
<script src="js/wizard.js"></script>

<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.sv.js"></script>

<script src="js/views/view.orderTranslation.js"></script>

<!-- Google Analytics: Change UA-XXXXX-X to be your site's ID. Go to http://www.google.com/analytics/ for more information.
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-12345678-1', 'auto');
    ga('send', 'pageview');
</script>
 -->

</body>
</html>
