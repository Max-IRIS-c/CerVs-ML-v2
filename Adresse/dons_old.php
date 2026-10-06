<?php
$pageNum = 9;
include('../header.php');


    $bdd = new PDO($dsn, $user, $password);
    $lstSource = $bdd->query("SELECT tCotId, concat(tCotCode,' - ',tCotNom) as 'Nom' FROM tblTypeCotisation order by tCotNom");

?>


<h1><?php echo $mrp->getText("Export des dons") ?></h1>
    <form action="exportDons.php">
        <label > <?php echo $mrp->getText("Du") ?></label>
        <input type="date" name="DateDebut">
        <label ><?php echo $mrp->getText("au") ?> </label>
        <input type="date" name="DateFin">
        <label><?php echo $mrp->getText("source") ?></label>
        <select name="Source" id=""><? ListeDeroulante($lstSource,'tCotId','Nom')?></select>
        <input type="submit" value="<?php echo $mrp->getText("Valider") ?>" name="Valider" class="ValiderPetit">
    </form>
<?php include('../footer.php');
