<?php
require_once ('../vendor/autoload.php');

$submenu='admin';
include('../src/header.inc.php');


if (isset($_POST["import"])) {
    $allowedFileType = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/octet-stream',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {
        $langCode=$_POST['langCode'];
        $targetPath = 'exportimport/' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadSheet = $Reader->load($targetPath);
        $excelSheet = $spreadSheet->getActiveSheet();
        $spreadSheetAry = $excelSheet->toArray();
        $sheetCount = count($spreadSheetAry);

        if($spreadSheetAry[0][0]=='Page' && $spreadSheetAry[0][1]=='Code' && $spreadSheetAry[0][2]=='Texte' && $spreadSheetAry[0][3]=='Traduction') {

            for ($i = 1; $i <= $sheetCount; $i++) {

                if(!empty($spreadSheetAry[$i][3])) {
                    $db->bindTxt('tradsTexte', $spreadSheetAry[$i][2]);
                    //$db->bindTxt('tradsCode', $spreadSheetAry[$i][1]);
                    $sqlQuery = 'SELECT * FROM admTradSource WHERE tradsTexte = :tradsTexte';
                    //tradsCode =
                    $resSource = $db->single($sqlQuery);

                    if ($resSource) {
                        $db->bindInt('tradsId', $resSource['tradsId']);
                        $db->bindTxt('langCode', $langCode);
                        $sqlQuery = 'SELECT * FROM admTradTexte WHERE tradsId = :tradsId AND langCode=:langCode';
                        //tradsCode =
                        $resTexte = $db->single($sqlQuery);
                        if($resTexte){
                            $db->bindInt('tradsId', $resSource['tradsId']);
                            $db->bindTxt('langCode', $langCode);
                            $db->bindTxt('tradtTexte', $spreadSheetAry[$i][3]);
                            $sqlUpdate = 'UPDATE admTradTexte SET tradtTexte=:tradtTexte WHERE tradsId = :tradsId AND langCode=:langCode';
                            $update = $db->query($sqlUpdate);
                        }else{
                            $sqlInsert = 'INSERT INTO admTradTexte (tradsId,langCode,tradtTexte) 
            VALUES (:tradsId,:langCode,:tradtTexte)';
                            $db->bindInt('tradsId', $resSource['tradsId']);
                            $db->bindTxt('langCode', $langCode);
                            $db->bindTxt('tradtTexte', $spreadSheetAry[$i][3]);
                            $insert = $db->query($sqlInsert);
                        }
                    }
                }
            }
        }else {
            $type = "error";
            $message = "Invalid column titles";
            echo $type.' '.$message;
        }
    } else {
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
        echo $type.' '.$message;
    }
    //header('location:adminTraductions.php');
}
$sqlQuery = 'SELECT * FROM admTradTexte';
$results = $db->query($sqlQuery);


// tout sélectionner
$sqlQuery = 'SELECT * FROM admTradTexte';
$results = $db->query($sqlQuery);

$lstLangue=$db->query("SELECT langCode,langNom FROM tblLangue WHERE langDefault=0 ORDER BY langNom");

?>

<h1><?=$mrp->getText('Gestion des Traductions'); ?></h1>
<p><?=$mrp->getTextXL('Pour importer les libellés choissisez la langue et cliquer sur import. ATTENTION votre fichier ecrasera tout ce qui est dejà enregistré dans la base','TRADIMPORT'); ?></p>
<br/><br/>
<div class="outer-container">
    <form action="" method="post" name="frmExcelImport"
          id="frmExcelImport" enctype="multipart/form-data">
        <div>
            <label><?=$mrp->getText('Selectionner fichier Excel'); ?></label>

            <input type="file"
                                                    name="file" id="file" accept=".xls,.xlsx">

            <select class="lstDeroulante" name="langCode">
                <?php fillList($lstLangue, '','langCode', 'langNom') ?>
            </select>

            <button type="submit" id="submit" name="import"
                    class="btn-submit">Import</button>

        </div>

    </form>

</div>
<div id="response"
     class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>


<?php
$sqlSelect = "SELECT * 
                FROM admTradTexte as txt
                INNER JOIN admTradSource as src ON txt.tradsId=src.tradsId";
$result = $db->query($sqlSelect);
if (! empty($result)) {
    ?>

    <table class='tutorial-table'>
        <thead>
        <tr>
            <th>Langue</th>
            <th>Source</th>
            <th>Code</th>
            <th>Traduction</th>

        </tr>
        </thead>
        <?php
        foreach ($result as $row) { // ($row = mysqli_fetch_array($result))
        ?>
        <tbody>
        <tr>
            <td><?php  echo $row['langCode']; ?></td>
            <td><?php  echo $row['tradsTexte']; ?></td>
            <td><?php  echo $row['tradsCode']; ?></td>
            <td><?php  echo $row['tradtTexte']; ?></td>
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <?php
}
?>
<?php include('../src/footer.inc.php'); ?>
<script src="../src/adminTable.js"></script>