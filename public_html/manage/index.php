<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
if (!empty($_SESSION['personal_number'])) {
    header('Location: main.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="../vendor/stoab/stoab.min.css"/>
    <link rel="stylesheet" href="../css/mod-sam/form.css"/>
    <script type="text/javascript" src="../vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../vendor/stoab/stoab.min.js"></script>
    <script type="text/javascript" src="./js/login.js"></script>
    <!--[if lt IE 9]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
            <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0"
                 height="42" width="820"
                 alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
        </a>
    </div>
    <script src="../vendor/html5shiv.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/ie.css">
    <![endif]-->
</head>
<body>
<div class="ui page grid">
    <div class="five wide computer ten wide centered mobile column">
        <div class="ui basic horizontal segment">
            <form class="ui large form loginManage" method="post" action="../src/misc/loginManage.php">
                <fieldset class="basic segment">
                    <div class="ui error message">
                        <i class="close icon"></i>

                        <div class="header">Header</div>
                        <p>Message</p>
                    </div>
                    <h3>Logga in med Tolktjanst</h3>

                    <div class="required field">
                        <label>E-post:</label>
                        <div class="ui left icon input">
                            <i class="user icon"></i>
                            <input id="email" name="email" type="email" placeholder="E-post">
                        </div>
                    </div>
                    <div class="required field">
                        <label>Lösenord</label>

                        <div class="ui left icon input">
                            <i class="lock icon"></i>
                            <input id="password" name="password" type="password" placeholder="Lösenord">
                        </div>
                    </div>
                    <div class="field">
                        <button type="submit" class="ui right labeled icon blue button login-btn">
                            <i class="right sign in icon"></i>Logga in
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>