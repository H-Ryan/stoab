<?php
/**
 * User: Samuil
 * Date: 22-06-2015
 * Time: 10:08 AM
 */
include_once "src/db/dbConnection.php";
include_once "src/db/dbConfig.php";
try {
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
} catch (PDOException $e) {
    return $e->getMessage();
}
?>
<div id="newsContainer" style="max-height: 250px; overflow: auto;">
    <div class="ui small feed">
        <?php
        $statement = $con->query("SELECT * FROM t_newsLetter WHERE n_time >= CURRENT_DATE() - 30 AND n_flag=1 ORDER BY n_time DESC LIMIT 10 ");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_OBJ);
        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch()) { ?>
                <div class="event" style="border: solid 1px #d3d3d3; margin-bottom: 5px">
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
                               href="newsletter.php?id=<?php echo $row->n_ID ?>">Läs mer</a>
                        </div>
                    </div>
                </div>
            <?php }
        } else {
            echo "<div><p class='text_3'>Just nu har vi inte några nyheter!</p></div>";
        }
        ?>


    </div>
</div>