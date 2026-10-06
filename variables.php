<?php

global $formulaireNew;

// Démarrage ou restauration de la session

if (session_status()==1)
{
session_start();
}
if (isset($_SESSION['travail'])) { $travail = $_SESSION['travail'];}
if (isset($_SESSION['plannification'])) {$plannification = $_SESSION['plannification'];}
if (isset($_SESSION['regroupement'])) {$regroupement = $_SESSION['regroupement'];}
if (isset($_SESSION['dossier'])) {$dossier = $_SESSION['dossier'];}
if (isset($_SESSION['objet'])) {$dossier = $_SESSION['objet'];}
if (isset($_SESSION['travail'])) {$travail = $_SESSION['travail'];}

$nom = $_SESSION['Prenom'] . " " . $_SESSION['Nom'];

$idUtilisateur = $_SESSION['id'];
$auth = $_SESSION['auth'];

/* paramètre de connexion à la base de donnée*/

// DB connexion are now gather in a config.php file (from 6th july 2021)
include ("../inc/config.php");



$formulaire = $formulaireNew;

/*
$bus = array (
		0=> '->',
		1=>'BUS Désiré',
		2=>'BUS Destiny',
		3=>'BUS Pacifique',
		5=>"BUS Liberty II",
		4=>'Au Pavillon',
		6=>'BUS(par)Peugeot',
		7=>'BUS(par) Mercedes'
);
*/
	
	$bus = array (
			0=> '->',

			1=>'BUS Désiré',
			2=>'BUS Destiny',
			3=>'BUS Pacifique II',

		    4=>'Au Pavillon',
			5=>"BUS Liberty II",
			6=>'BUS (par)Peugeot',

			7=>'BUS (par)Mercedes',
			8=>'BUS Pénalty (*)',
			9=>'BUS Spitex (*)',

			10=>'BUS Colibri',
            //11=>'Pavillon'
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

    return(strftime("%a %d.%m.%y",strtotime($dateSql)));
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
    if (empty($datedeb) || empty($datefin)) return 0;
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

    $heureHM = substr($heure, 0, 5);
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

function CheckBoxModif($variable, $name)
{   ?>
        <input class="input0" type="checkbox" value="1" name="<?php echo $name;?>"
        <?php if ($variable == 1) {?>checked<?php } else {}?>/>
    <?php
}

/**
 * @param $requet nom de la requete
 * @param $valeurDefaut champs a tester
 * @param $id id du champs de la tables de ou ce trouve la liste
 * @param $nom nom du champs de la tables ou ce trouve la liste
 */
function ListeModif($requet, $valeurDefaut, $id, $nom)

{

	include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();

    while ($row = $requet->fetch())
    {
        if($valeurDefaut == $row[$id] )
        {
            echo '<option value="'. $row[$id] . '" selected>'. $mrp->getText($row[$nom]) .'</option>' ;

        }
        else
        {			/* afficher l'?ment de la liste comme ?nt selected */

            echo '<option value="'. $row[$id] . '">'. $mrp->getText($row[$nom]) .'</option>' ;
        }
    }

    $requet->closeCursor();

}


function ListeModif2($requet, $valeurDefaut, $id, $v1, $v2)

{
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
$mrp = new Mrp();
    while ($row = $requet->fetch()){
        if($valeurDefaut == $row[$id] ){
            echo '<option value="'. $row[$id] . '" selected>'.$mrp->getText($row[$v1]). " ".$mrp->getText($row[$v2]).'</option>' ;
        } else {			/* afficher l'?ment de la liste comme ?nt selected */
            echo '<option value="'. $row[$id] . '">'. $mrp->getText($row[$v1])." ".$mrp->getText($row[$v2]) .'</option>' ;
        }
    }
    $requet->closeCursor();
}


function ListeDeroulante($requete,$id,$v1)
{
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";

$mrp = new Mrp();

    while ($a = $requete->fetch())
    {
        ?>
        <option  value="<?php echo $a[$id]; ?>"> <?php echo $mrp->getText($a[$v1]); ?></option>

        <?php
    }
    $requete->closeCursor();
}

function ListeDeroulante2($requete,$id,$v1,$v2)
{
        while ($a = $requete->fetch())
        {
            ?>
            <option style="text-transform: uppercase" value="<?php echo $a[$id]; ?>"> <?php echo $a[$v1]. " ".$a[$v2]; ?></option>

            <?php
        }
    $requete->closeCursor();
}

function ListeDeroulante3($requete,$id,$v1,$v2)
{
	while ($a = $requete->fetch())
	{
		?>
		<option  value="<?php echo $a[$id]; ?>"> <?php echo ucwords($a[$v1]). " ".ucwords($a[$v2]); ?></option>
			<?php
	}
	$requete->closeCursor();
}
// uniquement pourles liste déroulantes avec options personnalisées
function ListeDeroulante4($givenValue, $concernedListNbr){
    $listValues = determineList($concernedListNbr);
    $formatedGivenValue = (is_null($givenValue)) ? '' : intval($givenValue);
    foreach($listValues as $listVal){ ?>
        <option 
            value="<?php echo $listVal['value']; ?>"
            <?php echo $formatedGivenValue === $listVal['value'] ? ' selected' : null; ?>
        ><?php echo $listVal['label']; ?></option>  <?php
    }
}
function determineList($refNbr){
    include_once "../src/class/Db.class.php";
    include_once "../src/class/Mrp.class.php";
    $mrp = new Mrp(); 
    switch($refNbr){
        case 0: 
            return [
                ['value' => 0, 'label' => $mrp->getText('Non')],
                ['value' => 1, 'label' => $mrp->getText('Oui')],
                ['value' => '', 'label' => $mrp->getText('NC')],
            ];
            break;
        case 1:
            return [
                ['value' => 0, 'label' => $mrp->getText('Non')],
                ['value' => 1, 'label' => $mrp->getText('Oui')],
                ['value' => '', 'label' => $mrp->getText('Partiellement')],
            ];
            break;
        case 2: // consistence du repas
            return [
                ['value' => '', 'label' => '-'],
                ['value' => 0, 'label' => $mrp->getText('Coupé en petit morceau')],
                ['value' => 1, 'label' => $mrp->getText('Haché fin et humidifié')],
                ['value' => 2, 'label' => $mrp->getText('Mixé lisse')],
                ['value' => 3, 'label' => $mrp->getText('PEG')]
            ];
            break;
    }
}
function TextBoxListeDeroulante4($givenValue, $refList){ ?>
    <input 
        disabled
        type="text"
        value="<?php echo formatTextOfListeDeroulante4($givenValue, $refList); ?>"
        style="
            width: inherit;
            height: inherit;
            text-align: center;
            color: black;
            font-weight: bold;
        "
    /><?php 
}

function formatTextOfListeDeroulante4($givenValue, $refList){
    include_once "../src/class/Db.class.php";
    include_once "../src/class/Mrp.class.php";
    try{
        $mrp = new Mrp(); 
        if(is_null($givenValue)) throw new Exception();
        $listValues = determineList($refList);
        $formatedGivenValue = intval($givenValue);
        if(!isset($formatedGivenValue) || !is_int($formatedGivenValue)) throw new Exception();
        foreach($listValues as $listVal){ 
            if($listVal['value'] === $formatedGivenValue) return $listVal['label'];
        }
        throw new Exception();
    }catch(Exception $e){
        if($refList === 0) return $mrp->getText('NC');
        else if($refList === 1) return $mrp->getText('Partiellement');
    }
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
function determineConsistence($value){
    $mrp = new Mrp();
    switch($value){
        case 0: {
            return $mrp->getText('coupé en petits morceau');
            break;
        }
            case 1: {
            return $mrp->getText('haché fin et humidifié');
            break;
        }
            case 2: {
            return $mrp->getText('mixé lisse');
            break;
        }
            case 3: {
            return $mrp->getText('PEG');
            break;
        }
    }
    return '';
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
function getMyWorkRate(){    
    try{
        $id = $_SESSION['id'];
        include_once "../src/class/Db.class.php";
        $db = new Db(); 
        $req = "SELECT empTaux FROM tblEmployer WHERE empId = :id";
        $db->bindInt('id', $id);  
        $data = $db->query($req);
        return floatVal($data[0]['empTaux']) ?? null; 
    }catch(Exception $e){
        return null;
    } 
}

if (!class_exists('CSV')){
 class CSV{
    static function export($data,$name){
        try{
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
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
 }
}
/**
 * Formate une date en français (ex: "10 janvier 2026")
 *
 * @param string|null $dateString La date brute (ex: "2026-01-10", "10/01/2026", etc.)
 * @param string $format Le format de sortie souhaité (défaut: 'd F Y')
 * @return string La date formatée ou une chaîne vide si la date est invalide
 */
function formaterDateFr($dateString, $format = 'd F Y') {
    if (empty($dateString)) {
        return '';
    }

    try {
        // Création de l'objet DateTime
        $date = new DateTime($dateString);
        
        // Formatage initial (renvoie les mois en anglais par défaut)
        $dateFormatee = $date->format($format);
        
        // Tableau de traduction des mois et jours
        $traduction = [
            'January' => 'janvier', 'February' => 'février', 'March' => 'mars',
            'April' => 'avril', 'May' => 'mai', 'June' => 'juin',
            'July' => 'juillet', 'August' => 'août', 'September' => 'septembre',
            'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre',
            'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi',
            'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi', 'Sunday' => 'dimanche'
        ];
        
        // Remplacement des termes anglais par le français
        return str_replace(array_keys($traduction), array_values($traduction), $dateFormatee);
        
    } catch (Exception $e) {
        // En cas de date invalide, on retourne une chaîne vide ou la date originale
        return ''; 
    }
}
?>
