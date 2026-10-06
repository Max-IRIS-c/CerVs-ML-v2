<script>
    function refuserToucheEntree(event) {
        // Compatibilité IE / Firefox
        if (!event && window.event) {
            event = window.event;
        }
        // IE
        if (event.keyCode == 13) {
            event.returnValue = false;
            event.cancelBubble = true;
        }
        // DOM
        if (event.which == 13) {
            event.preventDefault();
            event.stopPropagation();
        }
    }
</script>

<?php include('../header.php'); ?>
<?php
$bdd = new PDO($dsn, $user, $password);
$stat = $bdd->query("SELECT * FROM tblTraCat3 WHERE cat3Id >=10");
$idUtilisateur = $_SESSION['id'];
$historique = $bdd->query("SELECT * FROM tblTravail
   LEFT JOIN tblTraCat3 on traCat3 = cat3Id 
    WHERE tblEmployer_empId = '$idUtilisateur' AND cat3Id >=10 
    ORDER BY traDate DESC"
);

if (isset($_POST['Anuller'])) {
    header("location: travail.php");
    exit();
}
if ( isset ($_POST['submit'])){
    try{
        // selon work rate pour ACCIDENT=29 /MALADIE=30 /APG=31 /MATERNITE=32
        $myWorkRate = getMyWorkRate();
        $debut = $_POST['dateDebut'];
        $fin = $_POST['dateFin'];
        $statu = intval($_POST['statu']);
        $total = ($statu === 29||$statu === 30||$statu === 31||$statu === 32) ? 
                    ($myWorkRate * (JourOuvrable($_POST['dateDebut'],$_POST['dateFin'])*8.4)) / 100 
                    : JourOuvrable($_POST['dateDebut'], $_POST['dateFin'])*8.4;
        $remarque = $_POST['remarque'] ?? '';
        
        $insert = $bdd->prepare('INSERT INTO tblTravail (traCat3, tblEmployer_empId,traDate,traDateFin,traHeureTot, traCommentaire, traDebut, traFin) VALUES(:traCat3, :traEmployer, :traDate, :traDateFin, :total,:commentaire, :traDebut, :traFin)');

        $result = $insert->execute(array(
            'traCat3' => $statu,
            'traEmployer' => $idUtilisateur,
            'traDateFin' => $fin,
            'traDate' => $debut,
            'traDebut' => '00:00:00',
            'traFin' => '00:00:00',
            'total' => $total,
            'commentaire'=> $remarque,
        ));
        if (!$result) {
            $erreur = $insert->errorInfo();
            die("Échec de l'insertion : " . $erreur[2]); 
        }
        header("location: travail.php");
        exit();
    }catch(Exception $e){
        echo $e->getMessage();
        return null;
    }
}
?>
<h1><?php echo $mrp->getText("Saisie des absences (1 ou plusieurs jours)") ?></h1>
<form method="post" name="addTime">
    <table>
        <tr>
            <th><?php echo $mrp->getText("Date de début") ?></th>
            <th><?php echo $mrp->getText("Date de fin (y.c)") ?></th>
            <th><?php echo $mrp->getText("Nb. de jour(s)") ?></th>
            <th><?php echo $mrp->getText("Statut") ?></th>
            <th><?php echo $mrp->getText("Remarque(s") ?>)</th>

        </tr>
        <tr>
            <td><input onkeypress="refuserToucheEntree(event)" id="dateDebut" type="date" name="dateDebut"></td>
            <td><input onkeypress="refuserToucheEntree(event)" id="dateFin" type="date" name="dateFin"></td>
            <td><div id="total"></div> <!--div ajax.calculDate.php--> </td>
            <td>
                <select name="statu" style="width: <?php echo $Largeur ?>">
                    <?php ListeDeroulante2($stat, 'cat3Id', 'cat3Code', 'cat3Nom') ?>
                </select>
            </td>
            <td><textarea name="remarque"></textarea></td>
            <td><input id="submit" type="submit" value="valider" name="submit" class="ValiderPetit"></td>
            <td><input id="submit" type="submit" value="Annuler" name="Anuller" class="SuprimerrPetit"></td>
        </tr>
    </table>
</form>
<h1><?php echo $mrp->getText("Historique des périodes d'absences") ?> </h1>
<table>
    <tr>
        <th><?php echo $mrp->getText("Date de début") ?></th>
        <th><?php echo $mrp->getText("Date de fin") ?></th>
        <th><?php echo $mrp->getText("Nb. de jour(s)") ?></th>
        <th><?php echo $mrp->getText("Statut") ?></th>
        <th><?php echo $mrp->getText("Remarque(s)") ?></th>
        <th></th>
    </tr>
    <?php 
        while ($row = $historique->fetch()){
            $heure = $row['traHeureTot']/8.4; ?>
            <tr>
                <td><?php echo dateToUser($row['traDate']);?></td>
                <td><?php echo dateToUser($row['traDateFin']);?></td>
                <td><?php echo JourOuvrable($row['traDate'], $row['traDateFin']) ?></td>
                <td><?php echo $row['cat3Code'];?></td>
                <td><textarea cols="70" disabled><?php echo $row['traCommentaire'];?></textarea></td>
                <td> <?php echo '<a href="modifPeriode.php?Id='.$row['traId'].'">'.$mrp->getText("Modifier").'</a>';?></td>
            </tr>
    <?php } ?>
</table>
<script src="../jquery-3.1.1.min.js"></script>
<script>
    $.get("ajax.calculDate.php", function (data) {
        $("#total").html(data);
    });
    $("#dateFin").change(function () {
        $.get("ajax.calculDate.php", {idDebut: $("#dateDebut").val(), idFin: $("#dateFin").val()}, function (data) {
            $("#total").html(data);
        });
    });
</script>
<?php include('../footer.php'); ?>