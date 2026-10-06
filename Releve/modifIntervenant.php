<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 22/08/2017
 * Description de la page
 */
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$searchedParam = $_GET['searchedParam'];
$statu = $_GET['statu'];
$beneficiaire = $_GET['beneficiaire'];
$lstIntervenant = $bdd->query("SELECT conNom, conPrenom,conId FROM tblContact WHERE conIntervenant = 1 order by conNom,conPrenom");

echo '<p>Bénéficiaire : '.$beneficiaire.'</p>';

if(isset($_POST['Valider'])){
    try{
        $searchedParam = $_POST['searchedParam'];
        $statu = $_POST['statu'];
        $beneficiaire = $_POST['beneficiaire'];
        $intervenant = $_POST['intervenant'];
        // contrôle si il y a déja un intervenant pour ce béméficiaire
        $req = $bdd->prepare('SELECT * FROM tblAlocIntervenant WHERE aloIntBenefic= :benficiaire');
        $req->execute(array(
            'benficiaire' => $beneficiaire));

        $resultat = $req->fetch();
        // si le résultat est vide on crée un enregistrement
        if (!$resultat){
            $insert = $bdd->prepare("INSERT INTO tblAlocIntervenant (aloIntBenefic, $statu ) VALUES (:beneficiaire, :intervenant)");
            $insert->execute(array(
                'beneficiaire' => $beneficiaire,
                'intervenant' => $intervenant,
            ));
            header("location: releve.php?Id=".$beneficiaire.'&searchedParam='.$searchedParam);
        }
        else{       
            $insert = $bdd->prepare("UPDATE tblAlocIntervenant SET $statu = :intervenant WHERE aloIntBenefic = :beneficiaire");
            $insert->execute(array(
                'intervenant' => $intervenant,
                'beneficiaire' => $beneficiaire

            ));
            header("location: releve.php?Id=".$beneficiaire.'&searchedParam='.$searchedParam);
            exit;
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
if(isset($_POST['Annuler'])) header("location: releve.php?Id=".$beneficiaire.'&searchedParam='.$searchedParam);

?>
<form method="post">
    <input type="hidden" name="searchedParam" value="<?php echo htmlspecialchars($searchedParam); ?>">
    <input type="hidden" name="statu" value="<?php echo htmlspecialchars($statu); ?>">
    <input type="hidden" name="beneficiaire" value="<?php echo htmlspecialchars($beneficiaire); ?>">
    <select name="intervenant" id="interv" >
        <option value="N/A">-></option>
        <?php  listeDeroulante2 ($lstIntervenant,'conId','conNom','conPrenom');?>
    </select>
    <input type="submit" name="Valider" value="Valider" class="valider">
    <input type="submit" name="Annuler" value="Annuler" class="Annuler">
</form>

<div id="detInterv"></div>

<?php include('../footer.php'); ?>
<script src="../jquery-3.1.1.min.js"></script>

<script>

    $.get("ajax.interInfo.php", function (data) {
        $("#detInterv").html(data);
    });

    $("#interv").change(function () {
        $.get("ajax.interInfo.php", {inter: $("#interv").val()}, function (data) {
            $("#detInterv").html(data);
        });
    });
</script>