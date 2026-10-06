<?php include('../header.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];

if( $_GET['statu']=='Modif'){
    $id = $_GET['Id'];
    // récup de l'Id du contact
    $ModifMedicament = $bdd->query("SELECT * FROM tblMedication WHERE medicId = $id");
    $ModifMedicament = $ModifMedicament ->fetch();
    $Conid = $ModifMedicament[1];
    $idContact = $Conid;

    $medical =$bdd->query("SELECT * FROM tblMedication WHERE medicConId = $Conid AND medicId !=$id");

}
else
    {
    $id = $_GET['Id'];
    $idContact = $id;
    $medical =$bdd->query("SELECT * FROM tblMedication WHERE medicConId = $id");
    }



if (isset($_POST['ValiderAjout'])){// si ajout Medicament

    $insert = $bdd->prepare("INSERT INTO tblMedication
 (medicConId, medicPrecicom,medFabriquand,medicPrisenoctu,medicPrisesoir,medicNom,medicPrisematin,medicPrisemidi,medicDossages)
VALUES
(:medicConId, :medicPrecicom,:medFabriquand,:medicPrisenoctu,:medicPrisesoir,:medicNom,:medicPrisematin,:medicPrisemidi,:medicDossages)");

    $insert->execute(array(
        'medicConId'=>$id,
        'medicPrecicom'=>$_POST['remarque'],
        'medFabriquand'=>$_POST['Fabriquand'],
        'medicPrisenoctu'=>$_POST['Nuit'],
        'medicPrisesoir'=>$_POST['Soir'],
        'medicNom'=>$_POST['Nom'],
        'medicPrisematin'=>$_POST['Matin'],
        'medicPrisemidi'=>$_POST['Midi'],
        'medicDossages'=>$_POST['Dossage']


    ));
    header("location: medication.php?Id=" . $id);

}

if (isset($_POST['ValiderModif'])){// si Modification Medicament
    $insert = $bdd->prepare("UPDATE tblMedication SET

medicConId =:medicConId,
 medicPrecicom =:medicPrecicom,
 medFabriquand =:medFabriquand,
 medicPrisenoctu =:medicPrisenoctu,
 medicPrisesoir =:medicPrisesoir,
 medicNom =:medicNom,
 medicPrisematin =:medicPrisematin,
 medicPrisemidi =:medicPrisemidi,
 medicDossages =:medicDossages

WHERE medicId = $id");


     $insert->execute(array(
        'medicConId'=>$Conid,
        'medicPrecicom'=>$_POST['remarque'],
        'medFabriquand'=>$_POST['Fabriquand'],
        'medicPrisenoctu'=>$_POST['Nuit'],
        'medicPrisesoir'=>$_POST['Soir'],
        'medicNom'=>$_POST['Nom'],
        'medicPrisematin'=>$_POST['Matin'],
        'medicPrisemidi'=>$_POST['Midi'],
        'medicDossages'=>$_POST['Dossage']


    ));

header("location: medication.php?Id=" . $Conid);
}
if (isset($_POST['Supprimer'])){// si Suppression Medicament


	$sql = "DELETE FROM tblMedication where medicId= ".$id;
	$stmt = $bdd->prepare($sql);
	$stmt->execute();


header("location: medication.php?Id=" . $Conid);
}
?>

<NAV>
    <ul>
       <? echo '<li class="textGauche"><a href="../Adresse/detContacte.php?conId=' . $idContact . '"> Retour au contact</a></li>'; ?>
       <? echo '<li class="textGauche"><a href="medical.php?Id=' . $idContact . '"> Retour aux infos médicales</a></li>'; ?>
       <? echo '<li class="textGauche"><a href="../social/social.php?Id=' .$idContact . '"> Infos Sociales</a></li>'; ?>

    </ul>
</NAV>

    <h1>Médication(s)</h1>
    <table class="affichage" style="margin-bottom: 0">

        <tr>
            <th>Nom du médicament</th>
            <th>Fabriquant</th>
            <th>Dosage</th>
            <th>Matin</th>
            <th>Midi</th>
            <th>Soir</th>
            <th>Nuit</th>
            <th>Remarque</th>
            <th></th>
        </tr>
        <?php
        // Formulaire pour Modification
        if ($_GET['statu']== 'Modif'){?>
        <form method="post">
            <tr>
                <td><input class="input150" name="Nom" value="<?php echo $ModifMedicament['medicNom'] ?>"></td>
                <td><input class="input150" name="Fabriquand" value="<?php echo $ModifMedicament['medFabriquand'] ?>"></td>
                <td><input class="input90" name="Dossage" value="<?php echo $ModifMedicament['medicDossages'] ?>"></td>
                <td><input class="input1" name="Matin" value="<?php echo $ModifMedicament['medicPrisematin'] ?>"></td>
                <td><input class="input1" name="Midi" value="<?php echo $ModifMedicament['medicPrisemidi'] ?>"></td>
                <td><input class="input1" name="Soir" value="<?php echo $ModifMedicament['medicPrisesoir'] ?>"></td>
                <td><input class="input1" name="Nuit" value="<?php echo $ModifMedicament['medicPrisenoctu'] ?>"></td>
                <td><textarea name="remarque" cols="30" rows="5"><?php echo $ModifMedicament['medicPrecicom'] ?></textarea></td>
                <td><input type="submit" name="ValiderModif" value="Valider" class="ValiderPetit">
                <input type="submit" name="Supprimer" value="Supprimer" class="SuprimerrPetit"></td>
            </tr>
        </form>

        <?php } while ($row = $medical->fetch()) { ?>

            <tr>
                <td><?php echo $row['medicNom']; ?></td>
                <td><?php echo $row['medFabriquand']; ?></td>
                <td><?php echo $row['medicDossages']; ?></td>
                <td><?php echo $row['medicPrisematin']; ?></td>
                <td><?php echo $row['medicPrisemidi']; ?></td>
                <td><?php echo $row['medicPrisesoir']; ?></td>
                <td><?php echo $row['medicPrisenoctu']; ?></td>
                <td><?php echo $row['medicPrecicom']; ?></td>
                <td><? echo '<a href="medication.php?Id='.$row['medicId'].'&statu=Modif"> Modification</a>';?></td>
            </tr>

        <?php } ?>


        <?php
        // Formulaire pour ajout
        if ($_GET['statu']!= 'Modif'){?>
        <form method="post">
        <tr>
            <td><input class="input150" name="Nom"></td>
            <td><input class="input150" name="Fabriquand"></td>
            <td><input class="input90" name="Dossage"></td>
            <td><input class="input1" name="Matin"></td>
            <td><input class="input1" name="Midi"></td>
            <td><input class="input1" name="Soir"></td>
            <td><input class="input1" name="Nuit"></td>
            <td><textarea name="remarque" cols="30" rows="5"></textarea></td>
            <td><input type="submit" name="ValiderAjout" value="Valider" class="valider"></td>
        </tr>
        </form>
    </table>

<?php }include('../footer.php');?>