<?php
/**
 * Created by PhpStorm.
 * User: Code Generated
 * Date: 2020-07-28
 * Time: 09:03
 */

$submenu='admin';
include_once "../src/header.inc.php";


/* RESET ACCESS
$resultPg = $db->query('SELECT * FROM admPages');
foreach( $resultPg as $lignePg ) {
    $db->bindInt('pageId',$lignePg['pageId']);

    $sqlQuery = 'SELECT * FROM tblAutorisation';
    $resultGp = $db->query($sqlQuery);
    foreach( $resultGp as $ligneGP ) {
        $db->bindInt('pageId',$lignePg['pageId']);
        $db->bindInt('autId',$ligneGP['autId']);
        $db->query('INSERT IGNORE INTO admPagesAutor (pageId,autId) VALUES (:pageId,:autId);');

    }
}*/

// Suppression d'un articles
if (isset($_GET['btDelete'])) {
    $iPageautorId = intval($_GET['pageautorId']);
    $sQueryDel = 'DELETE FROM admPagesAutor where pageautorId = :pageautorId';
    $db->bindInt('pageautorId', $iPageautorId);
    $sDelete = $db->query($sQueryDel);
}

$lstAutorisation=$db->query("SELECT autId,autNom FROM tblAutorisation ORDER BY autNom");
// tout sélectionner
$sQuery = 'SELECT * 
            FROM admPagesAutor as pa
            INNER JOIN tblAutorisation as aut ON aut.autId=pa.autId
            INNER JOIN admPages as pages ON pages.pageId=pa.pageId
            ORDER BY pageUrl ASC';
$aResult = $db->query($sQuery);
?>
<nav id="menu2">
    <ul>
        <?php
        if($auth >=3)
        {?>
            <li class="textGauche"><a href="utilisateur.php"> <?=$mrp->getText('Utilisateurs') ?></a></li>
            <li class="textGauche"><a href="autorisation.php"> <?=$mrp->getText('Autorisations') ?></a></li>
            <li class="textGauche"><a href="pagesautor.php"> <?=$mrp->getText('Pages Autor') ?></a></li>
            <li class="textGauche"><a href="dossier.php"> <?=$mrp->getText('Dossiers') ?></a></li>
            <li class="textGauche"><a href="marqueur.php"> <?=$mrp->getText('Liste des marqueurs') ?></a></li>
        <?php }?>
    </ul>
</nav>
<h1><?=$mrp->getText('Liste des Autorisations'); ?></h1>
<form id="form-search" method="POST">
    <input id="tbl-search-val" class="tbl-search" type="text" placeholder="<?=$mrp->getText('Rechercher..'); ?>">
    <select id="tbl-search-col" class="tbl-search" name="search-2">
        <option value="">--</option>
        <?php fillList($lstAutorisation, '','autId', 'autNom') ?>
    </select>
    <button class="btn-info btn-reset" type="reset"><?=$mrp->getText('Réinitialiser'); ?></button>
    <table class="tbl-display tbl-sort" >
        <thead>
        <tr>
            <th class="hidden"></th>
            <th><?=$mrp->getText('Page'); ?></th>
            <th><?=$mrp->getText('Autorisations'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach( $aResult as $aRow ) {?>
            <tr>
                <td id="pageautorId_<?php echo $aRow['pageautorId'];?>" class="hidden">
                    <span class="hide"><?php echo $aRow['pageautorId']?></span>
                </td>
                <td id="pageId_<?php echo $aRow['pageautorId'];?>">
                    <span class="hide"><?php echo $aRow['pageId']?></span>
                    <?php echo $aRow['pageUrl']?>
                </td>
                <td id="autId_<?php echo $aRow['pageautorId'];?>">
                    <span class="hide"><?php echo $aRow['autId']?></span>
                    <?php echo $aRow['autNom']?>
                </td>
                <td>
                    <a href="pagesautor.php?pageautorId=<?php echo $aRow['pageautorId']?>&btDelete=del" class="btn-delete"><?=$mrp->getText('Supprimer'); ?></a></td>

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