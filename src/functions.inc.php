<?php include_once __DIR__ . '/../src/dateFr.php'; ?><?php

// Démarrage ou restauration de la session
session_start();

global $formulaireNew;

$nom = $_SESSION['Prenom'] . " " . $_SESSION['Nom'];
$idUtilisateur = $_SESSION['id'];
//$auth = $_SESSION['auth'];

$auth = $_SESSION['auth'];
if (isset($_SESSION['travail'])) { $travail = $_SESSION['travail'];}
if (isset($_SESSION['plannification'])) {$plannification = $_SESSION['plannification'];}
if (isset($_SESSION['regroupement'])) {$regroupement = $_SESSION['regroupement'];}
if (isset($_SESSION['dossier'])) {$dossier = $_SESSION['dossier'];}
if (isset($_SESSION['objet'])) {$dossier = $_SESSION['objet'];}
/* paramètre de connexion à la base de donnée*/

$formulaire = $formulaireNew;

/*
$bus = array(
		0=> '->',
		1=>'BUS Désiré',
		2=>'BUS Destiny',
		3=>'BUS Pacifique',
		5=>"BUS Liberty II",
		4=>'Au Pavillon'
	);
*/

	$bus = array (
			0=> '->',

			1=>'BUS Désiré',
			2=>'BUS Destiny',
			3=>'BUS Pacifique II',

			/*4=>'Au Pavillon $',*/
			
			5=>"BUS Liberty II",
			6=>'BUS (par)Peugeot',

			7=>'BUS (par)Mercedes',
			8=>'BUS Pénalty (*)',
			9=>'BUS Spitex (*)',

			10=>'BUS Colibri'
	);
	
function dateToUserJour($dateSql)
{
if (isset($dateSql))
{

    $nbr = strlen($dateSql);
    if ($nbr != 10) {
        $dateSql = '0' . $dateSql;
    }

    setlocale (LC_TIME, 'fr_FR.utf8','fra');

    $jour = substr($dateSql, 8, 2);
    $Mois = substr($dateSql, 5, 2);
    $annee = substr($dateSql, 0, 4);
    $date = $jour . '.' . $Mois . '.' . $annee;

    return(strftimeFr("%a %d.%m.%y",strtotime($dateSql)));
}


}
function dateToUser($dateSql)
{
    if (isset($dateSql))
    {

        $nbr = strlen($dateSql);
        if ($nbr != 10) {
            $dateSql = '0' . $dateSql;
        }

        setlocale (LC_TIME, 'fr_FR.utf8','fra');

        $jour = substr($dateSql, 8, 2);
        $Mois = substr($dateSql, 5, 2);
        $annee = substr($dateSql, 0, 4);
        $date = $jour . '.' . $Mois . '.' . $annee;

        return $date;
    }


}

function dateToSql($dateUser)
{
    $nbr = strlen($dateUser);
    if ($nbr != 10)
    {
        $dateUser =  '0'.$dateUser;
    }

    $jour = substr($dateUser, 0, 2);
    $mois = substr($dateUser, 3, 2);
    $annee = substr($dateUser, 6, 4);
    $date = $annee . '-' . $mois . '-' . $jour;



}

function heureDiffDecimal($debut, $fin)
{


    $heurFin = "2016-06-19 " . $fin;
    $heurDebut = "2016-06-19" . $debut;

    $dteFin = new DateTime($heurFin);
    $dteDebut = new DateTime ($heurDebut);

    $datDiff = $dteDebut->diff($dteFin);
    $tps = $datDiff->format("%H:%I");
    $heure =  substr($tps,0,2);
    $minute = substr($tps,3,3);
    $minuteDecimal = ($minute/60)*100;
    if ($minuteDecimal < 10){
        $minuteDecimal = '0'.$minuteDecimal;
    }

    return $heure. '.' .$minuteDecimal;
}

function Diff($debut, $fin)
{

    $heurFin = "2016-06-19 " . $fin;
    $heurDebut = "2016-06-19" . $debut;

    $dteFin = new DateTime($heurFin);
    $dteDebut = new DateTime ($heurDebut);

    $datDiff = $dteDebut->diff($dteFin);


    return $datDiff->format("%H:%I");

}

function dateDiff($debut, $fin)
{

    $debut = dateToSql($debut);
    $fin = dateToSql($fin);

    $datetime1 = date_create($debut);
    $datetime2 = date_create($fin);
    $interval = date_diff($datetime1, $datetime2);
    if ($interval == 0)

    {  $interval = 1;}


    return $interval->format('%a jour');
}

function JourOuvrable($datedeb, $datefin)
{

    $nb_jours = 0;
    $dated = explode('-', $datedeb);
    $datef = explode('-', $datefin);
    $timestampcurr = mktime(0, 0, 0, $dated[1], $dated[2], $dated[0]);
    $timestampf = mktime(0, 0, 0, $datef[1], $datef[2], $datef[0]);
    while ($timestampcurr <= $timestampf) {

        if ((date('w', $timestampcurr) != 0) && (date('w', $timestampcurr) != 6)) {
            $nb_jours++;
        }
        $timestampcurr = mktime(0, 0, 0, date('m', $timestampcurr), (date('d', $timestampcurr) + 1), date('Y', $timestampcurr));

    }


    return $nb_jours;
}





function HeureHhMm($heure)
{

    $heureHM = substr((string)$heure, 0, 5);
    return $heureHM;

}

function CheckBox($valeur)
{

    if ($valeur == 1) {

        echo '<input class="input0" disabled type="checkbox" checked>';
    } else {
       echo '<input class="input0" disabled type="checkbox" >';
    }


}

function CheckBoxPDF($valeur)
{

    if ($valeur == 1) {

        echo '<img  src="../img/checkboxCheck.gif" alt="">';
    } else {
        echo '<img src="../img/checkbox.gif" alt="">';
    }


}


/**
 * @param $requet nom de la requete
 * @param $selectedValue champs a tester
 * @param $id id du champs de la tables de ou ce trouve la liste
 * @param $nom nom du champs de la tables ou ce trouve la liste
 */
function fillList($results, $selectedValue, $id, $nom,$mrp='')

{
    if($mrp==''){
        foreach( $results as $row ){
            if($selectedValue == $row[$id] )
            {
                echo '<option value="'. $row[$id] . '" selected>'. $row[$nom] .'</option>' ;

            }
            else
            {			/* afficher l'?ment de la liste comme ?nt selected */

                echo '<option value="'. $row[$id] . '">'. $row[$nom] .'</option>' ;
            }
        }
    }else{
        foreach( $results as $row ){
            if($selectedValue == $row[$id] )
            {
                echo '<option value="'. $row[$id] . '" selected>'. $mrp->getText($row[$nom]) .'</option>' ;

            }
            else
            {			/* afficher l'?ment de la liste comme ?nt selected */

                echo '<option value="'. $row[$id] . '">'. $mrp->getText($row[$nom]) .'</option>' ;
            }
        }
    }
}

function fillCheckBox($value, $name){
    ?>

    <input <?= $tag ?> class="input0" type="checkbox" value="1" name="<?php echo $name; ?>"
                       <?php if ($value == 1) { ?>checked<?php } ?>>


    <?php
}


function newPhoto($id,$legande,$name,$btnNam)
{
    $photo = "../imgParticipant/$id/$name.jpg";


    if (file_exists($photo)) {
        ?>

        <img src="<?php echo $photo ?>" style="width: 150px;">
        <?php

    } else {
        ?>
        <form method="post" enctype="multipart/form-data">
            <label for="Portrait"><?php echo $legande.' (JPG| max. 15 Ko) :'?></label><br/>
            <input type="file" name="<?php echo $name?>"/><br/>
            <input type="submit" name="<?php echo $btnNam?>" value="valider" class="valider"/>
        </form>
    <?php }

}

function NomPrenom($requet){

    echo $requet[0]. ' '.$requet[1];
}


function CompilDate($debut,$fin)
{

    $JourD = substr($debut, 8, 2);
    $JourF = substr($fin, 8, 2);
    $Mois = substr($fin, 5, 2);
    $Annee = substr($fin, 0, 4);
    if ($Mois == '01'){
        $Mois = 'janvier';
    }
    if ($Mois == '02'){
        $Mois = 'février';
    }
    if ($Mois == '03'){
        $Mois = 'mars';
    }
    if ($Mois == '04'){
        $Mois = 'avril';
    }
    if ($Mois == '05'){
        $Mois = 'mai';
    }
    if ($Mois == '06'){
        $Mois = 'juin';
    }
    if ($Mois == '07'){
        $Mois = 'juillet';
    }
    if ($Mois == '08'){
        $Mois = 'août';
    }
    if ($Mois == '09'){
        $Mois = 'septembre';
    }
    if ($Mois == '10'){
        $Mois = 'octobre';
    }
    if ($Mois == '11'){
        $Mois = 'novembre';
    }
    if ($Mois == '12'){
        $Mois = 'décembre';
    }


    return $JourD.' au '.$JourF.' '.$Mois.' '.$Annee;



}
function JourMoisAnnee($date)
{
    $Jour = substr($date, 8, 2);
    $Mois = substr($date, 5, 2);
    $Annee = substr($date, 0, 4);
    if ($Mois == '01'){
        $Mois = 'janvier';
    }
    if ($Mois == '02'){
        $Mois = 'février';
    }
    if ($Mois == '03'){
        $Mois = 'mars';
    }
    if ($Mois == '04'){
        $Mois = 'avril';
    }
    if ($Mois == '05'){
        $Mois = 'mai';
    }
    if ($Mois == '06'){
        $Mois = 'juin';
    }
    if ($Mois == '07'){
        $Mois = 'juillet';
    }
    if ($Mois == '08'){
        $Mois = 'août';
    }
    if ($Mois == '09'){
        $Mois = 'septembre';
    }
    if ($Mois == '10'){
        $Mois = 'octobre';
    }
    if ($Mois == '11'){
        $Mois = 'novembre';
    }
    if ($Mois == '12'){
        $Mois = 'décembre';
    }
    return $Jour.' '.$Mois.' '.$Annee;
}

class CSV{
    static function export($data,$name){

      header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
      header('Content-Disposition: attachment; filename="'.$name.'.csv"');

        $i = 0;
        foreach ($data as $v){
            if ($i==0){
                echo ('"'.implode('";"',array_keys($v)).'"'."\n");
            }
            echo ('"'.implode('";"',$v).'"'."\n");
            $i++;



        }


    }



}
function boolean($valeur,$mrp=''){
    if ($valeur == 1) {

        echo '<input class="input0" disabled type="checkbox" checked>';
    } else {
        echo '<input class="input0" disabled type="checkbox" >';
    }
}
class DateTimeLang extends DateTime {
    public function format($format): string {

        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Décember');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, parent::format($format)));
    }
}

?>