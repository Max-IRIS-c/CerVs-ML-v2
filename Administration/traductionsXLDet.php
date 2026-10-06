<?php
/**
 * Created by PhpStorm.
 * User: Maïté
 * Date: 15.06.2020
 * Time: 16:16
 */
$submenu='admin';
include('../src/header.inc.php');

$tradtId = intval($_GET["id"]);
$view = ($_GET["v"]);
$disabled=($view=='view')?'disabled':'';

$lstLangue=$db->query("SELECT langCode,langNom FROM tblLangue WHERE langDefault=0 ORDER BY langNom");

if (isset($_POST["btValid"])) {
    $tradtId = intval($_POST["tradtId"]);

    if($tradtId==0){
        $sqlInsert = 'INSERT INTO admTradTexteXL (tradsId,langCode,tradtTexte) 
		VALUES (:tradsId,:langCode,:tradtTexte)';
        $db->bindInt('tradsId', $_POST['tradsId']);
        $db->bindTxt('langCode', $_POST['langCode']);
        $db->bindTxt('tradtTexte', $_POST['tradtTexte']);
        $insert = $db->query($sqlInsert);
    }else{
        $sqlUpdate = 'UPDATE admTradTexteXL SET 
		tradsId=:tradsId,langCode=:langCode,tradtTexte=:tradtTexte
		WHERE tradtId = :tradtId';
        $db->bindInt('tradsId', $_POST['tradsId']);
        $db->bindTxt('langCode', $_POST['langCode']);
        $db->bindTxt('tradtTexte', $_POST['tradtTexte']);
        $db->bindInt('tradtId', $tradtId);
        $update = $db->query($sqlUpdate);
    }
    header('location:traductionsXL.php');
}

// Suppression d'un articles
if (isset($_POST['btDelete'])) {
    $tradtId = intval($_POST['tradtId']);
    $sqlDelete = 'DELETE FROM admTradTexteXL where tradtId = :tradtId';
    $db->bindInt('tradtId', $tradtId);
    $delete = $db->query($sqlDelete);
    header('location:traductionsXL.php');
}

// tout sélectionner
if($tradtId>0){
    $db->bindInt('tradtId', $tradtId);
    $sqlQuery = 'SELECT * 
              FROM admTradTexteXL
              INNER JOIN tblLangue ON admTradTexteXL.langCode = tblLangue.langCode
              INNER JOIN admTradSourceXL ON admTradTexteXL.tradsId = admTradSourceXL.tradsId
              WHERE tradtId=:tradtId';
    $aLine = $db->single($sqlQuery);
}else{
    $aLine=array();
}

?>
<style>
    #tradsTexte{
        width:100%;
        height:200px;
        overflow: auto;
        border:1px solid #000;
    }
</style>
<script type="text/javascript" src="../src/ckeditor/ckeditor.js"></script>
<nav <nav id="menu2">
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
<h1><?=$mrp->getText('Edition des Traductions'); ?></h1>

<form id="form-detail" method="post">
    <table class="tbl-edit" >
        <tr>
            <th class="hidden"></th>
            <td class="hidden">
                <h2><?=$mrp->getTextXL('Merci de laisser les séquences de code dans le texte ex:{CONTRACT-NAME} et les retours à la ligne tel que défini dans le texte en français','TXTTRADXL'); ?></h2>

                <input type="hidden" name="tradtId" value="<?php echo $tradtId;?>"/></td>
        </tr>
        <tr>
            <th><?=$mrp->getText('Page'); ?></th>
            <td>
                <?php echo $aLine['tradsPage']?>
            </td>
        </tr>
        <?php if($tradtId==0){ ?>
        <tr>
            <th><?=$mrp->getText('Source Texte'); ?></th>
            <td>
            <?php
                echo '<select name="tradsId" id="tradsId" style="width:100%" required '.$disabled.'>';
                echo '<option value="">'.$mrp->getText('Sélectionner').'</option>';
                $sqlQueryLang = 'SELECT *,src.tradsId as tradsId
                        FROM admTradSourceXL as src 
                        LEFT OUTER JOIN admTradTexteXL as txt ON src.tradsId = txt.tradsId 
                        WHERE tradtId IS NULL ORDER BY tradsTexte';
                $resultsLang = $db->query($sqlQueryLang);
                foreach ($resultsLang as $row) {
                    $sTexte = (empty($row["tradsCode"])) ? $row["tradsTexte"] : $row["tradsTexte"] . '(' . $row["tradsCode"] . ')';
                    echo '<option value="' . $row["tradsId"] . '">' . $sTexte . '</option>';
                }
                echo '</select>';
                ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th><?=$mrp->getText('Source Texte'); ?></th>
            <td>
                <?php if($tradtId>0){ echo '<input type="hidden" name="tradsId" value="'.$aLine["tradsId"].'"/>';} ?>
                <div id="tradsTexte">

                    <?php
                    echo $aLine["tradsTexte"];

                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><?=$mrp->getText('Langue'); ?></th>
            <td>
                <select name="langCode" <?=$disabled; ?>>
                    <?php
                    $sqlQueryLang = 'SELECT * FROM `tblLangue` ORDER BY langNom';
                    $resultsLang = $db->query($sqlQueryLang);
                    foreach( $resultsLang as $row ) {
                        echo '<option value='.$row["langCode"].'>'.$mrp->getText($row["langNom"]).'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><?=$mrp->getText('Traduction'); ?></th>
            <td>
                <textarea cols="75" class="ckeditor" id="editeur" name="tradtTexte" rows="10" required <?=$disabled; ?>><?php echo $aLine['tradtTexte'] ?></textarea>

            </td>
        </tr>
        <tr>
            <th></th>
            <td style="text-align:center;">
                <?php if($tradtId==0){ ?>
                <input type="submit" id="btn-add" name="btValid" value="<?=$mrp->getText('Ajouter'); ?>" class="btn-success">
                <?php }elseif($disabled=='disabled'){ ?>
                    <a href="traductionsXLDet.php?id=<?php echo $tradtId; ?>" class="btn-edit"><?=$mrp->getText('Modifier'); ?></a>&nbsp;&nbsp;
                    <a href="traductionsXL.php" class="btn-back"><?=$mrp->getText('Retour'); ?></a>

                <?php }else{ ?>
                    <input type="submit" id="btn-edit" name="btValid" value="<?=$mrp->getText('Sauvegarder'); ?>" class="btn-success">

                    <input type="submit" id="btn-delete" name="btDelete" value="<?=$mrp->getText('Supprimer'); ?>" class="btn-delete">
                <?php } ?>
            </td>
        </tr>
    </table>
</form>
<?php include('../src/footer.inc.php'); ?>
<script>
$("#tradsId").change(function () {
    var optionSelected = $(this).find("option:selected");
    $.get( "ajax.php", { id: optionSelected.val(),type:'trad' }, function( data ) {
        $('#tradsTexte').html(data);
    });
});
$('#form-detail').submit(function(){
    var btn= $(this).find("input[type=submit]:focus").attr("name");
    if(btn=='btDelete'){
        return confirm("<?=$mrp->getText('Veuillez confirmez la suppression') ?>");
    }

});
</script>
<script src="../src/js/adminTable.js"></script>