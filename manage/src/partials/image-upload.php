<?php
/**
 * User: Samuil
 * Date: 25-06-2015
 * Time: 12:33 AM
 */
?>
<div class="ui piled segment">
    <div class="ui stackable grid">
        <div class="row">
            <div class="four wide column">
                <div class="ui segment">
                    <form class="ui form" enctype="multipart/form-data" id="uploadPictureForm">
                        <div class="field">
                            <label>Välj bild att ladda upp:</label>
                            <div class="ui left icon input">
                                <i class="file image outline icon"></i>
                                <input type="file" class="ui orange button" name="fileUpload" id="fileUpload">
                            </div>
                        </div>
                        <div class="field">
                            <button type="button" class="ui labeled icon blue fluid button" id="btnUploadPicture">
                                <i class="upload icon"></i>
                                Ladda upp
                            </button>
                        </div>
                        <div class="ui indicating progress" id="uploadProgress" style="display: none;">
                            <div class="bar">
                                <div class="progress"></div>
                            </div>
                            <div class="label">Ladda upp bild</div>
                        </div>
                        <div class="ui success message">
                            <i class="close icon"></i>
                            <div class="header">Framgång!</div>
                            <p></p>
                        </div>
                        <div class="ui error message">
                            <i class="close icon"></i>
                            <div class="header">Fel!</div>
                            <p></p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="twelve wide column">
                <div class="ui segment">
                    <div class="ui three cards uploadedImages">
                        <?php
                        $files = glob("../images/uploaded/*.*");
                        $count = count($files);

                        $sortedArray = array();
                        for ($i = 0; $i < $count; $i++) {
                            $sortedArray[date ('YmdHis', filemtime($files[$i]))] = $files[$i];
                        }

                        krsort($sortedArray);
                        if ($count > 0) {
                            foreach ($sortedArray as $image)
                            {
                                $path = substr($image, 3);
                                echo "<div class='ui blue card'><div class='bordered image'><img class='ui image' src='$image' /></div><div class='extra content'><p class='header'>Vägen till bilden:</p><div class='description'><div style='overflow-x: auto; overflow-y: hidden;'><span class='ui compact message'>http://tolktjanst.com/$path</span></div></div></div></div>";
                            }
                        } else {
                            echo "<div id='noUploadedPictures'><span>Du behöver inte ha bilder några uppladdade just nu!</span></div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript" src="js/uploadPicture.js"></script>