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
<?php
/*
 * Mise en forme : html2pdf ne coupe les lignes que sur les espaces.
 * pdfTxt() découpe les mots plus longs que $maxChars (adresses mail, URL, numéros collés...)
 * pour qu'aucun texte ne dépasse de sa case.
 */
if (!function_exists('pdfTxt')) {
    function pdfTxt($value, $maxChars = 40, $nl2br = false)
    {
        $value = trim((string)$value);
        $value = preg_replace_callback('/[^\s<>]{' . ($maxChars + 1) . ',}(?![^<]*>)/u', function ($m) use ($maxChars) {
            return implode(' ', mb_str_split($m[0], $maxChars));
        }, $value);
        return $nl2br ? nl2br($value) : $value;
    }
}
?>
    <style>
        * {
            margin: 0;
            padding: 0;
            color: #000;
            font-family: "helvetica", sans-serif;
        }

        table {
            margin-top: 0;
            color: #000;
        }

        /* libellé de champ */
        th {
            padding: 1.6mm 1mm 0.6mm 1mm;
            vertical-align: bottom;
            text-align: left;
            color: #00852B;
            font-size: 9.5px;
            font-weight: normal;
            border: none;
        }

        /* case de valeur */
        td {
            padding: 1mm 1mm;
            vertical-align: top;
            text-align: left;
            border: solid 0.2mm #7A7A7A;
            font-size: 10.5px;
            line-height: 1.25;
        }

        td.nob {
            border: none;
        }

        /* bandeau de titre de section */
        td.section {
            background-color: #00AA00;
            color: #FFFFFF;
            font-size: 13px;
            font-weight: bold;
            padding: 1.2mm 2mm;
            border: none;
        }

        td.urgent {
            color: #9A0000;
            font-weight: bold;
        }

        th.urgent {
            color: #9A0000;
            font-weight: bold;
        }

        .header td {
            border: none;
            padding: 0;
            font-size: 11px;
        }

        .check td {
            border: none;
            padding: 1mm 1mm;
            vertical-align: middle;
            font-size: 11px;
        }

        .check img {
            vertical-align: middle;
        }

        .badge {
            background-color: #E6F4E6;
            font-weight: bold;
        }
    </style>
    <page <?php echo $marge ?>> <!-- page 1 -->
        <page_header>
            <table class="header" cellspacing="0">
                <tr>
                    <td style="width: 95mm; vertical-align: top"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="width: 95mm; text-align: right; vertical-align: middle"><strong><?php echo $mrp->getText("Fiche information Participant") ?></strong><br><?php echo $mrp->getText("Dernière mise à jour") ?>
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
    {echo dateToUser($infoMedical['medMaj']);}
else
    {echo dateToUser($infoSocial['socMaj']);}?>
                    </td>
                </tr>
            </table>
        </page_header>

        <table cellspacing="0">
            <tr>
                <td class="section" style="width: 120mm"><?php echo $mrp->getText("Boissons") ?></td>
                <td class="nob" style="width: 63mm; text-align: right; vertical-align: middle; padding: 0 0 0 2mm"><strong><?php echo pdfTxt($contact['conNom'] . ' ' . $contact['conPrenom'], 30); ?></strong>
                    <br>Né(e) le <?php echo dateToUser($contact['conDateNaissance']) ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th style="width: 140mm"><?php echo $mrp->getText("De quelle manière et conseils hydratation") ?></th>
                <td class="nob" rowspan="8" style="width: 42.4mm; padding: 1.6mm 0 0 4mm; text-align: right">
                    <?php
                    $portrait = "../imgParticipant/$id/portrait.jpg";

                    if (file_exists($portrait)) { ?>
                        <img src="../imgParticipant/<?php echo $id; ?>/portrait.jpg" style="max-width: 42mm; max-height: 58mm"> <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 140mm; height: 10mm"><?php echo pdfTxt($infoMedical['medBoisCondition'], 70, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Boissons préférées") ?></th>
            </tr>
            <tr>
                <td style="width: 140mm"><?php echo pdfTxt($infoMedical['medBoisPrefere'], 70, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Déconseillées") ?></th>
            </tr>
            <tr>
                <td style="width: 140mm"><?php echo pdfTxt($infoMedical['medBoisDecons'], 70, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Interdites") ?></th>
            </tr>
            <tr>
                <td style="width: 140mm"><?php echo pdfTxt($infoMedical['medBoisInterdit'], 70, true) ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Alimentation") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("De quelles manières et conseils alimentation"); //echo $mrp->getText("Dans quelle conditions") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medAlimCommentair'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Aliments préférés") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medAlimPrefere'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Déconseillé") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medAlimDecons'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Interdit") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medAlimInterdit'], 90, true) ?></td>
            </tr>
            <tr>
                <th>Consistance du repas</th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo determineConsistence($infoMedical['medFoodConsistence']); ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 150mm"><?php echo $mrp->getText("Médication"); ?></td>
                <td class="section" style="width: 29mm; text-align: right"><?php echo intval($medicalData['isMedicated']) === 1 ? 'Oui' : 'Non'; ?></td>
            </tr>
        </table>
        <table class="check" cellspacing="0" style="margin-top: 1mm">
            <tr>
                <td style="width: 44.7mm"><?php CheckBoxPDF($medicalData['matin']) ?> <?php echo "matin"; ?></td>
                <td style="width: 44.7mm"><?php CheckBoxPDF($medicalData['midi']) ?> <?php echo "midi" ?></td>
                <td style="width: 44.7mm"><?php CheckBoxPDF($medicalData['soir']) ?> <?php echo"soir" ?></td>
                <td style="width: 44.7mm"><?php CheckBoxPDF($medicalData['nuit']) ?> <?php echo"nuit" ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th>Habitudes</th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($medicalData['habitudes'], 90, true); ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Infos médicales") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th style="width: 40mm"><?php echo $mrp->getText("Type de handicap") ?></th>
                <th style="width: 55mm"><?php echo $mrp->getText("Handicaps associés") ?></th>
                <th style="width: 55mm"><?php echo $mrp->getText("Allergies") ?></th>
                <th style="width: 26.6mm"><?php echo $mrp->getText("Degré d'impotence") ?></th>
            </tr>
            <tr>
                <td style="width: 40mm"><?php echo pdfTxt($infoMedical['medTypeHandicap'], 20, true); ?></td>
                <td style="width: 55mm"><?php echo pdfTxt($infoMedical['medHandicapsAsso'], 28, true); ?></td>
                <td style="width: 55mm"><?php echo pdfTxt($infoMedical['medAllergies'], 28, true); ?></td>
                <td style="width: 26.6mm"><?php echo pdfTxt($infoMedical['impNom'], 13); ?></td>
            </tr>
        </table>

        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>
    </page>

    <page <?php echo $marge ?>> <!-- page 2 -->
        <page_header>
            <table class="header" cellspacing="0">
                <tr>
                    <td style="width: 95mm; vertical-align: top"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="width: 95mm; text-align: right; vertical-align: middle"><strong><?php echo $mrp->getText("Fiche information Participant") ?></strong><br><?php echo $mrp->getText(" Dernière mise à jour") ?>
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?><br>
                        <?php echo pdfTxt($contact['conNom'] . ' ' . $contact['conPrenom'], 40); ?>
                    </td>
                </tr>
            </table>
        </page_header>

        <table cellspacing="0">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Contacts proches") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <col style="width: 45.5mm">
            <col style="width: 51.5mm">
            <col style="width: 25mm">
            <col style="width: 25mm">
            <col style="width: 25mm">
            <tr>
                <th><?php echo $mrp->getText("Enfant de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($enfantDe['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($enfantDe['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($enfantDe['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($enfantDe[0].' '.$enfantDe[1], 23)?></td>
                <td><?php echo pdfTxt($enfantDe['conAdresse'], 27).'<br>'.pdfTxt($enfantDe['conNpa'].' '.$enfantDe['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($enfantDe['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($enfantDe['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($enfantDe['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("et").' '.$mrp->getText("de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($enfantEtDe['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($enfantEtDe['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($enfantEtDe['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($enfantEtDe[0].' '.$enfantEtDe[1], 23)?></td>
                <td><?php echo pdfTxt($enfantEtDe['conAdresse'], 27).'<br>'.pdfTxt($enfantEtDe['conNpa'].' '.$enfantEtDe['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($enfantEtDe['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($enfantEtDe['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($enfantEtDe['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Sous curatelle de") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($tuteur['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($tuteur['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($tuteur['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($tuteur[0].' '.$tuteur[1], 23)?></td>
                <td><?php echo pdfTxt($tuteur['conAdresse'], 27).'<br>'.pdfTxt($tuteur['conNpa'].' '.$tuteur['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($tuteur['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($tuteur['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($tuteur['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Dans l'institution") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($Institution['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($Institution['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($Institution['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($Institution[0].' '.$Institution[1], 23)?></td>
                <td><?php echo pdfTxt($Institution['conAdresse'], 27).'<br>'.pdfTxt($Institution['conNpa'].' '.$Institution['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($Institution['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($Institution['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($Institution['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th colspan="5"><?php echo $mrp->getText("Référent dans l'institution") ?></th>
            </tr>
            <tr>
                <td colspan="5"><?php echo pdfTxt($infoSocial['socReferentInstitut'], 90)?></td>
            </tr>
            <tr>
                <th colspan="2" class="urgent"><?php echo $mrp->getText("En cas d'urgence, prévenir en priorité") ?></th>
                <th><?php echo pdfTxt($urgence['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($urgence['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($urgence['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td colspan="2" class="urgent"><?php echo pdfTxt($urgence[0].' '.$urgence[1], 50)?></td>
                <td><?php echo pdfTxt($urgence['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($urgence['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($urgence['conTel3'], 15)?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Contacts médicaux") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <col style="width: 45.5mm">
            <col style="width: 51.5mm">
            <col style="width: 25mm">
            <col style="width: 25mm">
            <col style="width: 25mm">
            <tr>
                <th><?php echo $mrp->getText("Médecin généraliste") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($medecin1['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($medecin1['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($medecin1['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($medecin1[0].' '.$medecin1[1], 23)?></td>
                <td><?php echo pdfTxt($medecin1['conAdresse'], 27).'<br>'.pdfTxt($medecin1['conNpa'].' '.$medecin1['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($medecin1['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($medecin1['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($medecin1['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin, spécialiste") ?> 1</th>
                <th>Adresse</th>
                <th><?php echo pdfTxt($medecin2['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($medecin2['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($medecin2['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($medecin2[0].' '.$medecin2[1], 23)?></td>
                <td><?php echo pdfTxt($medecin2['conAdresse'], 27).'<br>'.pdfTxt($medecin2['conNpa'].' '.$medecin2['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($medecin2['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($medecin2['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($medecin2['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin, spécialiste") ?> 2</th>
                <th>Adresse</th>
                <th><?php echo pdfTxt($medecin3['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($medecin3['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($medecin3['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($medecin3[0].' '.$medecin3[1], 23)?></td>
                <td><?php echo pdfTxt($medecin3['conAdresse'], 27).'<br>'.pdfTxt($medecin3['conNpa'].' '.$medecin3['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($medecin3['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($medecin3['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($medecin3['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Caisse maladie") ?></th>
                <th><?php echo $mrp->getText("Adresse") ?></th>
                <th><?php echo pdfTxt($caisseMaladie['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($caisseMaladie['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($caisseMaladie['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td><?php echo pdfTxt($caisseMaladie[0].' '.$caisseMaladie[1], 23)?></td>
                <td><?php echo pdfTxt($caisseMaladie['conAdresse'], 27).'<br>'.pdfTxt($caisseMaladie['conNpa'].' '.$caisseMaladie['conLocaliter'], 27)?></td>
                <td><?php echo pdfTxt($caisseMaladie['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($caisseMaladie['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($caisseMaladie['conTel3'], 15)?></td>
            </tr>
            <tr>
                <th colspan="2"><?php echo $mrp->getText("N° d'assuré caisse maladie") ?></th>
                <th colspan="3"></th>
            </tr>
            <tr>
                <td colspan="2"><?php echo pdfTxt($infoMedical['medNoAssure'], 50)?></td>
                <td colspan="3" class="nob"></td>
            </tr>
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Assurance accident") ?></th>
                <th><?php echo pdfTxt($assAccident['telTyp1'], 15)?></th>
                <th><?php echo pdfTxt($assAccident['telTyp2'], 15)?></th>
                <th><?php echo pdfTxt($assAccident['telTyp3'], 15)?></th>
            </tr>
            <tr>
                <td colspan="2"><?php echo pdfTxt($assAccident[0].' '.$assAccident[1], 50)?></td>
                <td><?php echo pdfTxt($assAccident['conTel1'], 15)?></td>
                <td><?php echo pdfTxt($assAccident['conTel2'], 15)?></td>
                <td><?php echo pdfTxt($assAccident['conTel3'], 15)?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 2mm">
            <tr>
                <th><?php echo $mrp->getText("Fratrie et Amis") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoSocial['socFraterie'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Famille") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoSocial['socFamille'], 90, true) ?></td>
            </tr>
        </table>
        <table cellspacing="0" style="margin-top: 3mm">
            <tr>
                <td class="nob" style="width: 70mm; padding-left: 1mm; color: #00852B"><?php echo $mrp->getText("Est d'accord d'être publié(e)") ?> :</td>
                <td class="badge" style="width: 30mm; text-align: center"><?php echo formatTextOfListeDeroulante4($infoSocial['socPublication'], 0); ?></td>
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
            <table class="header" cellspacing="0">
                <tr>
                    <td style="width: 95mm; vertical-align: top"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="width: 95mm; text-align: right; vertical-align: middle"><strong>Fiche information Participant</strong><br>Dernière mise à jour
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?><br>
                        <?php echo pdfTxt($contact['conNom'] . ' ' . $contact['conPrenom'], 40); ?>
                    </td>
                </tr>
            </table>
        </page_header>

        <table cellspacing="0">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Tissus social") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th style="width: 92mm"><?php echo $mrp->getText("De quelle manière communiquer"); ?> ?</th>
                <th style="width: 92mm; text-align: right">Expression verbale : <strong style="color: #000"><?php echo intval($infoSocial['socExpressionVerbal']) === 1 ? 'Oui' : 'Non'; ?></strong></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 190mm"><?php echo pdfTxt($infoSocial['socExpresssion'], 90, true) ?></td>
            </tr>
            <!-------- Activités peu appréciées, phobies -------------------->
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Activités peu appréciées, phobies") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 190mm"><?php echo pdfTxt($infoSocial['socAimePas'], 90, true) ?></td>
            </tr>
            <!-------- Passion loisirs -------------------->
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Passion loisirs") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 190mm"><?php echo pdfTxt($infoSocial['socPassions'], 90, true) ?></td>
            </tr>
            <!-------- Amis -------------------->
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Amis") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 190mm"><?php echo pdfTxt($infoSocial['socAmis'], 90, true) ?></td>
            </tr>
            <!----------------------- Comportement et attitude ----------------------------->
            <tr>
                <th colspan="2"><?php echo $mrp->getText("Comportement et attitude") ?></th>
            </tr>
            <tr>
                <td colspan="2" style="width: 190mm"><?php echo pdfTxt($infoSocial['socComportement'], 90, true) ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Infos pratiques") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("Argent de poche") ?></th>
            </tr>
        </table>
        <table class="check" cellspacing="0" style="border: solid 0.2mm #7A7A7A">
            <tr>
                <td style="width: 44.6mm"><?php CheckBoxPDF($infoSocial['socGereseul']) ?> <?php echo $mrp->getText("Autonome") ?></td>
                <td style="width: 44.6mm"><?php CheckBoxPDF($infoSocial['socNegerepas']) ?> <?php echo $mrp->getText("ne gère pas") ?></td>
                <td style="width: 44.6mm"><?php CheckBoxPDF($infoSocial['socAvecaidephys']) ?> <?php echo $mrp->getText("aide") ?></td>
                <td style="width: 44.6mm"><?php CheckBoxPDF($infoSocial['socAvecconseil']) ?> <?php echo $mrp->getText("avec conseils") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("Mobilité") ?></th>
            </tr>
        </table>
        <table class="check" cellspacing="0" style="border: solid 0.2mm #7A7A7A">
            <tr>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socMarcheseul']) ?> <?php echo $mrp->getText("marche seul(e)") ?></td>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socFauteuilMan']) ?> <?php echo $mrp->getText("Fauteil roulant manuel") ?></td>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socFauteuilElec']) ?> <?php echo $mrp->getText("Electrique") ?></td>
            </tr>
            <tr>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socMarchaide']) ?> <?php echo $mrp->getText(" avec de l'aide") ?></td>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socMarchauxi']) ?> <?php echo $mrp->getText("moyens auxiliaires") ?></td>
                <td style="width: 60.8mm"><?php CheckBoxPDF($infoSocial['socSiege']) ?> <?php echo $mrp->getText("Siège bus") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("Technique transferts et déplacements"); //$mrp->getText("pour les longs déplacements, commentaires mobilité") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoSocial['socDeplacement'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Habillement,autonomie et style") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoSocial['socHabillement'], 90, true) ?></td>
            </tr>
        </table>
        <table cellspacing="0" style="margin-top: 3mm">
            <tr>
                <td class="nob" style="width: 50mm; padding-left: 1mm; color: #00852B"><?php echo $mrp->getText("Habitudes piscine") ?></td>
                <td class="nob" style="width: 50mm; text-align: right; padding-right: 2mm"><?php echo $mrp->getText("Avec gilet flottant") ?> :</td>
                <td class="badge" style="width: 30mm; text-align: center"><?php echo formatTextOfListeDeroulante4($infoSocial['socPiscineGilet'], 0) ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("Remarque concernant la piscine") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoSocial['socPiscine'], 90, true) ?></td>
            </tr>
        </table>

        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>
    </page>

    <page <?php echo $marge ?>> <!-- page 4 -->
        <page_header>
            <table class="header" cellspacing="0">
                <tr>
                    <td style="width: 95mm; vertical-align: top"><img style="height: 10mm" src="../img/logo.jpg" alt=""></td>
                    <td style="width: 95mm; text-align: right; vertical-align: middle"><strong>Fiche information Participant</strong><br>Dernière mise à jour
                        : <?php  if ($infoMedical['medMaj'] > $infoSocial['socMaj'])
                        {echo dateToUser($infoMedical['medMaj']);}
                        else
                        {echo dateToUser($infoSocial['socMaj']);}?><br>
                        <?php echo pdfTxt($contact['conNom'] . ' ' . $contact['conPrenom'], 40); ?>
                    </td>
                </tr>
            </table>
        </page_header>

        <table cellspacing="0">
            <tr>
                <td class="section" style="width: 187mm"><?php echo $mrp->getText("Soins") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th><?php echo $mrp->getText("Aide à l'élimination") ?></th>
            </tr>
        </table>
        <table class="check" cellspacing="0">
            <tr>
                <td style="width: 44.7mm"><?php CheckBoxPDF($infoMedical['medSonde']) ?> <?php echo $mrp->getText("Sonde") ?></td>
                <td style="width: 44.7mm"><?php CheckBoxPDF($infoMedical['medProtection']) ?> <?php echo $mrp->getText("Protection") ?></td>
                <td style="width: 44.7mm"><?php CheckBoxPDF($infoMedical['medMiseWc']) ?> <?php echo $mrp->getText("Mise WC") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medToilette'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Conseils pour soins et hygiène") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medEncontinence'], 90, true) ?></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Soins particuliers") ?></th>
            </tr>
            <tr>
                <td style="width: 190mm"><?php echo pdfTxt($infoMedical['medEscarres'], 90, true) ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 4mm">
            <tr>
                <td class="section" style="width: 89mm"><?php echo $mrp->getText("Confort jour") ?></td>
                <td class="nob" style="width: 1mm; padding: 0"></td>
                <td class="section" style="width: 89mm"><?php echo $mrp->getText("Confort nuit") ?></td>
            </tr>
        </table>
        <table cellspacing="0">
            <tr>
                <th style="width: 92mm"><?php echo $mrp->getText("Fréquence des changements de position") ?></th>
                <th style="width: 1mm; padding: 0"></th>
                <th style="width: 92mm"></th>
            </tr>
            <tr>
                <td style="width: 92.4mm"><?php echo pdfTxt($infoSocial['socFrequenceJour'], 46, true) ?></td>
                <td class="nob" style="width: 1mm; padding: 0"></td>
                <td class="nob" style="width: 97mm; padding: 0">
                    <table cellspacing="0">
                        <tr>
                            <td style="width: 26mm; vertical-align: middle"><?php echo $mrp->getText("Drap spécial"); ?></td>
                            <td class="badge" style="width: 13.3mm; text-align: center; vertical-align: middle"><?php echo formatTextOfListeDeroulante4($infoSocial['socdrapspec'], 0); //CheckBoxPDF($infoSocial['socdrapspec']) ?></td>
                            <td style="width: 26mm; vertical-align: middle"><?php echo $mrp->getText("Grenouillère"); ?></td>
                            <td class="badge" style="width: 13.3mm; text-align: center; vertical-align: middle"><?php echo formatTextOfListeDeroulante4($infoSocial['socgilet'], 0); ?></td>
                        </tr>
                        <tr>
                            <td style="width: 26mm; vertical-align: middle"><?php echo $mrp->getText("Attaches"); ?></td>
                            <td class="badge" style="width: 13.3mm; text-align: center; vertical-align: middle"><?php echo formatTextOfListeDeroulante4($infoSocial['socatache'], 0); ?></td>
                            <td style="width: 26mm; vertical-align: middle"><?php echo $mrp->getText("Barrière"); ?></td>
                            <td class="badge" style="width: 13.3mm; text-align: center; vertical-align: middle"><?php echo formatTextOfListeDeroulante4($infoSocial['socbarriere'], 0); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("habitudes pour la sieste position") ?></th>
                <th style="padding: 0"></th>
                <th><?php echo $mrp->getText("Habitude - Rituel du couché") ?></th>
            </tr>
            <tr>
                <td style="width: 92.4mm"><?php echo pdfTxt($infoSocial['socPossitionJour'], 46, true) ?></td>
                <td class="nob" style="width: 1mm; padding: 0"></td>
                <td style="width: 92.4mm"><?php echo pdfTxt($infoSocial['socSomeil'], 46, true) ?></td>
            </tr>
        </table>

        <table cellspacing="0" style="margin-top: 2mm">
            <tr>
                <th style="width: 92mm"><?php echo $mrp->getText("Illustration position jour") ?></th>
                <th style="width: 1mm; padding: 0"></th>
                <th style="width: 92mm"><?php echo $mrp->getText("Illustration position nuit") ?></th>
            </tr>
            <tr>
                <td style="height: 66mm; width: 92.4mm; text-align: center; vertical-align: middle"> <?php
                    $portrait = "../imgParticipant/$id/pja.jpg";

                    if (file_exists($portrait)) { ?>

                        <img src="../imgParticipant/<?php echo $id; ?>/pja.jpg" style="max-width: 88mm; max-height: 64mm"> <?php
                    }

                    ?></td>
                <td class="nob" style="width: 1mm; padding: 0"></td>
                <td style="height: 66mm; width: 92.4mm; text-align: center; vertical-align: middle"> <?php
                    $portrait = "../imgParticipant/$id/pna.jpg";

                    if (file_exists($portrait)) { ?>

                        <img src="../imgParticipant/<?php echo $id; ?>/pna.jpg" style="max-width: 88mm; max-height: 64mm"> <?php
                    }

                    ?></td>
            </tr>
        </table>

        <page_footer>
            <?php include '../footerPrint.php';?>

        </page_footer>
    </page>


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
