<?php
$pageNum = 25;
include '../header.php';
include_once('../src/fonctionsSql.php');
$bdd = new PDO($dsn, $user, $password);

$type = $_GET['type'];
$id = $_GET['Id'];
if ($_GET['type'] == 'chauffeur') {
    $actid = $bdd->query("SELECT actId,chauId,feuAller FROM tblchauffeur WHERE chauId = $id ");
    $actid = $actid->fetch();
    $trajet = $actid[2];
    $actid = $actid[0];

} else {
    $actid = $bdd->query("SELECT actId,feuId,feuAller, feuOrdre FROM tblAloFeuilleRout WHERE feuId = $id ");
    $actid = $actid->fetch();
    $trajet = $actid[2];
	$ordre = $actid[3];
    $actid = $actid[0];

}


if (isset($_POST['supprimer'])) // suppression de la lignes
{
    $rowId = $_POST['ID'];

    if ($_POST['typeModif'] == 'chauffeur') { // pour les chauffeurs

        $sql = "DELETE FROM tblchauffeur WHERE chauId= " . $rowId;
        $stmt = $bdd->prepare($sql);
        $stmt->execute();

    } else // pour les participants
    {
        $sql = "DELETE FROM tblAloFeuilleRout WHERE feuId= " . $rowId;
        $stmt = $bdd->prepare($sql);
        $stmt->execute();

    }

    header("location: feuilleRouteEdition.php?Id=$actid&trajet=$trajet");

}

if (isset($_POST['valider'])) // modification de la ligne
{
    if ($_POST['typeModif'] == 'chauffeur') // pour les chauffeurs
    {

        $Chauffeur = $_POST['chauffeur'];
        $Aidechauffeur = $_POST['aideChauffeur'];
        $chauId = $_POST['ID'];

        $insert = $bdd->prepare("UPDATE tblchauffeur SET
chauPrinc =:chauffeur, 
chauAide =:aideChauffeur,
busIdNom =:bus
WHERE chauId =  '$chauId'");

        $insert->execute(array(
            'chauffeur' => $Chauffeur,
            'aideChauffeur' => $Aidechauffeur,
            'bus' => intOuNull($_POST['BusNom']),

        ));

    }
    else // pour les participants
    {
        $lieu = $_POST['Lieu'];
        $participants = $_POST['Participant'];
        $accompagnant = $_POST['Accompagnant'];
        $parId = $_POST['ID'];
	    $ordre = $_POST['ordre'];
		

        $insert = $bdd->prepare("UPDATE tblAloFeuilleRout SET
feuAcc =:accompagnant, 
feuPart =:participant,
feuLieu =:lieu,
feuOrdre = :ordre
WHERE feuId =  '$parId'");

        $insert->execute(array(
            'accompagnant' => $accompagnant,
            'participant' => $participants,
            'lieu' => $lieu,
	        'ordre' => intOuNull($ordre)

        ));

    }
    header("location: feuilleRouteEdition.php?Id=$actid&trajet=$trajet");
}



if ($type == "chauffeur") {// Affichage Modification pour le chauffeur
    $data = $bdd->query(" SELECT * FROM tblchauffeur where chauId = $id");
    $data = $data->fetch();
    ?>
    <h1>Modifier les Conducteurs</h1>
    <form method="post">
        <table>
            <tr>
                <th>Bus</th>
                <th>Conducteur A</th>
                <th>Conducteur B</th>
            </tr>
            <tr>
                <td>
                <select name="BusNom">
	                <?
                    foreach($bus as $id=>$nom) {
                        if ($data['busIdNom'] == $id)
                        {
                            echo '<option selected value="' . $id . '">' . $nom . '</option>';
                        }
                        else
                        {
                            echo '<option  value="' . $id . '">' . $nom . '</option>';
                        }
                    }
                    ?></select></td>
                <td><input style="width: 250px" name="chauffeur" value="<?php echo $data['chauPrinc'] ?>"></td>
                <td><input style="width: 250px" name="aideChauffeur" value="<?php echo $data['chauAide'] ?>"></td>
                <td><input type="submit" VALUE="Valider" name="valider" class="ValiderPetit"></td>
                <input type="hidden" value="chauffeur" name="typeModif">
                <input type="hidden" value="<?php echo $data['chauId'] ?>" name="ID">
                <td><input type="submit" VALUE="Supprimer" name="supprimer" class="SuprimerrPetit"></td>
            </tr>
        </table>

    </form>


<?
} else {//  Affichage Modification pour les participants
    $data = $bdd->query(" SELECT * FROM tblAloFeuilleRout where feuId = $id");
    $data = $data->fetch();

    ?>
    <h1>Modifier l'arrêt</h1>
    <form method="post">
        <table>
            <tr>
                <th>Lieu</th>
                <th>Participants</th>
                <th>Accompgnants</th>
	            <th>Ordre</th>
            </tr>
            <tr>
                <td><input name="Lieu" value="<?php echo $data['feuLieu'] ?>"></td>
                <td><input name="Participant" value="<?php echo $data['feuPart'] ?>"></td>
                <td><input name="Accompagnant" value="<?php echo $data['feuAcc'] ?>"></td>
	            <td><input name="ordre" type="number" min="0" step="1" title="Nombre entier uniquement" value="<?php echo $data['feuOrdre'] ?>"></td>
                <td><input type="submit" VALUE="Valider" name="valider" class="ValiderPetit"></td>
                <input type="hidden" value="participants" name="typeModif">
                <input type="hidden" value="<?php echo $data['feuId'] ?>" name="ID">
                <td><input type="submit" VALUE="Supprimer" name="supprimer" class="SuprimerrPetit"></td>
            </tr>
        </table>

    </form>

	

<?php }

?>