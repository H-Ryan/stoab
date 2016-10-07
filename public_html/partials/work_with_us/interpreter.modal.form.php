<div class="modal fade" id="interpreterModalForm" tabindex="-1" role="dialog"
     aria-labelledby="formModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
                <h4 class="modal-title" id="formModalLabel">Intresseanmälan</h4>
            </div>
            <div class="modal-body">
                <section class="panel form-wizard mb-none" id="interpreterJobWizard">
                    <div class="tabs">
                        <ul class="nav nav-tabs nav-justify">
                            <li class="active">
                                <a href="#w2-account" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">1</span>
                                    Personal
                                </a>
                            </li>
                            <li>
                                <a href="#w2-profile" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">2</span>
                                    Språk och kompetens
                                </a>
                            </li>
                            <li>
                                <a href="#w2-confirm" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">3</span>
                                    Ytterligare information
                                </a>
                            </li>
                        </ul>
                        <form class="form-horizontal" novalidate="novalidate" id="interpreterJobForm">
                            <div class="tab-content">
                                <div id="w2-account" class="tab-pane active">
                                    <div class="form-group mt-lg">
                                        <div class="col-sm-6">
                                            <input type="text" name="firstName"
                                                   class="form-control"
                                                   placeholder="Förnamn"
                                                   data-msg-required="Skriv ditt förnamn."
                                                   data-msg-minlength="Fält förnamn bör innehålla mer än 3."
                                                   data-msg-maxlength="Fält förnamn bör innehålla mindre än 90."
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90"
                                                   data-rule-required="true"
                                                   maxlength="90">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="lastName"
                                                   class="form-control"
                                                   placeholder="Efternamn"
                                                   data-msg-required="Skriv ditt efternamn."
                                                   data-msg-minlength="Fält efternamn bör innehålla mer än 3."
                                                   data-msg-maxlength="Fält efternamn bör innehålla mindre än 90."
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90"
                                                   data-rule-required="true"
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="text" name="personalNumber"
                                                   class="form-control"
                                                   placeholder="Personnummer"
                                                   data-msg-required="Skriv ditt personnummer."
                                                   data-rule-personalNumber="true"
                                                   data-msg-personalNumber="Ditt personnummer måste vara i det här formatet ÅÅÅÅMMDD-XXXX"
                                                   data-rule-required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label
                                                class="control-label">Kön:</label><br/>
                                            <input type="radio" id="genderWoman"
                                                   checked name="gender" value="Kvinna">
                                            <label for="genderWoman">Kvinna</label>
                                            <input type="radio" id="genderMan"
                                                   name="gender" value="Man">
                                            <label for="genderMan">Man</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label">Jag
                                                har:</label><br/>
                                            <input type="radio" id="taxA" checked
                                                   name="tax" value="A-skatt">
                                            <label for="taxA">A-skatt</label>
                                            <input type="radio" id="taxF"
                                                   name="tax" value="F-skatt">
                                            <label for="taxF">F-skatt</label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label">Egen
                                                bil:</label><br/>
                                            <input type="radio" id="carYes" checked
                                                   name="car" value="Ja">
                                            <label for="carYes">Ja</label>
                                            <input type="radio" id="carNo"
                                                   name="car" value="Nej">
                                            <label for="carNo">Nej</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <input type="email" name="email"
                                                   class="form-control"
                                                   placeholder="E-post"
                                                   data-rule-required="true"
                                                   data-msg-required="Skriv ditt e-postadress."
                                                   data-msg-email="Den här e-post är inte giltig."
                                                   maxlength="90">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="phoneHome"
                                                   class="form-control interpreter-phone-group"
                                                   placeholder="Telefon (bostad):"
                                                   data-msg-number="Det ska feiyll nummer."
                                                   data-msg-minlength="Fält hemnummer bör innehålla mer än 8."
                                                   data-msg-maxlength="Fält hemnummer bör innehålla mindre än 11."
                                                   maxlength="11"
                                                   data-rule-number="true"
                                                   data-rule-minlength="8"
                                                   data-rule-maxlength="11"
                                                   data-rule-require_from_group='[1,".interpreter-phone-group"]'
                                                   data-msg-require_from_group="Du måste ange antingen hemnummer eller mobilnummer eller både.">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="phoneMobile"
                                                   class="form-control interpreter-phone-group"
                                                   placeholder="Mobiltelefon:"
                                                   data-msg-number="Det ska feiyll nummer."
                                                   data-msg-minlength="Fält mobilnummer bör innehålla mer än 8."
                                                   data-msg-maxlength="Fält mobilnummer bör innehålla mindre än 11."
                                                   maxlength="11"
                                                   data-rule-number="true"
                                                   data-rule-minlength="8"
                                                   data-rule-maxlength="11"
                                                   data-rule-require_from_group='[1,".interpreter-phone-group"]'
                                                   data-msg-require_from_group="Du måste ange antingen hemnummer eller mobilnummer eller både.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="text" name="address"
                                                   class="form-control"
                                                   placeholder="Gatuadress:"
                                                   data-rule-required="true"
                                                   data-msg-required="Skriv ditt adress."
                                                   data-msg-minlength="Fält plats bör innehålla mer än 5 tecken."
                                                   data-msg-maxlength="Fältet plats bör innehålla mindre än 90."
                                                   maxlength="90"
                                                   data-rule-minlength="5"
                                                   data-rule-maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <input type="text" name="postNumber"
                                                   class="form-control"
                                                   placeholder="Postnummer:"
                                                   data-rule-required="true"
                                                   data-msg-number="Det ska feiyll nummer."
                                                   data-msg-required="Skriv ditt post nummer."
                                                   data-msg-minlength="Fält Postnummer bör innehålla 5 tecken."
                                                   data-msg-maxlength="Fält Postnummer bör innehålla 5 tecken."
                                                   maxlength="5"
                                                   data-rule-minlength="5"
                                                   data-rule-maxlength="5"
                                                   data-rule-number="true">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="city"
                                                   class="form-control"
                                                   placeholder="Postort:"
                                                   data-rule-required="true"
                                                   data-msg-required="Skriv ditt stad."
                                                   data-msg-minlength="Fält stad bör innehålla mer än 3 tecken."
                                                   data-msg-maxlength="Fältet stad bör innehålla mindre än 90."
                                                   maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90">
                                        </div>
                                    </div>
                                </div>
                                <div id="w2-profile" class="tab-pane">
                                    <?php for ($i = 0; $i < 5; ++$i) {
    ?>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <?php if ($i == 0) {
        echo '<label for="language1">Språk:</label>';
    } else {
        echo '';
    } ?>
                                                <input type="text" name="language<?php echo $i; ?>"
                                                       id="language<?php echo $i; ?>"
                                                       class="form-control interpreter-language-group"
                                                       placeholder="<?php echo ($i == 0) ? 'Modersmål' : "Språk $i" ?>:"
                                                       data-rule-minlength="3"
                                                       data-msg-minlength="Fält Språk <?php echo $i; ?> bör innehålla mer än 3 tecken."
                                                       data-msg-maxlength="Fältet Språk <?php echo $i; ?> bör innehålla mindre än 90."
                                                       data-rule-maxlength="90"
                                                    <?php if ($i == 0) {
        ?>
                                                        data-msg-required="Du måste välja minst ett språk."
                                                        data-rule-required='true' <?php

    } ?>>
                                            </div>
                                            <div class="col-sm-6">
                                                <?php if ($i == 0) {
        echo '<label for="languageCompetence1">Kompetens:</label>';
    } else {
        echo '';
    } ?>
                                                <select
                                                    class="form-control input-lg mb-md interpreter-language-competence-group"
                                                    name="langCompetence<?php echo $i; ?>"
                                                    id="langCompetence<?php echo $i; ?>"
                                                    <?php if ($i == 0) {
        ?>
                                                        data-rule-required='true'
                                                        data-msg-required="Välj ett språk från listan." <?php

    } ?>>
                                                    <option value="">Kompetens</option>
                                                    <option
                                                        value="AT- Auktoriserad tolk">
                                                        AT- Auktoriserad tolk
                                                    </option>
                                                    <option
                                                        value="ST - Auktoriserad sjukvårdstolk">
                                                        ST - Auktoriserad
                                                        sjukvårdstolk
                                                    </option>
                                                    <option
                                                        value="RT - Auktoriserad rättstolk">
                                                        RT - Auktoriserad rättstolk
                                                    </option>
                                                    <option
                                                        value="ST &amp; RT - Auktoriserad sjukvårds- och rättstolk">
                                                        ST &amp; RT - Auktoriserad
                                                        sjukvårds- och rättstolk
                                                    </option>
                                                    <option
                                                        value="GT - Godkänd tolk">GT
                                                        - Godkänd tolk
                                                    </option>
                                                    <option value="ÖT - Övrig tolk">
                                                        ÖT - Övrig tolk
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php

} ?>
                                </div>
                                <div id="w2-confirm" class="tab-pane">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"
                                               for="experience">Tidigare
                                            erfarenhet:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="3" name="experience" id="experience"
                                                      style="margin-top: 0; margin-bottom: 0; height: 74px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"
                                               for="education">Tolkutbildning:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="3" name="education" id="education"
                                                      style="margin-top: 0; margin-bottom: 0; height: 74px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"
                                               for="referenceOne">Referens
                                            1:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="3" name="referenceOne" id="referenceOne"
                                                      style="margin-top: 0; margin-bottom: 0; height: 74px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"
                                               for="referenceTwo">Referens
                                            2:</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="3" name="referenceTwo" id="referenceTwo"
                                                      style="margin-top: 0; margin-bottom: 0; height: 74px;"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p>Ja, jag samtycker till och är fullt
                                                införstådd med att den information
                                                jag skickar kan registreras och
                                                lagras av Tolkning i Kristianstad AB enligt
                                                personuppgiftslagen (1998:204).</p>
                                        </div>
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-11">
                                            <div class="checkbox-custom">
                                                <input type="checkbox" name="terms"
                                                       id="w2-terms" data-rule-required="true"
                                                       data-msg-required="Du måste trycka på pul och samtycka.">
                                                <label for="w2-terms">Ja, jag
                                                    samtycker</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger hidden" id="interpreterJobError">
                                        <strong>Fel!</strong> Det gick inte att skicka din ansökan.
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
                                <a>Skicka</a>
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
