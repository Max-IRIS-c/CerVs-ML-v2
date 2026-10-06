<?php 
include ('../variables.php');
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
$bdd = new PDO($dsn, $user, $password);

if(empty($_GET['id']))
{

$archives  = $bdd->query("SELECT locId,lStatNom ,locStatu,conNom, locConId, locDateDep, locDateEnt ,locNbrPers, locNbrPersAcc FROM tblLocation 
LEFT JOIN tblContact on locConId = conId 
LEFT JOIN  tblLocStatu on locStatu = lStatId  ORDER BY locDateEnt DESC");
}
else
{
 $id= $_GET['id'];
$archives  = $bdd->query("SELECT locId,lStatNom ,locStatu,conNom, locConId, locDateDep, locDateEnt ,locNbrPers, locNbrPersAcc FROM tblLocation 
LEFT JOIN tblContact on locConId = conId 
LEFT JOIN  tblLocStatu on locStatu = lStatId WHERE locConId = '$id' ORDER BY locDateEnt DESC");   
}

?>
<table style="margin-top: 30px; " class="affichage">
    <tr>
    
        <th> <?php echo $mrp->getText("Client") ?></th>
        <th><?php echo $mrp->getText("Nbre de pers.") ?></th>
        <th><?php echo $mrp->getText(" Arrivée") ?></th>
        <th><?php echo $mrp->getText(" Départ") ?></th>
        <th><?php echo $mrp->getText("Statut") ?></th>
        <th colspan="2"></th>
    </tr>

    <?php while ($loc = $archives->fetch()){;?>
        <tr>
            <td><?php echo $loc['conNom']?></td>
            <td><?php echo $loc['locNbrPers']?></td>
            <td><?php echo dateToUser($loc['locDateEnt'])?></td>
            <td><?php echo dateToUser($loc['locDateDep'])?></td>
            <td><?php echo $loc['lStatNom']?></td>
            <td><?
                $filename = "../pdfContrats/ContratN°$loc[locId].pdf";
                if (file_exists($filename)) {
                echo '<a href="../pdfContrats/ContratN°'.$loc['locId'].'.pdf" target="_blank"> pdf</a>';}
                else { echo ' pas de pdf ';}?></td>
            <td><? echo '<a href="modifLocation.php?Id=' . $loc['locId'] . '">'. $mrp->getText("Modifier").'</a>'; ?></td>
        </tr>


    <?php } ?>

    
</table>