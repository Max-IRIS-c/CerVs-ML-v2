<?php include('../header.php');
$bdd = new PDO($dsn, $user, $password);
$Marqueur = $bdd->query("SELECT DISTINCT conMarquage FROM tblContact WHERE conMarquage is not null ORDER BY conMarquage ASC");


if (isset ($_GET['action']) AND $_GET['action'] =='supprimer') {
    $OldMarqueur = $_GET['Id'];

    $insert = $bdd->prepare("UPDATE tblContact SET	 
          
			conMarquage = :marqueur
			 

			 WHERE conMarquage = '$OldMarqueur'");
    $insert->execute(array(
        'marqueur' => NULL
    ));


    header("location: marqueur.php");



}

?>




    <nav <nav id="menu2">
    <ul>

        <?php
        if($auth >=3)
        {?>
            <li class="textGauche"><a href="utilisateur.php"><?php echo $mrp->getText("Employés") ?></a></li>
            <li class="textGauche"><a href="dossier.php"><?php echo $mrp->getText("Dossiers") ?></a></li>

        <?php }?>
    </ul>
</nav>
<h1><?php echo $mrp->getText("Liste des marqueurs utilisés") ?></h1>
    <form METHOD="post">
<table>
    <tr>
        <th><?php echo $mrp->getText("Marqueurs") ?></th>
        <th></th>
    </tr>
<? while($row = $Marqueur->fetch()) { ?>
<tr>
    <td><?php echo $row['conMarquage']?></td>
    <td><?php echo '<a href="marqueur.php?Id='.$row['conMarquage'].'&action=supprimer">'.$mrp->getText("Supprimer") .'</a>';?></td>
</tr>
    <?php } ?>


</table>
</form>



<?php include('../footer.php'); ?><?php
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 16/10/2017
 * Time: 15:37
 */