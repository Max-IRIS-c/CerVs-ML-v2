
<?php 
include('../variables.php');
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
// Create the db instance
$db = new DB();
$mrp = new Mrp();
$bdd = new PDO($dsn, $user, $password);
if (isset($_GET['modif'])){ // requet lors de la modification d'une intervention
    $id = $_GET['Id'];
      $intervention = $bdd->query("SELECT genSerNom,tServicesNom,Inter.conNom,Inter.conPrenom,
 Benefi.conNom ,Benefi.conPrenom ,intDate,intBeneficiaire,intIntervenant, intId, intDebut, intFin,intFacturable,
 intSubventioner,intCommentaire 
  FROM tblIntervention
  LEFT JOIN tblTypeServices ON intType = tServicesId
  LEFT JOIN tblGenreServices ON intGenre = genSerId
  LEFT JOIN tblContact AS Inter ON intIntervenant = Inter.conId
  LEFT JOIN tblContact AS Benefi ON intBeneficiaire = Benefi.conId
WHERE intFacturable <0.01 or intFacturable IS NULL AND intId !='$id'ORDER BY intDate");

      $interventionModif = $bdd->query("SELECT * FROM tblIntervention WHERE intId ='$id'");
      $interventionModif = $interventionModif->fetch();

    $intervenant = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conIntervenant =1 ORDER BY conNom ASC");
    $beneficiaire = $bdd->query("SELECT conNom,conPrenom,conId FROM tblContact WHERE conHandicaper =1 ORDER BY conNom ASC");
    $Genre = $bdd->query("SELECT * FROM tblGenreServices");
    $Type = $bdd->query("SELECT * FROM tblTypeServices");

}
else { // requet normal
    $intervention = $bdd->query("SELECT genSerNom,tServicesNom,Inter.conNom,Inter.conPrenom,
 Benefi.conNom ,Benefi.conPrenom ,intDate,intBeneficiaire,intIntervenant, intId, intDebut, intFin,intFacturable,
 intSubventioner,intCommentaire 
  FROM tblIntervention
  LEFT JOIN tblTypeServices ON intType = tServicesId
  LEFT JOIN tblGenreServices ON intGenre = genSerId
  LEFT JOIN tblContact AS Inter ON intIntervenant = Inter.conId
  LEFT JOIN tblContact AS Benefi ON intBeneficiaire = Benefi.conId
WHERE intFacturable <0.01 or intFacturable IS NULL ORDER BY intDate");
}

if (isset($_POST['Annuler'])){
    header("location: interOuverte.php");
}

if (isset($_POST['Valider'])){

print_r($_POST);

    $insert = $bdd->prepare("UPDATE tblIntervention SET
intBeneficiaire =:beneficiaire,
intIntervenant = :intervenant,
intDate =:Date,
intDebut=:debut,
intFin=:fin,
intFacturable=:total,
intType =:type,
intGenre =:genre,
intSubventioner=:subv,
intCommentaire=:commentaire
 WHERE intId = '$id'");

    $insert->execute(array(
        'beneficiaire' => $_POST['beneficiaire'],
        'intervenant' => $_POST['intervenant'],
        'Date' => $_POST['Date'],
        'debut' => $_POST['debut'],
        'fin' => $_POST['fin'],
        'total' => $_POST['facturable'],
        'type' => $_POST['type'],
        'genre' => $_POST['genre'],
        'subv' => $_POST['subv'],
        'commentaire' =>$_POST['commentaire'],

    ));





    header("location: interOuverte.php");
}



include('../heade.php');
?>


<h1><?php echo $mrp->getText("Listes des interventions ouvertes") ?></h1>


<table>
    <tr>
        <th><?php echo $mrp->getText("Bénéficiaires") ?></th>
        <th><?php echo $mrp->getText("Intervenants") ?></th>
        <th><?php echo $mrp->getText("Date") ?></th>
        <th><?php echo $mrp->getText("Début") ?></th>
        <th><?php echo $mrp->getText("Fin") ?></th>
        <th><?php echo $mrp->getText("Facturé") ?></th>
        <th><?php echo $mrp->getText("Type") ?> </th>
        <th><?php echo $mrp->getText("Genre") ?></th>
        <th><?php echo $mrp->getText("Subv.") ?></th>
        <th><?php echo $mrp->getText("Commentaire") ?></th>
        <th></th>
    </tr>

   <?php if (isset($_GET['modif']))

   {?>
       <form method="post">
       <tr>

           <td><select class="input90" name="beneficiaire"><?php ListeModif2($beneficiaire,$interventionModif['intBeneficiaire'],'conId','conNom', 'conPrenom');?></select></td>
           <td><select class="input90" name="intervenant"><?php ListeModif2($intervenant,$interventionModif['intIntervenant'],'conId','conNom', 'conPrenom');?></select></td>

           <td><input id="date"  name="Date" type="date" value="<?php echo $interventionModif['intDate'];?>"></td>

           <td><input  class="input1"  name="debut" value="<?php echo HeureHhMm($interventionModif['intDebut'])?>"</td>
           <td><input  class="input1"  name="fin" value="<?php echo HeureHhMm($interventionModif['intFin'])?>"</td>
           <td><input  class="input1" name="facturable" value="<?php echo $interventionModif['intFacturable']?>"</td>
           <td><select class="input90" name="type" id=""><?php ListeModif($Type,$interventionModif['intType'],'tServicesId','tServicesNom');?></select></td>
           <td><select class="input90" name="genre" id=""><?php ListeModif($Genre,$interventionModif['intGenre'],'genSerId','genSerNom');?></select></td>
           <td><?php CheckBoxModif($interventionModif['intSubventioner'],'subv')?></td>
           <td> <textarea name="commentaire"><?php echo $interventionModif['intCommentaire']?></textarea></td>
           <td><input type="submit" value="Valider" name="Valider" class="ValiderPetit">
               <input type="submit" value="Annuler" name="Annuler" class="SuprimerrPetit"></td>


       </tr>
       </form>
   <?php }

   ?>


    <? while($row = $intervention->fetch()) { ?>


        <tr>
            <td><?php echo $row[4]. ' '.$row[5]?></td>
            <td><?php echo $row[2]. ' '.$row[3]?></td>
            <td><?php echo DateToUser($row['intDate'])?></td>
            <td><?php echo HeureHhMm($row['intDebut'])?></td>
            <td><?php echo HeureHhMm($row['intFin'])?></td>
            <td><?php echo $row['intFacturable']?></td>
            <td><?php echo $row['tServicesNom']?></td>
            <td><?php echo $row['genSerNom']?></td>
            <td><?php  checkBox($row['intSubventioner'])?></td>
            <td><?php echo $row['intCommentaire']?></td>
            <td><? echo '<a href="interOuverte.php?Id='.$row['intId'].'&modif=1">'.$mrp->getText('Modifier').'</a>';?></td>

        </tr>

    <? }

    ?>
</table>
<?php include('../footer.php'); ?>