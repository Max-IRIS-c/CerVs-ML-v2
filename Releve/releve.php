<?php include('../header.php');
include ('verifi.php');
include_once('../src/fonctionsSql.php');

$id = $_GET['Id'];
$_SESSION['contact'] = $id;
$searchedParam = $_GET['searchedParam'] ?? '';
$bdd = new PDO($dsn, $user, $password);

$contact = $bdd->query("SELECT conNom, conPrenom, conLocaliter FROM tblContact WHERE conId = $id");
$contact = $contact->fetch();

$inter1 = $bdd->query("SELECT conId,conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntA = conId
 WHERE aloIntBenefic = '$id'");
$inter1 = $inter1->fetch();

$inter2 = $bdd->query("SELECT conId,conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntB = conId
 WHERE aloIntBenefic = '$id'");
$inter2 = $inter2->fetch();

$inter3 = $bdd->query("SELECT conId, conNom, conPrenom FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntC = conId
 WHERE aloIntBenefic = '$id'");
$inter3 = $inter3->fetch();

$inter4 = $bdd->query("SELECT conId, conNom, conPrenom, conIntervenant FROM tblAlocIntervenant
LEFT JOIN tblContact  on aloIntD = conId
 WHERE aloIntBenefic = '$id'");
$inter4 = $inter4->fetch();

$idInter1 = $inter1[0] ?? '';
$idInter2 = $inter2[0] ?? '';
$idInter3 = $inter3[0] ?? '';
$idInter4 = $inter4[0] ?? '';

$interTot = $bdd->query("SELECT conId, conNom, conPrenom FROM tblContact 
                            WHERE conId NOT IN ('$idInter1','$idInter2','$idInter3','$idInter4')
                            AND (conIntervenant = 1 OR intervenantParenthese = 1 OR intervenantCA = 1) 
                            AND conStatu = 1 ORDER BY conNom,conPrenom");

$typeServices = $bdd->query("SELECT * FROM tblTypeServices");
$typeAide = $bdd->query("SELECT * FROM tblGenreServices");

$historique= $bdd->query("SELECT * FROM tblIntervention 
LEFT JOIN tblContact on intIntervenant = conId 
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId  WHERE intBeneficiaire ='$id' AND (intFacturable <=0 OR intFacturable is NULL) ORDER BY intDate DESC");

$historiqueFac = $bdd->query("SELECT * FROM tblIntervention 
LEFT JOIN tblContact on intIntervenant = conId 
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId  WHERE intBeneficiaire ='$id' AND (intFacturable >0) ORDER BY intDate DESC");

if(isset($_POST['Valider'])){
    $beneficiaire = $id;
    $dateInt = ($_POST['date']);
    $debut= $_POST['debut'];
    $fin = $_POST['fin'];
    $type = $_POST['typeServices'];
    $genre = $_POST['typeAide'];
    $subventioner = $_POST['subventioner'] ?? 0;
    $commentaire = $_POST['commentaire'];
    $intervenant = $_POST['intervenant'];

    $insert= $bdd->prepare(" INSERT INTO tblIntervention
 (intBeneficiaire,intDate,intDebut,intFin,intType,intGenre,intSubventioner,intCommentaire,intIntervenant)
 VALUES
 (:beneficiaire,:dateInt,:debut,:fin,:type,:genre,:subv,:commentaire,:intervenant)");

    $insert->execute(array(
        'beneficiaire' => $beneficiaire ,
        'dateInt' => dateOuNull($dateInt),
        'debut' => dateOuNull($debut),
        'fin' => dateOuNull($fin),
        'type' => intOuNull($type),
        'genre' => intOuNull($genre),
        'subv' => intOuZero($subventioner),
        'commentaire' =>$commentaire ,
        'intervenant' => intOuZero($intervenant),
    ));
    header("location: releve.php?Id=".$id.'&searchedParam='.$searchedParam);

}
?>
<nav>
    <ul>
        <li><?php echo '<a href="beneficiaire.php?searchedParam='.$searchedParam.'"> Retour aux bénéficiaires</a>';?></li>
        <li><?php echo '<a href="../Adresse/detContacte.php?conId='.$id.'&searchedParam='.$searchedParam.'">Détail contact</a>';?></li>
        <li><?php echo '<a href="listeIntervention.php?Id='.$id.'&searchedParam='.$searchedParam.'"> Interventions effectuées </a>';?></li>
        <li><?php echo '<a href="printPlanif.php?Id='.$id.'&searchedParam='.$searchedParam.'" target="_blank"> Interventions planifiées </a>';?></li>
        <li><?php echo '<a href="releveInfos.php?fkContact='.$id.'&searchedParam='.$searchedParam.'">Informations</a>'; ?></li>
    </ul>
</nav>

<h1>Intervenant(s) pour: <?php echo $contact['conNom']. ' '.$contact['conPrenom'].', '.$contact['conLocaliter']?></p></h1>

<form>
<table style="padding-bottom: 0px; margin-bottom: 15px;">
    <tr>
        <td><td>Intervenant</td>
        <td>
            <select id="interv">
                <option value="aloIntA">Habituel</option>
                <option value="aloIntB">Occasionnel</option>
                <option value="aloIntC">Dépannage</option>
                <option value="autres">Recherche</option>
            </select>
        </td>
        <td></td>
        <td style="padding-top: 16px;">

        </td>
    </tr>
</table>
<?php //$testData = $interTot->fetch(); ?>
</form>
<div id="detInterv"> </div><!-- tableau dans le fichier ajax.inter.php-->
<h1>Interventions ouvertes pour le bénéficiaire <?php echo $contact['conNom']. ' '.$contact['conPrenom']; ?> </h1>
<div id="intervention">
    <form method="post">
        <table>
            <tr>
                <th>Intervenant</th>
                <th>Date</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Type de service</th>
                <th>Type d'aide</th>
                <th>Commentaire</th>
                <th>Subv.</th>
                <td></td>
            </tr>
            <tr>
                <td><select name="intervenant" class="input150">
                        <option value="<?php echo $idInter1  ?>"><?php echo ($inter1['conNom'] ?? '') . ' ' . ($inter1['conPrenom'] ?? '') ?></option>
                        <option value="<?php echo $idInter2 ?>"><?php echo ($inter2['conNom'] ?? '') . ' ' . ($inter2['conPrenom'] ?? '') ?></option>
                        <option value="<?php echo $idInter3 ?>"><?php echo ($inter3['conNom'] ?? '') . ' ' . ($inter3['conPrenom'] ?? '') ?></option>
                        <option value="<?php echo $idInter4 ?>"><?php echo ($inter4['conNom'] ?? '') . ' ' . ($inter4['conPrenom'] ?? '') ?></option>
                        <?php while ($a = $interTot->fetch()) {
                            ?>
                                <option value="<?php echo $a['conId']; ?>"><?php echo $a['conNom'] . ' ' . $a['conPrenom'] ?> </option>
                            <?php
                        }
                        $interTot->closeCursor(); ?>
                    </select></td>
                <td><input  name="date" type="date" style="wight:100px" ></td>
                <td><input id="debut" name="debut" class="input1"></td>
                <td><input id="fin" name="fin" class="input1"></td>

                <td><select class="input150"
                            name="typeServices"><? ListeDeroulante($typeServices, 'tServicesId', 'tServicesNom') ?></select>
                </td>
                <td><select class="input90"
                            name="typeAide"><? ListeDeroulante($typeAide, 'genSerId', 'genSerNom') ?></select></td>
                <td><textarea name="commentaire" cols="25"></textarea></td>
                <td><input class="input0" type="checkbox" name="subventioner" value="1"></td>
                <td colspan="2"><input id="submit" type="submit" name="Valider" class="ValiderPetit" value="Valider"></td>
            </tr>
            <?php 
            while ($a = $historique->fetch()){?>
            <tr>
                <td><a href="<?php echo '../intervention/intervention.php?Id='.$a['conId']?>"><?php echo $a['conNom'].' '.$a['conPrenom'];?></a></td>
                <td><?php echo dateToUserJour($a['intDate']);?></td>
                <td><?php echo HeureHhMm($a['intDebut']);?></td>
                <td><?php echo HeureHhMm($a['intFin']);?></td>
                <td><?php echo $a['tServicesNom'];?></td>
                <td><?php echo $a['genSerNom'];?></td>
                <td><textarea cols="25"><?php echo $a['intCommentaire'];?></textarea></td>
                <td><?php  CheckBox($a['intSubventioner']);?></td>
                <td><?php 
                    echo '<a href="releveModif.php?Id='.$a['intId'].'&searchedParam='.$searchedParam.'"> Modifier </a>';?>
                </td>
                <td><?php echo '<a href="print.php?Id='.$a['intId'].'" target="_blank"> Imprimer </a>';?></td>
            </tr>
            <?php } ?>

        </table>
    </form>

<h1>Interventions effectuées pour le bénéficiaire <?php echo $contact['conNom']. ' '.$contact['conPrenom'];?> </h1>



    <form method="post">
        <table>
            <tr>
                <th>Intervenant</th>
                <th>Date</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Facturé</th>
                <th>Type de service</th>
                <th>Type d'aide</th>
                <th>Commentaire</th>
                <th>Subv.</th>
                <td></td>
            </tr>
<?php while ($b = $historiqueFac->fetch()){?>
            <tr>
                <td><a href="<?php echo '../intervention/intervention.php?Id='.$b['conId']?>"><?php echo $b['conNom'].' '.$b['conPrenom'];?></a></td>
                <td><?php echo dateToUserJour($b['intDate']);?></td>
                <td><?php echo HeureHhMm($b['intDebut']);?></td>
                <td><?php echo HeureHhMm($b['intFin']);?></td>
                <td><?php echo $b['intFacturable'];?></td>
                <td><?php echo $b['tServicesNom'];?></td>
                <td><?php echo $b['genSerNom'];?></td>
                <td><textarea cols="25"><?php echo $b['intCommentaire'];?></textarea></td>
                <td><?php  CheckBox($b['intSubventioner']);?></td>
                <td><?php echo '<a href="releveModif.php?Id='.$b['intId'].'&searchedParam='.$searchedParam.'"> Modifier </a>';?></td>
                <td><?php echo '<a href="print.php?Id='.$b['intId'].'" target="_blank"> Imprimer </a>';?></td>
            </tr>
            <?php } ?>

        </table>



</div>
<script src="../jquery-3.1.1.min.js"></script>

<script>

    $.get('ajax.inter.php', {
        Id: "<?php echo $id; ?>",
        searchedParam: "<?php echo $searchedParam; ?>",
        statu: $("#interv").val(),
    }, function (data) {
        $("#detInterv").html(data);
    });

    $("#interv").change(function () {
        $.get('ajax.inter.php', {
            Id: "<?php echo $id; ?>",
            statu: $("#interv").val(),
            searchedParam: "<?php echo $searchedParam; ?>"
        }, function (data) {
            $("#detInterv").html(data);
        });
    });
</script>
<?php include('../footer.php'); ?>
