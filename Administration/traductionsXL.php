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

// tout sélectionner
$sqlQuery = 'SELECT * 
              FROM admTradTexteXL
              INNER JOIN tblLangue ON admTradTexteXL.langCode = tblLangue.langCode
              INNER JOIN admTradSourceXL ON admTradTexteXL.tradsId = admTradSourceXL.tradsId';
$results = $db->query($sqlQuery);
?>
<nav id="menu2">
    <ul>

        <?php
        if($auth >=3)
        {?>
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
    $sqlQueryLang = 'SELECT count(*) as sourceCount FROM `admTradSourceXL`  ORDER BY tradsTexte';
    $countLang = $db->single($sqlQueryLang);
    echo '<b>'.$mrp->getText('Nombre de mots dans chaque langue').'</b>: ';
    echo $mrp->getText('Français').': ';
    echo $countLang["sourceCount"];

    $sqlQueryLang = 'SELECT langNom,count(admTradTexteXL.langCode) as langCount FROM tblLangue
LEFT OUTER JOIN admTradTexteXL ON tblLangue.langCode = admTradTexteXL.langCode
WHERE langDefault=0
GROUP BY admTradTexteXL.langCode
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

    <br><br>
    <a href="traductionsXLDet.php" class="btn-add"><?=$mrp->getText('Ajouter'); ?></a>

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
                    $sTexte=substr(strip_tags($row["tradtTexte"]),0,50);
                    $sTexte=(empty($row["tradsCode"]))?$sTexte:$sTexte.'('.$row["tradsCode"].')';
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
                    <a href="traductionsXLDet.php?id=<?php echo $row['tradtId']; ?>" class="btn-edit"><?=$mrp->getText('Modifier'); ?></a>
                    <a href="traductionsXLDet.php?id=<?php echo $row['tradtId']; ?>&v=view" class="btn-edit"><?=$mrp->getText('Detail'); ?></a>

                </td>
            </tr>
            <?php
        } ?>

        </tbody>
    </table>
</form>
<?php include('../src/footer.inc.php'); ?>
<script src="../src/js/adminTable.js"></script>