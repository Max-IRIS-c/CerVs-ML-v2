<?php
include('../variables.php');
$bdd = new PDO($dsn, $user, $password);
$traId=$_SESSION['traId'] ;

if (!empty($_GET['date'])) {
    //Date modifier par l'utilisateur
    $Date = ($_GET['date']);
} else {
    //pas de modification de la date
    $Date = $bdd->query("SELECT traDate FROM tblTravail  
   WHERE traId ='$traId'");
    $Date = $Date->fetch();
     $Date = $Date[0];

}

$Historique = $bdd->query("SELECT * FROM tblTravail
 LEFT JOIN tblContact on tblContact_conId = conId
   LEFT JOIN  tblTraCat1 on traCat1 = cat1Id 
   LEFT JOIN  tblTraCat3 on traCat3 = cat3Id 
   LEFT JOIN  tblTraCat4 on traCat4 = cat4Id    
   WHERE traDate ='$Date' AND tblEmployer_empId = '$idUtilisateur'");

$Resumer = $bdd ->query("SELECT MIN(traDebut), MAX(traFin), SUM(traHeureTot) 
 FROM tblTravail 
 WHERE traDate ='$Date' AND tblEmployer_empId = '$idUtilisateur' ");
$Resumer = $Resumer->fetch();

?>
<h1> Résumé de la journée </h1>
<table  class="affichage" border="0">
    <tr>
        <th> Séquences </th>
        <th> Durée</th>
        <th> Statu</th>
        <th> Code Ofas</th>
        <th> Dossier</th>
        <th> Bénéficiaire</th>
        <th> </th>
    </tr>
    <? while($row = $Historique->fetch()) { ?>
        <tr>
            <td><?php echo HeureHhMm($row['traDebut']). " - ".HeureHhMm($row['traFin']);?></td>
            <td><?php echo HeureHhMm($row['traHeureTot'])?></td>
            <td><?php echo $row['cat3Code']?></td>
            <td><?php echo $row['cat1Code']?></td>
            <td><?php echo $row['cat4Code']?></td>
            <td><?php echo $row['conNom']. " ".$row['conPrenom']?></td>
            <td>
                <?php if (isset ($row['traDateFin'])) {
                    echo '<a href="modifPeriode.php?Id=' . $row['traId'] . '"> Modifier</a>';
                } else {
                    echo '<a href="modifTravail.php?Id=' . $row['traId'] . '"> Modifier</a>';
                } ?>
            </td>


        </tr>
    <? }
    $Historique->closeCursor();
    ?>
    <tr>
        <th> Journée </th>
        <th> Saisi  </th>
        <th colspan="5"> Durée totale de la journée de travail (y compris les pauses) </th>
    </tr>
<tr>
    <td><?php echo HeureHhMm($Resumer[0]) . " - ".HeureHhMm($Resumer[1])?></td>
    <td style="background-color: #98FB98" align="center"><?php echo HeureHhMm($Resumer[2])?></td>
    <td colspan="5"><?php echo Diff($Resumer[0],$Resumer[1])?></td>


</tr>
</table>



