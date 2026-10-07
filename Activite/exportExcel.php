<?php include_once __DIR__ . '/../src/dateFr.php'; ?><?php
include ("../variables.php");

try{
    $bdd = new PDO($dsn, $user, $password);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
    $id = $_GET['Id'];

    /*************************** get data *********************************/
    $participants = getParticipant($bdd, $id);
    $accompagnants = getAccompagnant($bdd, $id);
    $doublures = getDoublures($bdd, $id);
    $activite = getActiviteInfos($bdd, $id);
}catch(Exception $e){
    echo "Il y a eu un soucis.";
}



/* Headers pour forcer le téléchargement en CSV */
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=exportParticipant.csv');
echo "\xEF\xBB\xBF"; // UTF-8 BOM pour Excel
$output = fopen('php://output', 'w');

/*************************** display data *********************************/

displayActiviteInfos($activite, $output);
displayDataOnCsv($participants, $output, "Participants");
displayDataOnCsv($accompagnants, $output, "Accompagnants");
displayDataOnCsv($doublures, $output, "Doublures");
fclose($output);
exit;

function displayActiviteInfos($givenData, $output){
    $data = $givenData[0]; // première ligne
    if (empty($data)) return; 
    $date = ['title' => 'Date', 'data' => setDate($data['actDebut'], $data['actFin'])];
    $lieu = ['title' => 'Lieu', 'data' => $data['actLieu']];
    $resp = ['title' => 'Résponsable', 'data' => $data['responsableN']. ' ' .$data['responsableP']];
    $coResp = ['title' => 'Co-résp.', 'data' => $data['coResponsableN']. ' ' .$data['coResponsableP']];    
    $soinsResp = ['title' => 'Résp. des soins', 'data' => $data['InfirmierN']. ' ' .$data['InfirmierP']];
    $cuisinier = ['title' => 'Cuisinier', 'data' => $data['cuisinierN']. ' ' .$data['cuisinierP']];
    fputcsv($output, $date, ';', '"', '\\');
    fputcsv($output, $lieu, ';', '"', '\\');
    fputcsv($output, $resp, ';', '"', '\\');
    fputcsv($output, $coResp, ';', '"', '\\');
    fputcsv($output, $soinsResp, ';', '"', '\\');
    fputcsv($output, $cuisinier, ';', '"', '\\');
}

function displayDataOnCsv($givenData, $output, $sectionTitle = null, $isFirst = false){
    if (empty($givenData)) return;
    // ligne vide avant le titre (sauf pour le premier bloc)
    if (!$isFirst) fwrite($output, "\n"); 
    // title => accompagnant etc.
    if ($sectionTitle) fputcsv($output, ["*** $sectionTitle ***"], ';', '"', '\\');
    // entete
    fputcsv($output, array_keys($givenData[0]), ';', '"', '\\');
    //data
    foreach ($givenData as $row) {
        fputcsv($output, $row, ';', '"', '\\');
    }
}

function setDate($start, $end){
    $Date1 = strtotime($start);
    $Date2 = strtotime($end);
    $format1 = ("%d");
    $format2 = ("%d %B %G");

    if ($Date1 != $Date2) return (strftimeFr($format1, $Date1)) . ' au ' . (strftimeFr($format2, $Date2));
    else return (strftimeFr($format2, $Date1));
}
// date, lieu, responsable, co-resp., resp. cuisine
function getActiviteInfos($bdd, $idActivite){
    $req = "
        SELECT 
            tblActivites.actNom,tblActivites.actLieu,tblActivites.actTheme,
            Responsable.conNom as responsableN ,  Responsable.conPrenom as responsableP ,
            CoResponsable.conNom as  coResponsableN,CoResponsable.conPrenom as  coResponsableP,
            Cuisinier.conNom as cuisinierN, Cuisinier.conPrenom as cuisinierP,
            Infirmier.conNom as InfirmierN, Infirmier.conPrenom as InfirmierP,actBus,actDec, actDebut, actFin
        FROM tblActivites
        LEFT JOIN tblContact as Responsable on actResponsable = Responsable.conId
        LEFT JOIN tblContact as CoResponsable on actCoResponsable = CoResponsable.conId
        LEFT JOIN tblContact as Cuisinier on actCuisiniere = Cuisinier.conId
        LEFT JOIN tblContact as Infirmier on  actInfirmier = Infirmier.conId
        WHERE actId = :id ";
   
    return executeQuery($bdd, $req, $idActivite);
}
function getDoublures($bdd, $idActivite){
    $req = "
        SELECT DISTINCT 
            civNom as 'Titre', 
            conNom as Nom,
            conPrenom as 'Prénom', 
            conComplement as 'Complément',
            conAdresse as Adresse, 
            conAdresse2 as Adresse2, 
            conNpa as Npa, 
            conLocaliter as 'Localité', 
            conTel1 as 'Téléphone1', 
            conTel2 as 'Téléphone2',
            conTel3 as 'Téléphone3',
            conDateNaissance as 'Date de naissance',
            tblNationaliter.natNom as 'Nationalité',
            conAvs as 'AVS',
            conBanque as 'Banque',
            conAgence as 'Agence de',
            conIban as 'IBAN'
        FROM tblParticipants
        LEFT JOIN tblActivites ON tblActivites.actId = tblParticipants.actId
        LEFT JOIN tblContact ON tblParticipants.conIdD = conId
        LEFT JOIN tblNationaliter ON tblNationaliter.natId = tblContact.conNationalite
        LEFT JOIN tblCiviliter ON tblCiviliter_civId = civId
        WHERE tblParticipants.actId = :id
        ORDER BY Nom ASC
    ";
    return executeQuery($bdd, $req, $idActivite);
}

function getAccompagnant($bdd, $idActivite){
    $req = "
        SELECT DISTINCT 
            civNom as 'Titre', 
            conNom as Nom,
            conPrenom as 'Prénom', 
            conComplement as 'Complément',
            conAdresse as Adresse, 
            conAdresse2 as Adresse2, 
            conNpa as Npa, 
            conLocaliter as 'Localité', 
            conTel1 as 'Téléphone1', 
            conTel2 as 'Téléphone2',
            conTel3 as 'Téléphone3',
            conDateNaissance as 'Date de naissance',
            tblNationaliter.natNom as 'Nationalité',
            conAvs as 'AVS',
            conBanque as 'Banque',
            conAgence as 'Agence de',
            conIban as 'IBAN'
        FROM tblParticipants
        LEFT JOIN tblActivites ON tblActivites.actId = tblParticipants.actId
        LEFT JOIN tblContact ON tblParticipants.conIdA = conId
        LEFT JOIN tblNationaliter ON tblNationaliter.natId = tblContact.conNationalite
        LEFT JOIN tblCiviliter ON tblCiviliter_civId = civId
        WHERE tblParticipants.actId = :id
        ORDER BY Nom ASC
    ";
    return executeQuery($bdd, $req, $idActivite);
}

function getParticipant($bdd, $idActivite){
    $req = "
        SELECT DISTINCT 
            civNom as 'Titre', 
            conNom as Nom,
            conPrenom as 'Prénom', 
            conComplement as 'Complément',
            conAdresse as Adresse, 
            conAdresse2 as Adresse2, 
            conNpa as Npa, 
            conLocaliter as 'Localité', 
            conTel1 as 'Téléphone1', 
            conTel2 as 'Téléphone2',
            conTel3 as 'Téléphone3',
            conDateNaissance as 'Date de naissance',
            conAvs as 'AVS',
            conBanque as 'Banque',
            conIban as 'IBAN'
        FROM tblParticipants
        LEFT JOIN tblActivites ON tblActivites.actId = tblParticipants.actId
        LEFT JOIN tblContact ON tblParticipants.conIdP = conId
        LEFT JOIN tblCiviliter ON tblCiviliter_civId = civId
        WHERE tblParticipants.actId = :id
        ORDER BY Nom ASC
    ";
    return executeQuery($bdd, $req, $idActivite);
}
function executeQuery($bdd, $query, $id){
    if(!intval($id) || intval($id) === 0) throw new Exception();
    $req = $bdd->prepare($query);
    $req->execute(['id' => intval($id)]);
    $participants = $req->fetchAll();
    return $participants;
}
?>


