<?php
include('../src/header.inc.php');

$bAll=(isset($_POST["bAll"]) && $_POST["bAll"]==1)?true:false;
$langCode=(isset($_POST["langCode"]))?$_POST["langCode"]:'de';

$lstLangue=$db->query("SELECT langCode,langNom FROM tblLangue WHERE langDefault=0 ORDER BY langNom");

if($bAll==true){
    $sqlQuery = 'SELECT * FROM admTradSource as src 
            LEFT OUTER JOIN admTradTexte as txt ON src.tradsId = txt.tradsId AND langCode=:langCode
            ORDER BY tradsPage,tradsTexte';
    $sqlQueryXL = 'SELECT * FROM admTradSourceXL as src 
            LEFT OUTER JOIN admTradTexteXL as txt ON src.tradsId = txt.tradsId AND langCode=:langCode
            ORDER BY tradsPage,tradsTexte';
}else{
    $sqlQuery = 'SELECT * FROM admTradSource as src 
            LEFT OUTER JOIN admTradTexte as txt ON src.tradsId = txt.tradsId AND langCode=:langCode
            WHERE txt.tradtTexte IS NULL ORDER BY tradsPage,tradsTexte';
    $sqlQueryXL = 'SELECT * FROM admTradSourceXL as src 
            LEFT OUTER JOIN admTradTexteXL as txt ON src.tradsId = txt.tradsId AND langCode=:langCode
            WHERE txt.tradtTexte IS NULL ORDER BY tradsPage,tradsTexte';
}
$db->bindInt('langCode', $langCode);
$results = $db->query($sqlQuery);


?>
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
<h1><?=$mrp->getText('Gestion des Traductions'); ?></h1>
<p><?=$mrp->getTextXL('Choisissez la langue, tout ou seulement les textes non traduits ensuite bouton exporter tout en bas de page.','TRADEXPORT'); ?></p>
<br/><br/>
<form method="POST">
    <select class="lstDeroulante" name="langCode">
        <?php fillList($lstLangue, $langCode,'langCode', 'langNom') ?>
    </select>
    <input type="radio" id="new" name="bAll" <?=($bAll==false)?'checked="checked"':''; ?> value="0"/> <?=$mrp->getText('Traduction manquantes'); ?>&nbsp;
    <input type="radio" id="new" name="bAll" <?=($bAll==true)?'checked="checked"':''; ?> value="1"/> <?=$mrp->getText('Tout'); ?>
    <input type="submit" id="btn-edit" name="btExport" value="<?=$mrp->getText('Changer'); ?>" class="btn-info">
</form>
    <br />
    <div class="table-responsive">
        <form method="POST" id="convert_form" action="tradExportExcel.php">
            <table class="table table-striped table-bordered" id="table_content">
                <tr>
                    <th>Page</th>
                    <th>Code</th>
                    <th>Texte</th>
                    <th>Traduction</th>
                </tr>
                <?php
                foreach($results as $row)
                {
                    echo '
                <tr>
                  <td>'.$row["tradsPage"].'</td>
                  <td>'.$row["tradsCode"].'</td>
                  <td>'.$row["tradsTexte"].'</td>
                  <td>'.$row["tradtTexte"].'</td>
                </tr>
                ';
                }
                ?>
            </table>
            <input type="hidden" name="file_content" id="file_content" />
            <button type="button" name="convert" id="convert" class="btn btn-primary">Exporter</button>
        </form>
        <br />
        <br />
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</body>
</html>

<script>
    $(document).ready(function(){
        $('#convert').click(function(){
            var table_content = '<table>';
            table_content += $('#table_content').html();
            table_content += '</table>';
            $('#file_content').val(table_content);
            $('#convert_form').submit();
        });
    });
</script>

