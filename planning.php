<?php
$pageNum = 32;
include('../variables.php');
include('../heade.php');
include('../fonctionReservation.php');
setlocale(LC_TIME, 'fra_fra');

if (empty($_POST)){
    $start = date('Y-m-d');
    $end = date('Y-m-d', strtotime($start . "+10 weeks"));
}
else
{
    $start = $_POST['debut'];
    $end = $_POST['fin'];
}


setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
$bdd = new PDO($dsn, $user, $password);
$debut = new DateTime($start);
$debutSting = strtotime($start);
$finSting = strtotime($end);
$fin = new DateTime($end);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($debut, $interval, $fin);
$pavillon = $bdd->query("SELECT logId,logNom FROM tblLogement");
$req = $bdd->query("SELECT datediff(locDateDep,locDateEnt), locStatu, locPavId, locDateEnt FROM tblLocation");


?>
<nav>
    <ul>
        <li><a href="Location.php"> Liste des locations</a></li>
        <li class="textGauche"><?php echo '<a href="nouvelleLocation.php?Id=">Nouvelle Location </a>'; ?></li>
        
        <li><a href="liberty.php"> Objets en location </a></li>
    </ul>
</nav>

        <h1> Plan d'occupation </h1>

<table class="noMargin" style="margin-bottom: -10px;">
    <form method="POST">


        <td style="vertical-align: middle"> Période du</td>
        <td style="vertical-align: middle"><input type="date" name="debut" id="debute"></td>
        <td style="vertical-align: middle"> au</td>
        <td style="vertical-align: middle"><input type="date" name="fin" id="fin"></td>

        <td style="vertical-align: middle"><input type="submit" name="submit" value="Valider" class="Valider"</td>
    </form>
</table>


<table class="reservation">

    <tr>
        <th style="width: 110px;">Date</th>
        <?php $nbrLog = 1; // initialise le nombre de logement
        while ($log = $pavillon->fetch()) { ?>
            <th style="width: 50px;"><?php echo $log['logNom'] ?></th>
            <? $nbrLog++;
        } ?>
    </tr>
    <?
    $date = $debutSting;
    for ($i = $debut; $i <= $fin; $i->modify('+1 day')) {
        $jour = date('N', $date); // indique le jour ( 1 = Dimanche )
        $dateJour = date('d', $date);
        $semaine = date('W', $date);
        $mois = strftime('%B', $date);
        $colspan = $nbrLog;

        if ($jour == 1) {
            echo "<tr> <td style='background-color: #a6c9ff; height: 2px'> semaine $semaine</td>
<td style='background-color: #a6c9ff; height: 2px'>Source & Oasis</td>
<td style='background-color: #a6c9ff; height: 2px'>Source</td>
<td style='background-color: #a6c9ff; height: 2px'>Oasis</td>
<td style='background-color: #a6c9ff; height: 2px'>Pacifique II</td>
<td style='background-color: #a6c9ff; height: 2px'>Liberty II</td>
<td style='background-color: #a6c9ff; height: 2px'>Désiré</td>
<td style='background-color: #a6c9ff; height: 2px'>Destiny</td>


</tr>";
        }

        if ($dateJour == 1) {
            echo "<tr> <th colspan='$colspan' > $mois</th></tr>";
        }


        ?>
        <tr>
            <td style="text-align: right"><? echo(strftime(" %a %d.%m.%G", $date)) ?></td>
            <? for ($l = 1; $l < $nbrLog; $l++) {

                $logement = logement($date, $l);// apelle de fonction qui ce trouve dans fonctionReservation.php
                echo '<td style="background-color:'.$logement[3].'; font-weight: '.$logement[1].'">'.$logement[0].'</td>';
                if (($l == 1) AND ($logement[2]== 1)){ // si le logement "source&oasis" est reserver ou en provisoir le 2 prochaine case
                    // son afficher en non disponible  et la variable $l prend la valeur de 3

                    echo '<td style="background-color:'.$logement[3].';font-weight: '.$logement[1].'">Non disponible</td>
                          <td style="background-color:'.$logement[3].'; font-weight: '.$logement[1].'">Non disponible</td>';
                    $l = 3;

                }



            } ?>
        </tr>
        <?
        $date = $date + 86400;
    } ?>


</table>