<?php include('../header.php');
include('verifi.php');

$id = $_SESSION['contact'];
$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);
// détail de l'intervention a modifier
$modif = $bdd->query("SELECT * FROM tblIntervention WHERE intId = '$_GET[Id]'");
$modif = $modif->fetch();
// intervenant de l'intervention
$interInit = $bdd->query("SELECT conId, conNom, conPrenom FROM tblContact WHERE conId = $modif[intIntervenant]");
$interInit = $interInit->fetch();
$intervenant = $interInit[0];
// intervenant Habituelle
$inter1 = $bdd->query("SELECT conId,conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntA = conId
 WHERE aloIntBenefic = '$id'");
$inter1 = $inter1->fetch();
// intervenant occasionnelle
$inter2 = $bdd->query("SELECT conId,conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntB = conId
 WHERE aloIntBenefic = '$id'");
$inter2 = $inter2->fetch();
// intervenant Dépannage
$inter3 = $bdd->query("SELECT conId, conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntC = conId
 WHERE aloIntBenefic = '$id'");
$inter3 = $inter3->fetch();
// intervenant Reserve
$inter4 = $bdd->query("SELECT conId, conNom, conPrenom, conIntervenant FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntD = conId
 WHERE aloIntBenefic = '$id'");
$inter4 = $inter4->fetch();

$idInter1 = $inter1[0];
$idInter2 = $inter2[0];
$idInter3 = $inter3[0];
$idInter4 = $inter4[0];

// Liste de tous les intervenant qui ne sont pas déja afficher
$interTot = $bdd->query("SELECT conId, conNom, conPrenom FROM tblContact 
WHERE conId NOT IN ('$idInter1','$idInter2','$idInter3','$idInter4') AND conIntervenant = 1 AND conId != $intervenant ORDER BY conNom,conPrenom");
// menu déroulant
$typeServices = $bdd->query("SELECT * FROM tblTypeServices");
$typeAide = $bdd->query("SELECT * FROM tblGenreServices");

//historique des intervention
$historique = $bdd->query("SELECT * FROM tblIntervention 
LEFT JOIN tblContact on intIntervenant = conId 
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId  WHERE intBeneficiaire ='$id' AND intId !='$_GET[Id]'");


if (isset($_POST['Valider'])) {

    $beneficiaire = $id;
    $dateInt = ($_POST['date']);
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $type = $_POST['typeServices'];
    $genre = $_POST['typeAide'];
    $subventioner = $_POST['subventioner'];
    $commentaire = $_POST['commentaire'];
    $intervenant = $_POST['intervenant'];



    $insert = $bdd->prepare("UPDATE tblIntervention SET 
		intBeneficiaire =:beneficiaire,
		intIntervenant =:intervenant,
		intDate =:dateInt,
		intDebut =:debut,
		intFin =:fin,
		intType =:type,
		intGenre =:genre,
		intCommentaire =:commentaire,
		intSubventioner =:subventioner 
		WHERE intId = '$_GET[Id]'");

    $insert->execute(array(

        'beneficiaire' => $beneficiaire,
        'intervenant' => $intervenant,
        'dateInt' => $dateInt,
        'debut' => $debut,
        'fin' => $fin,
        'type' => $type,
        'genre' => $genre,
        'commentaire' => $commentaire,
        'subventioner' => $subventioner,

    ));

    header("location: releve.php?Id=" .$id.'&searched='.$searchedParam);

}
if (isset($_POST['Supprimer'])) {
    header("location: supReleve.php?Id=" . $_GET[Id].'&searchedParam='.$searchedParam);
}
?>


<h1>Intervenants</h1>
<form>
    <select id="interv">
        <option value="aloIntA">Habitude</option>
        <option value="aloIntB">Occasionnel</option>
        <option value="aloIntC">Dépannage</option>
        <option value="aloIntD">Reserve</option>
        <option value="autres">Autres</option>
    </select>
</form>

<div id="detInterv"></div>
<div id="intervention">
    <h1>Intervention pour le bénéficiaire</h1>

    <h2 id="erreurVide" style="display: none"> Les champs en rouge doivent être remplis </h2>
    <h2 id="erreurType" style="display: none"> Les champs en bleu ont un mauvais format, pour les date jj.mm.aaaa et
        pour les heures hh:mm</h2>

    <form method="post">
        <table>
            <tr>
                <th>Intervenant</th>
                <th>Date</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Type de services</th>
                <th>Type d'aide</th>
                <th>Commentaire</th>
                <th>Subv.</th>
                <td></td>
            </tr>
            <tr>
                <td><select name="intervenant">
                        <option value="<?php echo $interInit[0] ?>"><?php echo $interInit['conNom'] . ' ' . $interInit['conPrenom'] ?></option>
                        <?php if ($interInit[0] == $inter1[0]) {
                            echo "";} else { ?>
                        <option value="<?php echo $inter1[0] ?>"><?php echo $inter1['conNom'] . ' ' . $inter1['conPrenom'] ?></option>" <?php } ?>

                        <?php if ($interInit[0] == $inter2[0]) {
                            echo "";} else { ?>
                            <option value="<?php echo $inter2[0] ?>"><?php echo $inter2['conNom'] . ' ' . $inter2['conPrenom'] ?></option>" <?php } ?>

                        <?php if ($interInit[0] == $inter3[0]) {
                            echo "";} else { ?>
                            <option value="<?php echo $inter3[0] ?>"><?php echo $inter3['conNom'] . ' ' . $inter3['conPrenom'] ?></option>" <?php } ?>

                        <?php if ($interInit[0] == $inter4[0]) {
                            echo "";} else { ?>
                            <option value="<?php echo $inter4[0] ?>"><?php echo $inter4['conNom'] . ' ' . $inter4['conPrenom'] ?></option>" <?php } ?>
                        <? ListeModif2($interTot, $modif['intIntervenan'], 'conId', 'conNom','conPrenom') ?>

                        <?php
                        $interTot->closeCursor(); ?>
                    </select></td>
                <td><input id="date" name="date" style="width:150px" type="date" value="<?php echo ($modif['intDate']); ?>">
                </td>
                <td><input id="debut" name="debut" class="input1" value="<?php echo HeureHhMm($modif['intDebut']); ?>">
                </td>
                <td><input id="fin" name="fin" class="input1" value="<?php echo HeureHhMm($modif['intFin']); ?>"></td>

                <td><select style="width: 150px;"
                            name="typeServices"><? ListeModif($typeServices, $modif['intType'], 'tServicesId', 'tServicesNom') ?></select>
                </td>
                <td><select style="width: 100px;"
                            name="typeAide"><? ListeModif($typeAide, $modif['intGenre'], 'genSerId', 'genSerNom') ?></select>
                </td>
                <td><textarea name="commentaire" cols="25"><?php echo $modif['intCommentaire']; ?></textarea></td>
                <td><?php echo CheckBoxModif($modif['intSubventioner'], subventioner); ?></td>
                <td><input id="submit" type="submit" name="Valider" class="ValiderPetit" value="Valider"> </br>
                    <input type="submit" name="Supprimer" class="SuprimerrPetit" value="Supprimer"></td>
            </tr>
            <?php while ($a = $historique->fetch()) { ?>
                <tr>
                    <td><?php echo $a['conNom'] . ' ' . $a['conPrenom']; ?></td>
                    <td><?php echo dateToUser($a['intDate']); ?></td>
                    <td><?php echo HeureHhMm($a['intDebut']); ?></td>
                    <td><?php echo HeureHhMm($a['intFin']); ?></td>
                    <td><?php echo $a['tServicesNom']; ?></td>
                    <td><?php echo $a['genSerNom']; ?></td>
                    <td><textarea cols="25"><?php echo $a['intCommentaire']; ?></textarea></td>
                    <td><?php echo CheckBox($a['intSubventioner']); ?></td>
                    <td><?php echo '<a href="releveModif.php?Id=' . $a['intId'] . '&searchedParam'.$searchedParam.'"> Modifier </a>'; ?></td>
                </tr>
            <?php } ?>


        </table>
    </form>
</div>
<script src="../jquery-3.1.1.min.js"></script>

<script>

    $.get("ajax.inter.php", {
        searchedParam: "<?php echo $searchedParam ?>"
    }, function (data) {
        $("#detInterv").html(data);
    });

    $("#interv").change(function () {
        $.get("ajax.inter.php", {
            statu: $("#interv").val(),  
            searchedParam: "<?php echo $searchedParam ?>"
        }, function (data) {
            $("#detInterv").html(data);
        });
    });
</script>
<?php include('../footer.php'); ?>
