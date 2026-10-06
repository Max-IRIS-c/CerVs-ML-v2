<?php
$pageNum = 33;
include('../variables.php');
$bdd = new PDO($dsn, $user, $password);

if (!empty ($_GET)) {
    $id = $_GET['id'];
    $optLocation = $bdd->query("SELECT * FROM tblOptLocation
 LEFT JOIN tblTypeBien on optAttribue = tBienId WHERE optId != $id ");
    $optLocationModif = $bdd->query("SELECT * FROM tblOptLocation
 LEFT JOIN tblTypeBien on optAttribue = tBienId WHERE optId = $id ");
    $rOption = $optLocationModif->fetch();

} else {
    $optLocation = $bdd->query("SELECT * FROM tblOptLocation
LEFT JOIN tblTypeBien ON optAttribue = tBienId ");
}
$lstAttribution = $bdd->query("SELECT * FROM tblTypeBien");

if (!empty($_POST['Ajouter'])) // ajout de l'option
{
    $insert = $bdd->prepare('INSERT INTO tblOptLocation
(optNom, optPrix, optAttribue)VALUES(:optNom, :optPrix, :optAttribue)');

    $insert->execute(array(
        'optNom' => $_POST['nom'],
        'optPrix' => $_POST['prix'],
        'optAttribue' => $_POST['attributon'],
    ));

    header("location: optionLocation.php");
}

if (!empty($_POST['Modifier']))// modification de l'option

{
    $id2= $_POST['id'];

    $insert = $bdd->prepare("UPDATE tblOptLocation SET 
optNom =:optNom,
optPrix =:optPrix,
optAttribue =:optAttribue
WHERE optId = $id2 ");

    $insert->execute(array(

        'optNom' => $_POST['nom'],
        'optPrix' => $_POST['prix'],
        'optAttribue' => $_POST['attributon'],

    ));
    header("location: optionLocation.php");



}

if (!empty($_POST['Supprimer']))// suppression de l'option
{
    $sql = "DELETE FROM tblOptLocation where optId= ".$_POST['id'];
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    header("location: optionLocation.php");
}






include ('../heade.php');

?>
    <nav
    <nav id="menu2">
        <ul> <li><a href="Location.php"> Locations </a></li>
            <li><a href="planning.php"> Planning des locations </a></li>
        </ul>
    </nav>

    <h1>Options des locations</h1>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prix</th>
            <th>Concerne</th>
            <th></th>
        </tr>
        <?php
        if (!empty($_GET)) {
            ?>
            <form method="post">
            <tr>
                <td><input name="nom" value="<? echo $rOption ['optNom'] ?>"></td>
                <td><input name="prix" value="<? echo $rOption ['optPrix'] ?>"</td>
                <td><select name="attributon" ><?php ListeModif($lstAttribution,$rOption['optAttribue'],'tBienId','tBienNom')?> </select></td>
                <td><input type="hidden" name="id" value="<?php echo $rOption['optId']?>">
                    <input type="submit" value="Modifier" name="Modifier" class="ValiderPetit">
                    <input type="submit" value="Supprimer" name="Supprimer" class="SuprimerrPetit"></td>
            </tr>
            </form>

        <? }
        else
        {?>
            <form method="post">
            <tr>
                <td><input name="nom" value=""></td>
                <td><input name="prix" value=""</td>
                <td><select name="attributon" ><?php ListeDeroulante($lstAttribution,'tBienId','tBienNom')?> </select></td>
            <td><input type="submit" value="Valider" name="Ajouter" class="ValiderPetit"></td>
            <td><input type="submit" value="Annuler" name="Annuler" class="SuprimerrPetit"></td>
            </tr>
            </form>


       <? }
        while ($r = $optLocation->fetch()) {
            echo '<tr><td>' . $r['optNom'] . '</td>
<td>' . $r['optPrix'] . '</td>
<td>' . $r['tBienNom'] . '</td>
<td><a href="optionLocation.php?id=' . $r['optId'] . '">Modifier</a></td>
</tr>';
        }

        ?>
    </table>


<?php include('../footer.php'); ?>