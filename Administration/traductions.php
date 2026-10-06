<?php
/**
 * Created by PhpStorm.
 * User: Maïté
 * Date: 15.06.2020
 * Time: 16:16
 */
$submenu='admin';
include('../src/header.inc.php');
$lstLangue=$db->query("SELECT langCode,langNom FROM tblLangue WHERE langDefault=0 ORDER BY langNom");

if (isset($_POST["btValid"])) {
    $tradtId = intval($_POST["tradtId"]);

    if($tradtId==0){
        $sqlInsert = 'INSERT INTO admTradTexte (tradsId,langCode,tradtTexte) 
		VALUES (:tradsId,:langCode,:tradtTexte)';
        $db->bindInt('tradsId', $_POST['tradsId']);
        $db->bindTxt('langCode', $_POST['langCode']);
        $db->bindTxt('tradtTexte', $_POST['tradtTexte']);
        $insert = $db->query($sqlInsert);
    }else{
        $sqlUpdate = 'UPDATE admTradTexte SET 
		tradsId=:tradsId,langCode=:langCode,tradtTexte=:tradtTexte
		WHERE tradtId = :tradtId';
        $db->bindInt('tradsId', $_POST['tradsId']);
        $db->bindTxt('langCode', $_POST['langCode']);
        $db->bindTxt('tradtTexte', $_POST['tradtTexte']);
        $db->bindInt('tradtId', $tradtId);
        $update = $db->query($sqlUpdate);
    }
    header('location:traductions.php');
}

// Suppression d'un articles
if (isset($_POST['btDelete'])) {
    $tradtId = intval($_POST['tradtId']);
    $sqlDelete = 'DELETE FROM admTradTexte where tradtId = :tradtId';
    $db->bindInt('tradtId', $tradtId);
    $delete = $db->query($sqlDelete);
    header('location:traductions.php');
}

// tout sélectionner
$sqlQuery = 'SELECT * 
              FROM admTradTexte
              INNER JOIN tblLangue ON admTradTexte.langCode = tblLangue.langCode
              INNER JOIN admTradSource ON admTradTexte.tradsId = admTradSource.tradsId';
$results = $db->query($sqlQuery);
?>
<nav <nav id="menu2">
    <ul>

        <?php if($auth >=3){?>
            <li class="textGauche"><a href="traductions.php"> <?=$mrp->getText('Traductions Libellés') ?></a></li>
            <li class="textGauche"><a href="traductionsXL.php"> <?=$mrp->getText('Traductions Textes') ?></a></li>
            <li class="textGauche"><a href="tradExport.php"> <?=$mrp->getText('Export') ?></a></li>
            <li class="textGauche"><a href="tradImport.php"> <?=$mrp->getText('Import') ?></a></li>
        <?php }?>
    </ul>
</nav>
<h1><?=$mrp->getText('Gestion des Traductions'); ?></h1>
<p>
<?php
$sqlQueryLang = 'SELECT count(*) as sourceCount FROM `admTradSource`  ORDER BY tradsTexte';
$countLang = $db->single($sqlQueryLang);
echo '<b>'.$mrp->getText('Nombre de mots dans chaque langue').'</b>: ';
echo $mrp->getText('Français').': ';
echo $countLang["sourceCount"];

$sqlQueryLang = 'SELECT langNom,count(admTradTexte.langCode) as langCount FROM tblLangue
LEFT OUTER JOIN admTradTexte ON tblLangue.langCode = admTradTexte.langCode
WHERE langDefault=0
GROUP BY admTradTexte.langCode
ORDER BY langNom';
$resLang = $db->query($sqlQueryLang);
foreach( $resLang as $row ) {
    echo " - ".$row["langNom"]." ".$row["langCount"];
}
?></p><br/>

<form id="form-search" method="post">
    <input id="tbl-search-val" class="tbl-search" type="text" placeholder="<?=$mrp->getText('Rechercher..'); ?>">
    <select id="tbl-search-col2" class="tbl-search" name="search-3">
        <option value="">--</option>
        <?php fillList($lstLangue, '','langCode', 'langNom') ?>
    </select>
    <button class="btn-info btn-reset" type="reset"><?=$mrp->getText('Réinitialiser'); ?></button>

    <table class="tbl-display tbl-sort" >
        <thead>
        <tr>
            <th class="hidden"></th>
            <th><?=$mrp->getText('Page'); ?></th>
            <th><?=$mrp->getText('Source Texte'); ?></th>
            <th><?=$mrp->getText('Langue'); ?></th>
            <th><?=$mrp->getText('Traduction'); ?></th>
            <th></th>
        </tr>
        <tr class="tbl-edit-line">
            <td class="hidden"><input type="hidden" name="tradtId"/></td>
            <td><input type="text" name="tradsPage" style="width:200px" readonly/></td>
            <td>
                <select name="tradsId">
                    <?php
                    $sqlQueryLang = 'SELECT * FROM `admTradSource`  ORDER BY tradsTexte';
                    $resultsLang = $db->query($sqlQueryLang);
                    foreach( $resultsLang as $row ) {
                        $sTexte=(empty($row["tradsCode"]))?$row["tradsTexte"]:$row["tradsTexte"].'('.$row["tradsCode"].')';
                        echo '<option value='.$row["tradsId"].'>'.$sTexte.'</value>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="langCode">
                    <?php
                    $sqlQueryLang = 'SELECT * FROM `tblLangue` ORDER BY langNom';
                    $resultsLang = $db->query($sqlQueryLang);
                    foreach( $resultsLang as $row ) {
                        echo '<option value='.$row["langCode"].'>'.$mrp->getText($row["langNom"]).'</value>';
                    }
                    ?>
                </select>
            </td>
            <td><input type="text" name="tradtTexte" style="width:400px"/></td>
            <td><input type="submit" id="btn-add" name="btValid" value="<?=$mrp->getText('Ajouter'); ?>" class="btn-success">
                <input type="submit" id="btn-edit" name="btValid" value="<?=$mrp->getText('Sauvegarder'); ?>" class="btn-success hide">
                <input type="submit" id="btn-delete" name="btDelete" value="<?=$mrp->getText('Supprimer'); ?>" class="btn-delete hide"></td>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach( $results as $row ) {?>
            <tr>
                <td id="tradtId_<?php echo $row['tradtId'];?>" class="hidden">
                    <span class="hide"><?php echo $row['tradtId']?></span>
                </td>
                <td id="tradsPage_<?php echo $row['tradtId'];?>">
                    <?php echo $row['tradsPage']?>
                </td>
                <td id="tradsId_<?php echo $row['tradtId'];?>">
                    <span class="hide"><?php echo $row['tradsId']?></span>
                    <?php
                    $sTexte=(empty($row["tradsCode"]))?$row["tradsTexte"]:$row["tradsTexte"].'('.$row["tradsCode"].')';
                    echo $sTexte?>

                </td>
                <td id="langCode_<?php echo $row['tradtId'];?>">
                    <span class="hide"><?php echo $row['langCode']?></span>
                    <?php echo $row['langNom']?>
                </td>
                <td id="tradtTexte_<?php echo $row['tradtId'];?>">
                    <?php echo $row['tradtTexte']?>
                </td>
                <td>
                    <button class="btn-info btnDetail fa fa-input" type="button" id="<?php echo $row['horId']; ?>"><?=$mrp->getText('Modifier'); ?></button>

                </td>
            </tr>
            <?php
        } ?>

        </tbody>
    </table>
</form>
<?php include('../src/footer.inc.php'); ?>
<script src="../src/js/adminTable.js"></script>