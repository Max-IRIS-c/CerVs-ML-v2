<?php include('../header.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$searchedParam = $_GET['searchedParam'];

try{  
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
    echo 'here';
}catch(Exception $e){
    echo '<p>'.$e->getMessage().'</p>';
}

if(isset($_POST['confirmUpdate'])){
    try{
        $formatedData = formatDataForQuery($_POST);
        if(is_int($formatedData['idMedication2']) && $formatedData['idMedication2'] !== 0){
            updateMedication2($formatedData, $bdd);
        }else{
            insertNewMedication2($formatedData, $bdd);
        }
        header("location: medical.php?Id=".$id.'&searchedParam='.$searchedParam);
    }catch(Exception $e){
        echo '<p>'.$e->getMessage().'</p>';
    }
}

function insertNewMedication2($formatedData, $bdd){
    try{
        $insert = $bdd->prepare("INSERT INTO tblMedication2 (
            fkConId,
            isMedicated,
            habitudes,
            matin,
            midi,
            soir,
            nuit
        ) VALUES (
            :fkConId,
            :isMedicated,
            :habitudes,
            :matin,
            :midi,
            :soir,
            :nuit
        )");
        $insert->execute(array(
            'fkConId' => $formatedData['fkConId'], 
            'isMedicated' => $formatedData['isMedicated'],
            'habitudes' => $formatedData['habitudes'],
            'matin' => $formatedData['matin'], 
            'midi' => $formatedData['midi'],
            'soir' => $formatedData['soir'],
            'nuit' => $formatedData['nuit']
        ));
        return true;
    }catch(Exception $e){
        echo '<p>'.$e->getMessage().'</p>';
        return false; 
    }
}
function updateMedication2($formatedData, $bdd){
    try{
        $update = $bdd->prepare("UPDATE tblMedication2 SET
            isMedicated = :isMedicated,
            habitudes= :habitudes,
            matin = :matin,
            midi = :midi,
            soir = :soir,
            nuit = :nuit
            WHERE idMedication2 = :idMedication2
        ");
        $update->execute(array(
            'isMedicated' => $formatedData['isMedicated'],
            'habitudes' => $formatedData['habitudes'],
            'matin' => $formatedData['matin'],
            'midi' => $formatedData['midi'],
            'soir' => $formatedData['soir'],
            'nuit' => $formatedData['nuit'],
            'idMedication2' => $formatedData['idMedication2']
        ));
        return true;
    }catch(Exception $e){
        echo '<p>'.$e->getMessage().'</p>';
        return false;
    }
}
function formatDataForQuery($givenData){
    if(intval($givenData['isMedicated']) === 1){
        return [
            'idMedication2' => intval($givenData['idMedication2']) ?? 0,
            'fkConId' => intval($givenData['fkConId']),
            'isMedicated' => 1,
            'habitudes' => $givenData['habitudes'],
            'matin' => intval($givenData['matin']) ?? 0,
            'midi' => intval($givenData['midi']) ?? 0,
            'soir' => intval($givenData['soir']) ?? 0,
            'nuit' => intval($givenData['nuit']) ?? 0,
        ];
    }else {
        return [
            'idMedication2' => intval($givenData['idMedication2']) ?? 0,
            'fkConId' => intval($givenData['fkConId']),
            'isMedicated' => 0,
            'habitudes' => "",
            'matin' => 0,
            'midi' => 0,
            'soir' => 0,
            'nuit' => 0
        ];
    }
}

?>

<form method="post" id="coonf-form">
<NAV>
    <ul>
       <? echo '<li class="textGauche"><a href="../Adresse/detContacte.php?conId=' . $id. '&searchedParam='.$searchedParam. '"> Retour au contact</a></li>'; ?>
       <? echo '<li class="textGauche"><a href="medical.php?Id=' . $id .'&searchedParam='.$searchedParam. '"> Retour aux infos médicales</a></li>'; ?>
       <? echo '<li class="textGauche"><a href="../social/social.php?Id=' .$id .'&searchedParam='.$searchedParam.'"> Infos Sociales</a></li>'; ?>

    </ul>
</NAV>

    <h1>Médication(s)</h1>
     <div style="text-align: center;">
        <div style="display: flex; gap: 10px; width: 25%; justify-content: center; align-items: center; margin: auto;" >
            <fieldset>
                <legend>Médication: </legend>
                <div>
                    <input type="radio" id="radioYes" name="isMedicated" value="1" <?php 
                    echo  (intval($medicalData['isMedicated']) === 1) ? 'checked' : null; ?> />
                    <label for="huey">Oui</label>
                </div>
                <div>
                    <input type="radio" id="radioNo" name="isMedicated" value="0" 
                    <?php echo (intval($medicalData['isMedicated']) === 0) ? 'checked' : null; ?>/>                  
                    <label for="louie">Non</label>
                </div>
            </fieldset>         
        </div>
        <hr class="hrr" style="<?php echo (intval($medicalData['isMedicated']) === 0) ? 'display: none;' : null; ?>">
        <div id="daysZone" style="<?php echo (intval($medicalData['isMedicated']) === 0) ? 'display: none;' : null; ?>">
            <div>
                <p>Matin</p> 
                <?php CheckBoxModif($medicalData['matin'], 'matin')?>
            </div>
            <div>
                <p>Midi</p> 
                <?php CheckBoxModif($medicalData['midi'], 'midi')?>
            </div>
            <div>
                <p>Soir</p> 
                <?php CheckBoxModif($medicalData['soir'], 'soir')?>
            </div>
            <div>
                <p>Nuit</p> 
                <?php CheckBoxModif($medicalData['nuit'], 'nuit')?>
            </div>
        </div>                    
        <hr class="hrr" style="<?php echo (intval($medicalData['isMedicated']) === 0) ? 'display: none;' : null; ?>">
        <div>
            <p>Habitudes: </p>
            <textarea name="habitudes" style="width: 60%; height: 15vh;"><?php echo $medicalData['habitudes']; ?></textarea>
        </div>
        <input hidden type="text" name="fkConId" value="<?php echo $id; ?>"/>
        <input hidden type="text" name="idMedication2" value="<?php echo $medicalData['idMedication2']; ?>"/>
    </div>
    <?php if (($auth >= 3) Or ($auth == 1)) { ?>
        <form method="post" id="coonf-form">
            <input type="submit" value="Confirmer" name="confirmUpdate" class="valider">
            <input type="submit" value="Annuler" name="cancelUpdate" class="annuler">
        </form>
    <?php } ?>


    <script>
        addEventListener("load", (event) => {         
            const radioYes = document.getElementById('radioYes')
            const radioNo = document.getElementById('radioNo')
            const daysZone = document.getElementById('daysZone')
            const lines = document.querySelectorAll('.hrr')

            radioYes.addEventListener('click', () => {
                console.log("click : ", radioYes)
                daysZone.style.display = "flex"
                lines.forEach((line) => {  
                    line.style.display = 'block'
                })
            })
            radioNo.addEventListener('click', () => {
                console.log("click : ", radioNo)
                daysZone.style.display = 'none'
                lines.forEach((line) => {  
                    line.style.display = 'none'
                })
            })
        })
    </script>
    
    <style>
        #coonf-form{
            text-align: center;
        }
        #daysZone{
            display: flex; gap: 10px; width:75%; justify-content: center; align-items: center; margin: auto;
        }
        .hrr{
            width: 75%;
        }
        </style>
<?php /* include('../footer.php'); */ ?>


