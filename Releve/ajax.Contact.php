<?php
include '../variables.php';

include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
$mrp = new Mrp();
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);

if(isset($searchedParam)) {
	  $c= 0;
    $req = $bdd->prepare('SELECT * FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
    Where (conNom LIKE :nom or conPrenom LIKE :nom or conTel1 LIKE :nom or conTel2 LIKE :nom or conTel3 LIKE :nom or conTel4 LIKE :nom
    or conNpa LIKE :nom or conLocaliter LIKE :nom) AND conStatu = 1 AND conHandicaper = 1 AND conSecondaire is NULL ORDER BY conNom, conPrenom ASC');
    $req->execute(['nom' => $searchedParam.'%']);
    echo  'Texte de recherche '.' ' .$searchedParam;
	
} else { 
  $sql = 'SELECT * FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId where conStatu = 1 AND conHandicaper = 1 AND conSecondaire is NULL ORDER BY conNom, conPrenom ASC';
  $req = $bdd->query($sql);
}
//echo $_SESSION['statu'];
?>




<div style="overflow:auto; height: 500px; width: 100%; border: 1px solid #AAAAAA; margin-bottom: 70px; padding: 0;">
    <table class="affichage">
      <tr>
        <th><?php echo $mrp->getText('Nom') ?> </th>
        <th><?php echo $mrp->getText('Prénom') ?> </th>
        <th><?php echo $mrp->getText('Npa') ?> </th>
        <th><?php echo $mrp->getText('Localité') ?> </th>	
        <th><?php echo $mrp->getText('Tel 1') ?> </th>
        <th></th>
      </tr>
      <? while($row = $req->fetch()) { ?>
      <tr>
          <td><? echo $row['conNom']; ?></td> 
          <td><? echo $row['conPrenom']; ?></td>
          <td><? echo $row['conNpa']; ?></td>
          <td><? echo $row['conLocaliter']; ?></td>
          <td><? echo $row['conTel1']; ?></td>
          <td><? echo '<a href="releve.php?Id='.$row['conId'].'&searchedParam='.$searchedParam.'">'.$mrp->getText('Intervention').'</a>';?></td>
      </tr>

      <? }   
$req->closeCursor();   
?>

</table>
</div >
