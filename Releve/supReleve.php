<?php 
    include('../header.php');

    $id = $_GET[Id];
    $searchedParam = $_GET['searchedParam'];
    $bdd = new PDO($dsn, $user, $password);
    $contact = $_SESSION['contact'];
    if (isset($_POST['valider'])){
        $sql = "DELETE FROM tblIntervention where intId= ".$id;
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        header("location: releve.php?Id=".$contact.'&searchedParam='.$searchedParam);
    }
    if (isset($_POST['annuler'])){
        header("location: releve.php?Id=".$contact.'&searchedParam='.$searchedParam);
    }
?>

<h1> Veuillez confirmez la suppression </h1>
<form method="post">

    <table align="center">
        <tr>
            <td><input type="submit" value="valider" name="valider" class="valider">
                <input type="submit" value="annuler" name="annuler" class="annuler"></td>
        </tr>
    </table>


</form>

<?php include('../footer.php'); ?>
