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
    // Fonction de chargement des contacts
    function chargerContacts(recherche) {
        $.get("ajax.Contact.php", { recherche: recherche }, function(data) {
            $("#table").html(data);
        });
    }

    // Lors du chargement de la page : si searchedParam existe, on recharge
    $(document).ready(function() {
        var searchedParam = $("#liste").val();
        if (searchedParam !== "") {
            chargerContacts(searchedParam);
        } else {
            // Sinon charge par défaut
            chargerContacts("");
        }

        // Sur changement du champ
        $("#liste").change(function () {
            chargerContacts($(this).val());
        });

        // Sur appui sur Enter dans le champ
        $("#liste").keypress(function (e) {
            if (e.which === 13) { // 13 = Enter
                //e.preventDefault(); // évite un comportement par défaut éventuel
                chargerContacts($(this).val());
            }
        });
    });
</script>
<?php include('../footer.php'); ?>