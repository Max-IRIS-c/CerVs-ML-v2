<?php
include('../src/header.inc.php');


$aResponsable = $db->query("SELECT conId,CONCAT(conNom,' ',conPrenom) as conNomPrenom FROM tblContact WHERE conStatu = 1 AND conResponsable =1  order by conNom ASC ");
$aCoresponsable = $db->query("SELECT conId,CONCAT(conNom,' ',conPrenom) as conNomPrenom FROM tblContact WHERE conStatu = 1 AND conCoResponsable =1 order by conNom ASC ");
$aCuisinier = $db->query("SELECT conId,CONCAT(conNom,' ',conPrenom) as conNomPrenom FROM tblContact WHERE conStatu = 1 AND conCuisinier =1 order by conNom ASC");
$aInfirmier = $db->query("SELECT conId,CONCAT(conNom,' ',conPrenom) as conNomPrenom FROM tblContact WHERE conStatu = 1 AND conInfirmiere =1 order by conNom ASC");
$aTypeActivite = $db->query("SELECT * FROM tblTypeActivite");
$aCodeOFas = $db->query("SELECT cat1Id, CONCAT(cat1Code,' ',cat1Nom) as catNom FROM tblTraCat1 WHERE cat1Id BETWEEN 21 AND 24");
$aCodeTypeOfas = $db->query("SELECT * FROM tblOfasType ");
$aCodeCatOfas = $db->query("SELECT * FROM tblOfasCategorie ");
$aLangue = $db->query("SELECT * FROM tblLangue ");


if (isset($_POST['Valider']) OR $_POST['Ajout']) {

    $sqlInsert = 'INSERT INTO tblActivites 
    (langCode,actNom,actDebut,actFin,actDec,actType,actLieu,actTheme,actResponsable,actCoResponsable,actCuisiniere,actCodeOfas,actInfirmier,actOfasType,actOfaCat) 
		VALUES (:langCode,:actNom,:actDebut,:actFin,:actDec,:actType,:actLieu,:actTheme,:actResponsable,:actCoResponsable,
		:actCuisiniere,:actCodeOfas,:actInfirmier,:actOfasType,:actOfaCat)';
    $db->bindTxt('langCode', $_POST['langCode']);
    $db->bindTxt('actNom', $_POST['actNom'],'yes');
    $db->bindDate('actDebut', $_POST['actDebut']);
    $db->bindDate('actFin', $_POST['actFin']);
    $db->bindTxt('actDec', $_POST['actDec'],'yes');
    $db->bindInt('actType', $_POST['actType']);
    $db->bindTxt('actLieu', $_POST['actLieu'],'yes');
    $db->bindTxt('actTheme', $_POST['actTheme'],'yes');
    $db->bindInt('actResponsable', $_POST['actResponsable'],true);
    $db->bindInt('actCoResponsable', $_POST['actCoResponsable'],true);
    $db->bindInt('actCuisiniere', $_POST['actCuisiniere'],true);
    $db->bindInt('actCodeOfas', $_POST['actCodeOfas'],true);
    $db->bindInt('actInfirmier', $_POST['actInfirmier']);
    $db->bindInt('actOfasType', $_POST['actOfasType'],true);
    $db->bindInt('actOfaCat', $_POST['actOfaCat'],true);
    $insert = $db->query($sqlInsert);

    $idAct=$db->lastInsertId();
    var_dump($idAct);
    if (isset($_POST['Ajout'])) {
        header("location: modifParticipant.php?id=" . $idAct);
    } else {
        header("location: participants.php?Id=" . $idAct );
    }

}

?>
<script type="text/javascript" src="../src/ckeditor/ckeditor.js"></script>


<h1><?=$mrp->getText('Nouvelle activité') ?></h1>

<form action="#" method="post">
    <form  method="post">
        <table>
            <tr>
                <th><?=$mrp->getText('Type d\'activité') ?></th>
                <th><?=$mrp->getText('Nom de l\'activité') ?></th>
                <th><?=$mrp->getText('Date de début') ?></th>
                <th><?=$mrp->getText('Date de fin') ?></th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="actType"><?php fillList($aTypeActivite, $activite['actType'], 'tActId', 'tActNom',$mrp) ?></select></td>
                <td ><input style="width: 238px;" name="actNom" value="<?php echo $activite['actNom'] ?>"></td>
                <td><input class="input150" type="Date" name="actDebut" value="<?php echo $activite['actDebut'] ?>"></td>
                <td><input class="input150" type="Date" name="actFin" value="<?php echo $activite['actFin'] ?>"></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?=$mrp->getText('Lieu') ?></th>

                <th colspan="3"><?=$mrp->getText('Thème') ?></th>

            </tr>
            <tr>
                <td><input style="width: 238px;" name="actLieu" VALUE="<?php echo $activite['actLieu']; ?>"></td>
                <td colspan="3"><input style="width: 730px;" name="actTheme" VALUE="<?php echo $activite['actTheme']; ?>"></td>


            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?=$mrp->getText('Code Ofas') ?></th>
                <th><?=$mrp->getText('Type de cours') ?></th>
                <th><?=$mrp->getText('Critères d\'attribution') ?></th>
                <th><?=$mrp->getText('Langue') ?></th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="actCodeOfas">
                        <option> -></option><?php fillList($aCodeOFas, $activite['actCodeOfas'],'cat1Id', 'catNom') ?>
                    </select></td>

                <td><select style="width: 238px;" name="actOfasType">
                        <option> -></option><?php  fillList($aCodeTypeOfas,$activite['actOfasType'], 'ofaTypId', 'ofaTypNom',$mrp) ?>
                    </select></td>
                <td><select style="width: 238px;" name="actOfaCat">
                        <option> -></option><?php fillList($aCodeCatOfas,$activite['actOfaCat'], 'ofaCatId', 'ofaCatNom') ?>
                    </select></td>
                <td><select style="width: 238px;" name="langCode">
                        <?php fillList($aLangue,'fr', 'langCode', 'langNom',$mrp) ?>
                    </select></td>

            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?=$mrp->getText('Responsable') ?></th>
                <th><?=$mrp->getText('Co-Responsable') ?></th>
                <th><?=$mrp->getText('Cuisinier') ?></th>
                <th><?=$mrp->getText('Resp. des soins') ?></th>
            </tr>

            <tr>
                <td><select style="width:238px;" name="actResponsable" ><?php fillList($aResponsable,$activite['actResponsable'],'conId','conNomPrenom','')?></select></td>
                <td><select style="width:238px;" name="actCoResponsable"><?php fillList($aCoresponsable,$activite['actCoResponsable'],'conId','conNomPrenom','')?></select></td>
                <td><select style="width:238px;" name="actCuisiniere" ><?php fillList($aCuisinier,$activite['actCuisiniere'],'conId','conNomPrenom','')?></select></td>
                <td><select style="width:239px;" name="actInfirmier" ><?php fillList($aInfirmier,$activite['actInfirmier'],'conId','conNomPrenom','')?></select></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th COLSPAN="6"><?=$mrp->getText('Description de l\'activité') ?></th>
            </tr>
            <tr>
                <td colspan="6" >
                    <textarea cols="75" class="ckeditor" id="editeur" name="actDec" rows="10"><?php echo $activite['actDec'] ?></textarea>

            </tr>
            <tr>
                <td><input type="submit" name="Valider" VALUE="<?=$mrp->getText('Valider') ?>" class="ValiderPetit"></td>
                <td>
                    <input type="submit" name="Ajout" VALUE="<?=$mrp->getText('Ajout Participant') ?>" class="ValiderPetit"></td>
            </tr>
        </table>
    </form>

    <?php include('../src/footer.inc.php'); ?>