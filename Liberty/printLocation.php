<?php include_once __DIR__ . '/../src/dateFr.php'; ?><?php
    include('./class/locationObject.php');
    $pdfValid = true;
    $idLocation = intval($_GET['Id']) > 0 ? intval($_GET['Id']) : null;
    if(!$idLocation) $pdfValid = false;
    else $location = new LocationReservation($idLocation);

    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra'); // active les noms français
    date_default_timezone_set('Europe/Zurich'); // adapte le fuseau si besoin
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();

    function formatDateFr($date) {
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra', 'fr_FR'); 
        $timestamp = strtotime($date);
        return strftimeFr("%e %B %Y", $timestamp);
    }
    ?>
<style>
    * {
        margin: 0;
        padding: 0;
        color: #000;
        font-family: "helvetica", sans-serif;
    }
    .justify{
        text-align: justify;
    }
    table {
        width: 100%;
        color: #9A0000;
    }

    td {
        vertical-align: middle;
        text-align: justify;
        padding: 1mm;
    }

    th {
        vertical-align: middle;
        text-align: left;
        color: #00AA33;
        font-size: 12px;
        font-style: normal;
        font-weight: normal !important;
    }

    .footer td {
        vertical-align: bottom
    }

    h1 {
        width: 100%;
        text-align: center;
        font-size: 20px;
    }

    h2 {
        color: #000;
        margin-top: 3mm;
        font-size: 14px;
        padding-left: 3mm;
    }
    p {
        text-align: justify;
        margin-bottom: 3mm;        }
    hr{
        height: 1px;
        margin-top: -1mm;
    }
    .sub-title1{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    .min-input{
        width: min-content;
        vertical-align: center;
    }
    .dates {
        width: 100%;
        text-align: center;
        margin: 3mm 0;
        white-space: nowrap; 
    }

    .dates p {
        display: inline-block;
        margin: 0 2mm 0 0;
        text-align: left;
    }

    .dates-input {
        display: inline-block;
        min-width: 25mm;
        text-align: center;
        border: none;
        border-bottom: 1px solid #000;
    }
</style>
    <page backtop="30mm" backbottom="20mm" backleft="15mm" backright="15mm">
        <!-- HEADER -->
        <page_header>
            <table style="width:100%;">
                <tr>
                    <td style="text-align:left;">
                        <img src="../logo2.png" height="80">
                    </td>
                    <td style="width:40%; text-align:right; vertical-align:middle;">
                        <div style="text-align: right;" style="font-size:14px; line-height:1.7;">
                            Bus - Association Cerebral Valais
                        </div>
                        <div style="border-top:1px solid green; width:100%; margin-left:auto;"></div>
                    </td>
                </tr>
            </table>
        </page_header>
        <!-- FOOTER -->
        <page_footer>
            <table style="width:100%; font-size:10px;">
                <tr>
                    <td style="width: 25%; text-align: center; color: green;">Association Cerebral Valais</td>
                    <td style="width: 25%; text-align: center; color: green;">Tél. 027 346 70 44</td>
                    <td style="width: 25%; text-align: center; color: green;">www.cerebral-valais.ch</td>
                    <td style="width: 25%; text-align: center; color: green;">CH29 8080 8007 2513 0849 6</td>
                </tr>
            </table>
            <hr style="width: 100%; color: green;"/>
            <div style="text-align:center; font-size: 12px;">
                <p style="text-align:center; margin: 0;">En étroite collaboration avec l’association Cerebral Suisse et la Fondation suisse en faveur de l’enfant infirme moteur cérébral</p>
                <p style="text-align:center; margin: 0;">In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das cerebral gelähmte Kind</p>
            </div>
        </page_footer>
        <!-- onglet de titre avec bailleur et client -->
        <div>
            <h1 style="text-align: center; margin-bottom: 15px;">CONTRAT DE LOCATION</h1>
            <hr/>
            <h4  style="text-align: center;">Entre l’Association Cerebral Valais (bailleur), d’une part</h4>
            <p style="text-align: center;">Av. Tourbillon 9, 1950 Sion</p>
            <p style="text-align: center;">et</p>
            <h4 style="text-align: center;"><?php echo $location->client['societe']; ?></h4>
            <p style="text-align: center; margin-bottom: 15px;">
                <?php 
                    echo $location->client['name'] ? 
                        $location->client['name'].', '.$location->client['adress']
                        : $location->client['adress']; 
                ?>
            </p>
        </div>
        <!-- onglet de titre avec bailleur et client -->
         <table style="margin-top: 20px;">
            <tr>
                <td style="width: 200px;"><strong>Objet de la location:</strong></td>
                <td style="width: 200px;"><?php echo $location->locationObject->name.' - '.$location->locationObject->immatriculation; ?></td>
            </tr>
            <tr>
                <td><strong>Configuration:</strong></td>
                <td><?php echo '9 places dont '.$location->locationObject->personMax.' sièges.'; ?></td>
            </tr>
         </table>
         <p style="margin-top: 25px;">Il est convenu de ce qui suit: </p>
        <table>
            <tr>
                <td style="vertical-align: middle;">1.</td>
                <td>
                    Le bus susmentionné faisant l’objet de la location sera loué au tarif de
                    <?php
                        foreach($location->locationObject->prices as $index => $price){
                            if($index !== 0 && $price['priceUnity'] && $price['price']) echo ' ou';
                            if($price['priceUnity'] && $price['price']){
                                echo ' '.$price['price'].'.- '.$price['priceUnity'];
                            }
                        }
                    ?>
                    <?php /* 
                        echo '<ul style="list-style-type: disc;">'; 
                        foreach($location->locationObject->prices as $price){ 
                            if($price['priceUnity'] && $price['price']){ 
                                echo '<li>'.$price['price'].'.- '.$price['priceUnity'].'</li>'; 
                            } 
                        } 
                        echo '</ul>'; */ 
                    ?>
                    pour un minimum de 100.- pour la location.
                </td>
            </tr>
        </table>
        <div style="width: 100%; padding-top: 15px; padding-bottom: 15px;">
            <h1>
                <?php echo 'du '.formatDateFr($location->startDate).' au '.formatDateFr($location->endDate); ?> 
            </h1>
        </div>    
        <table>
            <tr>
                <td>2.</td>
                <td>Le véhicule se trouve aux logements du Botza à Vétroz (Route de la Zone Industrielle 2) sous le couvert à bus. La clé sera disponible au bureau de l'Association Cerebral Valais, à l'Av. de Tourbillon 9, du lundi au vendredi de 08h00 à 17h00.</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Le chauffeur doit obligatoirement être en possession d’un <strong>permis de type B.</strong></td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Les kilomètres effectués doivent impérativement être notés dans le carnet de bord au départ et à l’arrivée.</td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Il est primordial que le conducteur veille à la sécurité des personnes et des biens notamment en attachant au sol les chaises avec quatre sangles et la personne sur sa chaise avec les ceintures spécifiques.</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Les éventuels dysfonctionnements liés au véhicule devront être communiqués par téléphone au 079 717 10 91 ou par e-mail <strong>info@cerebral-vs.ch.</strong></td>
            </tr>
            <tr>
                <td>7.</td>
                <td>La totalité des frais de carburant sont à la charge du preneur/conducteur. Le véhicule est livré en état de marche. Les conducteurs sont tenus, en cas de besoin, de refaire le plein d’eau et d’huile. Ils sont tenus de conduire le véhicule loué avec la plus grande diligence et d’observer toutes les prescriptions légales en vigueur. Lors de la restitution, le véhicule doit être propre et le plein de carburant complété.</td>
            </tr>
            <tr>
                <td>8.</td>
                <td>En cas d’accident, le preneur est tenu d’avertir immédiatement le bailleur et la police (si nécessaire). Il veillera que soit dressé un croquis de l’accident et relèvera les noms et adresses des personnes impliquées dans l’accident ainsi que des témoins. En cas de panne, il est interdit d’abandonner le véhicule en panne sur place. En cas d’abandon du véhicule, le bailleur se réserve le droit de recours contre le preneur/conducteur.</td>
            </tr>
            <tr>
                <td>9.</td>
                <td>Le véhicule est couvert par une assurance responsabilité civile dont la franchise s’élève à Fr. 2'000.- par évènement (comprenant la franchise assurance, la perte de bonus ainsi que les frais occasionnés) à charge du preneur/conducteur.</td>
            </tr>
            <tr>
                <td>10.</td>
                <td>Le preneur/conducteur est entièrement responsable pour la perte du véhicule et pour tout dommage causé à celui-ci. </td>
            </tr>
            <tr>
                <td>11.</td>
                <td>Le preneur/conducteur est tenu de vérifier le véhicule avant que la location ne débute. S’il garde le silence, il sera admis que le véhicule loué était en ordre lors de la remise. Le preneur/conducteur encourt entièrement la responsabilité des dommages survenant durant la période de location. </td>
            </tr>
            <tr>
                <td>12.</td>
                <td>En cas de panne ou d’accident à l’étranger, le véhicule est assuré auprès du Touring Club Suisse (TCS). Les documents se trouvent dans les boîtes à gants du véhicule. </td>
            </tr>
            <tr>
                <td>13.</td>
                <td>Le bailleur n’assume aucune responsabilité, ni à l’égard du preneur/conducteur, ni envers des tiers, pour un dommage résultant d’un accident survenu pendant la période de location, seule l’assurance RC ou casco peut dédommager le preneur/conducteur ou un tiers. Le bailleur n’encourt pas non plus de responsabilité pour tout dommage résultant d’une défectuosité quelconque au véhicule loué qui serait causé au preneur/conducteur et qui l’empêcherait de poursuivre sa route, lui occasionnerait une perte de temps ou d’autres dommages indirects.</td>
            </tr>
            <tr>
                <td>14.</td>
                <td>Le Code des obligations est applicable à titre supplétif.</td>
            </tr>
            <tr>
                <td>15.</td>
                <td>En cas de litige résultant du présent contrat, le for est au domicile du bailleur (Sion). Le preneur déclare expressément qu’il renonce à son for ordinaire du domicile et qu’il se soumet au for convenu ici.</td>
            </tr>
        </table>
        <p>Les dispositions générales du contrat ci-dessus, avec inclusion de la clause attributive de juridiction sont partie intégrante du contrat de location automobile.</p>
       <div class="dates">
            <p>Fait à <input style="width: 50px; vertical-align: middle;" class="dates-input" type="text" value="Sion" />, le <input class="dates-input" type="text" value="<?php echo strftimeFr('%d %B %Y'); ?>" /></p>       
        </div>
        <table style="width:100%; margin-top:10mm;">
            <tr>
                <td style="text-align:center; width: 50%;">Date et signature du locataire :</td>
                <td style="text-align:center; width: 50%;">Date et signature du bailleur :</td>
            </tr>
            <tr style="margin-bottom: 25px;">
                <td style="width: 50%;"></td>
                <td style="text-align:center; width: 50%; font-size: 10px;"><input style="width: 50px; vertical-align: middle;" class="dates-input" type="text" value="Sion" />, le <input class="dates-input" type="text" value="<?php echo strftimeFr('%d %B %Y'); ?>" /></td>
            </tr>
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center;">
                    <img src="../sign.png" style="width: 50%;"/>
                </td>
            </tr>
        </table>
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
        $pdf->Output('../pdfContrats/ContratN°'.$id.'.pdf','F');
    } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        die($e);
    };
?>