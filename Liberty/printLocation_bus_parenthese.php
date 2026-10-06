<?php
    include('../variables.php');
    include('./class/locationObject.php');
    setlocale(LC_TIME, 'fra_fra');
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();
    $bdd = new PDO($dsn, $user, $password);
    $pdfValid = true;
    $idLocation = intval($_GET['Id']) > 0 ? intval($_GET['Id']) : null;
    $number_contact = $number_contact = isset($_GET['number_contact']) ? $_GET['number_contact'] : '-';

    if(!$idLocation) $pdfValid = false;
    else $location = new LocationReservation($idLocation);
?>
<style>
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 10pt;
        color: #000;
    }
    
    /* En-tête */
    .header-table {
        width: 100%;
        border: none;
    }
    .header-logo {
        text-align: left;
        vertical-align: middle;
    }
    .header-info {
        text-align: right;
        vertical-align: middle;
        padding-right: 10px;
    }
    .header-title {
        font-size: 10pt;
        margin: 0;
        color: #555; /* Couleur similaire au vert/gris du logo */
    }
    .header-separator {
        border-top: 2px solid #f7c399; /* Vert similaire au logo */
        margin-top: 5px;
        width: 100%;
    }

    /* Titres de section */
    .section-title {
        font-size: 10pt;
        font-weight: bold; 
        margin-top: 15px;
        margin-bottom: 10px;
        text-decoration: underline;
    }
    .orange{
        color: #ed7d31;
        border-bottom: 2px solid #ed7d31;
        text-decoration: none;
    }
    /* Champs et étiquettes */
    .field-row {
        margin-bottom: 8px;
        font-size: 10pt;
    }
    .label {
        font-weight: bold;
        display: inline-block;
        width: 180px; /* Largeur fixe pour l'alignement */
    }
    .input-line {
        border-bottom: 1px solid #000;
        display: inline-block;
        padding: 0 5px;
        min-width: 150px;
        color: #000;
    }
    .input-line-short {
        width: 50px;
    }
    .input-line-long {
        width: 350px;
    }
    
    /* Blocs spécifiques */
    .address-block {
        margin-left: 180px; /* Aligné avec les labels */
        font-style: normal;
        margin-bottom: 10px;
    }
    
    .prices-list {
        list-style-type: none;
        padding-left: 0;
        margin-top: 5px;
    }
    .prices-list li {
        margin-bottom: 6px;
        line-height: 1.4;
    }

    /* Zone de signature */
    .signature-area {
        margin-top: 50px;
        width: 100%;
    }
    .signature-box {
        width: 45%;
        display: inline-block;
        vertical-align: top;
    }
    .signature-line {
        margin-top: 40px;
        border-top: 1px solid #000;
        width: 80%;
    }.pdf-input {
        border: none;           /* Pas de bordure visible */
        background: none;       /* Pas de fond (transparent) */
        padding: 0;
        margin: 0 2px;          /* Très léger espacement pour ne pas coller au texte */
        font-family: Helvetica, Arial, sans-serif;
        font-size: 10pt;        /* Doit correspondre à la taille du texte environnant */
        color: #0000FF;         /* Couleur du texte saisi (ex: bleu pour distinguer) */
        vertical-align: baseline; /* Alignement parfait avec le texte */
        width: 130px;           /* Largeur de la zone de saisie */
        text-align: center;     /* Texte centré dans la zone */
    }
    
    /* Utilitaires */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .bold { font-weight: bold; }
    .highlight { background-color: #FFFF00; color: #000; padding: 0 2px; } /* Surlignage jaune */
</style>

<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">
    <!-- HEADER -->
    <page_header >
        <table class="header-table"style="margin-bottom: 7px;">
            <tr>
                <td class="header-logo" style="width: 40%;">
                    <img src="../logo-parenthese.png" height="80" alt="Logo La Parenthèse">
                </td>
                <td class="header-info" style="width: 60%;">
                    <p class="header-title">Bus - Association la parenthèse</p>
                    <div class="header-separator"></div>
                    <p style="font-size: 9pt; margin-top: 5px;">
                        <span class="input-line" style="width: 100px; background: none; border-bottom: 1px dotted #000;"><?= date('d.m.Y') ?></span>
                    </p>
                </td>
            </tr>
        </table>
    </page_header>

    <h2 class="text-center" style="margin-top: 10px;">CONTRAT DE LOCATION</h2>

    <div style="text-align: center; margin-bottom: 20px; font-size: 11pt;">
        <p style="margin: 5px 0;">
            <strong>Entre l'Association "la parenthèse" (bailleur) d'une part</strong><br>
            c/o Association Cerebral Valais, Av. Tourbillon 9 – 1950 Sion
        </p>
        <p style="margin: 10px 0;"><strong>et</strong></p>
        <p style="margin: 5px 0;">
            <strong><span class=""><?php echo $location->client['societe'] ?? $location->client['name'] ?></span></strong><br>
            <strong><span class=""><?php echo $location->client['adress']; ?></span></strong>
        </p>
    </div>
    <table style="width: 100%; margin-bottom: 5px;">
        <tr>
            <td style="width: 35%; font-weight: bold;">Objet de la location :</td>
            <td style="width: 65%; text-align: left;"><?= $location->locationObject->name ?> - <?= $location->locationObject->immatriculation ?></td>
        </tr>
    </table>
    <table style="width: 100%; margin-bottom: 5px;">
        <tr>
            <td style="width: 35%; font-weight: bold;">Configurations :</td>
            <td style="width: 65%;">
                <span class="text-align: justify; text-justify: inter-word;">
                    <strong>9 places maximum, soit :</strong> 3 sièges à l’avant y compris le
                    chauffeur et, à l’arrière, soit 2 sièges soit de la place pour 4
                    chaises roulantes.
                </span>
            </td>
        </tr>
    </table>
    <p style="padding: 10px;">Il est convenu de ce qui suit :</p>
    <table style="width: 100%; margin-bottom: 5px;">
        <tr>
            <td style="width: 10%; font-weight: bold;">1.</td>
            <td style="width: 90%;">
                Le bus susmentionné faisant l’objet de la location sera loué au tarif de 
                <?php
                    $objectPrices = $location->locationObject->prices;
                    if($objectPrices[0]) printLine("", $objectPrices[0]); 
                    if($objectPrices[1]){
                        printLine("ou", $objectPrices[1]);
                    } 
                    if($objectPrices[2]){
                        printLine("ou", $objectPrices[2]);
                    } 
                    function printLine($or, $givenPrice){
                        if(!$givenPrice['price']) return '';
                        echo $or.' '.$givenPrice['price'].' '.$givenPrice['priceUnity'].' ';
                    }

                    if($location->startDate !== $location->endDate) echo 'aux dates suivantes :';
                    else echo ' À la date suivante : ';
                ?>
            </td>
        </tr>
    </table>
    <strong><p style="width: 100%; text-align: center;">
        <?php 
            if($location->startDate !== $location->endDate) echo 'Du '.$location->startDate.' au '.$location->endDate;
            else echo 'Le '.$location->startDate;
        ?>
    </p></strong>
    <table style="width: 100%; margin-bottom: 5px;">
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">2.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le véhicule se trouve devant la maison de "la parenthèse" (Impasse du Bout de la Forêt 11,
                1898 St-Gingolph). La clé sera remise au locataire au moment de la prise de possession du
                véhicule.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">3.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le chauffeur doit obligatoirement être en possession d’un permis de type B.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">4.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Les kilomètres effectués doivent impérativement être notés dans le carnet de bord au départ et
                à l’arrivée.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">5.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Il est primordial que le conducteur veille à la sécurité des personnes et des biens notamment
                en attachant au sol les chaises avec quatre sangles et la personne sur sa chaise avec les
                ceintures spécifiques.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">6.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;">
                Les éventuels dysfonctionnements liés au véhicule devront être communiqués par  
                <?php if($number_contact !== "") echo $number_contact.' ou par '; ?>
                e-mail à info@laparenthese.ch
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">7.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                La totalité des frais de carburant sont à la charge du preneur/conducteur. Le véhicule est livré
                en état de marche. Les conducteurs sont tenus, en cas de besoin, de refaire le plein d’eau et
                d’huile. Ils sont tenus de conduire le véhicule loué avec la plus grande diligence et d’observer
                toutes les prescriptions légales en vigueur. Lors de la restitution, le véhicule doit être propre et
                le plein de carburant complété.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">8.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                En cas d’accident, le preneur est tenu d’avertir immédiatement le bailleur et la police (si
                nécessaire). Il veillera que soit dressé un croquis de l’accident et relèvera les noms et adresses
                des personnes impliquées dans l’accident ainsi que des témoins. En cas de panne, il est interdit
                d’abandonner le véhicule en panne sur place. En cas d’abandon du véhicule, le bailleur se
                réserve le droit de recours contre le preneur/conducteur.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">9.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le véhicule est couvert par une assurance responsabilité civile dont la franchise s’élève à
                Fr. 2'000.- par évènement (comprenant la franchise assurance, la perte de bonus ainsi que les
                frais occasionnés) à charge du preneur/conducteur.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">10.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le preneur/conducteur est entièrement responsable pour la perte du véhicule et pour tout
                dommage causé à celui-ci.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">11.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le preneur/conducteur est tenu de vérifier le véhicule avant que la location ne débute. S’il
                garde le silence, il sera admis que le véhicule loué était en ordre lors de la remise. Le
                preneur/conducteur encourt entièrement la responsabilité des dommages survenant durant la
                période de location. Un état des lieux sera fait avant la prise du véhicule et au retour.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">12.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                En cas de panne ou d’accident une assistance 24h/24 est incluse dans l'assurance véhicule
                auprès de l'Allianz 0800 22 33 44.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">13.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le bailleur n’assume aucune responsabilité, ni à l’égard du preneur/conducteur, ni envers des
                tiers, pour un dommage résultant d’un accident survenu pendant la période de location, seule
                l’assurance RC ou casco peut dédommager le preneur/conducteur ou un tiers. Le bailleur
                n’encourt pas non plus de responsabilité pour tout dommage résultant d’une défectuosité
                quelconque au véhicule loué qui serait causé au preneur/conducteur et qui l’empêcherait de
                poursuivre sa route, lui occasionnerait une perte de temps ou d’autres dommages indirects.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">14.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                Le Code des obligations est applicable à titre supplétif.
            </td>
        </tr>
        <tr>
            <td style="width: 10%; font-weight: bold; padding: 5px;">15.</td>
            <td style="width: 90%; padding: 5px; text-align: justify;"> 
                En cas de litige résultant du présent contrat, le for est au domicile du bailleur. Le preneur
                déclare expressément qu’il renonce à son for ordinaire du domicile et qu’il se soumet au for
                convenu ici.
                Les dispositions générales du contrat ci-dessus, avec inclusion de la clause attributive de juridiction
                sont partie intégrante du contrat de location automobile.
            </td>
        </tr>
    </table>
    <!-- Signatures -->
     <table style="width: 100%; border: none; border-collapse: separate; margin: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 50%; padding-bottom: 10px;">Fait à St-Gingolph, le <?= date('d.m.Y') ?></td>
            <td style="width: 50%; padding-bottom: 10px; text-align: left;"></td>
        </tr>
    </table>
    <table style="width: 100%; border: none; border-collapse: separate; margin-bottom: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 50%; padding-bottom: 10px;">Date et signature du locataire :</td>
            <td style="width: 50%; padding-bottom: 10px; text-align: left;">Date et signature du bailleur :</td>
        </tr>
    </table>    
    <!-- Footer -->
    <page_footer>
        <div style="width: 100%; left: 0; right: 0; text-align: center; font-size: 8pt; color: #E67E22; border-bottom: 1px solid #E67E22;">
            Association la parenthèse &nbsp;&nbsp;|&nbsp;&nbsp; Tél. +41 79 296 93 97 &nbsp;&nbsp;|&nbsp;&nbsp; www.laparenthese.ch
        </div>
    </page_footer>
</page>
<?php
    try {
        $content = ob_get_clean();
        require _('../vendor/autoload.php');
        $family = 'coucou';
        $style = 'regular';
        $file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');
        $pdf->writeHTML($content);
        $pdf->addFont($family, $style, $file);
        ob_get_clean();
        $pdf->output('contratIntervenant.pdf');
        $pdf->Output('./logement_Parenthese_contracts/contrat-Parenthese-'.$id.'.pdf','F');
    } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        die($e);
        echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    };
?>

<?php
    $content = ob_get_clean();

    require_once('../vendor/autoload.php');
    try{
        $pdf = new HTML2PDF('P', 'A4', 'fr');
        $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');
        $pdf->writeHTML($content);
        $pdf->Output('./bus_Parenthese_contracts/Contrat-Parenthese-' . $id . '.pdf', 'F');
    } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        // Gestion propre des erreurs Html2PDF
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    // Ou logger l'erreur
    } catch (Exception $e) {
        // Gestion des autres erreurs
        echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    }
?>