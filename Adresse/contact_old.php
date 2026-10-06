<?php
    $pageNum = 6;
    include('../header.php');
    $searchedParam = $_GET['searchedParam'] ?? "";
 ?>

<nav id="menu2">
<ul> <?php if ($auth >=4){
    echo '<li class="textGauche"><a href="ajoutContact.php">'.$mrp->getText("Ajouter un contact").'</a></li>';
    echo '<li class="textGauche"><a href="dons.php">'.$mrp->getText("Export des dons").'</a></li>';
    }?>
    <li class="textGauche"><a href="filtMarContact.php"><?php echo $mrp->getText("Filtre recherche marquage manuel") ?></a></li>
    <li class="textGauche"><a href="filtContact.php"> <?php echo $mrp->getText("Filtre recherche avancée") ?></a></li>
</ul>
</nav>
	

	



	<p><?php echo $mrp->getText("Chercher") ?></p> <input name="Date" type="text" id="liste" value="<?php echo $searchedParam; ?>"> 

    <h1> <?php echo $mrp->getText("Liste des adresses") ?></h1>
<div class="liste" id="table"></div>
<script src="../jquery-3.1.1.min.js"></script>
<script>
    $.get( "ajax.Contact.php", function( data ) {
        $( "#table" ).html( data );
    });
 
    $("#liste").change(function () {
       $.get( "ajax.Contact.php", { recherche: $("#liste").val() }, function( data ) {
            $( "#table" ).html( data );
        });
    });
 
</script>



 

<?php include('../footer.php'); ?>