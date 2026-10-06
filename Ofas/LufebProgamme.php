<?php
include '../src/header.inc.php';
$nomDecompte = 'Programme de travail PROSPHREH';


$req= $db->query("SELECT sum(traHeureTot)as total, cat1Code,cat1Nom,cat4Nom FROM tblTravail
LEFT JOIN tblTraCat1 on traCat1 = cat1Id
LEFT JOIN tblTraCat4 on traCat4 = cat4Id
where traCat1 in (25,26,27,28,29,30)
GROUP BY traCat1, traCat4");

include 'menu.php'; ?>
    <h1><?= $nomDecompte?></h1>


<table class="affichage">
    <? $ofas = '';
    $total = 0;
    foreach ($req as $res){

        if ($ofas != $res['cat1Nom'])
        {
            if ($total !=0){
                echo '<tr><th style="text-align: right">'.$mrp->getText('Total').'</th><td><strong>'.$total.'</strong></td></tr>';
                $total = 0;
            }

            echo '<tr><th>'.$mrp->getText($res['cat1Nom']).'</th><th>'.$mrp->getText('Heures imputées').'</th></tr>';
            $ofas = $res['cat1Nom'];
        }


        echo '<tr><td>'.$mrp->getText($res['cat4Nom']).'</td><td>'.$res['total'].'</td></tr>';

        $total+=$res['total'];
    }
    echo '<tr><th style="text-align: right">'.$mrp->getText('Total').'</th><td><strong>'.$total.'</strong></td></tr>';
    ?>

</table>


<?php
include_once '../src/footer.inc.php';