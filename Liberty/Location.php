<?php
$pageNum = 31;
include ("../header.php");
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'] ?? null;
$pavillon = $bdd->query("SELECT * FROM tblLogement WHERE logId = '$id'");
$pavillon = $pavillon->fetch();
$Date = date("Y-m-d");


if (!isset($id)){

    $location  = $bdd->query("SELECT locId,lStatNom ,locStatu,conNom, locConId, locDateDep, locDateEnt ,locNbrPers, locNbrPersAcc, locPavId, logNom, logtype, propriety FROM tblLocation 
    LEFT JOIN tblContact on locConId = conId 
    LEFT JOIN tblLogement on locPavId = logId
    LEFT JOIN  tblLocStatu on locStatu = lStatId WHERE locDateEnt >= '$Date' ORDER BY locDateEnt DESC ");
}
else{
    $location  = $bdd->query("SELECT locId,lStatNom ,locStatu,conNom, logtype, propriety, locConId, locDateDep, locDateEnt ,locNbrPers, locNbrPersAcc FROM tblLocation 
    LEFT JOIN tblContact on locConId = conId 
    LEFT JOIN tblLogement on locPavId = logId
    LEFT JOIN  tblLocStatu on locStatu = lStatId WHERE locPavId ='$id' AND locDateDep >= '$Date' ORDER BY locDateEnt DESC ");
}

$Client = $bdd->query("SELECT DISTINCT conId,conNom, locConId FROM tblLocation
    LEFT JOIN tblContact on locConId = conId ORDER BY conNom ASC ");
?>


<nav id="menu2" class="testNavPlannig">
    <ul>
        <li class="linktestlocation">
	        <a href="planning.php">
		        <?php echo $mrp->getText("Retour à la planification") ?>
	        </a></li>
    </ul>
</nav>

    
    <h1>Locations <? echo $pavillon['logNom'] ?? ''; ?> en cours </h1>

<table style="margin-top: 30px; " class="affichage">
    <tr>
        <?php if (empty($id)){
            echo "<th>".$mrp->getText("Objet") ."</th>";
        }?>
        <th><?php echo $mrp->getText("Client") ?></th>
        <th> <?php echo $mrp->getText("Nbre de pers") ?>.</th>
        <th> <?php echo $mrp->getText("Arrivée") ?></th>
        <th> <?php echo $mrp->getText("Départ") ?></th>
        <th><?php echo $mrp->getText("Statut")  ?></th>
        <th></th>
        <th></th>
    </tr>

    <?php while ($loc = $location->fetch()){;?>
        <tr>
            <?php if (empty($id)){
                echo "<td>".$loc['logNom']." </td>";
            }?>
            <td><?php echo $loc['conNom']?></td>
            <td><?php echo $loc['locNbrPers']?></td>
            <td><?php echo dateToUser($loc['locDateEnt'])?></td>
            <td><?php echo dateToUser($loc['locDateDep'])?></td>
            <td><?php echo $loc['lStatNom']?></td>
            <td><? echo '<a href="modifLocation.php?Id=' . $loc['locId'] . '">'.$mrp->getText("Modifier").'</a>'; ?></td>
            <td>
                <!-- td> echo '<a href="printLocation.php?Id=' . $loc['locId'] . '" target="_blank">'.$mrp->getText("Imprimer").' </a>'; ?></td> -->
                 <?php
                    $linkToContract = '';
                    switch(intval($loc['propriety'])){
                        case 0: { // cerebral
                            if($loc['logtype'] === '2') $linkToContract = "printLocation.php?Id=".$loc['locId']; // bus
                            else $linkToContract = "printLocation_logement.php?Id=".$loc['locId']; // logement
                            break;
                        }
                        case 1: { // parenthèse
                            if($loc['logtype'] === '2') $linkToContract = "redirect_printLocation_bus_parenthese.php?Id=".$loc['locId']; // bus
                            else $linkToContract = "printLocation_logement_parenthese.php?Id=".$loc['locId']; // logement
                            break;
                        }
                    }
                ?>
                <a href="<?=$linkToContract ?>" target="_blank"> Imprimer </a> 
            </td>
        </tr>


    <?php } ?>

    
</table>
<h1><?php echo $mrp->getText("Anciens contrats") ?></h1>
<table class="noMargin">

    <tr>
        <td> Recherche par client </td>
        <td> <select id="liste"><?php ListeDeroulante($Client,'conId','conNom');?>
 </select></td>
        
  
</table>


<div id="Archive"> </div>


<script src="../jquery-3.1.1.min.js"></script>
<script>
    $.get( "ajax.location.php", function( data ) {
        $( "#Archive" ).html( data );
    });
 
    $("#liste").change(function () {
       $.get( "ajax.location.php", { id: $("#liste").val() }, function( data ) {
            $( "#Archive" ).html( data );
        });
    });
 
</script>





<?php include ("../footer.php");?>