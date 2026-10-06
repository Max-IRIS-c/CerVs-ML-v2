<?php
	include('../src/class/contrats-gestion.php');
    $pageNum = 34;
    $start = $_GET['start'];
    $end = $_GET['end'];
    include ("../header.php");
    $bdd = new PDO($dsn, $user, $password);
    $id = $_GET['Id'];
	$errorPDFload = false;
	$sucessPDFload = false;
    $errorPDFDelete = false;
    $successPDFDelete = false;
    $mrp = new Mrp();

    $location = $bdd->query("SELECT * FROM tblLocation 
    LEFT JOIN tblContact on locConId = conId
    LEFT JOIN tblLogement on locPavId = logId WHERE locId = $id");
    $location = $location->fetch();

    $Client = $bdd->query("SELECT conNom, conId From tblContact Where conClientPavillons = 1 ORDER BY conNom");
    $Statu = $bdd->query("SELECT * FROM tblLocStatu");

    /*** enregistrement d'un contrat signé dans le dossier ./contractsFiles */
	if(isset($_POST['submit']) && isset($_FILES['newPDF'])){
		try{
			if(!$_POST['idContrat']) throw new Exception();	
			$record = Contract::recordNewPDFfile('liberty', $_POST['idContrat'], $_FILES['newPDF']);
			if(!$record) throw new Exception();
			else $sucessPDFload = true;
			//header('location: contrat.php?Id='.$id.'&searchedParam='.$searchedParam);
		}catch(Exception $e){
			$errorPDFload = true;
		}
	}
    /*** suppression d'un contrat signé dans le dossier ./contractsFiles */
    if(isset($_POST['deletePDF'])){
        try{   
			if(!$_POST['idContrat']) throw new Exception();	
            $delete = Contract::deletePDF('liberty', $_POST['idContrat']);
            if(!$delete) throw new Exception();
            $successPDFDelete = true;
        }catch(Exception $e){
            $errorPDFDelete = true;
        }
    }
?>
<nav>
    <ul>
        <li>Retour période séléctionnée : </li>
        <li><a href="planning.php?start=<?php echo $start; ?>&end=<?php echo $end; ?>">Globale</a></li>
        <li><a href="planningBus.php?start=<?php echo $start; ?>&end=<?php echo $end; ?>">Bus </a> </li>
        <li><a href="planningLogement.php?start=<?php echo $start; ?>&end=<?php echo $end; ?>">Logement </a> </li>
        <li><a href="planningParenthese.php?start=<?php echo $start; ?>&end=<?php echo $end; ?>">Parenthèse </a> </li>
        <li><a href="modifLocation.php?Id=<?php echo $id?>&start=<?php echo $start; ?>&end=<?php echo $end; ?>""> Modifier la réservation </a> </li>
        <!-- liens contrat bus -->
        <?php if($location['logtype'] === '2') { ?>
            <li><a href="printLocation.php?Id=<?=$id?>" target="_blank"> Contrat </a> </li>
        <?php } ?>
        <!-- liens contrat logement -->
        <?php if($location['logtype'] === '1') { ?>
            <li><a href="printLocation_logement.php?Id=<?=$id?>" target="_blank"> Contrat </a> </li>
        <?php } ?>
    </ul>
</nav>
<?php if($errorPDFload) { ?>
		<p>
			⚠️ <?= $mrp->getText("Le fichier n a pas pu être enregistré") ?> : </br>
			1) Vérifiez que vous avez bien séléctionné un fichier pdf valide. </br>
			2) Si oui, cliquez sur une autre page et revenez ici afin de réessayer. (ne pas cliquer sur les flèches du naviguateur)</br>
			3) Si le problème persiste, contactez le développeur.
		</p>
<?php 
    }
     if($sucessPDFload) { ?>
		<p class="success"> ✅ <?= $mrp->getText("Le fichier a bien été enregistré") ?></p>
<?php 
    }
    if($errorPDFDelete){ ?>
        <p>⚠️ <?= $mrp->getText("Il y a eu un soucis dans la suppression du pdf") ?> </p>
   <?php    }
    if($successPDFDelete){ ?>
        <p> ✅ <?= $mrp->getText("Le fichier a bien été supprimé") ?> </p>
   <?php } ?>

<h1><?= $mrp->getText("Détail de la location pour le pavillon") ?> <?echo $location['logNom']?></h1>
<table>
    <tr>
        <td><?= $mrp->getText("Client") ?></td>
        <td colspan="5"><select disabled name="Client">

                <?php ListeModif($Client,$location['locConId'],'conId','conNom')?>
            </select></td>
    </tr>
    <tr>
        <td colspan="6"><?= $mrp->getText("Personne reponsable administratif") ?></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Nom et prénom") ?></td>
        <td><input disabled name="responsableNomAdmin" class="input100" value="<?php echo $location['locRespNomPrenom']?>"></td>
        <td><?= $mrp->getText("Téléphone") ?></td>
        <td><input disabled name="responsableTelAdmin" class="input100" value="<?php echo $location['locRespTel']?>"></td>
        <td><?= $mrp->getText("E-Mail") ?></td>
        <td><input disabled name="responsableMailAdmin" class="input100" value="<?php echo $location['locRespMail']?>"></td>
    </tr>
    <tr>
        <td colspan="6"><?= $mrp->getText("Responsable du groupe") ?></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Nom et prénom") ?></td>
        <td><input disabled name="responsableNom" class="input100" value="<?php echo $location['locCoRespNomPrenom']?>"></td>
        <td><?= $mrp->getText("Téléphone") ?></td>
        <td><input disabled name="responsableTel" class="input100" value="<?php echo $location['locCoRespTel']?>"></td>
        <td><?= $mrp->getText("E-Mail") ?></td>
        <td><input disabled name="responsableMail" class="input100" value="<?php echo $location['locCoRespMail']?>"></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Nombre de personnes") ?></td>
        <td><input disabled  name="nbr" class="input1" value="<?php echo $location['locNbrPers']?>"></td>
        <td><?= $mrp->getText("dont en situation de handicap") ?></td>
        <td><input disabled  name="nbrAcc" class="input1" value="<?php echo $location['locNbrPersAcc']?>"></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Date du séjour") ?></td>
        <td><?= $mrp->getText("Du") ?> <input disabled  type="date" name="debut" value="<?php echo $location['locDateEnt']?>"></td>
        <td><?= $mrp->getText("au") ?> <input disabled type="date" name="fin" value="<?php echo $location['locDateDep']?>"></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Heure d'arrivée") ?></td>
        <td><input disabled  type="time" name="arrivee" value="<?php echo $location['locArrivee']?>"></td>
        <td><?= $mrp->getText("Heure de départ") ?> <input disabled type="time" name="depart" value="<?php echo $location['locDepart']?>"></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Remarques") ?></td>
        <td colspan="5"><textarea disabled name="remarque" cols="110" rows="5"><?php echo ($location['locRemarque'])?></textarea></td>
    </tr>
    <tr>
        <td><?= $mrp->getText("Statuts") ?></td>
        <td><select disabled name="Statu">

                <?php ListeModif($Statu,$location['locStatu'],'lStatId','lStatNom')?>
            </select></td>
    </tr>
    <tr>
        <th><?= $mrp->getText("Contrat signé") ?></th>
        <?php 
            $contractPDF = Contract::signedContrat('liberty', intval($location['locId']));
            if(!$contractPDF['exist']) { ?>
                <td>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="idContrat" value="<?php echo $location['locId']; ?>" />
                        <input id="newPDF" name="newPDF" type="file" accept=".pdf" />
                        <button class="sub" type="submit" name="submit" value="submit"><?= $mrp->getText("Enregistrer") ?></button>
                    </form>
                </td>
                <?php }elseif($contractPDF['exist']) { ?> 
                    <td class="signed">
                        <a class="button all" onclick="window.open('<?php echo './location_contracts/'.$contractPDF['url']; ?>', '_blank')">PDF</a>
                        <form method="post">
                            <input type="hidden" name="idContrat" value="<?php echo $location['locId']; ?>"/>
                            <button class="delete" name="deletePDF" value="1"><?= $mrp->getText("Supprimer le PDF") ?></button>
                        </form>
                    </td>
            <?php } 
        ?>
    </tr>
</table>
<style>
    .delete{
        background-color: #e53935;
        color: white;
        border: none;
        padding: 7px;
    }
    .delete:hover{
        cursor: pointer;
    }
</style>

<?php include ("../footer.php");?>