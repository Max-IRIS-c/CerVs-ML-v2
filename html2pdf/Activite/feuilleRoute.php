<?php
$pageNum = 24;
include('../header.php');

setlocale (LC_TIME, 'fr_FR.utf8','fra');
$bdd = new PDO($dsn, $user, $password);
$actId = $_GET[Id];
$id = $actId;

$dateAct = $bdd->query("SELECT actId,actDebut,actFin FROM tblActivites WHERE actId = $actId");
$dateAct = $dateAct->fetch();

$aller1 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 1 and feuAller = 0  ");
$aller2 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 2 and feuAller = 0  ");
$aller3 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 3 and feuAller = 0 ");
$aller4 = $bdd->query("SELECT * FROM tblAloFeuilleRout where  actId = $id and busId = 4 and feuAller = 0 ");

$retour1 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 1 and feuAller = 1   ");
$retour2 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 2 and feuAller = 1 ");
$retour3 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 3 and feuAller = 1 ");
$retour4 = $bdd->query("SELECT * FROM tblAloFeuilleRout where actId = $id and busId = 4 and feuAller = 1 ");

$Chauffeuraller1 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 1 and feuAller = 0  ");
$Chauffeuraller1 = $Chauffeuraller1->fetch();
$Chauffeuraller2 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 2 and feuAller = 0  ");
$Chauffeuraller2 = $Chauffeuraller2->fetch();
$Chauffeuraller3 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 3 and feuAller = 0 ");
$Chauffeuraller3 = $Chauffeuraller3->fetch();
$Chauffeuraller4 = $bdd->query("SELECT * FROM tblchauffeur where  actId = $id and busId = 4 and feuAller = 0 ");
$Chauffeuraller4 = $Chauffeuraller4->fetch();


$Chauffeurretou1 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 1 and feuAller = 1   ");
$Chauffeurretou1 = $Chauffeurretou1->fetch();
$Chauffeurretou2 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 2 and feuAller = 1 ");
$Chauffeurretou2 = $Chauffeurretou2->fetch();
$Chauffeurretou3 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 3 and feuAller = 1 ");
$Chauffeurretou3 = $Chauffeurretou3->fetch();
$Chauffeurretou4 = $bdd->query("SELECT * FROM tblchauffeur where actId = $id and busId = 4 and feuAller = 1 ");
$Chauffeurretou4 = $Chauffeurretou4->fetch();

$Date1 = strtotime($dateAct['actDebut']);
$Date2 = strtotime($dateAct['actFin']);
?>


<nav>
    <ul>
        <li><?php echo'<a href="participants.php?Id='.$actId.'" > Retour </a>' ?></li>
        <li><?php echo'<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=0 " > Modifier l\'aller </a>' ?></li>
        <li><?php echo'<a href="feuilleRouteEdition.php?Id='.$actId.'&trajet=1 " > Modifier le retour </a>' ?></li>


    </ul>
</nav>

<h1>Aller - <?php echo strftime("%d %B %G",$Date1 )?></h1>
    <h2><?=$bus[$Chauffeuraller1['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

        <tr>
            <td><strong>Chauffeur</strong></td>
        </tr>
        <tr>
            <td><?php echo $Chauffeuraller1['chauPrinc'] ?></td>
            <td><?php echo $Chauffeuraller1['chauAide'] ?></td>
            <td></td>

        </tr>

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depA =  $aller1->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depA['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depA['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depA['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeuraller2['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depB =  $aller2->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depB['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeuraller3['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeuraller3['chauPrinc'] ?></td>
                <td><?php echo $Chauffeuraller3['chauAide'] ?></td>

            </tr>

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>

        </tr>
        <?php while ($depC =  $aller3->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depC['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeuraller4['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($depD =  $aller4->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $depD['feuAcc']?></td>


            </tr>
        <? }?>

    </table>


<h1>Retour - <?php echo strftime("%d %B %G",$Date2 )?></h1>
    <h2><?=$bus[$Chauffeurretou1['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeurretou1['chauPrinc'] ?></td>
                <td><?php echo $Chauffeurretou1['chauAide'] ?></td>
            </tr>

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($retA =  $retour1->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $retA['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retA['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retA['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeurretou2['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeurretou2['chauPrinc'] ?></td>
                <td><?php echo $Chauffeurretou2['chauAide'] ?></td>

            </tr>

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($retB =  $retour2->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $retB['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retB['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retB['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeurretou3['busIdNom']] ?></h2>
    <table style="margin-bottom: 0; padding-bottom: 0">

            <tr>
                <td>Chauffeur</td>
                <td><?php echo $Chauffeurretou3['chauPrinc'] ?></td>
                <td><?php echo $Chauffeurretou3['chauAide'] ?></td>

            </tr>

        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($retC =  $retour3->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $retC['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retC['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retC['feuAcc']?></td>


            </tr>
        <? }?>

    </table>
    <h2><?=$bus[$Chauffeurretou4['busIdNom']] ?></h2>
    <table>
        <tr>
            <td>Chauffeur</td>
            <td><?php echo $Chauffeurretou4['chauPrinc'] ?></td>
            <td><?php echo $Chauffeurretou4['chauAide'] ?></td>

        </tr>



        <tr>
            <td><strong>Départ</strong></td>
            <td><strong>Participants</strong></td>
            <td><strong>Accompagnants</strong></td>
        </tr>
        <?php while ($retD =  $retour4->fetch()){ ?>

            <tr>
                <td style="border-bottom:solid 1px "><?php echo $retD['feuLieu']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retD['feuPart']?></td>
                <td style="border-bottom:solid 1px "><?php echo $retD['feuAcc']?></td>


            </tr>
        <? }?>

    </table>


<?php include('../footer.php');?>