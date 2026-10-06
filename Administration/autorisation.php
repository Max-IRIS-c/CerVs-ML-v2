<?php
/**
 * Created by PhpStorm.
 * User: Code Generated
 * Date: 2020-07-28
 * Time: 09:06
 */

$submenu='admin';
include_once "../src/header.inc.php";

if (isset($_POST["btValid"])) {
    $iAutId = intval($_POST["autId"]);

    if($AutId==0){
        $sQueryIns = 'INSERT INTO tblAutorisation (autNom) 
		VALUES (:autNom)';
        $db->bindTxt('autNom', $_POST['autNom']);
        $sInsert = $db->query($sQueryIns);

        $iAutId = $db->lastInsertId();
    }else{
        $sQueryUp = 'UPDATE tblAutorisation SET 
		autNom=:autNom
		WHERE autId = :autId';
        $db->bindTxt('autNom', $_POST['autNom']);
        $db->bindInt('autId', $iAutId);
        $sUpdate = $db->query($sQueryUp);
    }
    header('location:autorisation.php');
}

// Suppression d'un articles
if (isset($_POST['btDelete'])) {
    $iAutId = intval($_POST['autId']);
    $sQueryDel = 'DELETE FROM tblAutorisation where autId = :autId';
    $db->bindInt('autId', $iAutId);
    $sDelete = $db->query($sQueryDel);
    header('location:autorisation.php');
}

// tout sélectionner
$sQuery = 'SELECT * FROM tblAutorisation';
$aResult = $db->query($sQuery);
?>

<h1><?=$mrp->getText('Liste des Autorisations'); ?></h1>
<form id="form-search" method="POST">
    <input id="tbl-search-val" class="tbl-search" type="text" placeholder="<?=$mrp->getText('Rechercher..'); ?>">
    <select id="tbl-search-col" class="tbl-search" name="search-COLUMNINDEX">
        <option value="">--</option>
    </select>
    <button class="btn-info btn-reset" type="reset"><?=$mrp->getText('Réinitialiser'); ?></button>
    <table class="tbl-display tbl-sort" >
        <thead>
        <tr>
            <th class="hidden"></th>
            <th><?=$mrp->getText('Nom Autorisation'); ?></th>
            <th></th>
        </tr>
        <tr class="tbl-edit-line">
            <td class="hidden"><input type="hidden" name="autId"/></td>
            <td><input type="text" name="autNom" required/></td>
            <td><input type="submit" id="btn-add" name="btValid" value="<?=$mrp->getText('Ajouter'); ?>" class="btn-success">
                <input type="submit" id="btn-edit" name="btValid" value="<?=$mrp->getText('Modifier'); ?>" class="btn-success hide">
                <input type="submit" id="btn-delete" name="btDelete" value="<?=$mrp->getText('Supprimer'); ?>" class="btn-delete hide"></td>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach( $aResult as $aRow ) {?>
            <tr>
                <td id="autId_<?php echo $aRow['autId'];?>" class="hidden">
                    <span class="hide"><?php echo $aRow['autId']?></span>
                </td>
                <td id="autNom_<?php echo $aRow['autId'];?>">
                    <?php echo $aRow['autNom']?>
                </td>
                <td>
                    <button class="btn-info btnDetail" type="button" id="<?php echo $aRow['autId']; ?>"><?=$mrp->getText('Modifier'); ?></button>
                </td>
            </tr>
            <?php
        } ?>

        </tbody>
    </table>
</form>
<?php
include_once '../src/footer.inc.php';
?>
<script src="../src/js/adminTable.js"></script>