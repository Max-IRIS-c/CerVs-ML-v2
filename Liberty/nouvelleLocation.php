<?php

$pageNum = 34;

include ("../header.php");
include_once("../src/fonctionsSql.php");

$bdd = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // ⚡ Exceptions activées
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);
$id = $_GET['Id'] ?? '';

$pavillon = $bdd->query("SELECT * FROM tblLogement WHERE logId = '$id'");
$pavillon = $pavillon->fetch();
$LstPavillon = $bdd->query("SELECT logNom,logId FROM tblLogement");
$Client = $bdd->query("SELECT conNom, conId From tblContact Where conClientPavillons = 1 AND conStatu = 1 ORDER BY conNom");
$Statu = $bdd->query("SELECT * FROM tblLocStatu");
if (isset ($_POST['Annuler'])){
    if (empty($id)){
        header("location:Location.php");
    }
    else{
    header("location:Location.php?Id=".$id);
    }
}

if (isset($_POST['Valider'])){
    try{
        if (empty($id)){
            $id = $_POST['logId'];}
            $insert = $bdd->prepare('INSERT INTO tblLocation
                (locConId,locRespNomPrenom,locRespTel,locRespMail,locCoRespNomPrenom,locCoRespTel,locCoRespMail,locPavId,
                locNbrPers,locNbrPersAcc,locDateEnt,locDateDep,locRemarque,locStatu,locArrivee,locDepart)
                VALUES 
                (:locConId,:locRespNomPrenom,:locRespTel,:locRespMail,:locCoRespNomPrenom,:locCoRespTel,:locCoRespMail,:locPavId,
                :locNbrPers,:locNbrPersAcc,:locDateEnt,:locDateDep,:locRemarque,:locStatu,:locArrivee,:locDepart)');


            $insert->execute(array(
                'locConId' => intOuNull($_POST['Client']),
                'locRespNomPrenom' => $_POST['responsableNomAdmin'],
                'locRespTel' => $_POST['responsableTelAdmin'],
                'locRespMail' => $_POST['responsableMailAdmin'],
                'locCoRespNomPrenom' => $_POST['responsableNom'],
                'locCoRespTel' => $_POST['responsableTel'],
                'locCoRespMail' => $_POST['responsableMail'],
                'locPavId' => intOuNull($id),
                'locNbrPers' => intOuNull($_POST['nbr']),
                'locNbrPersAcc' => intOuNull($_POST['nbrAcc']),
                'locDateEnt' => dateOuNull($_POST['debut']),
                'locDateDep' => dateOuNull($_POST['fin']),
                'locRemarque' => $_POST['remarque'],
                'locArrivee' => dateOuNull($_POST['arrivee']),
                'locDepart' => dateOuNull($_POST['depart']),
                'locStatu' => intOuNull($_POST['Statu']) ?? 1
            ));
            header("location:Location.php");
    }catch(Exception $e){
        echo $e->getMessage();
    }
}

?>
    <h1><?= $mrp->getText("Nouvelle location") ?><?php echo $pavillon['logNom'] ?? '' ?></h1>
    <form method="post">
        <table>
            <tr>
                <td><?= $mrp->getText("Date du séjour") ?></td>
                <td><?= $mrp->getText("Du") ?> <input type="date" name="debut" id="DateDebut"></td> <td><?= $mrp->getText("au") ?> <input type="date" name="fin" id="DateFin"></td>
            </tr>

            <? if (empty($id)){?>
                <tr>
                    <td><?= $mrp->getText("Logement") ?> </td>
                    <td colspan="5"><div id="pavillon" style="padding-left:0 "></div></td>
                </tr>
            <?}?>

            <tr>
                <td><?= $mrp->getText("Client") ?></td>
                <td colspan="5"><select name="Client">
                        <option value="">-></option>
                        <?php ListeDeroulante($Client,'conId','conNom')?>
                    </select></td>
            </tr>
            <tr>
                <td colspan="6"><?= $mrp->getText("Personne reponsable administratif") ?></td>
            </tr>
            <tr>
                <td><?= $mrp->getText("Nom et prénom") ?></td>
                <td><input name="responsableNomAdmin" class="input100"></td>
                <td><?= $mrp->getText("Téléphone") ?></td>
                <td><input name="responsableTelAdmin" class="input100"></td>
                <td><?= $mrp->getText("E-Mail") ?></td>
                <td><input name="responsableMailAdmin" class="input100"></td>
            </tr>
            <tr>
                <td colspan="6"><?= $mrp->getText("Responsable du groupe") ?></td>
            </tr>
            <tr>
                <td><?= $mrp->getText("Nom et prénom") ?></td>
                <td><input name="responsableNom" class="input100"></td>
                <td><?= $mrp->getText("Téléphone") ?></td>
                <td><input name="responsableTel" class="input100"></td>
                <td><?= $mrp->getText("E-Mail") ?></td>
                <td><input name="responsableMail" class="input100"></td>
            </tr>
            <tr>
                <td><?= $mrp->getText("Nombre de personnes") ?></td>
                <td><input name="nbr" class="input1"></td>
                <td><?= $mrp->getText("dont en situation de handicap") ?></td>
                <td><input name="nbrAcc" class="input1"></td>
            </tr>

            <tr>
                <td><?= $mrp->getText("Heure d'arrivée") ?></td>
                <td><input type="time" name="arrivee" value=""></td>
                <td><?= $mrp->getText("Heure de départ") ?> <input type="time" name="depart" value=""></td>
            </tr>
            <tr>
                <td><?= $mrp->getText("Remarque") ?></td>
                <td colspan="5"><textarea name="remarque" cols="110" rows="5"></textarea></td>
            </tr>
            <tr>
                <td><?= $mrp->getText("Statu") ?></td>
                <td><select name="Statu">

                        <?php /** @var  $Statu */
                        ListeDeroulante($Statu,'lStatId','lStatNom')?>
                    </select></td>
            </tr>
            <tr>
                <td> <input type="submit" value="Valider" name="Valider" class="valider"></td>
                <td> <input type="submit" value="Annuler" name="Annuler" class="Annuler"></td>
            </tr>
        </table>
    </form>


<?php include ("../footer.php");?>

<script src="../jquery-3.1.1.min.js"></script>
<script>
    $.get("ajax.pavillon.php", {
        debut: $("#DateDebut").val(), 
        fin: $("#DateFin").val()},
        function (data) {
            $("#pavillon").html(data);
        }
    );

    $("#DateFin").change(function () {
        $.get("ajax.pavillon.php", {
            debut: $("#DateDebut").val(), 
            fin: $("#DateFin").val()}, 
            function (data) {
                $("#pavillon").html(data);
            }
        );
    });

    $("#DateDebut").change(function () {
        $.get("ajax.pavillon.php", {debut: $("#DateDebut").val(), fin: $("#DateFin").val()}, function (data) {
            $("#pavillon").html(data);
        });
    });
</script>
