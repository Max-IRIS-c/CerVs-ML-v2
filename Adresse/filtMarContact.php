<?php include('../header.php');

$bdd = new PDO($dsn, $user, $password);
$Marqueur = $bdd->query("SELECT DISTINCT conMarquage FROM tblContact WHERE conMarquage is not null ORDER BY conMarquage ASC");

if  (isset($_POST['Valider'])){
    $marqueur = $_POST['marqueur'];
var_dump($marqueur);
header("location: expotExcel.php?Id=" . $marqueur );
}
if  (isset($_POST['ValiderPDF'])){
    $marqueur = $_POST['marqueur'];
    var_dump($marqueur);
    header("location: exportPDF.php?Id=" . $marqueur );
}


?>

<?php if ($auth > 1 )
{

    ?>

    <nav id="menu2">
        <ul>
            <li class="textGauche"><a href="contact.php"><?php echo $mrp->getText("Tous les contacts") ?></a></li>
            <li class="textGauche"><a href="filtContact.php"><?php echo $mrp->getText("Filtre recherche avancée") ?> </a></li>
        </ul>
    </nav>


    <?php
}

?>
    <form method="post">
<table style="margin: 0; padding-bottom: 0">
    <tr style="margin-bottom: 0">
       <td><?php echo $mrp->getText(" Chercher selon marquage manuel ") ?><select Id="Marqueur" name="marqueur" >
               <option>-></option><?php ListeDeroulante($Marqueur, 'conMarquage', 'conMarquage') ?></select></td>
    </tr>
</table>
</form>


    <h1 style="margin-bottom: 0"><?php echo $mrp->getText("Résultat selon marquage manuel sélectionné") ?> </h1>
    <div class="liste" id="table"></div>
    <script src="../jquery-3.1.1.min.js"></script>
    <script>
        $.get( "ajax.ContactFiltre.php", function( data ) {
            $( "#table" ).html( data );
        });

        $("#Marqueur").change(function () {
            $.get( "ajax.ContactFiltre.php", { recherche: $("#Marqueur").val() }, function( data ) {
                $( "#table" ).html( data );
            });
        });

    </script>





<?php include('../footer.php'); ?>