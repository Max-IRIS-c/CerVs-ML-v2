<?php include('../header.php');
$_SESSION['Id'] = $_GET['Id'];
$id = $_GET['Id'];
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);
$nom = $bdd->query("SELECT conNom,conPrenom FROM tblContact WHERE conId = $id");
$nom = $nom->fetch();
?>

    <nav>
        <ul>
            <li><?php echo '<a href="intervention.php?Id='.$id.'&searchedParam='.$searchedParam.'"> Retour  </a>';?></li>
        </ul>
    </nav>
    <h1> Filtre pour le décompte pour <?php echo $nom['conNom']. ' '.$nom['conPrenom']?>  </h1>
    <form method="POST" action="print.php" target="_blank">
        <table>
            <tr>
                <td> Période du</td>
                <td><input type="date" name="debut"></td>
                <td> au</td>
                <td><input type="date" name="fin"> <input type="hidden"  name="Id" value="<?php echo $_GET['Id']?>"></td>
                <td><input type="submit" name="submit" value="Valider" class="Valider"></td>
            </tr>

        </table>
    </form>
<?php include('../footer.php'); ?>