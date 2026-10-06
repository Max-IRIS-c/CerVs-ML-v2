<?php include('../header.php');

$id = $_GET['id'];
$bdd = new PDO($dsn, $user, $password);
$activiter = $bdd->query("SELECT actId, actNom FROM tblActivites");
$activiter = $activiter->fetch();
if (isset($_POST['valider'])){

    $sql = "DELETE FROM tblActivites where actId= ".$id;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();


    header("location: activite.php");
}

if (isset($_POST['annuler'])){

    header("location: activite.php");
}

?>

<h1> Veuillez confirmez la suppression de l'activiter <?php echo $activiter['actNom']?></h1>
<form method="post">

    <table align="center">
        <tr>
            <td><input type="submit" value="valider" name="valider" class="valider">
                <input type="submit" value="annuler" name="annuler" class="annuler"></td>
        </tr>
    </table>


</form>

<?php include('../footer.php'); ?>
