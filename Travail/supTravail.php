<?php include('../header.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 14/08/2017
 * Description de la page
 */
$id = $_SESSION['traId'];
$bdd = new PDO($dsn, $user, $password);
if (isset($_POST['valider'])){

    $sql = "DELETE FROM tblTravail where traId= ".$id;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();


    header("location: travail.php");
}

if (isset($_POST['annuler'])){

    header("location: travail.php");
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
