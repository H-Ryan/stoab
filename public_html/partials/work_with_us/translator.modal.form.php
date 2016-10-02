<div class="modal fade" id="translatorModalForm" tabindex="-1" role="dialog"
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

                <section class="panel form-wizard mb-none" id="translatorJobWizard">
                    <div class="tabs">
                        <ul class="nav nav-tabs nav-justify">
                            <li class="active">
                                <a href="#w2-personal" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">1</span>
                                    Personal
                                </a>
                            </li>
                            <li>
                                <a href="#w2-languages" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">2</span>
                                    Språk
                                </a>
                            </li>
                            <li>
                                <a href="#w2-information" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">3</span>
                                    Bakgrundsinformation
                                </a>
                            </li>
                            <li>
                                <a href="#w2-bank" data-toggle="tab"
                                   class="text-center">
                                    <span class="badge hidden-xs">4</span>
                                    Faktura och bank uppgifter
                                </a>
                            </li>
                        </ul>
                        <form class="form-horizontal" novalidate="novalidate" id="translatorJobForm">
                            <div class="tab-content">
                                <div id="w2-personal" class="tab-pane active">
                                    <div class="form-group mt-lg">
                                        <div class="col-sm-6">
                                            <input type="text" name="firstName"
                                                   class="form-control"
                                                   placeholder="Förnamn"
                                                   data-msg-required="Skriv ditt förnamn."
                                                   data-msg-minlength="Fält förnamn bör innehålla mer än 3."
                                                   data-msg-maxlength="Fält förnamn bör innehålla mindre än 90."
                                                   maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90"
                                                   data-rule-required="true"
                                                   required>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="lastName"
                                                   class="form-control"
                                                   placeholder="Efternamn"
                                                   data-msg-required="Skriv ditt efternamn."
                                                   data-msg-minlength="Fält efternamn bör innehålla mer än 3."
                                                   data-msg-maxlength="Fält efternamn bör innehålla mindre än 90."
                                                   maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90"
                                                   data-rule-required="true"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <input type="text" name="personalNumber"
                                                   class="form-control"
                                                   placeholder="Personnummer"
                                                   data-msg-required="Skriv ditt personnummer."
                                                   data-rule-personalNumber="true"
                                                   data-msg-personalNumber="Ditt personnummer måste vara i det här formatet ÅÅÅÅMMDD-NNNN"
                                                   data-rule-required="true"
                                                   required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Kön:</label>
                                            <input type="radio" id="genderWoman" checked
                                                   name="gender" value="Kvinna">
                                            <label for="genderWoman">Kvinna</label>
                                            <input type="radio" id="genderMan"
                                                   name="gender" value="Man">
                                            <label for="genderMan">Man</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="text" name="address"
                                                   class="form-control"
                                                   placeholder="Gatuadress:" required
                                                   data-msg-required="Skriv ditt adress."
                                                   data-msg-minlength="Fält plats bör innehålla mer än 5 tecken."
                                                   maxlength="90"
                                                   data-msg-maxlength="Fält plats bör innehålla mindre än 90."
                                                   data-rule-minlength="5"
                                                   data-rule-maxlength="90"
                                                   data-rule-required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <input type="text" name="phoneHome"
                                                   class="form-control phone-group"
                                                   placeholder="Telefon (bostad):"
                                                   data-msg-number="Det ska feiyll nummer."
                                                   data-rule-number="true"
                                                   data-msg-minlength="Fält hemnummer bör innehålla mer än 8."
                                                   data-msg-maxlength="Fält hemnummer bör innehålla mindre än 11."
                                                   maxlength="11"
                                                   data-rule-minlength="8"
                                                   data-rule-maxlength="11"
                                                   data-rule-require_from_group='[1,".phone-group"]'
                                                   data-msg-require_from_group="Du måste ange antingen hemnummer eller mobilnummer eller både.">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="phoneMobile"
                                                   class="form-control phone-group"
                                                   placeholder="Telefonmobil:"
                                                   data-msg-number="Det ska feiyll nummer."
                                                   data-rule-number="true"
                                                   data-msg-minlength="Fält mobilnummer bör innehålla mer än 8."
                                                   data-msg-maxlength="Fält mobilnummer bör innehålla mindre än 11."
                                                   maxlength="11"
                                                   data-rule-minlength="8"
                                                   data-rule-maxlength="11"
                                                   data-rule-require_from_group='[1,".phone-group"]'
                                                   data-msg-require_from_group="Du måste ange antingen hemnummer eller mobilnummer eller både.">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="email" name="email"
                                                   class="form-control"
                                                   placeholder="E-post" required
                                                   data-msg-required="Skriv ditt e-postadress."
                                                   data-msg-email="Den här e-post är inte giltig."
                                                   data-rule-required="true"
                                                   data-rule-email="true"
                                                   maxlength="90">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <select
                                                class="form-control mb-md subject-group"
                                                name="subject"
                                                data-rule-require_from_group='[1,".subject-group"]'
                                                data-msg-require_from_group="Du måste välja en av de fördefinierade ämnesområden eller ange en specifik i 'Annat' fältet.">
                                                <option value="">Ämnesområde:</option>
                                                <option value="Allmän">Allmän</option>
                                                <option value="Teknik">Teknik</option>
                                                <option value="Medicin">Medicin</option>
                                                <option value="Juridisk">Juridisk
                                                </option>
                                                <option value="Ekonomi">Ekonomi</option>
                                                <option value="Miljö">Miljö</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="otherSubject"
                                                   class="form-control subject-group"
                                                   placeholder="Annat"
                                                   data-msg-minlength="Fält mobilnummer bör innehålla mer än 3."
                                                   data-msg-maxlength="Fält mobilnummer bör innehålla mindre än 90."
                                                   maxlength="90"
                                                   data-rule-minlength="3"
                                                   data-rule-maxlength="90"
                                                   data-rule-require_from_group='[1,".subject-group"]'
                                                   data-msg-require_from_group="Du måste välja en av de fördefinierade Ämnesområden eller ange en specifik i 'Annat' fältet.">
                                        </div>
                                    </div>
                                </div>
                                <div id="w2-languages" class="tab-pane">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <table class="table ">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        Från
                                                    </th>
                                                    <th class="text-center">
                                                        Till
                                                    </th>
                                                    <th class="text-center">
                                                        Pris/Ord
                                                    </th>
                                                    <th class="text-center">
                                                        Pris/Tim
                                                    </th>
                                                    <th class="text-center">
                                                        Auktoriserad av kammarkollegiet
                                                        t.o.m.
                                                    </th>
                                                    <th class="text-center">
                                                        Månad,År
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php for ($k = 1; $k < 5; ++$k) {
    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="from<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="to<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="priceWord<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="priceHour<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="authorized<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="monthOrYear<?php echo $k ?>"
                                                                   class="form-control">
                                                        </td>
                                                    </tr>
                                                <?php
} ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <div id="w2-information" class="tab-pane">
                                    <div class="form-group">
                                        <table class="table mb-none ">
                                            <thead>
                                            <tr>
                                                <th class="text-center">
                                                    Översättareutbildning
                                                </th>
                                                <th class="text-center">
                                                    Utbildningslängdår
                                                </th>
                                                <th class="text-center">
                                                    Examensår
                                                </th>
                                                <th class="text-center">
                                                    Land
                                                </th>
                                                <th class="text-center">
                                                    Språk Från Till
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php for ($g = 1; $g < 4; ++$g) {
    ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="training<?php echo $g ?>"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="period<?php echo $g ?>"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="degree<?php echo $g ?>"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="country<?php echo $g ?>"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="lang<?php echo $g ?>"
                                                           class="form-control">
                                                </td>
                                            </tr>
                                            <?php
} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label"
                                               for="education">Har övriga kurs eller
                                            högskolutbildning eller arbetat som
                                            översättare:</label>
                                        <div class="col-sm-12 mb-none">
                                            <textarea class="form-control" rows="3"
                                                      id="education" name="education"
                                                      style="margin-top: 0; margin-bottom: 0; height: 74px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"
                                               for="referenceOne">Referens för
                                            översättning:</label>
                                        <table class="table mb-none">
                                            <thead>
                                            <tr>
                                                <th class="text-center">
                                                    Namn Efternamn
                                                </th>
                                                <th class="text-center">
                                                    Telefon
                                                </th>
                                                <th class="text-center">
                                                    Jobb & Bransch
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" name="name1"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="telephone1"
                                                           class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="job1"
                                                           class="form-control">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="w2-bank" class="tab-pane">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label
                                                class="control-label">Egenföretagare:</label>
                                            <input class="ml-md" type="radio"
                                                   id="employedYes" checked
                                                   name="employed" value="Ja">
                                            <label for="employedYes">Ja</label>
                                            <input class="ml-md" type="radio" id="employedNo"
                                                   name="employed" value="Nej">
                                            <label for="employedNo">Nej</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="bankgiro"
                                                   class="form-control"
                                                   placeholder="Bankgiro">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label class="control-label">Skickas med en
                                                kopia på F-skattbeviset</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="plusgiro"
                                                   class="form-control"
                                                   placeholder="Plusgiro">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <input type="text" name="bankAccount"
                                                   class="form-control"
                                                   placeholder="Bank konto">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="bankName"
                                                   class="form-control"
                                                   placeholder="Bankensnamn">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <input type="text" name="clearingNumber"
                                                   class="form-control"
                                                   placeholder="Clearing nu">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="bankCardNumber"
                                                   class="form-control"
                                                   placeholder="Kontonummer">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <p class="mb-none">* Översättningar som
                                                förmedlas via Tolkning i Kristianstad AB ska utföras i
                                                enlighet medLagen 1975:689 Tystnadsplikt
                                                för vissa översättare.<br/>
                                                * Översättningen ska levereras i tid och
                                                i enlighet med beställningsvillkor från
                                                förmedlingen.<br/>
                                                * Översättningsarbeten ska godkännes
                                                kvalitet och vara färdig att kund
                                                användas.<br/>
                                                * Din ansökan ska kompletteras bifogas
                                                belastningsregistret från polis
                                                myndigheten.</p>
                                        </div>

                                    </div>
                                    <div class="alert alert-danger hidden" id="translatorJobError">
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
