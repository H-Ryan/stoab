<?php
ini_set("session.use_only_cookies", TRUE);
ini_set("session.use_trans_sid", FALSE);
session_start();
include_once "src/db/dbConnection.php";
include_once "src/db/dbConfig.php";
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <title>
        STÖ AB - Sarvari tolkning och översättning / Nyheter
    </title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/grid.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css"/>
    <link rel="stylesheet" href="lib/semantic/semantic.min.css" />

    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src='js/modal.js'></script>
    <script src='js/TMForm.js'></script>
    <script src="lib/semantic/semantic.min.js"></script>
    <script src='js/custom/newsletter.js'></script>
    <!--[if lt IE 9]>
    <div style=' clear: both; text-align:center; position: relative;'>
        <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
            <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0"
                 height="42" width="820"
                 alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."/>
        </a>
    </div>
    <script src="js/html5shiv.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
    <![endif]-->
</head>
<body>
<div class="page">
    <!--========================================================
                              HEADER
    =========================================================-->
    <?php include("src/partials/shared/header.php"); ?>
    <!--========================================================
                              CONTENT
    =========================================================-->
    <?php
    try {
        $db = new dbConnection(HOST, DATABASE, USER, PASS);
        $con = $db->get_connection();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    ?>
    <section>
        <div class="container">
            <div class="row wrap_8"></div>
            <div class="ui middle aligned two column stackable grid">
                <?php if (isset($_GET['id'])) {?>
                <div class="column">
                    <?php
                    $statement = $con->prepare("SELECT * FROM t_newsLetter WHERE n_ID=:id");
                    $statement->bindParam(":id", $_GET['id']);
                    $statement->execute();
                    $statement->setFetchMode(PDO::FETCH_OBJ);
                    while ($row = $statement->fetch()) {
                        echo "<div>".$row->n_Text."</div>";
                    }
                    ?>
                </div>
                <div class="column">

                </div>
                <?php } else {
                    echo "<p>Invalid parameters!</p>";
                }?>
            </div>
        </div>
        <?php include("src/partials/shared/follow-us.html") ?>
    </section>
</div>
<!--========================================================
                          FOOTER
=========================================================-->
<?php include("src/partials/shared/footer.html"); ?>
<script src="js/script.js"></script>
</body>
</html>