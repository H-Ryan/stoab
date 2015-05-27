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

    <link rel="stylesheet" href="css/form.css"/>

    <link rel="stylesheet" href="lib/stoab/stoab.min.css"/>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.2.1.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src='js/modal.js'></script>
    <script src='js/TMForm.js'></script>
    <script src="lib/stoab/stoab.min.js"></script>
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
            <div class="ui middle aligned stackable grid">
                <div class="centered row">
                    <?php if (isset($_GET['id'])) { ?>
                        <div class="twelve wide column " style="height:400px; ">
                            <?php
                            $statement = $con->prepare("SELECT * FROM t_newsLetter WHERE n_ID=:id");
                            $statement->bindParam(":id", $_GET['id']);
                            $statement->execute();
                            $statement->setFetchMode(PDO::FETCH_OBJ);
                            while ($row = $statement->fetch()) {
                                $daysAgo = "";
                                $date1 = new DateTime($row->n_time);
                                $date2 = new DateTime(date("Y-m-d H:i:s"));
                                $interval = $date1->diff($date2);
                                if ($interval->format('%d') === "0") {
                                    $daysAgo = "Idag";
                                } else {
                                    $daysAgo = $interval->format('%d') . " dagar sedan.";
                                }
                                echo "<div class='ui stacked segment' style='height: 400px; max-height: 400px; overflow: auto;'><div class='ui header'>" . $row->n_title . " - Publicerat: <span>" . $daysAgo . "</span></div>" . $row->n_text . "</div>";
                            }
                            ?>
                        </div>
                    <?php } else {
                        echo "<div class='twelve wide column'><p>Invalid parameters!</p></div>";
                    } ?>
                    <div class="four wide column">
                        <div id="newsContainer" class="ui segment"
                             style="height: 400px; max-height: 400px; overflow: auto;">
                            <div class="ui small feed">
                                <?php
                                $statement = $con->query("SELECT * FROM t_newsLetter WHERE n_time >= CURRENT_DATE() - 30 AND n_flag=1 ORDER BY n_time DESC");
                                $statement->execute();
                                $statement->setFetchMode(PDO::FETCH_OBJ);
                                while ($row = $statement->fetch()) { ?>
                                    <div class="event" id="<?php echo $row->n_ID; ?>"
                                         style="border: solid 1px <?php echo ($row->n_ID === $_GET['id']) ? "lightseagreen" : "#d3d3d3"; ?>; margin-bottom: 5px">
                                        <div class="label">
                                            <i class="mail outline icon"></i>
                                        </div>
                                        <div class="content">
                                            <div class="summary" style="line-height: initial;">
                                                <?php
                                                echo $row->n_title . " - Publicerat: ";
                                                $date1 = new DateTime($row->n_time);
                                                $date2 = new DateTime(date("Y-m-d H:i:s"));
                                                $interval = $date1->diff($date2);
                                                if ($interval->format('%d') === "0") {
                                                    echo "Idag";
                                                } else {
                                                    echo $interval->format('%d') . " dagar sedan.";
                                                }
                                                ?>
                                            </div>
                                            <div class="extra text">
                                                <?php echo $row->n_postScript; ?>
                                            </div>
                                            <div class="meta">
                                                <a class="linkViewMore"
                                                   href="newsletter.php?id=<?php echo $row->n_ID; ?>">Läs mer</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("src/partials/shared/follow-us.html") ?>
    </section>
    <span style="display: none;" id="newsID"><?php echo $_GET['id']; ?></span>
</div>
<!--========================================================
                          FOOTER
=========================================================-->
<?php include("src/partials/shared/footer.html"); ?>
<script src="js/script.js"></script>
</body>
</html>