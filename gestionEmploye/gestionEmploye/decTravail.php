<?php include('../header.php');

$bdd = new PDO($dsn, $user, $password);
$type = $_GET['Type'];
$id = $_GET['Id'];

$contact =  $bdd->query("SELECT * FROM tblEmployer WHERE empId = $id");
$contact = $contact->fetch();

?>
<nav>
    <ul><li><a href="gestion.php">Retour à la listes des employés</a></li></ul>
</nav>
<?

if ($type == 1){

    echo "<h1> Période du décompte pour  $contact[empNom] $contact[empPrenom]</h1>";
}
else
{
    echo "<h1> Période du décompte ventillation pour $contact[empNom] $contact[empPrenom]</h1>";
}?>


<table>
   <? if ($type == 1){

    echo "<form method='POST' action='decompteHeure.php' target='_blank'>";
    }
    else
    {
        echo "<form method='POST' action='ventilation.php' target='_blank'>";
    }?>



        <td> Période du</td>
        <td><input type="date" name="debut" id="debute"></td>
        <td> au</td>
        <td><input type="date" name="fin" id="fin"></td>
    <td>Type de décompte <select name="TypeDecompte">
            <option value="1">Centre de charges</option>
            <option value="2">Code OFAS</option>
            <option value="3">Bénéfcaire</option>

        </select>

    </td>
        <input type="hidden" name="employer" value="<?php echo $id?>">

        <td><input type="submit" name="submit" value="Valider" class="Valider"</td>
    </form>
</table>


<?php include('../footer.php'); ?>









