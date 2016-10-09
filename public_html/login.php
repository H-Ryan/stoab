<!DOCTYPE html>
<html>
<head>

    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Tolkning i Kristianstad AB - Logga in</title>

    <meta name="keywords" content=""/>
    <meta name="description" content="">
    <meta name="author" content="Tolkning i Kristianstad AB">

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
                        <h2 class="heading-quaternary m-md">Logga in </h2>
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
                    <div class="featured-boxes">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="loginBox" class="featured-box featured-box-primary align-left m-none">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Kundinloggning</h4>
                                        <form id="frmLogIn">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="customerNumber">Kundnummer</label>
                                                        <input type="text" name="customerNumber"
                                                               class="form-control input-lg"
                                                               id="customerNumber"
                                                               data-rule-maxlength="5"
                                                               data-rule-minlength="5"
                                                               data-rile-digits="true"
                                                               data-rule-required="true"
                                                               data-msg-digits="Fält kundnummer får bara innehålla siffror."
                                                               data-msg-required="Fält kundnummer krävs."
                                                               data-msg-minlength="Fält kundnummer bör innehålla 5 tecken."
                                                               data-msg-maxlength="Fält kundnummer bör innehålla 5 tecken."
                                                               maxlength="6">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="password">Lösenord</label>
                                                        <input type="password" name="password" value=""
                                                               class="form-control input-lg"
                                                               id="password"
                                                               data-rule-required="true"
                                                               data-msg-required="Fält lösenord krävs.">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="alert alert-danger hidden" id="loginError">
                                                    <strong>Fel!</strong> Referenserna du har angett är felaktiga.
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="#" id="linkToForgotPass">Glömt lösenordet?</a>
                                                </div>
                                                <!--<div class="col-md-6">
															<span class="remember-box checkbox">
																<label for="rememberme">
                                                                    <input type="checkbox" id="rememberme"
                                                                           name="rememberme">Kom ihåg
                                                                </label>
															</span>
                                                </div>-->
                                                <div class="col-md-6">
                                                    <button type="submit"
                                                            class="btn btn-primary pull-right mb-xl"
                                                            data-loading-text="Loading..." id="btnLogIn">Logga in
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div id="forgotPassBox" class="featured-box featured-box-primary align-left m-none"
                                     style="display: none;">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Glömt dina
                                            inloggningsuppgifter?</h4>
                                        <form id="frmForgotPass">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="customerNumber">Kundnummer</label>
                                                        <input type="text" name="customerNumber"
                                                               class="form-control input-lg"
                                                               id="customerNumber"
                                                               data-rule-maxlength="5"
                                                               data-rule-minlength="5"
                                                               data-rile-digits="true"
                                                               data-rule-required="true"
                                                               data-msg-digits="Fält kundnummer får bara innehålla siffror."
                                                               data-msg-required="Fält kundnummer krävs."
                                                               data-msg-minlength="Fält kundnummer bör innehålla 5 tecken."
                                                               data-msg-maxlength="Fält kundnummer bör innehålla 5 tecken."
                                                               maxlength="6">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="email">E-post:</label>
                                                        <input type="email" name="email"
                                                               id="email"
                                                               class="form-control input-lg"
                                                               data-rule-maxlength="90"
                                                               data-rule-email="true"
                                                               data-rule-required="true"
                                                               required
                                                               data-msg-required="Fält e-postadress krävs."
                                                               data-msg-email="Den här e-post är inte giltig."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="alert alert-danger hidden" id="forgotPassError">
                                                    <strong>Fel!</strong> Det fanns ett problem att skicka din
                                                    förfrågan.
                                                </div>
                                                <div class="alert alert-danger hidden" id="forgotPassBlockedError">
                                                    <strong>Fel!</strong> Ditt konto är blockerat. Kontakta administratören.
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="#" id="linkToLogin">Logga in</a>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button"
                                                            class="btn btn-primary pull-right mb-xl"
                                                            data-loading-text="Loading..." id="btnResetPassword">Skicka
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="interpreterLoginBox"
                                     class="featured-box featured-box-primary align-left m-none">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Tolkinloggning</h4>
                                        <form id="interpreterLoginForm" method="post">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="interpreter_email">E-post</label>
                                                        <input type="email" id="interpreter_email"
                                                               name="interpreter_email"
                                                               class="form-control input-lg"
                                                               data-rule-maxlength="90"
                                                               data-rule-email="true"
                                                               data-rule-required="true"
                                                               data-msg-maxlength="Fältet e-post bör innehålla mindre än 90."
                                                               data-msg-required="Fält e-postadress krävs."
                                                               data-msg-email="Den här e-post är inte giltig."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="interpreter_password">Lösenord</label>
                                                        <input type="password" id="interpreter_password"
                                                               name="interpreter_password"
                                                               class="form-control input-lg"
                                                               data-rule-required="true"
                                                               data-msg-required="Fält lösenord krävs.">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="alert alert-danger hidden" id="interpreterLoginError">
                                                    <strong>Fel!</strong> Referenserna du har angett är felaktiga.
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="#" id="linkToInterpreterForgotPass">Glömt lösenordet?</a>
                                                </div>
                                                <!--<div class="col-md-6">
															<span class="remember-box checkbox">
																<label for="rememberme">
                                                                    <input type="checkbox" id="rememberme"
                                                                           name="rememberme">Remember Me
                                                                </label>
															</span>
                                                </div>-->
                                                <div class="col-md-6">
                                                    <button type="button" id="interpreterLoginBtn"
                                                            class="btn btn-primary pull-right mb-xl"
                                                            data-loading-text="Loading...">Logga in
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div id="interpreterForgotPassBox"
                                     class="featured-box featured-box-primary align-left m-none"
                                     style="display: none;">
                                    <div class="box-content">
                                        <h4 class="heading-primary text-uppercase mb-md">Glömt dina
                                            inloggningsuppgifter?</h4>
                                        <form id="interpreterFrmForgotPass">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="interpreter_number">Tolknummer</label>
                                                        <input type="text" name="interpreter_number"
                                                               class="form-control input-lg"
                                                               id="interpreter_number"
                                                               data-rule-maxlength="4"
                                                               data-rule-minlength="4"
                                                               data-rile-digits="true"
                                                               data-rule-required="true"
                                                               data-msg-digits="Fält tolknummer får bara innehålla siffror."
                                                               data-msg-required="Fält tolknummer krävs."
                                                               data-msg-minlength="Fält tolknummer bör innehålla 4 tecken."
                                                               data-msg-maxlength="Fält tolknummer bör innehålla 4 tecken."
                                                               maxlength="4">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label for="tolk_re_email">E-post:</label>
                                                        <input type="email" name="interpreter_re_email"
                                                               id="tolk_re_email"
                                                               class="form-control input-lg"
                                                               data-rule-maxlength="90"
                                                               data-rule-email="true"
                                                               data-rule-required="true"
                                                               required
                                                               data-msg-required="Fält e-postadress krävs."
                                                               data-msg-email="Den här e-post är inte giltig."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="alert alert-danger hidden" id="forgotInterpreterPassError">
                                                    <strong>Fel!</strong> Det fanns ett problem att skicka din
                                                    förfrågan.
                                                </div>
                                                <div class="alert alert-danger hidden" id="forgotInterpreterPassBlockedError">
                                                    <strong>Fel!</strong> Ditt konto är blockerat. Kontakta administratören.
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="#" id="linkToInterpreterLogin">Logga in</a>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="submit"
                                                            class="btn btn-primary pull-right mb-xl"
                                                            data-loading-text="Loading..."
                                                            id="btnInterpreterResetPassword">Skicka
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Bland våra kunder finns offentliga myndigheter, privata företag, skolor och
                                    privatpersoner.<br>
                                    Vi har erfarenhet av tolkning och översättning.<br>
                                    Vill du bli kund hos Tolkning i Kristianstad AB ? <br>
                                    Registrera dig genom att klicka på knappen nedan och fyll i formuläret.<br>
                                    Vi arbetar vi med hög kvalitet och snabb service.<br>
                                    Vi ger dig en kostnadsfri utbildning hur man använder sig av tolk via kontakt eller
                                    telefon.<br>
                                    Vi hoppas att ni finner detta intressant och att ni vill ta del av denna utbildning.
                                    Vänligen kontakta vår kundservice för mer information!</p>
                                <button class="btn btn-lg btn-primary pull-right" data-toggle="modal"
                                        data-target="#registerFormModal">
                                    Registrera
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="registerFormModal" tabindex="-1" role="dialog"
             aria-labelledby="formModalLabelRigister"
             aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="formModalLabelRigister">Registera</h4>
                    </div>
                    <div class="modal-body">
                        <section class="panel form-wizard" id="registerWizard">
                            <div class="tabs">
                                <ul class="nav nav-tabs nav-justify wizard-steps">
                                    <li class="active">
                                        <a href="#company" data-toggle="tab" class="text-center" aria-expanded="false">
                                            <span class="badge hidden-xs">1</span>
                                            Huvudkontor
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#branch" data-toggle="tab" class="text-center" aria-expanded="false">
                                            <span class="badge hidden-xs">2</span>
                                            Avdelning
                                        </a>
                                    </li>
                                </ul>
                                <form id="registeringForm" class="form-horizontal" novalidate="novalidate">
                                    <div class="tab-content">
                                        <div id="company" class="tab-pane active">
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-5 control-label" for="org_name">Org
                                                        namn:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="org_name" id="org_name"
                                                               class="form-control" placeholder="Org namn"
                                                               data-rule-required="true"
                                                               data-msg-required="Fält org. namn krävs."
                                                               data-msg-minlength="Den org. namn bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den org. namn bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-minlength="3"
                                                               data-rule-maxlength="90">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label" for="org_number">Org
                                                        nr:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="org_number" id="org_number"
                                                               class="form-control" placeholder="Org nr"
                                                               data-rule-required="true"
                                                               data-msg-required="Fält organisationnummer krävs."
                                                               data-msg-minlength="Den organisationnummer området bör innehålla mer än 10 tecken."
                                                               data-msg-maxlength="Den organisationnummer området bör innehålla mer än 13 tecken."
                                                               maxlength="13" data-rule-minlength="10"
                                                               data-rule-maxlength="13">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-12 control-label" for="invoice_reference">Faktura
                                                        referensnr:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="invoice_reference"
                                                               id="invoice_reference"
                                                               class="form-control" placeholder="Faktura referensnr"
                                                               data-msg-minlength="Den kund området bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den kund området bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-minlength="3"
                                                               data-rule-maxlength="90">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="contact_person">Kontaktperson:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="contact_person" id="contact_person"
                                                               class="form-control" placeholder="Kontaktperson"
                                                               data-msg-minlength="Den kontaktperson området bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den kontaktperson området bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-maxlegth="90"
                                                               data-rule-minlegth="3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="phone">Telefonnr:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" placeholder="Telefonnummer"
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-msg-require_from_group="Du måste ange antingen telefonnummer eller mobilnummer eller både."
                                                               data-msg-minlength="Fältet telefonnummer bör innehålla mer än 8."
                                                               data-msg-maxlength="Fältet telefonnummer bör innehålla mindre än 15."
                                                               maxlength="15"
                                                               data-rule-maxlength="15"
                                                               data-rule-minlength="8"
                                                               data-rule-require_from_group='[1,".reg-phone-group"]'
                                                               data-rule-number="true"
                                                               class="form-control reg-phone-group" name="phone"
                                                               id="phone">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label" for="mobile">Mobil:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="mobile" id="mobile"
                                                               class="form-control reg-phone-group" placeholder="Mobil"
                                                               data-rule-maxlength="11"
                                                               data-rule-minlength="8"
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-rule-number="true"
                                                               data-rule-require_from_group='[1,".reg-phone-group"]'
                                                               data-msg-require_from_group="Du måste ange antingen telefonnummer eller mobilnummer eller både."
                                                               data-msg-minlength="Fält mobil bör innehålla mer än 8 tecken."
                                                               data-msg-maxlength="Fältet mobil bör innehålla mindre än 11.">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label" for="email">E-post:</label>
                                                    <div class="col-sm-12">
                                                        <input type="email" name="email" id="email"
                                                               class="form-control" placeholder="E-post"
                                                               data-rule-maxlength="90"
                                                               data-rule-email="true"
                                                               data-rule-required="true"
                                                               required
                                                               data-msg-required="Fält e-postadress krävs."
                                                               data-msg-email="Den här e-post är inte giltig."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label" for="address">Adress:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="address" id="address"
                                                               class="form-control" placeholder="Adress"
                                                               data-rule-maxlength="90"
                                                               data-rule-minlength="5"
                                                               data-rule-required="true"
                                                               required data-msg-required="Fält adress krävs."
                                                               data-msg-minlength="Fält adress bör innehålla mer än 5 tecken."
                                                               data-msg-maxlength="Fältet adress bör innehålla mindre än 90."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="zip">Postnummer:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="zip" id="zip"
                                                               class="form-control" placeholder="Postnummer"
                                                               data-rule-maxlength="5"
                                                               data-rule-minlength="5"
                                                               data-rule-required="true"
                                                               data-rule-number="true"
                                                               required
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-msg-required="Fält postnummer krävs."
                                                               data-msg-minlength="Fält postnummer bör innehålla mer än 5 tecken."
                                                               data-msg-maxlength="Fältet postnummer bör innehålla mindre än 5."
                                                               maxlength="5">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label" for="city">Postort:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="city" id="city"
                                                               class="form-control" placeholder="Postort"
                                                               data-msg-minlength="Den postort området bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den postort området bör innehålla mer än 90 tecken."
                                                               data-msg-required="Fält postort krävs."
                                                               maxlength="90" data-rule-maxlength="90"
                                                               data-rule-minlength="3" data-rule-required="true">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="branch" class="tab-pane">
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-12 control-label" for="branch_name">Avdelningsnamn: </label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_name" id="branch_name"
                                                               class="form-control"
                                                               placeholder="Företagsnamn, namn på boendet"
                                                               data-msg-minlength="Den avdelningsnamn bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den avdelningsnamn bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-minlength="3">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-12 control-label" for="branch_contact_person">Kontaksperson:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_contact_person"
                                                               id="branch_contact_person"
                                                               class="form-control" placeholder="Kontaksperson"
                                                               data-msg-minlength="Den kontaksperson bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den kontaksperson bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-minlength="3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="branch_phone">Telefonnr:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" placeholder="Telefonnummer"
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-msg-minlength="Fältet telefonnummer bör innehålla mer än 8."
                                                               data-msg-maxlength="Fältet telefonnummer bör innehålla mindre än 15."
                                                               maxlength="15"
                                                               data-rule-maxlength="15"
                                                               data-rule-minlength="8"
                                                               data-rule-number="true"
                                                               class="form-control"
                                                               name="branch_phone"
                                                               id="branch_phone">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="branch_mobile">Mobil:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_mobile" id="branch_mobile"
                                                               class="form-control"
                                                               placeholder="Mobil"
                                                               data-rule-maxlength="11"
                                                               data-rule-minlength="8"
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-rule-number="true"
                                                               data-msg-minlength="Fält mobil bör innehålla mer än 8 tecken."
                                                               data-msg-maxlength="Fältet mobil bör innehålla mindre än 11.">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-12 control-label"
                                                           for="branch_email">E-post:</label>
                                                    <div class="col-sm-12">
                                                        <input type="email" name="branch_email" id="branch_email"
                                                               class="form-control" placeholder="E-post"
                                                               data-rule-maxlength="90"
                                                               data-rule-email="true"
                                                               data-msg-email="Den här e-post är inte giltig."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="branch_address">Plats(adress):</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_address" id="branch_address"
                                                               class="form-control"
                                                               placeholder="Plats"
                                                               data-rule-maxlength="90"
                                                               data-rule-minlength="5"
                                                               data-msg-minlength="Fält plats bör innehålla mer än 5 tecken."
                                                               data-msg-maxlength="Fältet adress bör innehålla mindre än 90."
                                                               maxlength="90">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group ">
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="branch_zip">Postnummer:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_zip" id="branch_zip"
                                                               class="form-control" placeholder="Postnummer"
                                                               data-rule-maxlength="5"
                                                               data-rule-minlength="5"
                                                               data-rule-number="true"
                                                               data-msg-number="Det ska feiyll nummer."
                                                               data-msg-minlength="Fält postnummer bör innehålla mer än 5 tecken."
                                                               data-msg-maxlength="Fältet postnummer bör innehålla mindre än 5."
                                                               maxlength="5">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="col-sm-4 control-label"
                                                           for="branch_city">Postort:</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" name="branch_city" id="branch_city"
                                                               class="form-control" placeholder="Postort"
                                                               data-msg-minlength="Den postort bör innehålla mer än 3 tecken."
                                                               data-msg-maxlength="Den postort bör innehålla mer än 90 tecken."
                                                               maxlength="90" data-rule-minlength="3">
                                                    </div>
                                                </div>
                                                <div class="alert alert-danger hidden" id="registerError">
                                                    <strong>Fel!</strong> Det fanns ett problem att ta emot din
                                                    förfrågan.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="panel-footer">
                                <ul class="pager">
                                    <li class="previous disabled">
                                        <a><i class="fa fa-angle-left"></i> Tillbaka</a>
                                    </li>
                                    <li class="finish hidden pull-right">
                                        <a>Registera</a>
                                    </li>
                                    <li class="next ">
                                        <a>Nästa <i class="fa fa-angle-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </section>
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

<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>

<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="js/theme.init.js"></script>

<!-- form wizard -->
<script src="js/wizard.js"></script>


<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker.sv.js"></script>

<script src="js/views/view.customer.login.js"></script>
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
