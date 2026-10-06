<?php

// Démarrage ou restauration de la session
session_start();

$nom = $_SESSION['Prenom'] . " " . $_SESSION['Nom'];
$idUtilisateur = $_SESSION['id'];
//$auth = $_SESSION['auth'];
$auth = $_SESSION['auth'];
$travail = $_SESSION['travail'];
/* paramètre de connexion à la base de donnée*/

$user = 'adminInternet';
$password = 'X99Sk3hsP86iBa';
$dsn = 'mysql:host=localhost:3307;dbname=alunis-CerVs17';
$plannification = $_SESSION['plannification'];
$formulaire = $formulaireNew;
$regroupement = $_SESSION['regroupement'];
$dossier = $_SESSION['dossier'];
$objet = $_SESSION['objet'];

/*
$bus = array(
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
{?>

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

    while ($row = $requet->fetch())
    {
        if($valeurDefaut == $row[$id] )
        {
            echo '<option value="'. $row[$id] . '" selected>'. $row[$nom] .'</option>' ;

        }
        else
        {			/* afficher l'?ment de la liste comme ?nt selected */

            echo '<option value="'. $row[$id] . '">'. $row[$nom] .'</option>' ;
        }
    }

    $requet->closeCursor();

}


function ListeModif2($requet, $valeurDefaut, $id, $v1, $v2)

{

    while ($row = $requet->fetch())
    {
        if($valeurDefaut == $row[$id] )
        {
            echo '<option value="'. $row[$id] . '" selected>'. $row[$v1]. " ".$row[$v2] .'</option>' ;

        }
        else
        {			/* afficher l'?ment de la liste comme ?nt selected */

            echo '<option value="'. $row[$id] . '">'. $row[$v1]." ".$row[$v2] .'</option>' ;
        }
    }

    $requet->closeCursor();

}


function ListeDeroulante($requete,$id,$v1)
{
    while ($a = $requete->fetch())
    {
        ?>
        <option  value="<?php echo $a[$id]; ?>"> <?php echo $a[$v1]; ?></option>

        <?php
    }
    $requete->closeCursor();
}

function ListeDeroulante2($requete,$id,$v1,$v2)
{
        while ($a = $requete->fetch())
        {
            ?>
            <option  value="<?php echo $a[$id]; ?>"> <?php echo $a[$v1]. " ".$a[$v2]; ?></option>

            <?php
        }
    $requete->closeCursor();
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

?>