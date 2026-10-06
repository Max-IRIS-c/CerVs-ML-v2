<?php
include("../variables.php");
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$_POST['Intervenant'] = $_POST['Intervenant'] === '' ? null : intval($_POST['Intervenant']);
$_POST['Accompagnant'] = $_POST['Accompagnant'] === '' ? null : intval($_POST['Accompagnant']);
$recherche = implode(' AND ', $_POST);

//$recherche = (implode($_POST, ' AND ')); // Récuperation de la chaine a teste

$req = $bdd->prepare("SELECT *  from tblContact
  LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
  WHERE $recherche AND conStatu = 1 ORDER BY conNom,conPrenom");
$req->execute();

if  (isset($_POST['Valider'])){
    $requet = $_POST['Recherche'];
    echo "CSV";
    header("location: expotExcelAvance.php?Id=" . $requet );
}
if(isset($_POST['ValiderPDF'])){
    $requet = $_POST['Recherche'];
    echo "PDF";
    header("location: exportPDFavance.php?Id=" . $requet );
}



$nbr = $bdd->query("SELECT count(conId)as nombre  from tblContact

 WHERE $recherche AND conStatu = 1 ");
$nbr = $nbr->fetch();
$nbr = $nbr['nombre'];





include('../heade.php')
?>

<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="contact.php"><?php echo $mrp->getText("Tous les contacts") ?></a></li>
        <li class="textGauche"><a href="filtMarContact.php"><?php echo $mrp->getText(" Filtre recherche marquage manuel"); ?></a></li>
        <li class="textGauche"><a href="filtContact.php"><?php echo $mrp->getText(" Filtre recherche avancée"); ?></a></li>
    </ul>
</nav>
<form method="post">
    <input hidden name="Recherche" value="<?php echo $recherche?>">
    <input type="submit" name="Valider" value="Export CSV" class="valider">
    <?php if ($nbr < 26){ ?>
        <input type="submit" name="ValiderPDF" value="Export PDF" class="valider">
    <? } ?>



</form>

<table class="affichage">


    <tr>
	    <th><?php echo $mrp->getText("Société") ?></th>
        <th><?php echo $mrp->getText("Nom") ?></th>
        <th><?php echo $mrp->getText("Prénom") ?></th>
        <th><?php echo $mrp->getText("Npa") ?></th>
        <th><?php echo $mrp->getText("Localité") ?></th>

	    <!-- Modif FC -- -->
	    <th><?php echo $mrp->getText("Tel1 or Tel 2 or Tel 3 or Tel 4") ?></th>
	    
	    <!--
	    <th><?php // echo $mrp->getText("Tel 1") ?></th>
        <th><?php // echo $mrp->getText("Tel 2") ?></th>
        <th><?php // echo $mrp->getText("Tel 3") ?></th>
        <th><?php // echo $mrp->getText("Tel 4") ?></th>
        -->
	    <!-- Modif FC -- -->
	    
	    <th><?php echo $mrp->getText("Marqueur") ?></th>
        <th></th>
    </tr>

	

    <?

    $i = 0;
    while ($row = $req->fetch()) { ?>
        <tr>
	        <td><? echo $row['conSociete']; ?></td>
            <td><? echo $row['conNom']; ?></td>
            <td><? echo $row['conPrenom']; ?></td>
            <td><? echo $row['conNpa']; ?></td>
            <td><? echo $row['conLocaliter']; ?></td>
	        
	        <td><? echo $row['conTel1'] ." <br/> ".  $row['conTel2']." <br/> ".  $row['conTel3']." <br/> ".  $row['conTel4']; ?></td>
	        
	        <!--
	        <td><? echo $row['conTel1']; ?></td>
            <td><? echo $row['conTel2']; ?></td>
            <td><? echo $row['conTel3']; ?></td>
            <td><? echo $row['conTel4']; ?></td>
            -->
	        <td><? echo $row['conMarquage']; ?></td>
            <td><? echo '<a href="detContacte.php?conId=' . $row['conId'] . '">'.$mrp->getText("Détail").'</a>'; ?></td>
        </tr>

        <? $i = $i + 1;
    }
    $req->closeCursor();
    ?>
    <?php echo ' Nombre de contacts trouvés ' . $i; ?>
</table>

<?php include ('../footer.php');


