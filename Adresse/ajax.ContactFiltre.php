<?php
include '../variables.php';
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();
$bdd = new PDO($dsn, $user, $password);

if(!empty($_GET['recherche'])) {
    $c= 0;
    $marqueur = $_GET['recherche'];
    $req = $bdd->query("SELECT * FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
Where conMarquage = '$marqueur' AND conStatu =1 ORDER BY conNom, conPrenom");
    $nbr = $bdd->query("SELECT count(conId) as nombre FROM tblContact 
Where conMarquage = '$marqueur' AND conStatu =1 ");
    $nbr = $nbr->fetch();
    $nbr = $nbr['nombre'];


} else
{ $sql = 'SELECT * FROM tblContact LEFT JOIN tblCiviliter on tblCiviliter_civId = civId where conStatu = 1 ORDER BY conNom, conPrenom';
    $req = $bdd->query($sql);

    $nbr = $bdd->query("SELECT COUNT(conId)as nombre FROM tblContact WHERE conStatu =1");
    $nbr = $nbr->fetch();
    $nbr = $nbr['nombre'];
}

?>



<form method="post">
    <table style="margin: 0; padding-bottom: 0">
        <tr style="margin-bottom: 0">

            <td><input type="submit" value="Exporter (CSV)" class="valider" name="Valider">  </td>
            <?php if ($nbr <= 50){?>
            <td><input type="submit" value="Exporter (PDF)" class="valider" name="ValiderPDF">

            <?}?>

                <input type="hidden" value="<?php echo $marqueur; ?>" name="marqueur"></td>
        </tr>
    </table>
</form>
<div style="overflow:auto; height: 500px; width: 100%; border: 1px solid #AAAAAA; margin-bottom: 70px; padding: 0;">

    <table class="affichage" style="margin-top: 0">


        <tr>
            <th><?php echo $mrp->getText("Titre") ?> </th>
            <th><?php echo $mrp->getText("Nom") ?>  </th>
            <th><?php echo $mrp->getText("Prénom") ?>  </th>
            <th><?php echo $mrp->getText("Npa") ?>  </th>
            <th><?php echo $mrp->getText("Localité") ?>  </th>
            <th><?php echo $mrp->getText("Tél. 1") ?> </th>
            <th><?php echo $mrp->getText("Tél. 2") ?>  </th>
            <th><?php echo $mrp->getText("Tél. 3") ?> </th>
            <th><?php echo $mrp->getText("Tél. 4") ?> </th>
            <th><?php echo $mrp->getText("Marqueur") ?> </th>
            <th></th>
        </tr>


        <?

        $i= 0;
        while($row = $req->fetch()) { ?>
            <tr>
                <td><? echo $row['civNom']; ?></td>
                <td><? echo $row['conNom']; ?></td>
                <td><? echo $row['conPrenom']; ?></td>
                <td><? echo $row['conNpa']; ?></td>
                <td><? echo $row['conLocaliter']; ?></td>
                <td><? echo $row['conTel1']; ?></td>
                <td><? echo $row['conTel2']; ?></td>
                <td><? echo $row['conTel3']; ?></td>
                <td><? echo $row['conTel4']; ?></td>
                <td><? echo $row['conMarquage']; ?></td>
                <td><? echo '<a href="detContacte.php?conId='.$row['conId'].'">'.$mrp->getText("Détail") .'</a>';?></td>
            </tr>

        <? $i= $i+1; }
        $req->closeCursor();
        ?>
        <?php echo' Nombre de contacts trouvés '.$i;?>
    </table>
</div >
