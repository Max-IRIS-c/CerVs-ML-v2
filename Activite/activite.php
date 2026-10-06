<?php
$pageNum = 20;
include('../header.php');



//

if (isset($_GET['annee']))
{
    $annee = $_GET['annee'];
}
else
{
    $annee = date("Y");
}

$debut = $annee.'-01-01';
$fin = $annee.'-12-31';

$bdd = new PDO($dsn, $user, $password);

$Week = $bdd->query("SELECT * FROM tblActivites WHERE actStatu != 1 AND  actType = 1 and (actDebut between '$debut' AND '$fin') ORDER BY actDebut ");

$Terrifics = $bdd->query("SELECT * FROM tblActivites WHERE  actStatu != 1 AND actType = 2 and (actDebut between '$debut' AND '$fin') ORDER BY actDebut ");

$Camps = $bdd->query("SELECT * FROM tblActivites WHERE actStatu != 1 AND  actType = 3   and (actDebut between '$debut' AND '$fin')ORDER BY actDebut ");

$Autres = $bdd->query("SELECT * FROM tblActivites WHERE actStatu != 1 AND  actType = 4  and (actDebut between '$debut' AND '$fin') ORDER BY actDebut ");



if (($auth !=2)){
?>

<nav id="menu2">
    <ul>
        <li><a style="" href="ajouActivite.php"><?php echo $mrp->getText('Nouvelle activité') ?> </a></li>
        <li><a style="" href="activite.php?annee=<?=$annee-1?>"> <?php echo $mrp->getText('Activités') ?>  <?=$annee-1?> </a></li>
        <li><a style="" href="activite.php?annee=<?=$annee+1?>"> <?php echo $mrp->getText('Activités') ?>  <?=$annee+1?> </a></li>
    </ul>

</nav>
<?php } ?>

<h1><?php echo $mrp->getText('Listes des activités année') ?> <?= $annee ?> </h1>
<div>
    <table class="noMargin" style="margin-bottom: 65px;">
        <tr>
            <td style="width:25%; vertical-align: top;background-color: #ff979e;">
                <div id="Terrifics">
                    <h1> <?php echo $mrp->getText('Terrifics') ?> </h1>
                    <table>
                        <?php while ($terrifics = $Terrifics->fetch()) { ?>
                            <tr>
                                <td><?
                                    echo '<a href="participants.php?Id=' . $terrifics['actId'] . '">' . JourMoisAnnee($terrifics['actDebut']) . ' : ' . $terrifics['actNom'] . ' </a>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
            <td style="width:25%; vertical-align: top; background-color: #99ccff">
                <div id="WeekEnds">
                    <h1> <?php echo $mrp->getText('Week-ends') ?> </h1>
                    <table>
                        <?php while ($week = $Week->fetch()) { ?>
                            <tr>
                                <td>
                                    <? echo '<a href="participants.php?Id=' . $week['actId'] . '">' . CompilDate($week['actDebut'], $week['actFin']) . ' </a>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                </div>
            </td>
            <td style="width:25%; vertical-align: top; background-color: #4dff95;">
                <div id="Camps">
                    <h1> <?php echo $mrp->getText('Camps') ?> </h1>
                    <table>

                        <?php while ($camps = $Camps->fetch()) { ?>
                            <tr>
                                <td><?
                                    echo '<a href="participants.php?Id=' . $camps['actId'] . '">' . CompilDate($camps['actDebut'], $camps['actFin']). ' : ' . $camps['actNom'] . ' </a>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
            <td style="width:25%; vertical-align: top; background-color: #ff9138;">
                <div id="autres">
                    <h1><?php echo $mrp->getText('la parenthèse') ?> </h1>
                    <table>
                        <?php while ($autres = $Autres->fetch()) { ?>
                            <tr>
                                <td><?
                                    echo '<a href="participants.php?Id=' . $autres['actId'] . '">' . CompilDate($autres['actDebut'], $autres['actFin']) . '</a>'; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
        </tr>
    </table>


    <?php include('../footer.php'); ?>
