<?php
include('../variables.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$contrat = $bdd->query("SELECT * FROM tblContrat WHERE contId ='$id'");
$contrat = $contrat->fetch();
$employer = $contrat['cont_conId'];
$contact = $bdd->query("SELECT conNpa, conLocaliter, conNom, conPrenom, conDateNaissance,natNom,conAdresse, conAvs,
 conBanque, conAgence, conIban FROM tblContact
  LEFT JOIN tblNationaliter on conNationalite = natId WHERE conId = '$employer'");
$contact = $contact->fetch();



use Spipu\Html2Pdf\Html2Pdf;

ob_start();
?>
    <style>
        * {

            margin: 0;
            padding: 0;
            color: #000;
            font-family: "helvetica", sans-serif;

        }

        table {


            width: 100%;
            color: #9A0000;

        }

        td {


            vertical-align: middle;
            text-align: left;

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

        h1 {width: 100%;
            text-align: right;
            font-size: 20px;


          }

        h2 {

            color: #000;
            margin-top: 3mm;
            margin-bottom: 3mm;
            font-size: 14px;
            padding-left: 3mm;
        }
        p {
            text-align: justify;
            margin-bottom: 3mm;


        }


    </style>
    <page backtop="8mm" backbottom="25mm" backleft="15mm" backright="5mm">

    <page_header>
        <table>
            <tr>
                <td style=" vertical-align: top;  padding-left: 5mm"><img style="width: 205mm" src="../img/papier_logo.gif"></td>

            </tr>
        </table>
    </page_header>



        <h1 style="margin-top:-4mm;">CONTRAT DE TRAVAIL <br><br></h1>
        <h2 style="margin-top:-4mm; text-align: right"> Intervenant(e) - année <?php echo $contrat['contAnnee']; ?>  </h2>


        <p style="margin-top: 20mm"> <strong>Entre :</strong> L'Association Cerebral Valais, dénommée ci-après Cerebral Valais </p>

        <p > <strong>Et :</strong> L'employé(e) </p>
        <table style="margin-bottom: ^5mm">
            <tr>
                <td>  Prénom & Nom</td>
                <td> : <strong><?php echo ($contact['conPrenom'].' '.$contact['conNom'])?></strong></td>
            </tr>
            <tr>
                <td>Date de naissance</td>
                <td> : <strong><?php echo dateToUser($contact['conDateNaissance'])?></strong></td>
            </tr>
            <tr>
                <td>Lieu d'origine / Nationalité</td>
                <td> : <strong><?php echo $contact['natNom']?></strong></td>
            </tr>
            <tr>
                <td>Adresse</td>
                <td> : <strong><?php echo $contact['conAdresse'].' '.$contact['conNpa'].' '.$contact['conLocaliter']?></strong></td>
            </tr>
            <tr>
                <td>N° AVS</td>
                <td> : <strong><?php echo $contact['conAvs']?></strong></td>
            </tr>
            <tr>
                <td>Coordonnées bancaires (nom et lieu)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td> : <strong><?php echo $contact['conBanque'].' '.$contact['conAgence']?></strong></td>
            </tr>
            <tr>
                <td>Compte IBAN</td>
                <td> : <strong><?php echo $contact['conIban']?></strong></td>
            </tr>

        </table>

        <h2>1. Fonction / position</h2>
        <p>L’employé(e) est engagé(e) par Cerebral Valais en temps qu’intervenant(e) auprès de personnes en situation de handicap.</p>      
        <p>Les tâches sont stipulées sur le descriptif de fonction remis par le secrétariat en même temps que le contrat. <br><br>La fiche d’intervention définit le lieu de travail.</p>


        <h2>2. Durée du contrat</h2>
        <p>Le présent contrat est conclu pour une période indéterminée dès le <strong><?php echo dateToUser($contrat['contDebut']) ?></strong>. <br>
            Il restera en vigueur pour tous les services de relève de l’année civile en cours.</p>

        <h2>3. Durée du travail / taux d’occupation</h2>

        <p>Les heures de travail sont stipulées sur la fiche d’intervention. L’intervenant s’engage de manière ferme à effectuer les heures stipulées sur la fiche d’intervention reçue et ne peut, sans certificat médical, annuler les interventions prévues. En cas de désistement sans raison valable, l’intervenant devra s’acquitter auprès de Cerebral Valais d’une indemnité minimum de Fr. 100.- . De plus, en fonction des motifs injustifiés de cessation d’activités, des frais de relève en fonction du dommage causé aux personnes concernées peuvent être demandés en sus.  </p>

        <h2>4. Rémunération</h2>

        <p>Le salaire horaire brut s'élève à CHF. 19.60 de l'heure. Les vacances et jours fériés sont indemnisés
        mensuellement sous forme d’une indemnité ajoutée au salaire.</p>
        <p>Les cotisations légales pour l’AVS/AI/APG/AC/LAANP/AF sont déduites du salaire brut. Pour les personnes,
        détentrices de permis B - L, soumises à la retenue de l'impôt à la source communal, cantonal et fédéral la
        déduction est de 10%.</p>
        <p>LPP (prévoyance professionnelle) : il n’a pas été prévu de cotisation. Toutefois, en cas de volume de travail
        important et si le salaire annuel brut soumis est susceptible d’atteindre le montant de CHF. 21’060.- la cotisation à
        la LPP sera retenue et déduite du salaire brut.</p>


        <h2>5. Marquage des heures</h2>

       <p style="margin-bottom: 0">L'employé(e) doit transmettre au bureau de l'Association Cerebral Valais pour le 20 de chaque mois le décompte horaire de travail. Les heures sont indiquées par 1/4 d'heure et sont comptées dès l'arrivée chez la personne en situation de handicap jusqu'au départ. Le salaire est versé mensuellement par l'association pour le 25 (ou le prochain jour ouvrable) de chaque mois. En cas de retour tardif des informations, le salaire sera reporté au mois suivant.</p>
        <page_footer>
            <p style="color: #00AA00; font-size: 10px; text-align: center">
                Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
                ___________________________________________________________________________________________________________________</p>
            <p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse et la Fondation suisse
                en faveur de l’enfant infirme moteur cérébral<br>
                In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das cerebral gelähmte Kind</p>
            <p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>1</strong>  sur <strong>2</strong></p>
        </page_footer>
        </page>

        <page backtop="8mm" backbottom="10mm" backleft="15mm" backright="5mm">
        <h2>6. Assurances</h2>

        <p> L'employé est assuré par l'employeur contre les accidents professionnels, y compris ceux survenant sur le chemin direct entre le domicile et le lieu de travail. Pour les accidents non professionnels, l’employé est assuré selon le contrat de base prévu par la LAA (loi sur l’assurance accident).</p> 
        <p>L’employé n’est pas assuré en cas de maladie, l’employeur se conformant aux obligations légales (échelle bernoise).</p> 


        <h2>7. Frais de déplacement</h2>

        <p>Le déplacement sur le lieu de travail est à la charge de l’intervenant.</p>
        <p>Le transport de personnes ne fait pas partie du cahier des charges. Un tel transport peut être toléré dans les seuls
        cas où le transport est effectué sur mandat de la personne ou son représentant légal qui en supporte les frais. Cas
        échéant la responsabilité de Cerebral est exclue.</p>

        <h2>8. Cessation des rapports de service</h2>

        <p>Chaque partie peut résilier le contrat de travail par écrit pour la fin de chaque mois. La résiliation doit parvenir à
        l'autre partie au plus tard le dernier jour ouvrable du mois de résiliation. Une résiliation tardive est cependant
        acceptée également, mais le délai est reporté d'un mois.
        Les délais de résiliation sont les suivants :</p>

            <table>
                <tr>
                    <td>Durant le temps d'essai	</td>
                    <td>- 7 jours en tout temps,</td>
                </tr>
                <tr>
                    <td>Dès l'engagement définitif&nbsp;&nbsp;</td>
                    <td>- 1 mois pour la fin d'un mois durant la 1ère année de service,</td>
                </tr><tr>
                    <td></td>
                    <td>- 2 mois pour la fin d'un mois de la 2ème à la 9ème année de service</td>
                </tr><tr>
                    <td></td>
                    <td>- 3 mois pour la fin d'un mois dès la 10ème année de service</td>
                </tr>

            </table><br><br>

        <p>En cas de faute grave, l'employeur peut licencier l'employé(e) avec effet immédiat.</p>
        <h2>9. Dispositions générales complémentaires</h2>
            <table>
                <tr>
                    <td> Font partie intégrante du présent contrat :</td>
                    <td>- le descriptif de fonction</td>
                </tr>
                <tr>
                    <td></td>
                    <td><strong>- un extrait du casier judiciaire (à joindre)</strong></td>
                </tr>

            </table><br>

        <p>Des modifications et adjonctions au présent contrat n’ont validité que si elles sont formulées par écrit et
            acceptée mutuellement.<br><br>
        Un extrait récent du casier judiciaire est à joindre au présent contrat. Celui-ci sera valable 3 ans.<br><br>
        L'employé(e) s'engage à garder le secret sur toutes les informations auxquelles il (elle) a accès à sa fonction.<br><br>
        Pour tout ce qui n’est pas prévu dans le présent contrat, les parties s’en remettent aux prescriptions légales
            applicables en la matière.<br><br>
        Pour tous les points qui n'auraient pas été prévus dans le présent contrat, les parties s'en remettent aux
            prescriptions légales applicables en la matière.<br><br>
            Le for juridique est à Sion.</p>

            
            <p style="text-align: Left; margin-top: 5mm; margin-left: 108mm"><strong>Pour l’Association Cerebral Valais</strong>
            <br> Le directeur, <?php echo ('Bruno PERROUD')?> <br><br>Lieu et date: ...............................................<br>Signature:</p>


            <p style="text-align: left; margin-top: mm; margin-top:-22mm">
            <strong>Pour l' employé(e) </strong><br><br><?php echo ($contact['conPrenom'].' '.$contact['conNom'])?> se déclare d’accord <br>avec le présent contrat, établi en double exemplaire<br> et confirme avoir reçu les annexes mentionnées au point 9 <br><br>
            Lieu et date: .....................................................<br>
            Signature:</p>






            <page_footer>
        <p style="color: #00AA00; font-size: 10px; text-align: center">
            Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
            ___________________________________________________________________________________________________________________</p>
        <p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse et la Fondation suisse
            en faveur de l’enfant infirme moteur cérébral<br>
            In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das cerebral gelähmte Kind</p>
        <p style="text-align: right; margin-right: 15mm;font-size: 10px"> page <strong>2</strong>  sur <strong>2</strong></p>
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
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};
