<?php
$pageNum = 32;

include('../variables.php');
include('../heade.php');
include('../fonctionReservation.php');

setlocale(LC_TIME, 'fra_fra');

if (empty($_POST)) {
    $start = date('Y-m-d');
    $end = date('Y-m-d', strtotime($start . "+10 weeks"));
} else {
    $start = $_POST['debut'];
    $end = $_POST['fin'];
}

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
$bdd = new PDO($dsn, $user, $password);

$debut = new DateTime($start);
$fin = new DateTime($end);
$debutSting = strtotime($start);
$finSting = strtotime($end);

// définition manuelle des colonnes pour gérer le cas spécial Source & Oasis
$colonnes = [
    ['type' => 'bdd', 'id' => 1, 'nom' => 'UAT'],            // exemple
    ['type' => 'virtuel', 'nom' => 'Source & Oasis'],        // colonne calculée
    ['type' => 'bdd', 'id' => 2, 'nom' => 'Source'],         // logId exact à confirmer
    ['type' => 'bdd', 'id' => 3, 'nom' => 'Oasis'],          // logId exact à confirmer
    ['type' => 'bdd', 'id' => 4, 'nom' => 'Parenthèse'],     // logId exact à confirmer
    // tu peux continuer avec les autres logements
];
?>

<nav id="menu2">
    <ul>
        <li>
            <a href="Location.php">
                <?php echo ucfirst($mrp->getText('Liste des locations')); ?>
            </a>
        </li>
        <li class="textGauche">
            <a href="nouvelleLocation.php?Id=">
                <?php echo ucfirst($mrp->getText('Nouvelle location')) ?>
            </a>
        </li>
        <li>
            <a href="liberty.php">
                <?php echo ucfirst($mrp->getText('Objets en location')) ?>
            </a>
        </li>
    </ul>
</nav>

<h1 id="planOccupationTest"> <?php echo $mrp->getText('Plan d\'occupation') ?> </h1>
<div id="boutonBusLogement">
    <a class="button button1" href="planningBus.php">
        <?php echo ucfirst($mrp->getText('Planning des bus')) ?>
    </a>
    <a class="button button2" href="planningLogement.php">
        <?php echo ucfirst($mrp->getText('Planning des logements')) ?>
    </a>
</div>

<style>
    th {
        width: 10%;
    }
    td {
        height: 3em;
        text-align: center;
    }
    .reservation {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
    #debute {
        margin-right: 5em;
    }
    #boutonBusLogement {
        display: flex;
        flex-direction: row;
        justify-content: center;
        font-weight: bold;
    }
</style>

<div class="containerOrigine-test">
    <form method="POST">
        <fieldset>
            <legend><?php echo $mrp->getText('Choix de la période') ?></legend>

            <label for="debute"><?php echo $mrp->getText('Période de début :') ?></label>
            <input type="date" name="debut" id="debute">

            <label for="fin"><?php echo $mrp->getText('Période de fin :') ?></label>
            <input type="date" name="fin" id="fin">

            <input type="submit" name="submit" class="Valider" value="Valider">
        </fieldset>
    </form>

    <table class="reservation" style='height: 3em; width: 100%'>
        <tr style='width: auto;'>
            <th id="reservation-th-date"><?php echo $mrp->getText('Date') ?></th>
            <?php foreach ($colonnes as $col): ?>
                <th style="width: 50px;"><?php echo $col['nom']; ?></th>
            <?php endforeach; ?>
        </tr>

        <?php
        // boucle jour par jour
        for ($i = clone $debut; $i <= $fin; $i->modify('+1 day')) {
            $timestamp = $i->getTimestamp();
            $jour = (int)$i->format('N');       // 1=lundi ... 7=dimanche
            $dateJour = (int)$i->format('d');
            $semaine = $i->format('W');
            $mois = strftime('%B', $timestamp);
            $colspan = 1 + count($colonnes);

            if ($jour == 1) {
                echo "<tr>
                        <td style='background-color: #a6c9ff; height: 2px'>" . $mrp->getText("semaine") . " $semaine</td>";
                foreach ($colonnes as $col) {
                    echo "<td style='background-color: #a6c9ff; height: 2px;'>" . $col['nom'] . "</td>";
                }
                echo "</tr>";
            }

            if ($dateJour == 1) {
                echo "<tr><th colspan=".$colspan.">$mois</th></tr>";
            }

            echo "<tr>";
            echo "<td style='text-align: left'>" . strftime(" %a %d.%m.%G", $timestamp) . "</td>";

            $skipNext = 0;
            foreach ($colonnes as $col) {
                if ($skipNext > 0) {
                    $skipNext--;
                    continue;
                }

                if ($col['type'] === 'virtuel') {
                    // Vérifier si Source & Oasis sont réservés
                    $logSource = logement($bdd, $mrp, $timestamp, 2);
                    $logOasis  = logement($bdd, $mrp, $timestamp, 3);

                    if ($logSource[2] == 1 || $logOasis[2] == 1) {
                        echo '<td style="background-color:red; font-weight:bold; color:white;">Non disponible</td>';
                        // Bloquer les 2 colonnes suivantes (Source + Oasis)
                        $skipNext = 2;
                    } else {
                        echo '<td style="background-color:#B0E0E6;">Libre</td>';
                    }
                } else {
                    $logement = logement($bdd, $mrp, $timestamp, $col['id']);
                    if (!is_array($logement) || count($logement) < 4) {
                        $logement = ['', '', 0, '#FFFFFF'];
                    }
                    echo '<td style="background-color:' . $logement[3] .
                        '; font-weight:' . $logement[1] . '">' . $logement[0] . '</td>';
                }
            }

            echo "</tr>";
        }
        ?>
    </table>
</div>
