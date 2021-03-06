<!DOCTYPE html>
<html>
<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>C4 SPRÅKPARTNER AB - Kontakta oss</title>

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
                    <div class="col-md-3">
                        <h2 class="heading-quaternary m-md">Kontakta oss</h2>
                    </div>
                    <div class="col-md-9">
                        <?php include 'subHeader.php'; ?>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="center">Hos oss kan ni  boka tolk dygnet runt.
Ni kan boka tolk via vår web inloggning eller på telefon : <a href="tel:+46-10-562-4210">(010) 562 42 10</a>
Observera , Övriga tjänster nås under kontorstid.</h4>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-success hidden" id="contactSuccess">
                        <strong>Klart!</strong> Ditt meddelande är sänt!
                    </div>

                    <div class="alert alert-danger hidden" id="contactError">
                        <strong>Fel!</strong> Det uppstod ett fel när meddelandet skulle skickas.
                    </div>
                    <form id="contactForm" action="src/services/contact.php" method="POST">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="name">Namn *</label>
                                    <input type="text"
                                    data-rule-required="true"
                                           data-rule-minlength="3" data-rule-maxlength="90"
                                           data-msg-minlength="Fält namn bör innehålla mer än 3 tecken."
                                           data-msg-maxlength="Fält namn bör innehålla mindre än 90." maxlength="90"
                                           class="form-control" name="name" id="name" data-msg-required="Skriv ditt namn.">
                                </div>
                                <div class="col-md-4">
                                    <label for="companyName">Företagsnamn *</label>
                                    <input type="text" data-msg-required="Skriv ditt företagsnamn."
                                           data-rule-minlength="3" data-rule-maxlength="90"
                                           data-msg-minlength="Fält företagsnamn bör innehålla mer än 3 tecken."
                                           data-msg-maxlength="Fält företagsnamn bör innehålla mindre än 90."
                                           maxlength="90" class="form-control" name="companyName"
                                           id="companyName"  data-rule-required="true">
                                </div>
                                <div class="col-md-4">
                                    <label for="phone">Telefon *</label>
                                    <input type="text" data-rule-required="true" data-msg-number="Det ska feiyll nummer."
                                           data-rule-minlength="9" data-rule-maxlength="11"
                                           data-msg-required="Skriv ditt telefon nummer."
                                           data-msg-minlength="Fältet Telefon bör innehålla mer än 9."
                                           data-msg-maxlength="Fältet Telefon bör innehålla mindre än 11."
                                           maxlength="11" data-rule-number="true" class="form-control"
                                           name="phone" id="phone"  >

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="subject">Ämne *</label>
                                    <input type="text" data-rule-required="true" data-msg-required="Skriv ditt e-post ämne."
                                           data-rule-minlength="3" data-rule-maxlength="90"
                                           data-msg-minlength="Fältet ämne bör innehålla mer än 3 tecken."
                                           data-msg-maxlength="Fält ämne bör innehålla mindre än 90." maxlength="90"
                                           class="form-control" name="subject" id="subject" >
                                </div>
                                <div class="col-md-6">
                                    <label for="email">E-post *</label>
                                    <input type="email" data-rule-required="true" data-msg-required="Skriv ditt e-post adress."
                                           data-rule-email="true" data-rule-maxlength="90"
                                           data-msg-email="Den här e-post är inte giltig."
                                           data-msg-maxlength="Fältet e-postadress ska innehålla mindre än 90 tecken."
                                           maxlength="90" class="form-control" name="email" id="email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="message">Meddelande *</label>
                                    <textarea maxlength="5000" data-rule-required="true" data-msg-required="Skriv ditt medelande."
                                              data-rule-minlength="6" data-rule-maxlength="5000"
                                              data-msg-minlength="Fältet medelande bör innehålla mer än 6 tecken."
                                              rows="10" class="form-control" name="message" id="message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                              <button type="submit" name="submitForm" class="btn btn-primary btn-lg mb-xlg"
                                     data-loading-text="Loading...">Skicka</button>
                              <button type="button" name="resetForm" id="resetForm" class="btn btn-primary btn-lg mb-xlg">Rensa</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">


                    <h4 class="heading-primary">Vårt Kontor</h4>


                    <div id="googlemaps" class="google-map small mt-xs"></div>
                    <ul class="list list-icons list-icons-style-3 mt-sm">
                        <li><i class="fa fa-phone"></i> <strong>Telefon:</strong> <a href="tel:+46-10-562-4210">(010) 562 42 10</a></li>
                        <li><i class="fa fa-envelope"></i> <strong>E-Post:</strong> <a
                                href="mailto: kundtjanst@c4tolk.se"> kundtjanst@c4tolk.se</a></li>
                                <li><i class="fa fa-map-marker"></i> <strong>Besöksadress: Nya boulevarden 10 (Våning 3), 291 31 Kristianstad</strong>
                                </li>
                        <li><i class="fa fa-envelope"></i> <strong>Postadress: BOX 21, 291 21 Kristianstad</strong></li>
                    </ul>


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

<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>

<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
<!-- form wizard -->
<script src="js/wizard.js"></script>

<!-- Current Page Vendor and Views -->
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.sv.js"></script>

<script src="js/views/view.orderTranslation.js"></script>
<script src="js/views/view.contact.js"></script>

<script src="http://maps.google.com/maps/api/js?key=AIzaSyD2wZMCzEnceJsC9kC_-4t7UZWBDYIDu8s"></script>
<script>

    // Map Markers
    var mapMarkers = [{
        address: "Industrigatan 2A, 291 36 Kristianstad",
        //html: "<strong>Kontor</strong><br>Nya Boulevarden 10,291 31 Kristianstad",
        icon: {
            image: "img/marker.png",
            iconsize: [46, 46],
            iconanchor: [23, 46]
        },
        popup: true
    }],

    // Map Initial Location
      initLatitude = 56.042898,
      initLongitude = 14.156860,

    // Map Extended Settings
      mapSettings = {
        controls: {
            draggable: (($.browser.mobile) ? false : true),
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: true
        },
        scrollwheel: false,
        markers: mapMarkers,
        latitude: initLatitude,
        longitude: initLongitude,
        zoom: 16
    },
     map = $("#googlemaps").gMap(mapSettings),

    // Map Center At
     mapCenterAt = function (options, e) {
        e.preventDefault();
        $("#googlemaps").gMap("centerAt", options);
    };

    // Borders
    /*$("#googlemapsBorders").gMap({
     controls: {
     draggable: (($.browser.mobile) ? false : true),
     panControl: true,
     zoomControl: true,
     mapTypeControl: true,
     scaleControl: true,
     streetViewControl: true,
     overviewMapControl: true
     },
     scrollwheel: false,
     latitude: 37.09024,
     longitude: -95.71289,
     zoom: 3
     });*/


</script>


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
