<?php
/**
 * User: Samuil
 * Date: 18-06-2015
 * Time: 10:59 AM.
 */
ini_set('session.use_only_cookies', true);
ini_set('session.use_trans_sid', false);
session_start();
include './functions.php';
include '../email/Emails.php';

$referrer = $_SERVER['HTTP_REFERER'];
if (!empty($referrer)) {
    $uri = parse_url($referrer);
    if ($uri['host'] != $_SERVER['HTTP_HOST']) {
        exit("Form submission from $referrer not allowed.");
    }
} else {
    exit("Referrer not found. Please <a href='".$_SERVER['SCRIPT_NAME']."'>try again</a>.");
}
$emailer = new Emails();
$data = array();
$toEmail = 'rekrytering@c4tolk.se';

if (isset($_POST['name']) && isset($_POST['personalNumber']) && !empty($_POST['address']) && !empty($_POST['email']) && !empty($_POST['city_date'])) {
    $name = $_POST['name'];
    $personalNumber = $_POST['personalNumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $city_date = $_POST['city_date'];
    $subject = 'Samarbetsavtal';
    $tolk_subject = 'Tolkning i Kristianstad AB - Samarbetsavtal';
    $messageToTolkAssign = "<!DOCTYPE html>
            <head>
                <meta http-equiv='Content-Type' content='text/html' charset='utf-8'>
            </head>
            <body>
            <div class='col-md-10 col-md-push-1' style='border-style: inset;'>
              <h2 style='text-align: center;'>Samarbetsavtal</h2>
              <p class='lead' style='text-align: justify;'>
                Denna överenskommelse syftar till att parterna tillsammans skall verka för en positiv utveckling av branschen och bransch förhållandena ,och även bidra till ökad status på tolkyrket samt förbättra arbetsvillkoren för tolkarna. Denna överenskommelse syftar till att parterna tillsammans skall verka för en positiv utveckling av branschen och branschförhållandena, och även bidra till ökad status på tolkyrket samt förbättra arbetsvillkoren för tolkarna.
                Parterna skall verka för att branschen får en positiv utveckling i fråga om kompetensförsörjning, kvalitetsramar, utbildning, upphandlingsdirektiv och förmedlingsarbete.
              </p>
              <div class='row'>
                <div class='col-md-10 col-md-push-1'>
                  <p class='lead' style='font-weight: bold;'>Målet är att parterna tillsammans skall bidra till:</p>
                  <ul style='text-align: left;' class='lead' >
                    <li>Ökad status och därmed ökad rekrytering till tolkyrket</li>
                    <li>Ökat antal tolkar med högsta kompetens.</li>
                    <li>Påverka för utveckling av utbildning och kvalitetsramar, som exempelvis Kammarkollegiets auktorisation.</li>
                    <li>Informera och bidra till att ansvariga upphandlare får en ökad förståelse för sambandet mellan relevanta anbudsvillkor och hög tjänstekvalitet.</li>
                  </ul>
                  <p class='lead' style='text-align: justify;'>Överenskommelsens syfte är att genom samverkan mellan Vision och språkförmedlingen undanröja tvister samt verka för att kvalitetsutbudet av tolkar höjs.
                    Denna överenskommelse skall innefatta de tolkar som tar sina uppdrag via <b>Tolkförmedlingen Tolkning i Kristianstad AB</b>
                  </p>
                </div>
              </div>
              <p class='lead' style='font-weight: bold;'>§ 1 PRIORITERING HOS Tolkning i Kristianstad AB</p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <p class='lead'>Vid tillsättning av uppdrag skall de tolkar som äger högsta kompetens prioriteras. Prioriteringsordning innebär att:</p>
                <ul style='text-align: left;' class='lead' >
                  <li >I första hand skall de tolkar som av Kammarkollegiet erhållit specialkompetens för sjukvårdstolkning eller/och rättstolkning prioriteras för de uppdrag som ligger inom kompetensområdet eller kräver sådan kompetens samt arvoderas avtalsmässigt för specialkompetens.
                  </li>
                  <li>I andra hand skall av Kammarkollegiet auktoriserad tolkprioriteras.</li>
                  <li>I de språk där auktorisation saknas samt då det saknas tillgång till auktoriserad tolk av annan anledning skall tolk som genomgått fullständigt tolkutbildningprioriteras.</li>
                  <li>Skulle annan tolk än de ovannämnda anlitas är minimum kravet att denna skall ha genomgått introduktionskursen ”tolkteknik/tolketik” samt testas med TSR:s lämplighetstest för tolkaspiranter. Idag aktiva tolkar som formellt inte genomgått teoretisk utbildning motsvarande ovanstående grundutbildning, men som ändå förvärvat motsvarande kompetens genom lång yrkesutövning och praktisk arbete skall likställas medminimumkravet.</li>
                </ul>
                <p class='lead' >För uppdrag hos Migrationsverket, familjerätten, socialförvaltningen eller försäkringskassan skall uppdraget arvoderas som tolkning motsvarande specialkompetens om uppdraget är sådan art och svårighet att specialkompetens erfordras, samt att uppdraget utförs av en tolk som innehar sådan kompetens som uppdraget kräver. En förutsättning för att detta ska kunna tillämpas är att kunden godkänner detta,
                  All tolkning som innefattas av Domstolsverkets taxor för tolkar skall arvoderas enligt denna förordning.
                  I denna överenskommelse är parterna överens om att <b>Tolkning i Kristianstad AB</b> ´s prioritering av tolk även tar hänsyn till:
                </p>
                <ul style='text-align: left;' class='lead' >
                  <li>Närhetsprincipen</li>
                  <li>Kundönskemål</li>
                  <li>Lojalitet och tillgänglighet för förmedlingen</li>
                  <li>Avvikelser/allmänna kvalitetsbedömningar</li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§ 2 KONTAKTTOLKNING</p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <ul style='text-align: left;' class='lead'>
                  <li>Kontakttolkning arvoderas alltid för minst en timme om ingen annan överenskommelse finns mellan Vision-tolk och Tolkning i Kristianstad AB.</li>
                  <li>Tolkning i Kristianstad AB skall försöka verka för att upphandlande myndighetdefinierar förfrågningsunderlaget och skall-kraven utifrån denna princip.</li>
                  <li>Tolk har rätt till full ersättning för varje utdebiterat/slutfört uppdrag samt uppdrag som omfattas av beskrivning i §4. I de fall felbokning/dubbelbokning sker utan hans/hennes påverkan skall tolken hållas skadelös och erhålla ersättning för uppdraget samt uppkomna kostnader. Detta förutsatt att Tolkning i Kristianstad AB och tolk är överens gällande den aktuellabokningen.</li>
                  <li>Platstolkning skall i möjligaste mån följa närhetsprincipen ochmiljöcertifieringstänkandet varvid högst meriterande tolk med bostadsadress närmast tolkstället skallprioriteras.</li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§ 3 AVBESTÄLLNINGSTID</p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <ul style='text-align: left;' class='lead' >
                  <li>Alla uppdrag som återkallas senare än 18 timmar före uppdraget skall arvoderas som utfört tolkuppdrag för den tiden uppdraget gällde inklusive eventuellt uppkomna kostnader så som påbörjad resa och dyl</li>
                  <li>Erbjuds tolken ersättningsuppdrag skall detta uppdrag motsvara det avbeställda uppdraget i tid, plats och kompetensnivå .Skulle ersättningsuppdraget inte motsvara det avbeställda uppdragets villkor i tid, plats kompetens skall mellanskillnad utgå. Denna punkt ärdispositiv.</li>
                  <li>Ett uppdrag anses vara avbokat vid den tidpunkten då tolkförmedlaren meddelat tolken detta antingen via E-post, sms eller telefon.</li>
                  <li>Avvikelser/allmänna kvalitetsbedömningar</li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§4 TOLKARS TILLGÄNGLIGHET & ANBPKNING</p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <ul style='text-align: left;' class='lead' >
                  <li>Avbokar tolken uppdraget (utan giltig orsak) senare än 18 timmar så debiteras tolken en avgift på 100 kr.
                    Är avbokningen av sådan karaktär att <b>Tolkning i Kristianstad AB</b> inte har möjlighet att tillsätta uppdraget
                    och företagets kund kräver vite är tolk skyldig att godkänna detta om tolken inte har giltiga skäl så som
                    dokumenterad sjukdom eller dylikt. Denna punkt förutsätter att det finns en dialog mellan tolken och
                    <b>Tolkning i Kristianstad AB</b> och att man löser detta i dialog ochsamförstånd.
                  </li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§ 5 FÖRUTSÄTTNINGAR FÖR ERSÄTTNING</p>
              <p class='lead'>
                Om tolk är försenad till tolkuppdraget, skall motsvarande tid dras från arvodet. Ovanstående gäller dock inte
                om förseningen beror på omständigheter som tolken inte kunnat påverka själv, dvs. force majeure eller sjukdom.
                Uteblir tolk från ett uppdrag utan att ha meddelat <b>Tolkning i Kristianstad AB</b> om orsaken, kommer avdrag
                att ske. Avdraget kommer motsvara den ersättningen som skulle ha utgått om uppdraget utförts.
                Kräver kunden Vite – görs motsvarande avdrag på tolkens kommande ersättning hos <b>Tolkning i Kristianstad AB</b>.
                Krav på ersättning för utförda uppdrag, ska lämnas in snarast, dock senast första vardagen i nästkommande månad.
                Sker inte detta kommer uppdraget att arvoderas utifrån beställd tid.
              </p>
              <p class='lead' style='font-weight: bold;'>§6 FRISKVÅRD och FÖRSÄKRING</p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <ul style='text-align: left;' class='lead' >
                  <li>Tolkar som tolkar mer än 500 timmar/år ersätts med 500 kr mot uppvisat kvitto gällande någon form av friskvård. Därefter sjunker summan med antal timmar (t.ex. 252 timmar = 252 kr).Denna punkt är dispositiv.</li>
                  <li>Vid krav från uppdragsgivaren på vaccinationsskydd, skall kostnaderna för att erhålla skyddet bestridas av förmedling.</li>
                  <li>Tolk som anlitas frekvent av förmedlingen erbjuds möjlighet till professionell handledning.</li>
                  <li>Olycksfallsförsäkring som gäller för tolken under tiden tolken utför uppdraget åt förmedlingen skall tecknas och skall även gälla resa till och från uppdraget.</li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§7 KOMPETENSUTVECKLING</p>
              <p class='lead'>
                Tolk som är registrerad vid <b>Tolkning i Kristianstad AB</b> och tolkar kontinuerligt erhåller ersättning för:
              </p>
              <div class='row'>
              <div class='col-md-10 col-md-push-1'>
                <ul style='text-align: left;' class='lead' >
                  <li>Anmälningsavgiften i samband med avläggande av auktorisationsprov hos Kammarkollegiet, samt ett andra försök.</li>
                  <li>Övriga tolkutbildningar, arrangerade av godkänd tolkutbildningsanordnare, bidrar förmedlingen med minst halva anmälningsavgiften om tolken arbetar endast för berörd förmedling. Anlitas tolk av fler förmedlingar reduceras avgiften i relation till tolkens tillgänglighet. Denna punkt är dispositiv.</li>
                  <li>Genom förmedlingen erbjuda diverse utbildningar.</li>
                </ul>
              </div>
            </div>
              <p class='lead' style='font-weight: bold;'>§8 STATISTIK</p>
              <p class='lead'>Vision har rätt att ta del av förmedlingens tillsättningsstatistik avseende kompetensnivåer, dock högst två gånger per år och med en periodicitet om 6 månader.</p>
              <p class='lead' style='font-weight: bold;'>§ 9 TVISTER</p>
              <p class='lead'>Skulle det förekomma tvist mellan tolkförmedling och ansluten medlem skall facklig tolkrepresentant samt representant från tolkorganisation informeras och beredas möjlighet att bisitta/representera medlemmen.</p>
              <p class='lead' style='font-weight: bold;'>§ 10 ÖVRIGT</p>
              <p class='lead'>Detta avtal är ett komplement till arvodesavtalet.</p>
              <p class='lead' style='text-align: center; font-weight: bold;'>
                Med vänliga hälsning <br>
                Tolkning i Kristianstad Verkställande Direktör Bayaneh Rahmani
              </p>
            </div>
            <table style='width: 80%; margin-left: 10%; margin-right: 10%; text-align: center;
                    font-family: verdana, arial, sans-serif; font-size: 14px; color: #fff;
                    border-radius: 5px; border: 3px solid #424242;' cellpadding='10'>
                <thead></thead>
                <tbody>
                <tr>
                    <td style='background-color: #ff9900; padding: 8px; border: 1px solid #fff;'>
                        <p><span style='font-weight:bold;'>Namnförtydligande:</span> " .$name."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #ff9900; padding: 8px; border: 1px solid #fff;'>
                        <p><span style='font-weight:bold;'>Personnummer:</span> " .$personalNumber."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #ff9900; padding: 8px; border: 1px solid #fff;'>
                        <p><span style='font-weight:bold;'>Adress:</span> " .$address."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #ff9900; padding: 8px; border: 1px solid #fff;'>
                        <p><span style='font-weight:bold;'>Email adress:</span> " .$email."</p>
                    </td>
                </tr>
                <tr>
                    <td style='background-color: #ff9900; padding: 8px; border: 1px solid #fff;'>
                        <p><span style='font-weight:bold;'>Ort/Datum:</span> " .$city_date."</p>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr style='width: 80%;margin-left: auto; margin-right: auto;'/>
            </body>
            </html>";

    $data['error'] = ($emailer->send_email($toEmail, 'Tolkning i Kristianstad AB', $subject, $messageToTolkAssign)) ? 0 : 1;
    $data['error'] = ($emailer->send_email($email, $name, $tolk_subject, $messageToTolkAssign)) ? 0 : 1;
    $data['messageHeader'] = 'Framgång';
    $data['positiveMessage'] = 'E-postmeddelandet har skickats.';
    echo json_encode($data);
} else {
    $data['error'] = 3;
    $data['messageHeader'] = 'Fields Missing Error';
    $data['errorMessage'] = 'Some of the required fields are missing!';
    echo json_encode($data);
}
