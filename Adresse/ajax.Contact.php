<?php

function FormatTypeOfContact($givenType){
    if($givenType === null) return "Non";
    if(intval($givenType) === 1) return "Oui";
    else if(intval($givenType) === 0) return "Inactif";
}

include '../variables.php';

	$bdd = new PDO($dsn, $user, $password);
	// $bdd = new PDO('mysql:host=localhost:3307;dbname=alunis-CerVs17DEV', 'fcAdmin', 'Je30fm$2049$sdkr'); // Modif FC

include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
if(!empty($_GET['recherche'])) { //Si texte saisi dans le champ de recherhe
	$c= 0;
    $req = $bdd->prepare("SELECT conSociete,civNom,conNom,conPrenom,conNpa,conLocaliter,conTel1,conTel2,conTel3,conId, conHandicaper,conAccompagnant,conModif,conIntervenant,conSociete, conParenthese, conMembLaPar FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId
Where (conNom LIKE :nom or conPrenom LIKE :nom or conTel1 LIKE :nom or conTel2 LIKE :nom or conTel3 LIKE :nom or conTel4 LIKE :nom
or conNpa LIKE :nom or conLocaliter LIKE :nom or conSociete LIKE :nom) AND conStatu = 1 AND conNom !='-'ORDER BY conNom ASC, conPrenom ASC");
    $req->execute(['nom' => '%'.$_GET['recherche'].'%']);

    echo  $mrp->getText("Texte de recherche saisi").' : '.' ' .$_GET['recherche'];

	
} 
else // Si rien n'a été saisi
{
    $sql = "SELECT civNom,conNom,conPrenom,conNpa,conLocaliter,conTel1,conTel2,conTel3,conId,conAccompagnant,conModif,
conIntervenant,conHandicaper, conModif,conSociete, conParenthese, conMembLaPar FROM tblContact
LEFT JOIN tblCiviliter on tblCiviliter_civId = civId where conStatu = 1 AND conNom !='-' ORDER BY conModif DESC,  conNom ASC, conPrenom ASC  LIMIT 20";
  $req = $bdd->query($sql);
}
echo  $mrp->getText("Liste des derniers contacts modifiés");
?>




<div style="overflow:auto; height: 500px; width: 100%; border: 1px solid #AAAAAA; margin-bottom: 70px; padding: 0;">
    <table class="affichage">


      <tr>
        <th><?php echo $mrp->getText("Société") ?></th>
        <th><?php echo $mrp->getText("Nom") ?></th>
        <th><?php echo $mrp->getText("Prénom") ?></th>
        <th><?php echo $mrp->getText("Psh") ?></th>
        <th><?php echo $mrp->getText("Acc") ?></th>
        <th><?php echo $mrp->getText("Int") ?></th>
        <th><?php echo $mrp->getText("lapa") ?></th>
		<th><?php echo $mrp->getText("Npa") ?></th>
		<th><?php echo $mrp->getText("Localité") ?></th>
		<th style="min-width: 30mm"><?php echo $mrp->getText("Tél") ?>. 1 </th>
          <th><?php echo $mrp->geTtext("Dernière modif.") ?> </th>

        <th></th>
      </tr>
	
<?php
$i = 0;
while($row = $req->fetch()) { 
    $intervenantStatus = FormatTypeOfContact($row['conIntervenant']);
    $accompagnantStatus = FormatTypeOfContact($row['conAccompagnant']);
?>
<tr>
    <td><? echo $row['conSociete']; ?></td> 
    <td><? echo $row['conNom']; ?></td> 
		<td><?php echo $row['conPrenom']; ?></td>
		<td><?php CheckBox($row['conHandicaper']) ?></td>
		<td><?php echo $accompagnantStatus //CheckBox($row['conAccompagnant']) ?></td>
		<td><?php echo $intervenantStatus //CheckBox($row['conIntervenant']) ?></td>
	
	<!-- Modif FC -- -->
    <!-- <td><? CheckBox($row['conParenthese']) ?></td>-->
	<td><?php CheckBox($row['conMembLaPar'], 'MemPar'); ?></td>
	<!-- Modif FC -- -->

	<td><? echo $row['conNpa']; ?></td>
		<td><? echo $row['conLocaliter']; ?></td>
		<td><? echo $row['conTel1']; ?></td>
		<td><? echo DateToUser($row['conModif']); ?></td>

		<td><? 
      echo '<a href="detContacte.php?conId='.$row['conId'].'&searchedParam='.$_GET['recherche'].'"> Détail</a>';
    ?></td>
</tr>

<? $i++; }
$req->closeCursor();   
?>
        <?php echo $mrp->getText("Nombre de contacts").' :  '.$i;?>
</table>
</div >
