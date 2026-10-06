<?php include('../header.php');
$id = $_GET['Id'];
$bdd = new PDO($dsn, $user, $password);
$contact = $bdd->query("SELECT conNom,conPrenom FROM tblContact WHERE conId = '$id'");
$contact = $contact->fetch();
$lstBeneficiaire = $bdd->query("SELECT DISTINCT intBeneficiaire,conNom,conPrenom, conId FROM tblIntervention 
LEFT JOIN tblContact on intBeneficiaire = conId WHERE intIntervenant = '$id' AND (intFacturable <1 OR intFacturable is NULL) ORDER BY conNom")

// Pour le 12.12.2017 Finir le formulaire et modifier le fichier print2.php
?>
<nav>
    <ul>
        <li><?php echo '<a href="intervention.php?Id='.$id.'"> Retour  </a>';?></li>
    </ul>
</nav>



<h1> Impression groupée des interventions  pour <?php echo $contact['conNom'].' '.$contact['conPrenom']?></h1>
<table>
    <form method="POST" action=../Releve/print2.php target="_blank">


        <td> Période du</td>
        <td><input type="date" name="debut" id="debute"></td>
        <td> au</td>
        <td><input type="date" name="fin" id="fin"></td>
        <td>Bénéficiaire</td>
        <td><select name="beneficiaire">
                <option>-></option>
                <?php ListeDeroulante2($lstBeneficiaire,'conId','conNom','conPrenom')?></select></td>

        <td><input type="hidden" name="intervenenant" value="<? echo $id ?>">
            <input type="submit" name="submit" value="Valider" class="Valider"</td>
    </form>
</table>


<?php include('../footer.php'); ?>



