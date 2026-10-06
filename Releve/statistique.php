<?php
include ("../header.php");

$bdd = new PDO($dsn,$user,$password);
$lstType = $bdd->query("SELECT * FROM tblTypeServices");
$lstType2 = $bdd->query("SELECT * FROM tblTypeServices");
?>

<h1><?php echo $mrp->getText("Statistiques des prestations") ?> </h1>
    <form action="printStatistique.php" method="post" target="_blank">
        <table>
            <h2><?= $mrp->getText("Par bénéficiaire") ?></h2>
            <tr>
                <td><?php echo $mrp->getText("période du:") ?></td>
                <td><input type="date" name="debut"></td>
                <td><?php echo $mrp->getText("au") ?></td>
                <td><input type="date" name="fin"></td>
                <td><?php echo $mrp->getText("Type de services") ?></td>
                <td>
                    <select name="tServices">
                        <option value="0">-></option>
                        <?php ListeDeroulante($lstType,'tServicesId','tServicesNom') ?>
                    </select>
                </td>
                <td><?= $mrp->getText("Subventionné") ?></td>
                <td>
                    <select name="subventionnedStatus">
                        <option value="no" selected>Non</option>
                        <option value="yes">Oui</option>
                        <option value="both">Les deux</option>
                    </select>
                </td>
                <td><input type="submit" name="Valider" value="Valider" class="ValiderPetit"></td>
            </tr>
        </table>
    </form>
    <form action="./printStatistiquesIntervenant.php" method="post" target="_blank">
        <table>
            <h2><?= $mrp->getText("Par Intervenant") ?></h2>
            <tr>
                <td><?php echo $mrp->getText("période du:") ?></td>
                <td><input type="date" name="debut"></td>
                <td><?php echo $mrp->getText("au") ?></td>
                <td><input type="date" name="fin"></td>
                <td><?php echo $mrp->getText("Type de services") ?></td>
                <td><select name="tServices">
                        <option value="0">-></option>
                        <?php ListeDeroulante($lstType2,'tServicesId','tServicesNom')?></select></td>
                <td><input type="submit" name="Valider" value="Valider" class="ValiderPetit"></td>
            </tr>
        </table>
    </form>

<?php
include ("../footer.php");