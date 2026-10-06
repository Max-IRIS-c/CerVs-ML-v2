<?php
$pageNum = 30;
include ("../header.php");

$bdd = new PDO($dsn, $user, $password);
$Pavillon = $bdd->query("SELECT logId,logNom FROM tblLogement WHERE actif = 1");



?>
<nav>
    <ul>
        <li><a href="Location.php"><?php echo $mrp->getText('Liste des locations') ?> </a></li>
        <li><a href="planning.php"><?php echo $mrp->getText(' Planning') ?></a></li>
        <li><a href="optionLocation.php"><?php echo $mrp->getText(' Options (pour contrats)') ?></a></li>
        <li><a href="gestion-typePresta.php">Types de prestations</a></li>
        <li><a href="gestion-payementModality.php">Modalités des prix</a></li>
    </ul>
</nav>
<h1> <?php echo $mrp->getText('Objets en location') ?></h1>
<table>
    <tr>
        <th><?php echo $mrp->getText('Nom de l\'objet') ?> 
         </th>
        <th> </th>
    </tr>

    <?php while ($row = $Pavillon->fetch()) { ?>
        <tr>
            <td><?php echo $row['logNom'] ?> </td>
            <td><? echo '<a href="detPavillon.php?Id=' . $row['logId'] . '">'. $mrp->getText('Détail').'</a>'; ?></td>
        </tr>

    <?php } ?>


</table>



<?php include ("../footer.php");?>
