<?php
session_start();

include('../variables.php');

include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
$mrp = new Mrp();
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$contact = $bdd->query("SElect conNom, conPrenom, conId, conDateNaissance FROM tblContact WHERE conId ='$id'");
$contact = $contact->fetch();
$infoMedical = $bdd->query("SELECT * FROM tblMedical
 LEFT JOIN tblDegreImpot on medDegre = impId where medConId = '$id'");

$infoMedical = $infoMedical->fetch();
$infoSocial = $bdd->query("SELECT * FROM tblSocial where socConId = '$id'");
$infoSocial = $infoSocial->fetch();
$medical = $bdd->query("SElECT * FROM tblMedication WHERE medicConId='$id'");

$enfantDe = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblSocial INNER JOIN tblContact on socPere = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE socConId = '$id')as telTyp1,(select tTelNom from tblSocial INNER JOIN tblContact on socPere = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE socConId = '$id')as telTyp2,(select tTelNom from tblSocial INNER JOIN tblContact on socPere = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE socConId = '$id')as telTyp3 FROM tblSocial
  INNER JOIN tblContact on socPere = conId WHERE socConId = '$id'");
$enfantDe = $enfantDe->fetch();

$enfantEtDe = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblSocial INNER JOIN tblContact on socMere = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE socConId = '$id')as telTyp1,(select tTelNom from tblSocial INNER JOIN tblContact on socMere = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE socConId = '$id')as telTyp2,(select tTelNom from tblSocial INNER JOIN tblContact on socMere = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE socConId = '$id')as telTyp3 FROM tblSocial
  INNER JOIN tblContact on socMere = conId WHERE socConId = '$id'");
$enfantEtDe = $enfantEtDe->fetch();

$tuteur = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblSocial INNER JOIN tblContact on socTuteur = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE socConId = '$id')as telTyp1,(select tTelNom from tblSocial INNER JOIN tblContact on socTuteur = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE socConId = '$id')as telTyp2,(select tTelNom from tblSocial INNER JOIN tblContact on socTuteur = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE socConId = '$id')as telTyp3 FROM tblSocial
  INNER JOIN tblContact on socTuteur = conId WHERE socConId = '$id'");
$tuteur = $tuteur->fetch();

$Institution = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblSocial INNER JOIN tblContact on socInstitution = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE socConId = '$id')as telTyp1,(select tTelNom from tblSocial INNER JOIN tblContact on socInstitution = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE socConId = '$id')as telTyp2,(select tTelNom from tblSocial INNER JOIN tblContact on socInstitution = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE socConId = '$id')as telTyp3 FROM tblSocial
  INNER JOIN tblContact on socInstitution = conId WHERE socConId = '$id'");
$Institution = $Institution->fetch();



$urgence = $bdd->query("SELECT conNom,conPrenom,conTel1,conTel2,conTel3,
  (select tTelNom from tblSocial INNER JOIN tblContact on socUrgence = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE socConId = '$id')as telTyp1,(select tTelNom from tblSocial INNER JOIN tblContact on socUrgence = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE socConId = '$id')as telTyp2,(select tTelNom from tblSocial INNER JOIN tblContact on socUrgence = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE socConId = '$id')as telTyp3 FROM tblSocial
  INNER JOIN tblContact on socUrgence = conId WHERE socConId = '$id'");
$urgence = $urgence->fetch();

$medecin1 = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblMedical INNER JOIN tblContact on medMedecin1 = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE medConId = '$id')as telTyp1,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin1 = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE medConId = '$id')as telTyp2,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin1 = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE medConId = '$id')as telTyp3 FROM tblMedical
  INNER JOIN tblContact on medMedecin1 = conId WHERE medConId = '$id'");
$medecin1 = $medecin1->fetch();

$medecin2 = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblMedical INNER JOIN tblContact on medMedecin2 = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE medConId = '$id')as telTyp1,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin2 = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE medConId = '$id')as telTyp2,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin2 = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE medConId = '$id')as telTyp3 FROM tblMedical
  INNER JOIN tblContact on medMedecin2 = conId WHERE medConId = '$id'");
$medecin2 = $medecin2->fetch();

$medecin3 = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblMedical INNER JOIN tblContact on medMedecin3 = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE medConId = '$id')as telTyp1,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin3 = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE medConId = '$id')as telTyp2,(select tTelNom from tblMedical INNER JOIN tblContact on medMedecin3 = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE medConId = '$id')as telTyp3 FROM tblMedical
  INNER JOIN tblContact on medMedecin3 = conId WHERE medConId = '$id'");
$medecin3 = $medecin3->fetch();

$caisseMaladie = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblMedical INNER JOIN tblContact on medCaisseMaladie = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE medConId = '$id')as telTyp1,(select tTelNom from tblMedical INNER JOIN tblContact on medCaisseMaladie = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE medConId = '$id')as telTyp2,(select tTelNom from tblMedical INNER JOIN tblContact on medCaisseMaladie = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE medConId = '$id')as telTyp3 FROM tblMedical
  INNER JOIN tblContact on medCaisseMaladie = conId WHERE medConId = '$id'");
$caisseMaladie = $caisseMaladie->fetch();

$assAccident = $bdd->query("SELECT conNom,conPrenom,conAdresse,conTel1,conTel2,conTel3,conNpa,conLocaliter,
  (select tTelNom from tblMedical INNER JOIN tblContact on medAccident = conId
    INNER JOIN tblTypeTelephone on conTel1T = tTelId WHERE medConId = '$id')as telTyp1,(select tTelNom from tblMedical INNER JOIN tblContact on medAccident = conId
  INNER JOIN tblTypeTelephone on conTel2T = tTelId WHERE medConId = '$id')as telTyp2,(select tTelNom from tblMedical INNER JOIN tblContact on medAccident = conId
  INNER JOIN tblTypeTelephone on conTel3T = tTelId WHERE medConId = '$id')as telTyp3 FROM tblMedical
  INNER JOIN tblContact on medAccident = conId WHERE medConId = '$id'");
$assAccident = $assAccident->fetch();
 $marge = "backright='5mm' backtop='20mm' backbottom='5mm'";

$medical = $bdd->query("SELECT * FROM tblMedication2 WHERE fkConId = '$id'");
$medicalData = $medical->fetch();
if(!$medicalData){
    $medicalData = [
        'idMedication2' => null,
        'isMedicated' => 0,
        'habitudes' => '',
        'matin' => 0,
        'midi' => 0,
        'soir' => 0,
        'nuit' => 0
    ];
}

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

            margin-top: 10px;
            width: 100%;
            color: #9A0000;

        }

        td {

            padding-left: 1mm;
            vertical-align: top;
            text-align: left;
            border: solid 1px;
            font-size: 15px;
            word-wrap: break-word;


        }

        th {

            padding-left: 1mm;
            vertical-align: top;
            text-align: left;
            color: #00AA33;
            font-size: 15px;
            font-style: normal;
            font-weight: normal !important;
            ;
        }

        .infoPratique td{

            border: none;
        }


        .footer td {
            vertical-align: bottom;
            border: none;
        }

        h2 {
            background-color: #00AA00;
            color: #fff;
            margin: 0;
            width: 25mm;
            font-size: 18px;
        }

        .paire {

            background-color: #FFE4C4;
        }

        .impaire {

            background-color: #DEB887;
        }

        .affichage {
            margin-left: 2mm;


        }

        .affichage td {

            padding-bottom: 0;
        }

        .affichage th {
            padding-left: 2mm;
        }

        .footer {
            border-top: solid 1px;
        }
        .footer td{
            font-size: 14px;
        }
        .checkboxLine{
            margin: 15px;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }
        .checkboxLine td{
            width: 20%;
            border: none;
        }
        .checkboxLine2{
            margin: 15px;
            width: 100%;
            display: flex;
            justify-content: left;
            gap: 15px;
            align-items: center;
        }
    </style>
    <page <?php echo $marge ?>> <!-- page 1 -->
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0; border: none"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle; border: none"><?php echo $mrp->getText("Fiche information Participant") ?><br><?php echo $mrp->getText("Dernière mise à jour") ?>
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
    {echo dateToUser($infoMedical['medMaj']);}
else
    {echo dateToUser($infoSocial['socMaj']);}?> <br>
                        

                    </td>
                </tr>
            </table>
        </page_header>
        <table style="margin-top:-5mm; margin-bottom: 0mm">
            <tr>
                <th style=" width:50%"><h2><?php echo $mrp->getText("Boissons") ?></h2></th>
                <td style=" width:50%; text-align: right;border: none;"> <strong> <?php echo $contact['conNom'] . ' ' . $contact['conPrenom']; ?>
                        <br> Né(e) le <?php echo dateToUser($contact['conDateNaissance']) ?> </strong></td>
            </tr>
        </table>
        <table>
            <tr>
                <th style="width: 60%; padding-bottom: 0"><?php echo $mrp->getText("De quelle manière et conseils hydratation") ?></th>
                <td style="width: 24%;padding: 0; text-align: right;border: none" rowspan="10">
                    <?php
                    $portrait = "../imgParticipant/$id/portrait.jpg";

                    if (file_exists($portrait)) { ?>
                        <img  src="../imgParticipant/<?php echo $id; ?>/portrait.jpg" style=" width: 95%;"> <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td style="border: solid 1px; width: 145mm; padding-bottom: -2mm; height: 12mm "><?php echo $infoMedical['medBoisCondition'] ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Boissons préférées") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 145mm;"><?php echo $infoMedical['medBoisPrefere'] ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Déconseillées") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 145mm;"><?php echo $infoMedical['medBoisDecons'] ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Interdites") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 145mm;"><?php echo $infoMedical['medBoisInterdit'] ?></td>
            </tr>

        </table>
        <table>
            <tr>
                <th >
                    <h2><?php echo $mrp->getText("Alimentation") ?> </h2>
                </th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("De quelles manières et conseils alimentation"); //echo $mrp->getText("Dans quelle conditions") ?></th>
            </tr>
            <tr>
                <td style="width: 195mm;border: solid 1px;"><?php echo nl2br($infoMedical['medAlimCommentair']) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Aliments préférés") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 195mm"><?php echo $infoMedical['medAlimPrefere'] ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Déconseillé") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 195mm"><?php echo $infoMedical['medAlimDecons'] ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Interdit") ?></th>
            </tr>
            <tr>
                <td style="border: solid 1px;width: 195mm"><?php echo $infoMedical['medAlimInterdit'] ?></td>
            </tr>
            <tr>
                <th>Consistance du repas</th>
            </tr>
            <tr>
                <td><?php echo determineConsistence($infoMedical['medFoodConsistence']); ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th>
                    <h2><?php echo $mrp->getText("Médication"); ?> </h2>
                </th>
            </tr>
            <tr>   
                <td style="width: 20mm; font-weight: bold; border: none;">
                    <p><?php echo intval($medicalData['isMedicated']) === 1 ? 'Oui' : 'Non'; ?></p>
                </td>
            </tr> 
        </table>
        <table>
            <tr class="checkboxLine">
                    <td>
                        <p><?php CheckBoxPDF($medicalData['matin']) ?>
                        <?php echo "matin"; ?></p>
                    </td>
                    <td>
                        <p><?php CheckBoxPDF($medicalData['midi']) ?>
                        <?php echo "midi" ?></p>
                    </td>
                    <td>
                        <p><?php CheckBoxPDF($medicalData['soir']) ?>
                        <?php echo"soir" ?></p>
                    </td>
                    <td>
                        <p><?php CheckBoxPDF($medicalData['nuit']) ?>
                        <?php echo"nuit" ?></p>
                    </td>
                </tr>
            </table>
            <table>
            <tr style="padding-top: 15px;">
                <td style="border: none;">Habitudes</td>
            </tr>
            <tr>
                <td style="padding: 2.5px; color: black; width: 195mm; height: min-content;"><?php echo $medicalData['habitudes']; ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th colspan="4"><h2><?php echo $mrp->getText("Infos médicales") ?></h2></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Type de handicap") ?></th>
                <th><?php echo $mrp->getText("Handicaps associés") ?></th>
                <th><?php echo $mrp->getText("Allergies") ?></th>
                <th><?php echo $mrp->getText("Degré d'impotence") ?></th>
            </tr>
            <tr>
                <td style="width: 25mm"><?php echo $infoMedical['medTypeHandicap']; ?></td>
                <td style="width: 60mm"><?php echo $infoMedical['medHandicapsAsso']; ?></td>
                <td style="width: 64mm"><?php echo $infoMedical['medAllergies']; ?></td>
                <td style="width: 15mm"><?php echo $infoMedical['impNom']; ?></td>
            </tr>

        </table>


        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>


    </page>
     <page <?php echo $marge ?>> <!-- page 2 -->
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0;border: none;"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle;border: none;"><?php echo $mrp->getText("Fiche information Participant") ?> <br> <?php echo $mrp->getText(" Dernière mise à jour") ?>
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?> <br>
                        <?php echo $contact['conNom'] . ' ' . $contact['conPrenom']; ?>
                    </td>
                </tr>
            </table>
        </page_header>
        <table >
            <tr>
                <th colspan="5"><h2><?php echo $mrp->getText("Contacts proches") ?></h2></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Enfant de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $enfantDe['telTyp1']?></th>
                <th><?php echo $enfantDe['telTyp2']?></th>
                <th><?php echo $enfantDe['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 35mm"><?php echo $enfantDe[0].' '.$enfantDe[1]?></td>
                <td style="width: 48mm"><?php echo $enfantDe['conAdresse'].'<br>'.$enfantDe['conNpa'].' '.$enfantDe['conLocaliter']?> </td>
                <td style="width: 30mm"><?php echo $enfantDe['conTel1']?></td>
                <td style="width: 30mm"><?php echo $enfantDe['conTel2']?></td>
                <td style="width: 30mm"><?php echo $enfantDe['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("et").' '.$mrp->getText("de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $enfantEtDe['telTyp1']?></th>
                <th><?php echo $enfantEtDe['telTyp2']?></th>
                <th><?php echo $enfantEtDe['telTyp3']?></th>
            </tr>
            <tr>
                <td><?php echo $enfantEtDe[0].' '.$enfantEtDe[1]?></td>
                <td><?php echo $enfantEtDe['conAdresse'].'<br>'.$enfantEtDe['conNpa'].' '.$enfantEtDe['conLocaliter']?></td>
                <td><?php echo $enfantEtDe['conTel1']?></td>
                <td><?php echo $enfantEtDe['conTel2']?></td>
                <td><?php echo $enfantEtDe['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Sous curatelle de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $tuteur['telTyp1']?></th>
                <th><?php echo $tuteur['telTyp2']?></th>
                <th><?php echo $tuteur['telTyp3']?></th>
            </tr>
            <tr>
                <td><?php echo $tuteur[0].' '.$tuteur[1]?></td>
                <td><?php echo $tuteur['conAdresse'].'<br>'.$tuteur['conNpa'].' '.$tuteur['conLocaliter']?></td>
                <td><?php echo $tuteur['conTel1']?></td>
                <td><?php echo $tuteur['conTel2']?></td>
                <td><?php echo $tuteur['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Dans l'institution") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $Institution['telTyp1']?></th>
                <th><?php echo $Institution['telTyp2']?></th>
                <th><?php echo $Institution['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 42mm;"><?php echo $Institution[0].' '.$Institution[1]?></td>
                <td><?php echo $Institution['conAdresse'].'<br>'.$Institution['conNpa'].' '.$Institution['conLocaliter']?></td>
                <td><?php echo $Institution['conTel1']?></td>
                <td><?php echo $Institution['conTel2']?></td>
                <td><?php echo $Institution['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Référent dans l'institution") ?></th>
                <td colspan="4"><?php echo $infoSocial['socReferentInstitut']?></td>
            </tr>
            <tr>
                <th colspan="2"><strong style="color: #9A0000"><?php echo $mrp->getText("En cas d'urgence, prévenir en priorité") ?></strong> </th>
                <th><?php echo $urgence['telTyp1']?></th>
                <th><?php echo $urgence['telTyp2']?></th>
                <th><?php echo $urgence['telTyp3']?></th>
            </tr>
            <tr>
                <td colspan="2"><?php echo $urgence[0].' '.$urgence[1]?></td>

                <td><?php echo $urgence['conTel1']?></td>
                <td><?php echo $urgence['conTel2']?></td>
                <td><?php echo $urgence['conTel3']?></td>
            </tr>

        </table>
        <table>
            <tr>
                <th colspan="5"><h2><?php echo $mrp->getText("Contacts médicaux") ?></h2></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin généraliste") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $medecin1['telTyp1']?></th>
                <th><?php echo $medecin1['telTyp2']?></th>
                <th><?php echo $medecin1['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 42mm"><?php echo $medecin1[0].' '.$medecin1[1]?></td>
                <td style="width: 48mm"><?php echo $medecin1['conAdresse'].'<br>'.$medecin1['conNpa'].' '.$medecin1['conLocaliter']?></td>
                <td style="width: 30mm"><?php echo $medecin1['conTel1']?></td>
                <td style="width: 30mm"><?php echo $medecin1['conTel2']?></td>
                <td style="width: 30mm"><?php echo $medecin1['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin, spécialiste") ?> 1</th>
                <th>Adresse</th>
                <th><?php echo $medecin2['telTyp1']?></th>
                <th><?php echo $medecin2['telTyp2']?></th>
                <th><?php echo $medecin2['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 42mm"><?php echo $medecin2[0].' '.$medecin2[1]?></td>
                <td style="width: 48mm"><?php echo $medecin2['conAdresse'].'<br>'.$medecin2['conNpa'].' '.$medecin2['conLocaliter']?></td>
                <td style="width: 30mm"><?php echo $medecin2['conTel1']?></td>
                <td style="width: 30mm"><?php echo $medecin2['conTel2']?></td>
                <td style="width: 30mm"><?php echo $medecin2['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin, spécialiste") ?> 2</th>
                <th>Adresse</th>
                <th><?php echo $medecin3['telTyp1']?></th>
                <th><?php echo $medecin3['telTyp2']?></th>
                <th><?php echo $medecin3['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 42mm"><?php echo $medecin3[0].' '.$medecin3[1]?></td>
                <td style="width: 48mm"><?php echo $medecin3['conAdresse'].'<br>'.$medecin3['conNpa'].' '.$medecin3['conLocaliter']?></td>
                <td style="width: 30mm"><?php echo $medecin3['conTel1']?></td>
                <td style="width: 30mm"><?php echo $medecin3['conTel2']?></td>
                <td style="width: 30mm"><?php echo $medecin3['conTel3']?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Caisse maladie") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo $caisseMaladie['telTyp1']?></th>
                <th><?php echo $caisseMaladie['telTyp2']?></th>
                <th><?php echo $caisseMaladie['telTyp3']?></th>
            </tr>
            <tr>
                <td style="width: 42mm"><?php echo $caisseMaladie[0].' '.$caisseMaladie[1]?></td>
                <td style="width: 48mm"><?php echo $caisseMaladie['conAdresse'].'<br>'.$caisseMaladie['conNpa'].' '.$caisseMaladie['conLocaliter']?></td>
                <td style="width: 30mm"><?php echo $caisseMaladie['conTel1']?></td>
                <td style="width: 30mm"><?php echo $caisseMaladie['conTel2']?></td>
                <td style="width: 30mm"><?php echo $caisseMaladie['conTel3']?></td>
            </tr>
            <tr>
                <th colspan="2"><?php echo $mrp->getText("N° d'assuré caisse maladie") ?></th>
                <td colspan="3"><?php echo $infoMedical['medNoAssure']?></td>
            </tr>
            <tr>

                <th colspan="2"> <?php echo $mrp->getText("Assurance accident") ?></th>
                <th><?php echo $assAccident['telTyp1']?></th>
                <th><?php echo $assAccident['telTyp2']?></th>
                <th><?php echo $assAccident['telTyp3']?></th>
            </tr>
            <tr>
                <td colspan="2"><?php echo $assAccident[0].' '.$assAccident[1]?></td>

                <td><?php echo $assAccident['conTel1']?></td>
                <td><?php echo $assAccident['conTel2']?></td>
                <td><?php echo $assAccident['conTel3']?></td>
            </tr>
        </table>
        <table>
            <tr>              
                <th style="width:60mm "><?php echo $mrp->getText("Fratrie et Amis") ?></th>
            </tr>
            <tr>
                <td  colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socFraterie']) ?></td>
            </tr>
            <tr>
                <th style="width:60mm "><?php echo $mrp->getText("Famille") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socFamille']) ?></td> 
            </tr>
        </table>
        <table>
            <tr>
                <th style="padding: 7px;"><?php echo $mrp->getText("Est d'accord d'être publié(e)") ?>:</th>
                <td style="padding: 7px;"><?php echo formatTextOfListeDeroulante4($infoSocial['socPublication'], 0); ?></td>
            </tr>

        </table>
        <?php function wrapWithoutCuttingWords($text, $maxLength = 20) {
                $words = explode(' ', $text);
                $lines = [];
                $currentLine = '';

                foreach ($words as $word) {
                    if (strlen($currentLine . ' ' . $word) <= $maxLength) {
                        $currentLine .= ($currentLine === '' ? '' : ' ') . $word;
                    } else {
                        $lines[] = $currentLine;
                        $currentLine = $word;
                    }
                }
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }

                return implode('<br>', $lines);
            } ?>
         <page_footer>
             <?php include '../footerPrint.php';?>

         </page_footer>
    </page>
    <page <?php echo $marge ?>> <!-- page 3 -->
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0; border: none"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle; border: none"> Fiche information Participant <br> Dernière mise à jour
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?> <br>
                        <?php echo $contact['conNom'] . ' ' . $contact['conPrenom']; ?>

                    </td>
                </tr>
            </table>
        </page_header>

        <table>
            <tr>
                <th colspan="2"><h2><?php echo $mrp->getText("Tissus social") ?></h2></th>
            </tr>
            <tr>
                <th style="width:50% "><?php echo $mrp->getText("De quelle manière communiquer"); ?> ? </th>
                <th style="width:50% ">Expression verbale : <?php echo intval($infoSocial['socExpressionVerbal']) === 1 ? 'Oui' : 'Non'; ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socExpresssion']) ?></td>
            </tr>
            <!-------- Activités peu appréciées, phobies -------------------->
            <tr>
                <th style="width:50% "><?php echo $mrp->getText("Activités peu appréciées, phobies") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socAimePas']) ?></td>
            </tr>
            <!-------- Passion loisirs -------------------->
            <tr>               
                <th style="width:50% "><?php echo $mrp->getText("Passion loisirs") ?></th>
            </tr>
            <tr>    
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socPassions']) ?></td>
            </tr>
            <!-------- Amis -------------------->
            <tr>    
                <th style="width:60mm "><?php echo $mrp->getText("Amis") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socAmis']) ?></td>
            </tr>
            <!----------------------- Comportement et attitude ----------------------------->
            <tr>    
                <th style="width:60mm "><?php echo $mrp->getText("Comportement et attitude") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 195mm"><?php echo nl2br($infoSocial['socComportement']) ?></td>
            </tr>
        </table>
        <table class="infoPratique">
            <tr>
                <th colspan="10"><h2><?php echo $mrp->getText("Infos pratiques") ?></h2></th>
            </tr>

        </table>
        <table style="border: solid 1px;">
            <tr>
                <th colspan="4"><?php echo $mrp->getText("Argent de poche") ?></th>
            </tr>
            <tr>
                <td style="width: 47mm; border: none"><?php echo $mrp->getText("Autonome") ?><?php CheckBoxPDF($infoSocial['socGereseul']) ?></td>
                <td style="width: 47mm; border: none"><?php echo $mrp->getText("ne gère pas") ?> <?php CheckBoxPDF($infoSocial['socNegerepas']) ?></td>
                <td style="width: 47mm; border: none"><?php echo $mrp->getText("aide") ?> <?php CheckBoxPDF($infoSocial['socAvecaidephys']) ?></td>
                <td style="width: 47mm; border: none"> <?php echo $mrp->getText("avec conseils") ?> <?php CheckBoxPDF($infoSocial['socAvecconseil']) ?></td>

            </tr>

        </table>
        <table style="border: solid 1px;" class="infoPratique">
            <tr>
                <th colspan="3"><?php echo $mrp->getText("Mobilité") ?></th>
            </tr>
            <tr>
                <td style=" width: 62mm; border: none"><?php echo $mrp->getText("marche seul(e)") ?> <?php CheckBoxPDF($infoSocial['socMarcheseul']) ?></td>
                <td style=" width: 62mm; border: none"><?php echo $mrp->getText("Fauteil roulant manuel") ?><?php CheckBoxPDF($infoSocial['socFauteuilMan']) ?></td>
                <td style=" width: 63mm; border: none"><?php echo $mrp->getText("Electrique") ?> <?php CheckBoxPDF($infoSocial['socFauteuilElec']) ?></td>
            </tr>
            <tr>
                <td style="border: none"><?php echo $mrp->getText(" avec de l'aide") ?><?php CheckBoxPDF($infoSocial['socMarchaide']) ?></td>
                <td><?php echo $mrp->getText("moyens auxiliaires") ?><?php CheckBoxPDF($infoSocial['socMarchauxi']) ?></td>
                <td style=" width: 63mm; border: none"><?php echo $mrp->getText("Siège bus") ?> <?php CheckBoxPDF($infoSocial['socSiege']) ?></td>

            </tr>
            <tr>
                <th colspan="3"><?php echo $mrp->getText("Technique transferts et déplacements"); //$mrp->getText("pour les longs déplacements, commentaires mobilité") ?></th>
            </tr>
            <tr>

                <td colspan="3" style="width: 195mm"><?php echo nl2br($infoSocial['socDeplacement']) ?></td>
            </tr>
        </table>
        <table style=" width:195mm;">
            <tr>
                <th ><?php echo $mrp->getText("Habillement,autonomie et style") ?></th>
            </tr>
            <tr>
                <td style="width: 195mm"><?php echo nl2br($infoSocial['socHabillement']) ?></td>
            </tr>
        </table>
        <table style=" width:195mm;">
            <tr>
                <th><?php echo $mrp->getText("Habitudes piscine") ?></th>
                <td style="width:191mm; border: none"><?php echo $mrp->getText("Avec gilet flottant") ?> : <?php echo formatTextOfListeDeroulante4($infoSocial['socPiscineGilet'], 0) ?></td>
            </tr>
        </table>
        <table>
            <tr>
                <th><?php echo $mrp->getText("Remarque concernant la piscine") ?></th>
            </tr>
            <tr>
                <td style="width:136mm"><?php echo nl2br($infoSocial['socPiscine']) ?></td>
            </tr>
        </table>

        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>
    </page>
    <page <?php echo $marge ?>> <!-- page 4 -->
        <page_header>
            <table>
                <tr>
                    <td style=" width:50%;  vertical-align: top;  padding: 0; border: none"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="text-align: right;width:50%; vertical-align: middle; border: none"> Fiche information Participant <br> Dernière mise à jour
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?> <br>
                        <?php echo $contact['conNom'] . ' ' . $contact['conPrenom']; ?>

                    </td>
                </tr>
            </table>
        </page_header>
        <table>
            <tr>
                <th colspan="2"><h2><?php echo $mrp->getText("Soins") ?></h2></th>
            </tr>
        </table>
        <table>
            <tr>               
                <th><?php echo $mrp->getText("Aide à l'élimination") ?></th>
            </tr>
        </table>
        <table>
            <tr class="checkboxLine">
                <td>
                    <p><?php CheckBoxPDF($infoMedical['medSonde']) ?>
                    <?php echo $mrp->getText("Sonde") ?></p>
                </td>
                <td>
                    <p><?php CheckBoxPDF($infoMedical['medProtection']) ?>
                    <?php echo $mrp->getText("Protection") ?></p>
                </td>
                <td>
                    <p><?php CheckBoxPDF($infoMedical['medMiseWc']) ?>
                    <?php echo $mrp->getText("Mise WC") ?></p>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td  style="width: 195mm;border: solid 1px;"><?php echo nl2br($infoMedical['medToilette']) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Conseils pour soins et hygiène") ?></th>
            </tr>
            <tr>
                <td style="width: 195mm;border: solid 1px;"><?php echo nl2br($infoMedical['medEncontinence']) ?></td>
            </tr>
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Soins particuliers") ?></th>
            </tr>
            <tr>
                <td style="width: 195mm;border: solid 1px;">
                    <?php echo nl2br($infoMedical['medEscarres']) ?>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th style="width: 95mm"><h2><?php echo $mrp->getText("Confort jour") ?></h2></th>
                <th style="width: 95mm"><h2><?php echo $mrp->getText("Confort nuit") ?></h2></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Fréquence des changements de position") ?></th>
            </tr>
            <tr>
                <td style="width: 95mm"><?php echo nl2br($infoSocial['socFrequenceJour']) ?></td>
                <td style="width: 95mm">
                    <table>
                        <tr>
                            <td style="width: 20mm; border: 1px solid black; padding: 5px; vertical-align: center;"><?php echo $mrp->getText("Drap spécial"); ?></td><td style="padding: 5px; vertical-align: center; border: none; background-color: #7da6ff; color: white;"><?php echo formatTextOfListeDeroulante4($infoSocial['socdrapspec'], 0); //CheckBoxPDF($infoSocial['socdrapspec']) ?></td>   
                            <td style="width: 20mm; border: 1px solid black; padding: 5px; vertical-align: center;"><?php echo $mrp->getText("Grenouillère"); ?></td><td style="padding: 5px; vertical-align: center; border: none; background-color: #7da6ff; color: white;"><?php echo formatTextOfListeDeroulante4($infoSocial['socgilet'], 0); ?></td>
                        </tr>
                        <tr>
                            <td style="width: 20mm; border: 1px solid black; padding: 5px; vertical-align: center;"><?php echo $mrp->getText("Attaches"); ?></td><td style="padding: 5px; vertical-align: center; border: none; background-color: #7da6ff; color: white;"><?php echo formatTextOfListeDeroulante4($infoSocial['socatache'], 0); ?></td>
                            <td style="width: 20mm; border: 1px solid black; padding: 5px; vertical-align: center;"><?php echo $mrp->getText("Barrière"); ?></td><td style="padding: 5px; vertical-align: center; border: none; background-color: #7da6ff; color: white;"><?php echo formatTextOfListeDeroulante4($infoSocial['socbarriere'], 0); ?></td>
                        </tr>  
                    </table>      
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("habitudes pour la sieste position") ?></th>
                <th><?php echo $mrp->getText("Habitude - Rituel du couché") ?></th>
            </tr>
            <tr>
                <td style="width: 95mm"><?php echo nl2br($infoSocial['socPossitionJour']) ?></td>
                <td style="width: 95mm"><?php echo nl2br($infoSocial['socSomeil']) ?></td>
            </tr>

        </table>
        <table>
            <tr>
                <th style="width: 95mm"><?php echo $mrp->getText("Illustration position jour") ?></th>
                <th style="width: 95mm"><?php echo $mrp->getText("Illustration position nuit") ?></th>
            </tr>
            <tr>
                <td style="height: 70mm; width: 95mm"> <?php
                    $portrait = "../imgParticipant/$id/pja.jpg";

                    if (file_exists($portrait)) { ?>

                        <img src="../imgParticipant/<?php echo $id; ?>/pja.jpg" style=" max-width: 85mm"> <?php
                    }

                    ?></td>
                <td style="height: 70mm; width: 95mm"> <?php
                    $portrait = "../imgParticipant/$id/pna.jpg";

                    if (file_exists($portrait)) { ?>

                        <img src="../imgParticipant/<?php echo $id; ?>/pna.jpg" style=" max-width: 85mm"> <?php
                    }

                    ?></td>
            </tr>




        </table>

        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>
    </page>
    <style>
        .td-txt{
            display: flex;
        }
        .medication-line{
            display: flex;
        }
        .medication-line td{
            border: none;
        }
        .medication-line p{
            width: 20%;
        }
        #confort-night-zone{
            /*width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            */
        }
        #confort-night-zone tr{
            
        }
    </style>

<?php

try {
    
    require _('../vendor/autoload.php');
    $family = 'coucou';
    $style = 'regular';
    $file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
    $pdf = new HTML2PDF('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');
	$content = ob_get_clean();
    $pdf->writeHTML($content);
    $pdf->addFont($family, $style, $file);
	ob_get_clean();
    $pdf->output('ficheInfoParticipant.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e){
   die($e);
};
