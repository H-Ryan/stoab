<?php
include_once "./src/db/dbConnection.php";
include_once "./src/db/dbConfig.php";

try {
    $db = new dbConnection(HOST, DATABASE, USER, PASS);
    $con = $db->get_connection();
} catch (PDOException $e) {
    return $e->getMessage();
}
$languages = [];
try {
    $statement = $con->query("SELECT * FROM t_languages ORDER BY l_languageName");
    $statement->setFetchMode(PDO::FETCH_OBJ);
    while ($row = $statement->fetch()) {
        $languages[$row->l_languageID] = $row->l_languageName;
    }
} catch (PDOException $e) {
    return $e->getMessage();
}

?>
<div class="col-md-6 col-sm-12 mt-xs">
    <h4 class="heading-quaternary mb-none pull-right" style="font-size:16px"><strong>
            <a href="tel:+46-10-166-1010"><img class="m-xs" src="img/icons/24-hour-support.png" alt="Ringa"/></a>Kontakta
            oss på : 010-166 10 10
        </strong></h4>
</div>
<div class="col-md-6 col-sm-12">
    <div class="get-started mt-md">
        <button class="btn btn-md btn-primary" data-toggle="modal" data-target="#orderTranslationModal">Beställ
            översättning
        </button>
        <button class="btn btn-md btn-primary ml-md" data-toggle="modal" data-target="#bookInterpreterModal">Beställ
            tolk
        </button>
    </div>
</div>


<div class="modal fade" id="bookInterpreterModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="formModalLabel">Beställ Tolk</h4>
            </div>
            <div class="modal-body p-none">
                <section class="panel form-wizard mb-none" id="wizard">
                    <div class="tabs m-xs">
                        <ul class="nav nav-tabs nav-justify wizard-steps">
                            <li class="active">
                                <a href="#customerOrGuest" data-toggle="tab" class="text-center" aria-expanded="false">
                                    <span class="badge hidden-xs"></span>
                                    Redan Kund
                                </a>
                            </li>
                            <li class="">
                                <a href="#account" data-toggle="tab" class="text-center" aria-expanded="false">
                                    <span class="badge hidden-xs">1</span>
                                    Uppdrag
                                </a>
                            </li>
                            <li class="">
                                <a href="#profile" data-toggle="tab" class="text-center" aria-expanded="false">
                                    <span class="badge hidden-xs">2</span>
                                    Kontaktperson / Fakturering
                                </a>
                            </li>
                            <li class="">
                                <a href="#confirm" data-toggle="tab" class="text-center" aria-expanded="true">
                                    <span class="badge hidden-xs">3</span>
                                    Skicka
                                </a>
                            </li>
                        </ul>
                        <form class="bookingForm form-horizontal" novalidate="novalidate">
                            <input type="hidden" name="organizationNumber" value="0000000000">
                            <input type="hidden" name="orderer" value="100000">
                            <input type="hidden" name="clientNumber" value="100000">
                            <input type="hidden" value="NI" name="tolk_type"/>
                            <div class="tab-content">
                                <div id="customerOrGuest" class="tab-pane active">
                                    <div class="form-group">
                                        <p class="center m-xlg">
                                            Om du redan är kund hos oss, klicka <strong><a
                                                    href="login.php">här</a></strong>
                                            för att logga in, eller klicka nästa för att beställa tolk.
                                        <p>
                                    </div>
                                </div>
                                <div id="account" class="tab-pane">
                                    <div class="form-group mt-lg">
                                        <label class="col-sm-2 control-label" for="client">Klient:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="client" id="client" class="form-control"
                                                   placeholder="Klient"
                                                   data-rule-maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-msg-minlength="Den kund området bör innehålla mer än 3 tecken."
                                                   data-msg-maxlength="Den kund området bör innehålla mer än 90 tecken."
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="language">Språk:</label>
                                        <div class="col-sm-10">
                                            <select class="form-control  mb-md" name="language" id="language"
                                                    data-rule-required="true"
                                                    data-msg-required="Fält språk krävs.">
                                                <option value=''>Språk</option>
                                                <?php
                                                foreach ($languages as $key => $value) {
                                                    echo "<option value='" . $key . "'>" . $value . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <blockquote class="with-borders p-none">
                                        <div class="form-group">
                                            <div class="radio pl-xlg">
                                                <div class="row">
                                                    <div class="col-md-6 ">
                                                        <label>
                                                            <input type="radio" name="type" id="KT" value="KT" data-rule-required="true"
                                                                   data-msg-required="Fält typ av tolkning krävs.">
                                                            Kontakttolkning
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <label>
                                                            <input type="radio" name="type" id="TT" value="TT">
                                                            Telefontolkning
                                                        </label>
                                                    </div>
                                                </div>
                                                <!--<div class="row">
                                                    <div class="col-md-6 ">
                                                        <label>
                                                            <input type="radio" name="type" id="KP" value="KP">
                                                            Kontaktperson
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 ">
                                                        <label>
                                                            <input type="radio" name="type" id="SH" value="SH">
                                                            Studiehandledning
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>
                                                            <input type="radio" name="type" id="SS" value="SS">
                                                            Språkstöd
                                                        </label>
                                                    </div>
                                                </div>-->
                                            </div>
                                        </div>
                                    </blockquote>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="date">Datum:</label>
                                        <div class="col-sm-10" id="sandbox-container">
                                            <input type="text" class="form-control" id="date" name="date"
                                                   data-rule-required="true"
                                                   data-msg-required="Fält datum krävs.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="startTime">Starttid:</label>
                                        <div class="col-sm-4" id="startTime">
                                            <div class="col-sm-6">
                                                <select title="Starttid" name="start_hour" id="starttid"
                                                        class="form-control mb-md" style="padding: 0;"
                                                        data-rule-required="true"
                                                        data-rule-digits="true"
                                                        data-msg-required="Fält starttid krävs.">
                                                    <?php
                                                    for ($i = 0; $i < 3; $i++) {
                                                        for ($j = 0; $j < 10; $j++) {
                                                            if ($i == 2 && $j == 4)
                                                                break;
                                                            elseif ($i == 1 && $j == 2)
                                                                echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                            else
                                                                echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select title="Starttid" name="start_minute" id="starttid1"
                                                        class="form-control mb-md" style="padding: 0;"
                                                        data-rule-required="true"
                                                        data-rule-digits="true"
                                                        data-msg-required="Fält starttid krävs.">
                                                    <option value="0" selected>00</option>
                                                    <option value="1">15</option>
                                                    <option value="2">30</option>
                                                    <option value="3">45</option>
                                                </select>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label" for="endTime">Sluttid:</label>
                                        <div class="col-sm-4" id="endTime">
                                            <div class="col-md-6">
                                                <select title="Sluttid" name="end_hour" id="sluttid"
                                                        class="form-control mb-md" style="padding: 0;"
                                                        data-rule-required="true"
                                                        data-rule-digits="true" data-msg-required="Fält sluttid krävs.">
                                                    <?php
                                                    for ($i = 0; $i < 3; $i++) {
                                                        for ($j = 0; $j < 10; $j++) {
                                                            if ($i == 2 && $j == 4)
                                                                break;
                                                            elseif ($i == 1 && $j == 3)
                                                                echo "<option selected='selected' value='" . intval($i . $j) . "'>$i$j</option>";
                                                            else
                                                                echo "<option value='" . intval($i . $j) . "'>$i$j</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select title="Sluttid1" name="end_minute" id="sluttid1"
                                                        class="form-control mb-md" style="padding: 0;"
                                                        data-rule-required="true"
                                                        data-rule-digits="true" data-msg-required="Fält sluttid krävs.">
                                                    <option value="0" selected>00</option>
                                                    <option value="1">15</option>
                                                    <option value="2">30</option>
                                                    <option value="3">45</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="profile" class="tab-pane">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="contactPerson">Beställare:</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="contactPerson" id="contactPerson"
                                                   class="form-control" placeholder="Beställare"
                                                   data-rule-maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-required="true"
                                                   required
                                                   data-msg-required="Fält beställaren krävs."
                                                   data-msg-minlength="Fält beställaren bör innehålla mer än 3 tecken."
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="organization">Företag/
                                            Organisation:</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="organization" id="organization"
                                                   class="form-control" placeholder="Organisation"
                                                   data-rule-maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-required="true"
                                                   required
                                                   data-msg-required="Fält organisation krävs."
                                                   data-msg-minlength="Fält organisation bör innehålla mer än 3 tecken."
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="organization">E-postadress:</label>
                                        <div class="col-sm-12">
                                            <input type="email" name="email" class="form-control"
                                                   placeholder="E-postadress"
                                                   data-rule-maxlength="90"
                                                   data-rule-email="true"
                                                   data-rule-required="true"
                                                   required
                                                   data-msg-required="Fält e-postadress krävs."
                                                   data-msg-email="Den här e-post är inte giltig." maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label class="col-sm-4 control-label" for="telephone">Telefon:</label>
                                            <div class="col-sm-12">
                                                <input type="text" name="telephone" id="telephone"
                                                       class="form-control phone-group"
                                                       placeholder="Telefon"
                                                       data-rule-require_from_group='[1,".phone-group"]'
                                                       data-msg-require_from_group="Du måste ange antingen hemnummer eller mobilnummer eller både."
                                                       data-rule-maxlength="15"
                                                       maxlength="15"
                                                       data-rule-minlength="8"
                                                       data-msg-number="Det ska feiyll nummer."
                                                       data-rule-number="true"
                                                       data-msg-minlength="Fält telefon bör innehålla mer än 8 tecken."
                                                       data-msg-maxlength="Fältet telefon bör innehålla mindre än 15.">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-4 control-label" for="mobile">Mobil:</label>
                                            <div class="col-sm-12">
                                                <input type="text" name="mobile" id="mobile"
                                                       class="form-control phone-group"
                                                       placeholder="Mobil"
                                                       data-rule-maxlength="11"
                                                       data-rule-minlength="8"
                                                       data-msg-number="Det ska feiyll nummer."
                                                       data-rule-number="true"
                                                       data-rule-require_from_group='[1,".phone-group"]'
                                                       data-msg-require_from_group="Du måste ange antingen telefonnummer eller mobilnummer eller både."
                                                       data-msg-minlength="Fält mobil bör innehålla mer än 8 tecken."
                                                       data-msg-maxlength="Fältet mobil bör innehålla mindre än 11.">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="address">Plats för tolkning:</label>
                                        <div class="col-sm-12">
                                            <input type="text" name="address" id="address" class="form-control"
                                                   placeholder="Plats"
                                                   data-rule-maxlength="90"
                                                   data-rule-minlength="5"
                                                   data-rule-required="true"
                                                   required data-msg-required="Fält plats krävs."
                                                   data-msg-minlength="Fält plats bör innehålla mer än 5 tecken."
                                                   data-msg-maxlength="Fältet adress bör innehålla mindre än 90."
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label class="col-sm-4 control-label" for="post_code">Postnummer:</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="post_code" id="post_code" class="form-control"
                                                       placeholder="Postnummer"
                                                       data-rule-maxlength="5"
                                                       data-rule-minlength="5"
                                                       data-rule-required="true"
                                                       data-rule-number="true"
                                                       required
                                                       data-msg-number="Det ska feiyll nummer."
                                                       data-msg-required="Fält post nummer krävs."
                                                       data-msg-minlength="Fält post nummer bör innehålla mer än 5 tecken."
                                                       data-msg-maxlength="Fältet post nummer bör innehålla mindre än 5."
                                                       maxlength="5">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-4 control-label" for="city">Ort:</label>
                                            <div class="col-sm-12">
                                                <select id="city" name="city" class="form-control  mb-md"
                                                        data-rule-required="true"
                                                        data-msg-required="Fält ort krävs.">
                                                    <option value=''>Ort</option>
                                                    <?php
                                                    try {
                                                        $statement = $con->query("SELECT * FROM t_city ORDER BY c_cityName");
                                                        $statement->setFetchMode(PDO::FETCH_OBJ);
                                                        while ($row = $statement->fetch()) {
                                                            echo "<option value='" . $row->c_cityName . "'>" . $row->c_cityName . "</option>";
                                                        }
                                                    } catch (PDOException $e) {
                                                        return $e->getMessage();
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="confirm" class="tab-pane">
                                    <div class="alert alert-danger hidden" id="interpretationOrderError">
                                        <strong>Fel!</strong> Det gick inte att registrera din beställning.
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="comment">Kommentar:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="5" id="comment" name='message'
                                                      style="margin-top: 0; margin-bottom: 0; height: 150px;"
                                                      data-rule-maxlength="255"
                                                      data-rule-minlength="5"
                                                      data-msg-minlength="Fält kommentar bör innehålla mer än 5 tecken."
                                                      data-msg-maxlength="Fältet kommentar bör innehålla mindre än 255."></textarea>
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
                                <a>Boka</a>
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

<div class="modal fade" id="orderTranslationModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="formModalLabel">Beställning av Översättning </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hidden" id="translationOrdertSuccess">
                    <strong>Framgång!</strong> Vi har tagit emot din beställning!
                </div>

                <div class="alert alert-danger hidden" id="translationOrdertError">
                    <strong>Fel!</strong> Det uppstod ett fel när du skickar din beställning.
                </div>
                <form class="bookingForm form-horizontal" novalidate="novalidate" id="orderTranslationForm"
                      action="src/services/order-translation-form.php" method="POST"
                      enctype="multipart/form-data">
                    <input type="hidden" value="true" name="emailSent" id="emailSent">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="clientNumber">Kundnummer eller org
                                nummer:</label>
                            <div class="col-sm-12">
                                <input type="text" name="clientNumber" id="clientNumber" class="form-control"
                                       placeholder=""
                                       data-msg-number="Fält post nummer ska endast innehålla siffror."
                                       data-msg-required="Skriv ditt kundnummer eller org nummer."
                                       data-msg-minlength="Måste innehålla fler än 4 tecken."
                                       data-msg-maxlength="Fältet organisationnummer bör innehålla mindre än 13."
                                       maxlength="13"
                                       data-rule-maxlength="13"
                                       data-rule-minlength="4"
                                       data-rule-required="true"
                                       data-rule-number="true" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="name">Namn:</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control"
                                       data-msg-required="Skriv ditt namn."
                                       data-msg-minlength="Fält namn bör innehålla mer än 3."
                                       data-msg-maxlength="Fält namn bör innehålla mindre än 90."
                                       data-rule-maxlength="90"
                                       data-rule-minlength="3"
                                       data-rule-required="true"
                                       maxlength="90"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="phone">Telefon:</label>
                            <div class="col-sm-12">
                                <input type="text" value="" data-msg-number="Det ska feiyll nummer."
                                       data-msg-required="Skriv ditt telefon nummer."
                                       data-msg-minlength="Fältet Telefon bör innehålla mer än 8."
                                       data-msg-maxlength="Fältet Telefon bör innehålla mindre än 15." maxlength="15"
                                       data-rule-maxlength="15"
                                       data-rule-minlength="8"
                                       data-rule-required="true"
                                       data-rule-number="true" class="form-control" name="phone"
                                       id="phone" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="email">E-post:</label>
                            <div class="col-sm-12">
                                <input type="email" value="" data-msg-required="Skriv din e-post adress."
                                       data-msg-email="Den här e-post är inte giltig."
                                       data-msg-maxlength="Fältet e-postadress ska innehålla mindre än 90 tecken."
                                       data-rule-maxlength="90"
                                       data-rule-required="true"
                                       data-rile-email="true"
                                       maxlength="90" class="form-control" name="email" id="email" required>
                            </div>
                        </div>
                    </div>
                    <blockquote class="with-borders m-lg mb-none">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label class="control-label" for="client">Jag önskar i första hand översättning
                                    via:</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="radio ml-md">
                                    <label>
                                        <input type="radio" name="by" id="by" value="E-post" checked="" required>
                                        E-post
                                    </label>
                                    <label class="ml-xlg">
                                        <input type="radio" name="by" id="by" value="Telefon">
                                        Telefon
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-lg">
                                <label class="control-label" for="client">Önskat leveransdatum:</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="radio ml-md">
                                    <label>
                                        <input type="radio" name="ddate" id="ddate" value="Normal" checked="" required>
                                        Normal (8-12 dagar)
                                    </label>
                                    <label class="ml-xlg">
                                        <input type="radio" name="ddate" id="ddate" value="Express">
                                        Express (Max 5 dagar)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </blockquote>
                    <div class="form-group mb-none">
                        <label class="col-sm-12 control-label" for="language">Språk:</label>
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="fromLang">Från:</label>
                            <div class="col-sm-12">
                                <select class="form-control  mb-md" name="fromLang" id="fromLang"
                                        data-msg-required="Välj ett av de språk från rullgardinsmenyn." required>
                                    <option value=''>Språk</option>
                                    <?php
                                    foreach ($languages as $key => $value) {
                                        echo "<option value='" . $value . "'>" . $value . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-sm-12 control-label" for="toLang">till:</label>
                            <div class="col-sm-12">
                                <select class="form-control  mb-md" name="toLang" id="toLang"
                                        data-msg-required="Välj ett av de språk från rullgardinsmenyn." required>
                                    <option value=''>Språk</option>
                                    <?php
                                    foreach ($languages as $key => $value) {
                                        echo "<option value='" . $value . "'>" . $value . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12 control-label">Ladda upp dokument:</label>
                        <div class="col-md-12 mt-md ml-md">
                            <div class="col-md-2">
                                <div class="input-group">
									<span class="input-group-btn">
										<span class="btn btn-primary btn-file">
											Ladda Upp&hellip;
                                            <input type="file" id="attachment" name="attachment">
										</span>
									</span>
                                </div>
                            </div>
                            <div class="col-md-6 pl-xlg">
                                <input type="text" id="uploadInput" class="form-control" readonly
                                       data-msg-required="Välj ett av de språk från rullgardinsmenyn."
                                       data-rule-extension="pdf|doc|docx|odt|txt"
                                       data-msg-extension="Välj en fil med ett av följande tillägg:<br /> pdf | doc | docx | odt | txt."
                                       required>
                            </div>
                            <div class="col-md-12 help-block">
                                Vänligen bifoga endast PDF eller Word dokument
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="col-sm-12 control-label" for="comment">Skriv en kort kommentar om dokumentet
                                du laddar upp:</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" rows="3" id="comment" name="comment"
                                          data-rule-maxlength="255"
                                          data-rule-minlength="5"
                                          data-msg-minlength="Fält kommentar bör innehålla mer än 5 tecken."
                                          data-msg-maxlength="Fältet kommentar bör innehålla mindre än 255."></textarea>
                            </div>
                        </div>
                    </div>
                    <hr class="solid tall m-none">
                    <div class="form-group mt-md">
                        <div class="col-md-12">
                            <input type="submit" value="Beställ" class="btn btn-primary pull-right mr-md"
                                   data-loading-text="Loading...">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>