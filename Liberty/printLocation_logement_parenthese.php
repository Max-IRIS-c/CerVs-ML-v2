<?php
    include('../variables.php');
    include('./class/locationObject.php');
    setlocale(LC_TIME, 'fra_fra');
    use Spipu\Html2Pdf\Html2Pdf;
    ob_start();
    $bdd = new PDO($dsn, $user, $password);

    $pdfValid = true;
    $idLocation = intval($_GET['Id']) > 0 ? intval($_GET['Id']) : null;
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
    }
    
    /* Utilitaires */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .bold { font-weight: bold; }
    .highlight { background-color: #FFFF00; color: #000; padding: 0 2px; } /* Surlignage jaune */
</style>

<page backtop="20mm" backbottom="20mm" backleft="15mm" backright="15mm">
    <!-- HEADER -->
    <page_header>
        <table class="header-table">
            <tr>
                <td class="header-logo" style="width: 40%;">
                    <img src="../logo-parenthese.png" height="80" alt="Logo La Parenthèse">
                </td>
                <td class="header-info" style="width: 60%;">
                    <p class="header-title">Maison de la parenthèse – St-Gingolph</p>
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
    <!-- Coordonnées -->
    <table style="width: 100%; border: none; margin-bottom: 15px;">
        <tr>
            <!-- COLONNE GAUCHE : Responsable Administratif -->
            <td style="width: 50%; vertical-align: top; padding-right: 30px;">
                <div class="section-title">Coordonnées du responsable administratif</div>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">Nom et prénom :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->adminResponsable['nameAndFirstname'] ?></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">Téléphone :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->adminResponsable['phone'] ?></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">E-mail :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->adminResponsable['mail'] ?></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 30px; border-left: 1px solid grey;">
                <div class="section-title">Responsable du groupe</div>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">Nom et prénom :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->groupResponsable['nameAndFirstname'] ?></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">Natel :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->groupResponsable['phone'] ?></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 40%;">E-mail :</td>
                        <td style="width: 60%; text-align: right; font-weight: bold;"><?= $location->groupResponsable['mail'] ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Produit -->
    <div class="section-title orange">Produit</div>
    <table style="width: 100%; border: none; border-collapse: separate; margin-bottom: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">Logement loué : </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left; font-weight: bold;"><?= $location->locationObject->name ?></td>
        </tr>
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">Nombre de personnes : </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                <strong><?= $location->infos['locNbrPers'] ?> personne<?php echo intval($location->infos['locNbrPers']) > 1 ? 's' : null ?></strong>
                <br/>(dont env. <?= $location->infos['locNbrPersAcc'] ?> personne<?php echo intval($location->infos['locNbrPersAcc']) > 1 ? 's' : null ?> en situation de handicap) 
            </td>
        </tr>
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">Dates : </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                du <?= $location->startDate ?> <span style="text-decoration: underline; font-weight: bold;">arrivée à 11h00 </span>
                <br/>au <?= $location->endDate ?> <span style="text-decoration: underline; font-weight: bold;">départ à 17h00 </span>
            </td>
        </tr>
    </table>
    <!-- Prix - Conditions -->
    <div class="section-title orange">Prix - Conditions</div>
    <div style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; margin-bottom: 10px;">
        <strong>Conditions générales de location et instructions de séjour :</strong><br>
        En signant le présent contrat, le locataire s'engage à les respecter.
    </div>
    <table style="width: 100%; border: none; border-collapse: separate; margin-bottom: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">
                <strong>Tarifs <?= date("Y"); ?></strong> : 
                <br/><span style="font-size: 7pt;">(* TVA 8% non comprise)</span>
            </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                <strong><?php 
                    $givenPrice = $location->locationObject->prices[0];
                    echo $givenPrice['price'].' '.$givenPrice['priceUnity'];
                ?></strong> y compris moyens auxiliaires et adaptations      
            </td>
        </tr>
        <tr>
            <td style="width: 40%; padding-bottom: 10px;"></td> 
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                <strong>Repas non-compris :</strong> Cuisine équipée à disposition ou possibilité de se faire livrer les repas.      
            </td>
        </tr>
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">
                <strong>Acompte</strong> (20% de la location) : 
                <br/>Montant à régler sous 30 jours.
            </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                Une facture sera envoyée dès réception du contrat signé.   
            </td>
        </tr>
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">
                <strong>Paiement du solde</strong> :
            </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                Sur facture à la fin du séjour. Payable à 30 jours.  
            </td>
        </tr>        
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">
                <strong>Nettoyage</strong> : 
            </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                Par vos soins à la fin du séjour. Selon check list remise à l'arrivée
            </td>
        </tr>       
        <tr>
            <td style="width: 40%; padding-bottom: 10px;">
                <strong>Charges supplémentaires</strong> : 
            </td>
            <td style="width: 60%; padding-bottom: 10px; text-align: left;">
                Machine à laver et/ou séchoir sur demande
            </td>
        </tr>
    </table>
    <!-- Signatures -->
     <table style="width: 100%; border: none; border-collapse: separate; margin-bottom: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 50%; padding-bottom: 10px;">Signature du locataire :</td>
            <td style="width: 50%; padding-bottom: 10px; text-align: left;">Signature du bailleur :</td>
        </tr>
    </table>
    <table style="width: 100%; border: none; border-collapse: separate; margin-bottom: 15px; padding-left: 40px; padding-right: 40px;">
        <tr>
            <td style="width: 50%; padding-bottom: 10px;">Lieu et date :</td>
            <td style="width: 50%; padding-bottom: 10px; text-align: left;">Lieu et date :</td>
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
        $pdf->Output('./logement_Parenthese_contracts/ContratN°' . $id . '.pdf', 'F');
    } catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
        // Gestion propre des erreurs Html2PDF
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    // Ou logger l'erreur
    } catch (Exception $e) {
        // Gestion des autres erreurs
        echo "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    }
?>