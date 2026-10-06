<?php include('../header.php');
?>
<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<?php
$bdd = new PDO($dsn, $user, $password);
$_SESSION['traId'] = $_GET['Id'] ?? '';
$id = $_GET['Id'] ?? '';
//liste déroulante
$stat = $bdd->query("SELECT * FROM tblTraCat3 WHERE cat3Id >=10 ");

$periode = $bdd->query("SELECT * FROM tblTravail WHERE traId = '$id'");
$periode = $periode->fetch();

$historique = $bdd->query("SELECT * FROM tblTravail
   LEFT JOIN tblTraCat3 on traCat3  = cat3Id 
    WHERE tblEmployer_empId = '$idUtilisateur' AND cat3Id >=10 AND traId != '$id'");
if (isset($_POST['Anuller'])) {header("location: travail.php");}
if ( isset ($_POST['submit']))
{
    $debut = ($_POST['dateDebut']);
    $fin = ($_POST['dateFin']);
    $total = JourOuvrable($_POST['dateDebut'],$_POST['dateFin'])*8.4;
    $statu = $_POST['statu'];
    $remarque =$_POST['commentaire'];


    $insert = $bdd->prepare("UPDATE tblTravail SET
traDate=:debut,
traDateFin=:fin,
traHeureTot=:total,
traCat3=:statu,
traCommentaire=:commentaire
 WHERE traId = '$id'");

    $insert->execute(array(
        'debut' => $debut,
        'fin' => $fin,
        'total' => $total,
        'statu' => $statu,
        'commentaire' =>$remarque,

    ));


    header("location: travail.php");
}

?>
<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="supTravail.php"> Supprimer </a></li>
    </ul>
</nav>

<h1> Modifier saisie des absences (1 ou plusieurs jours) </h1>
<h2 id="erreurVide" style="display: none"> Les champs en rouge doivent être remplis </h2>
<h2 id="erreurType" style="display: none"> Les champs en bleu ont un mauvais format, pour les date jj/mm/aaaa</h2>

<form method="post" name="addTime">
    <table>
        <tr>
            <th>Date début</th>
            <th>Date Fin y.c</th>
            <th>Nbr de jour</th>
            <th>Statu</th>
            <th>Remarques</th>

        </tr>
        <tr>
            <td><input id="dateDebut" type="date" name="dateDebut" value="<?php echo ($periode['traDate']);?>"></td>
            <td><input id="dateFin"  type="date" name="dateFin" value="<?php echo ($periode['traDateFin']);?>"></td>
            <td><div id="total"></div> <!--div ajax.calculDateModif.php--> </td>

            <td><select name="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeModif($stat, $periode['traCat3'], 'cat3Id', 'cat3Code') ?>
                </select></td>
            <td><textarea id="commentaire" cols="30" name="commentaire"><?php echo $periode['traCommentaire'];?></textarea></td>
            <td><input id="submit" type="submit" value="valider" name="submit" class="ValiderPetit"></td>
            <td><input id="submit" type="submit" value="Annuler" name="Anuller" class="SuprimerrPetit"></td>
        </tr>

    </table>
</form>

<h1>Historique des absences </h1>
<table class="affichage">

    <tr>
        <th>Date début</th>
        <th>Date Fin </th>
        <th>Nbr de jour</th>
        <th>Statut</th>
        <th></th>

    </tr>
    <?php while ($row = $historique->fetch()){
        $heure = $row['traHeureTot']/8.4;
        ?>
        <tr>

            <td><?php echo dateToUser($row['traDate']);?></td>
            <td><?php echo dateToUser($row['traDateFin']);?></td>
            <td><?php echo round($heure,1)?></td>
            <td><?php echo $row['cat3Code'];?></td>
            <td><textarea disabled><?php echo $row['traCommentaire'];?></textarea></td>
            <td> <?php echo '<a href="modifPeriode.php?Id='.$row['traId'].'"> Modifier</a>';?></td>


        </tr>
    <?php } ?>

</table>

<script>
    $.get("ajax.calculDateModif.php", function (data) {
        $("#total").html(data);
    });


    $("#dateFin").change(function () {
        $.get("ajax.calculDateModif.php", {idDebut: $("#dateDebut").val(), idFin: $("#dateFin").val()}, function (data) {
            $("#total").html(data);
        });

    });
</script>


<?php include('../footer.php'); ?>
