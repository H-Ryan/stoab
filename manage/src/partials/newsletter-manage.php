<?php
/**
 * User: Samuil
 * Date: 17-04-2015
 * Time: 12:27 PM
 */

?>
<div class="ui piled segment">
    <div class="ui grid">
        <div class="centered row">
            <div class="six wide column">
                <div class="ui segment">
                    <div id="newsContainer" style="max-height: 400px; overflow: auto;">
                        <div class="ui small feed">
                            <?php
                            $statement = $con->query("SELECT * FROM t_newsLetter WHERE n_time >= CURRENT_DATE() - 30  ORDER BY n_time DESC ");
                            $statement->execute();
                            $statement->setFetchMode(PDO::FETCH_OBJ);
                            if ($statement->rowCount() > 0) {
                                while ($row = $statement->fetch()) { ?>
                                    <div class="event" style="border: solid 1px #d3d3d3; margin-bottom: 5px">
                                        <div class="label">
                                            <i class="mail outline icon"></i>
                                            <form class="newsManageIDForm">
                                                <input type="hidden" name="newsID"
                                                       value="<?php echo $row->n_ID; ?>"/>

                                                <div class="ui grid">
                                                    <div class="row">
                                                        <div class="column">
                                                            <div class="three ui vertical fluid inverted buttons">
                                                                <div class="mini ui blue inverted button btnNewsManageView">
                                                                    View
                                                                </div>
                                                                <div class="mini ui orange inverted button btnNewsManageEdit">
                                                                    Edit
                                                                </div>
                                                                <div class="mini ui red inverted button btnNewsManageDelete">
                                                                    Delete
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="content">
                                            <div class="summary">
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
                </div>
            </div>
            <div class="ten wide column">
                <div class="ui basic segment" id="containerUpdateNews">
                    <form id="newsLetterModifyForm" style="display: none;">
                        <input type="hidden" name="newsId" value="" id="newsIdManage" />
                        <div class="ui grid">
                            <div class="row">
                                <div class="ten wide centered column">
                                    <div class="field">
                                        <label for="newsTitleManage">Title:</label>
                                        <input type="text" name="newsTitle" id="newsTitleManage"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <div class="field">
                                        <textarea id="newsPrescriptManage" maxlength="200">...</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="centered column">
                                            <span id="chars"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <div class="field">
                                        <label for="newsLetterManage"></label>
                                        <textarea id="newsLetterManage"  name="newsLetter">Do you have some news?</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ui divider"></div>
                            </div>
                            <div class="row">
                                <div class="column">
                                    <div class="two fluid ui inverted buttons">
                                        <button type="button" class="ui inverted orange button" id="btnPreviewNewsManage">PREVIEW</button>
                                        <button type="button" class="ui inverted blue button" id="btnUpdateNewsLetterManage">UPDATE</button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="ui modal previewManage">
                    <i class="close icon"></i>

                    <div class="content">
                        <div class='ui segment' style='height: 400px; max-height: 400px; overflow: auto;'>
                            <div class='ui header'>

                            </div>
                            <div id="newsContent">

                            </div>
                        </div>
                    </div>
                    <div class="actions">
                        <div class="centered row">
                            <div class="column">
                                <div class="ui positive button">
                                    OK
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui basic modal newsManageUpdateAction">
    <i class="close icon"></i>
    <div class="header">
        Uppdatera nyhetsbrev inträde
    </div>
    <div class="content">
        <div class="image">
            <i class="warning sign icon"></i>
        </div>
        <div class="description">
            <p>Är du säker på att du vill uppdatera detta nyhetsbrev posten?</p>
        </div>
    </div>
    <div class="actions">
        <div class="two fluid ui inverted buttons">
            <button type="button" class="ui red basic inverted button btnManageActionNo">
                <i class="remove icon"></i>
                Nej
            </button>
            <button type="button" class="ui green basic inverted button btnManageActionYes">
                <i class="checkmark icon"></i>
                Ja
            </button>
        </div>
    </div>
</div>
<div class="ui basic modal newsManageDeleteAction">
    <i class="close icon"></i>
    <div class="header">
        Radera nyhetsbrev inträde
    </div>
    <div class="content">
        <div class="image">
            <i class="warning sign icon"></i>
        </div>
        <div class="description">
            <p>Är du säker på att du vill radera detta nyhetsbrev posten?</p>
        </div>
    </div>
    <div class="actions">
        <div class="two fluid ui inverted buttons">
            <button type="button" class="ui red basic inverted button btnManageActionNo">
                <i class="remove icon"></i>
                Nej
            </button>
            <button type="button" class="ui green basic inverted button btnManageActionYes" data-id="">
                <i class="checkmark icon"></i>
                Ja
            </button>
        </div>
    </div>
</div>