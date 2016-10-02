<?php
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

    <title>STÖ AB - Sarvari tolkning och översättning</title>

    <meta name="keywords" content=""/>
    <meta name="description" content="">
    <meta name="author" content="STÖ AB">

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
    <link rel="stylesheet" href="css/theme-blog.css">

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

        <div class="slider-container light rev_slider_wrapper">
            <div id="revolutionSlider" class="slider rev_slider" data-plugin-revolution-slider
                 data-plugin-options='{"gridwidth": 1170, "gridheight": 400, "disableProgressBar": "on"}'>
                <ul>
                    <li data-transition="fade">

                        <img src="img/slides/1.jpg"
                             alt=""
                             data-bgposition="center center"
                             data-bgfit="cover"
                             data-bgrepeat="no-repeat"
                             data-kenburns="on"
                             data-duration="9000"
                             data-ease="Linear.easeNone"
                             data-scalestart="150"
                             data-scaleend="100"
                             data-rotatestart="0"
                             data-rotateend="0"
                             data-offsetstart="0 0"
                             data-offsetend="0 0"
                             data-bgparallax="10"
                             class="rev-slidebg">

                        <div class="tp-caption"
                             data-x="280"
                             data-y="225"
                             data-start="1000"
                             data-transform_in="x:[-300%];opacity:0;s:500;"><img
                                src="img/slides/slide-title-border-blue.png" alt=""></div>

                        <div class="tp-caption featured-label heading-primary"
                             data-x="center"
                             data-y="210"
                             data-start="500"
                             style="font-size: 50px; z-index: 5; color: #e78900 !important; text-shadow: 0 2px 2px #424242;"
                             data-transform_in="y:[100%];s:500;"
                             data-transform_out="opacity:0;s:500;">DIN SPRÅKPARTNER
                        </div>

                        <div class="tp-caption"
                             data-x="860"
                             data-y="225"
                             data-start="1000"
                             data-transform_in="x:[300%];opacity:0;s:500;"><img
                                src="img/slides/slide-title-border-blue.png" alt=""></div>

                    </li>
                    <li data-transition="fade">
                        <img src="img/slides/2.jpg"
                             alt=""
                             data-bgposition="right center"
                             data-bgpositionend="center center"
                             data-bgfit="cover"
                             data-bgrepeat="no-repeat"
                             data-kenburns="on"
                             data-duration="9000"
                             data-ease="Linear.easeNone"
                             data-scalestart="110"
                             data-scaleend="100"
                             data-rotatestart="0"
                             data-rotateend="0"
                             data-offsetstart="0 0"
                             data-offsetend="0 0"
                             data-bgparallax="10"
                             class="rev-slidebg">

                        <div class="tp-caption featured-label heading-primary"
                             data-x="center"
                             data-y="170"
                             data-start="1000"
                             data-transform_idle="o:1;"
                             data-transform_in="y:[100%];z:0;rZ:-35deg;sX:1;sY:1;skX:0;skY:0;s:600;e:Power4.easeInOut;"
                             data-transform_out="opacity:0;s:500;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-splitin="chars"
                             data-splitout="none"
                             data-responsive_offset="on"
                             style="font-size: 50px; line-height: 55px; color: #e78900 !important; text-shadow: 0 2px 2px #424242;"
                             data-elementdelay="0.05">VI SKAPAR KOMMUNIKATION<br/>MELLAN SPRÅK OCH KULTURER
                        </div>

                    </li>
                    <li data-transition="fade">

                        <img src="img/slides/3.jpg"
                             alt=""
                             data-bgposition="center center"
                             data-bgfit="cover"
                             data-bgrepeat="no-repeat"
                             data-kenburns="on"
                             data-duration="9000"
                             data-ease="Linear.easeNone"
                             data-scalestart="150"
                             data-scaleend="100"
                             data-rotatestart="0"
                             data-rotateend="0"
                             data-offsetstart="0 0"
                             data-offsetend="0 0"
                             data-bgparallax="10"
                             class="rev-slidebg">

                        <div class="tp-caption featured-label heading-primary"
                             data-x="center"
                             data-y="200"
                             data-start="400"
                             style="font-size: 50px; z-index: 5; color: #e78900 !important; text-shadow: 0 2px 2px #424242;"
                             data-transform_in="y:[100%];s:500;"
                             data-transform_out="opacity:0;s:500;">KVALITETEN I FÖRETAGET UTGÖRS <br/>AV NOGGRANT
                            UTVALDA TOLKAR
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="home-intro light mb-none pt-sm" id="home-intro">
            <div class="container">
                <?php include 'subHeader.php'; ?>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row mt-xlg">
                <div class="col-md-12 center ">
                    <h1 class="heading-quaternary mt-xlg">Våra Tjänster</h1>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-12 pr-xlg pr-xlg">
                    <ul class="history mr-xlg ml-xlg">
                        <li class="appear-animation " data-appear-animation="fadeInUp">
                            <div class="thumb">
                                <img src="img/tjanster/img01.jpg" alt=""/>
                            </div>
                            <div class="featured-box featured-box-quaternary">
                                <div class="box-content">
                                    <h4 class="heading-quaternary mb-xl"><strong>Tolkning</strong></h4>
                                    <p>Vi erbjuder tolkning som möjliggör kommunikation när språkbarriärerna blir för
                                        stora mellan två eller flera personer. Våra anslutna tolkar har kompetens till
                                        och med auktorisation enligt kammarkollegiet.</p>
                                </div>
                            </div>
                        </li>
                        <li class="appear-animation" data-appear-animation="fadeInUp">
                            <div class="thumb">
                                <img src="img/tjanster/img02.jpg" alt=""/>
                            </div>
                            <div class="featured-box featured-box-secondary">
                                <div class="box-content">
                                    <h4 class="heading-secondary mb-xl"><strong>Översättning</strong></h4>
                                    <p>Vi genomför översättning av all form av dokumentation. Allt från validering av
                                        körkort och betyg till översättning av juridiska dokument, dokument från
                                        myndigheter, medicinska handlingar osv på alla språk.</p>
                                </div>
                            </div>
                        </li>
                        <li class="appear-animation" data-appear-animation="fadeInUp">
                            <div class="thumb">
                                <img src="img/tjanster/img07.jpg" alt=""/>
                            </div>
                            <div class="featured-box featured-box-primary">
                                <div class="box-content">
                                    <h4 class="heading-primary mb-xl"><strong>Språkanalys</strong></h4>
                                    <p>Språkanalys är ett utlåtande från en lingvist, som tillsammans med en språkexpert
                                        med en specifik språkbakgrund lyssnar på en bandinspelning.</p>
                                </div>
                            </div>
                        </li>
                        <li class="appear-animation" data-appear-animation="fadeInUp">
                            <div class="thumb">
                                <img src="img/tjanster/img03.jpg" alt=""/>
                            </div>
                            <div class="featured-box featured-box-tertiary">
                                <div class="box-content">
                                    <h4 class="heading-tertiary mb-xl"><strong>Kurser</strong></h4>
                                    <p>Kurser för nyanlända som ska etablera sig i Sverige. Introduktionskurs för
                                        blivande tolkar.</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <section class="section section-default section-no-border">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 center ">
                        <h1 class="heading-quaternary">Vi Är</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 center">
                        <p class="lead">Ett företag med stora visioner, vi brinner för enkla lösningar
                          och för en god kommunikation. Vi har ett genuint intresse för människor och
                          allas rätt i samhället och för att tillgodose era behov har vi full service dygnet runt.</p>
                        <a class="btn btn-primary" href="omoss.php">Läs mer</a>
                    </div>
                </div>

            </div>
        </section>


        <div class="container">
            <div class="row mt-xlg">
                <div class="col-md-12">
                    <h1 class="center heading-quaternary">Nyheter</h1>
                    <div class="owl-carousel owl-theme show-nav-title top-border"
                         data-plugin-options='{"responsive": {"0": {"items": 1}, "479": {"items": 1}, "768": {"items": 2}, "979": {"items": 3}, "1199": {"items": 3}}, "items": 3, "margin": 10, "loop": false, "nav": true, "dots": false}'>

                        <?php
                        $statement = $con->query('SELECT * FROM t_newsLetter WHERE n_time >= CURRENT_DATE() - 30 AND n_flag=1 ORDER BY n_time DESC');
                        $statement->execute();
                        $statement->setFetchMode(PDO::FETCH_OBJ);
                        if ($statement->rowCount() > 0) {
                            while ($row = $statement->fetch()) {
                                ?>
                                <div>
                                    <div class="recent-posts">
                                        <article class="post">
                                            <div class="date">
                                                <span class="day"><?php $d = new DateTime($row->n_time);
                                echo $d->format('d') ?></span>
                                                <span class="month"><?php $m = new DateTime($row->n_time);
                                echo $d->format('M') ?></span>
                                            </div>
                                            <h4>
                                                <a href="nyheter.php?id=<?php echo $row->n_ID ?>"><?php echo $row->n_title; ?></a>
                                            </h4>
                                            <p>
                                                <?php echo $row->n_postScript; ?>
                                                <a href="nyheter.php?id=<?php echo $row->n_ID ?>" class="read-more">Läs
                                                    mer <i class="fa fa-angle-right"></i></a>
                                            </p>
                                        </article>
                                    </div>
                                </div>
                            <?php

                            }
                        } else {
                            echo '<div><h2>Just nu har vi inte några nyheter!</h2></div>';
                        }
                        ?>
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

<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>


<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>


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
