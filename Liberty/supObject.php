<?php 
    include('../header.php');
    include('./class/locationObject.php');
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 14/08/2017
 * Description de la page
 */
$id = intval($_GET['Id']) > 0 ? intval($_GET['Id']) : null;
$object = !$id ? null : new LocationObject($id);

$bdd = new PDO($dsn, $user, $password);
if (isset($_POST['valider'])){

    $sql = "UPDATE tblLogement SET actif = 0 WHERE logId= ".$id;
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    header("location: liberty.php");
}

if (isset($_POST['annuler'])){

    header("location: Location.php");
}

?>

<h1> Veuillez confirmez la suppression de l'objet : "<?php echo $object->name; ?>"</h1>
<form method="post">

    <table align="center">
        <tr>
            <td><input type="submit" value="valider" name="valider" class="valider">
            <input type="submit" value="annuler" name="annuler" class="annuler"></td>
        </tr>
    </table>


</form>

<?php include('../footer.php'); ?>
