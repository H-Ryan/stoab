<?php
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
?>
<header id="header"
        data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 57, "stickySetTop": "-57px", "stickyChangeLogo": true}'>
    <div class="header-body">
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-logo">
                        <a href="index.php">
                            <img alt="Porto" width="87" height="98" data-sticky-width="43" data-sticky-height="50"
                                 data-sticky-top="30" src="img/logo2.png">
                        </a>
                    </div>
                </div>
                <div class="header-column">
                    <div class="header-row">
                        <nav class="header-nav-top">
                            <ul class="nav nav-pills">
                                <li class="hidden-xs">
                                    <span class="ws-nowrap"><i class="fa fa-phone"></i> <a href="tel:+46-10-542-4210">(010) 542 42 10</a></span>
                                </li>
                                <li class="hidden-xs">
                                        <span class="ws-nowrap"><i class="fa fa-envelope"></i> <a
                                                href="mailto: info@c4tolk.se">info@c4tolk.se</a></span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="header-row">
                        <div class="header-nav">
                            <button class="btn header-btn-collapse-nav" data-toggle="collapse"
                                    data-target=".header-nav-main">
                                <i class="fa fa-bars"></i>
                            </button>
                            <div
                                class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse">
                                <nav>
                                    <ul class="nav nav-pills" id="mainNav">
                                        <li>
                                            <a data-hash data-hash-offset="70" href="tjanster.php">Tjänster</a>
                                        </li>
                                        <li>
                                            <a data-hash data-hash-offset="70" href="omoss.php">Om oss</a>
                                        </li>
                                        <li>
                                            <a data-hash data-hash-offset="70" href="arbetamedoss.php">Arbeta med
                                                oss</a>
                                        </li>
                                        <li>
                                            <a data-hash data-hash-offset="70" href="kontaktaoss.php">Kontakta
                                                oss</a>
                                        </li>
                                        <li>
                                            <?php
                                            if (!empty($_SESSION['organization_number']) && !empty($_SESSION['user_number'])) {
                                                ?>
                                                <a data-hash data-hash-offset="70" href="kundpanel.php">Min panel</a>
                                            <?php

                                            } elseif (!empty($_SESSION['personal_number']) && !empty($_SESSION['tolk_number'])) {
                                                ?>
                                                <a data-hash data-hash-offset="70" href="tolkpanel.php">Min panel</a>
                                            <?php

                                            } else {
                                                ?>
                                                <a data-hash data-hash-offset="70" href="login.php">Logga in</a>
                                            <?php

                                            } ?>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php
if (isset($arrResult)) {
                                                if ($arrResult['response'] == 'success') {
                                                    ?>
        <div class="alert alert-success" id="contactSuccess">
            Vi har mottagit ditt dokument. Vi återkommer så snart som möjligt.
        </div>
        <?php

                                                } elseif ($arrResult['response'] == 'error') {
                                                    ?>
        <div class="alert alert-danger" id="contactError">
            Misslyckad sändning, försök igen. (<?php echo $arrResult['error']; ?>)
        </div>
        <?php

                                                }
                                            }
?>
