<?php
include ("../header.php");
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';
$location = $bdd->query("SELECT * FROM tblLocation 
LEFT JOIN tblContact on locConId = conId
LEFT JOIN tblLogement on locPavId = logId WHERE locId = $id");
$location = $location->fetch();
$LstLogement = $bdd->query("SELECT * FROM tblLogement");

$Client = $bdd->query("SELECT conNom, conId From tblContact Where conClientPavillons = 1 ORDER BY conNom");
$Statu = $bdd->query("SELECT * FROM tblLocStatu");
if (isset ($_POST['Annuler'])){
    header("location:DetLocation.php?Id=".$id."&start=".$_POST['start']."&end=".$_POST['end']);
}

if (isset($_POST['Valider'])){


    $insert = $bdd->prepare("UPDATE tblLocation SET
locConId =:locConId,
locRespNomPrenom =:locRespNomPrenom,
locRespTel =:locRespTel,
locRespMail =:locRespMail,
locCoRespNomPrenom =:locCoRespNomPrenom,
locCoRespTel =:locCoRespTel,
locCoRespMail =:locCoRespMail,
locNbrPers =:locNbrPers,
locNbrPersAcc =:locNbrPersAcc,
locDateEnt =:locDateEnt,
locDateDep =:locDateDep,
locRemarque =:locRemarque,
locArrivee =:locArrivee,
locDepart =:locDepart,
locStatu =:locStatu,
locPavId = :locPavillon
where locId = '$id'");




    $insert->execute(array(
        'locConId' => $_POST['Client'],
        'locRespNomPrenom' => $_POST['responsableNomAdmin'],
        'locRespTel' => $_POST['responsableTelAdmin'],
        'locRespMail' => $_POST['responsableMailAdmin'],
        'locCoRespNomPrenom' => $_POST['responsableNom'],
        'locCoRespTel' => $_POST['responsableTel'],
        'locCoRespMail' => $_POST['responsableMail'],
        'locNbrPers' => $_POST['nbr'],
        'locNbrPersAcc' => $_POST['nbrAcc'],
        'locDateEnt' => $_POST['debut'],
        'locDateDep' => $_POST['fin'],
        'locRemarque' => $_POST['remarque'],
        'locStatu' => $_POST['Statu'],
        'locArrivee' => $_POST['arrivee'],
        'locDepart' => $_POST['depart'],
        'locPavillon'=>$_POST['Logement']
    ));

    header("location:DetLocation.php?Id=".$id."&start=".$_POST['start']."&end=".$_POST['end']);
    }

?>
<nav>
<ul>
        <li><a href='supLocation.php?Id=<? echo $id?>'><?php echo $mrp->getText("Supprimer") ?></a></li>
    </ul>
</nav>
    <h1> <?php echo $mrp->getText("Modification") ?> -  <?echo $location['logNom']?></h1>
    <form method="post">
        <input type="hidden" name="start" value="<?php echo $start; ?>" />
        <input type="hidden" name="end" value="<?php echo $end; ?>" />
        <table>
        <tr>
                <td><?php echo $mrp->getText("Logement") ?> </td>
                <td colspan="5"><select name="Logement">

                        <?php ListeModif($LstLogement,$location['locPavId'],'logId','logNom')?>
                    </select></td>
            </tr>

            <tr>
                <td><?php echo $mrp->getText("Client") ?></td>
                <td colspan="5"><select name="Client">

                        <?php ListeModif($Client,$location['locConId'],'conId','conNom')?>
                    </select></td>
            </tr>
            <tr>
                <td colspan="6"><?php echo $mrp->getText("Responsable administratif") ?></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Nom") ?>  <?php echo $mrp->getText("et") ?> <?php echo $mrp->getText("prénom") ?></td>
                <td><input name="responsableNomAdmin" class="input150" value="<?php echo $location['locRespNomPrenom']?>"></td>
                <td><?php echo $mrp->getText("Téléphone"); ?></td>
                <td><input name="responsableTelAdmin" class="input100" value="<?php echo $location['locRespTel']?>"></td>
                <td><?php echo $mrp->getText("E-Mail") ?></td>
                <td><input name="responsableMailAdmin" class="input100" value="<?php echo $location['locRespMail']?>"></td>
            </tr>
            <tr>
                <td colspan="6"><?php echo $mrp->getText("Responsable du groupe") ?></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Nom") ?>  <?php echo $mrp->getText("et") ?> <?php echo $mrp->getText("prénom") ?></td>
                <td><input name="responsableNom" class="input150" value="<?php echo $location['locCoRespNomPrenom']?>"></td>
                <td><?php echo $mrp->getText("Téléphone") ?></td>
                <td><input name="responsableTel" class="input100" value="<?php echo $location['locCoRespTel']?>"></td>
                <td><?php echo $mrp->getText("E-Mail") ?></td>
                <td><input name="responsableMail" class="input100" value="<?php echo $location['locCoRespMail']?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Nombre de personnes") ?></td>
                <td><input name="nbr" class="input1" value="<?php echo $location['locNbrPers']?>"></td>
                <td><?php echo $mrp->getText("dont en situation de handicap") ?></td>
                <td><input name="nbrAcc" class="input1" value="<?php echo $location['locNbrPersAcc']?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Dates du séjour") ?></td>
                <td><?php echo $mrp->getText("du") ?> <input type="date" name="debut" value="<?php echo $location['locDateEnt']?>"></td> <td><?php echo $mrp->getText("au") ?> <input type="date" name="fin" value="<?php echo $location['locDateDep']?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Heure d'arrivée") ?></td>
                <td><input type="time" name="arrivee" value="<?php echo $location['locArrivee']?>"></td>
                <td><?php echo $mrp->getText("Heure de départ") ?><input type="time" name="depart" value="<?php echo $location['locDepart']?>"></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Remarque(s)") ?></td>
                <td colspan="5"><textarea name="remarque" cols="110" rows="5"><?php echo ($location['locRemarque'])?></textarea></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Statut") ?></td>
                <td><select name="Statu">

                        <?php ListeModif($Statu,$location['locStatu'],'lStatId','lStatNom')?>
                    </select></td>
            </tr>
            <tr>
                <td> <input type="submit" value="Valider" name="Valider" class="valider"></td>
                <td> <input type="submit" value="Annuler" name="Annuler" class="Annuler"></td>
            </tr>




        </table>






    </form>


<?php include ("../footer.php");?>