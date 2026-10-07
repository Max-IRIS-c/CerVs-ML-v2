<?php include('../header.php');
$id = $_GET['Id'];
$_SESSION['intervenant'] = $id;
$searchedParam= $_GET['searchedParam'] ?? '';

$bdd = new PDO($dsn,$user,$password);
$interventionOuverte = $bdd->query("SELECT * FROM tblIntervention
LEFT JOIN tblContact on intBeneficiaire = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE intIntervenant = $id AND (intFacturable <= 0 OR intFacturable is NULL)  order by intDate DESC ");

$intervention = $bdd->query("SELECT * FROM tblIntervention
LEFT JOIN tblContact on intBeneficiaire = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE intIntervenant = '$id' AND intFacturable > 0  order by intDate DESC ");

$somme =$bdd ->query(" SELECT count(intId) FROM tblIntervention WHERE intIntervenant ='$id'");
$somme = $somme ->fetch();

$contact = $bdd->query("SELECT conNom, conPrenom FROM tblContact WHERE conId = $id");
$contact = $contact->fetch();

?>
<nav <nav id="menu2">
	<ul>
        <li><?php echo '<a href="../Adresse/detContacte.php?conId='.$id.'&searchedParam='.$searchedParam.'">'.$mrp->getText("Retour au contact").'</a>';?></li>
        <li><?php echo '<a href="listeIntervention.php?Id='.$id.'&searchedParam='.$searchedParam.'">'.$mrp->getText("Liste des interventions effectuées").' </a>';?></li>
        <li><?php echo '<a href="impGroupe.php?Id='.$id.'&searchedParam='.$searchedParam.'"> '.$mrp->getText("Impression groupée").'  </a>';?></li>
	</ul>
</nav>

<h1><?= $mrp->getText("Interventions ouvertes pour") ?> :  <?php echo $contact['conNom'] . ' ' . $contact['conPrenom'] ?></h1>
<table class="affichage">
    <tr>
        <th> <?= $mrp->getText("Bénéficiaire") ?></th>
        <th> <?= $mrp->getText("Date") ?></th>
        <th> <?= $mrp->getText("Début") ?></th>
        <th> <?= $mrp->getText("Fin") ?></th>
        <th> <?= $mrp->getText("Facturé") ?></th>
        <th> <?= $mrp->getText("Type d'aide") ?></th>
        <th> <?= $mrp->getText("Type de service") ?></th>
        <th> <?= $mrp->getText("Subv.") ?></th>
        <th> <?= $mrp->getText("Commentaire") ?></th>
        <th> <?= $mrp->getText("Modifier") ?></th>
        <th> <?= $mrp->getText("Imprimer") ?></th>

    </tr>

    <?php while($row = $interventionOuverte->fetch()) { ?>
        <tr>
            <td><a href="<?php echo '../Releve/releve.php?Id='.$row['conId'].'&searchedParam='.$searchedParam; ?>"><?php echo $row['conNom'].' '.$row['conPrenom'];?></a></td>
            <td><? echo DateToUser($row['intDate']); ?> </td>
            <td><? echo HeureHhMm($row['intDebut']); ?></td>
            <td><? echo HeureHhMm($row['intFin']); ?> </td>
            <td class="input0"><? echo $row['intFacturable']; ?> </td>
            <td><? echo $row['genSerNom']; ?>  </td>
            <td><? echo $row['tServicesNom']; ?> </td>
            <td class=> <?php
                if ($row['intSubventioner'] == 1)
                {echo '<INPUT  class="input0" disabled type="checkbox" name="Parent" value="1" checked>';}
                else
                {echo '<INPUT class="input0" disabled type="checkbox" name="Parent" value="0" >';}
                ?></td>
            <td><? echo $row['intCommentaire']; ?> </td>
            <td><?php echo '<a href="modifIntervention.php?Id='.$row['intId'].'&searchedParam='.$searchedParam.'">'.$mrp->getText("Modifier").'</a>';?></td>
            <td><?php echo '<a href="../Releve/print.php?Id='.$row['intId'].'" target="_blank">'.$mrp->getText("Imprimer").'</a>';?></td>

    <?php } ?>
</table>


<h1><?= $mrp->getText("Interventions effectuées") ?></h1>
<table class="affichage">
	<tr>
        <th> <?= $mrp->getText("Bénéficiaire") ?></th>
        <th> <?= $mrp->getText("Date") ?></th>
        <th> <?= $mrp->getText("Début") ?></th>
        <th> <?= $mrp->getText("Fin") ?></th>
        <th> <?= $mrp->getText("Facturé") ?></th>
        <th> <?= $mrp->getText("Type d'aide") ?></th>
        <th> <?= $mrp->getText("Type de service") ?></th>
        <th> <?= $mrp->getText("Subv.") ?></th>
        <th> <?= $mrp->getText("Commentaire") ?></th>
        <th> <?= $mrp->getText("Modifier") ?></th>
	</tr>

	<?php while($row = $intervention->fetch()) { ?>

	<tr>
        <td><a href="<?php echo '../Releve/releve.php?Id='.$row['conId'].'&searchedParam='.$searchedParam; ?>"><?php echo $row['conNom'].' '.$row['conPrenom'];?></a></td>
        <td><? echo DateToUser($row['intDate']); ?> </td>


		<td><? echo HeureHhMm($row['intDebut']); ?></td>
		<td><? echo HeureHhMm($row['intFin']); ?> </td>
		<td class="input0"><? echo $row['intFacturable']; ?> </td>
		<td><? echo $row['genSerNom']; ?>  </td>
		<td><? echo $row['tServicesNom']; ?> </td>
		<td class=> <?php
                        if ($row['intSubventioner'] == 1)
                            {echo '<INPUT  class="input0" disabled type="checkbox" name="Parent" value="1" checked>';}
                        else
                            {echo '<INPUT class="input0" disabled type="checkbox" name="Parent" value="0" >';}
               ?></td>
		<td><? echo $row['intCommentaire']; ?> </td>
		<td><?php echo '<a href="modifIntervention.php?Id='.$row['intId'].'&searchedParam='.$searchedParam.'"> Modifier </a>';?></td>
	</tr>
	<?php } ?>
</table>





<?php include('../footer.php'); ?>
