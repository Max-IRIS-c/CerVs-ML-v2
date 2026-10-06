<?php
    include("../variables.php");
    $bdd = new PDO($dsn, $user, $password);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // set WHERE condition as string
    $recievedData = $_POST;
    $recherche = formatRequestCondition($_POST);
    //params array for the request
    $dataWithoutNull = array_filter($recievedData, function($value) {
        return $value !== null; // pour binder req car "IS NULL"
    });
    $params = createParamsOfRequest($dataWithoutNull);
    // -- contacts data --
    $concernedContacts = getConcernedUsers($bdd, $recherche, $params);
    $contactsForExport = formatDataForExport($concernedContacts);
    $nbr = intval($concernedContacts) <= 0 ? 0 : count($concernedContacts);

    include('../heade.php')
?>

<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="contact.php"><?php echo $mrp->getText("Tous les contacts") ?></a></li>
        <li class="textGauche"><a href="filtMarContact.php"><?php echo $mrp->getText(" Filtre recherche marquage manuel"); ?></a></li>
        <li class="textGauche"><a href="filtContact.php"><?php echo $mrp->getText(" Filtre recherche avancée"); ?></a></li>
    </ul>
</nav>
<?php if($nbr > 0){ ?>
    <div id="submits">
        <form method="post" action="./expotExcelAvance.php">
            <input type="hidden" name="contacts" value="<?php echo htmlspecialchars(json_encode($contactsForExport)); ?>"/>
            <input type="submit" name="Valider" value="Export CSV" class="valider">
        </form>
        <form method="post" action="./exportPDFavance.php">
            <input type="hidden" name="contacts" value="<?php echo htmlspecialchars(json_encode($contactsForExport)); ?>"/>
            <input type="submit" name="ValiderPDF" value="Export PDF" class="valider">
        </form>
    </div>
<?php } ?>
<p id="nbr">Nombre de contact trouvés...<strong><?php echo $nbr; ?></strong>...</p>
<table class="affichage">
    <tr>
	    <th><?php echo $mrp->getText("Société") ?></th>
        <th><?php echo $mrp->getText("Nom") ?></th>
        <th><?php echo $mrp->getText("Prénom") ?></th>
        <th><?php echo $mrp->getText("Npa") ?></th>
        <th><?php echo $mrp->getText("Localité") ?></th>
	    <!-- Modif FC -- -->
	    <th><?php echo $mrp->getText("Tel1 or Tel 2 or Tel 3 or Tel 4") ?></th>
	    <!--
	    <th><?php // echo $mrp->getText("Tel 1") ?></th>
        <th><?php // echo $mrp->getText("Tel 2") ?></th>
        <th><?php // echo $mrp->getText("Tel 3") ?></th>
        <th><?php // echo $mrp->getText("Tel 4") ?></th>
        -->
	    <!-- Modif FC -- -->
	    <th><?php echo $mrp->getText("Marqueur") ?></th>
        <th></th>
    </tr>
    <?
    $i = 0;
    if(count($concernedContacts) > 0) foreach($concernedContacts as $row){ ?>
        <tr>
	        <td><? echo $row['conSociete']; ?></td>
            <td><? echo $row['conNom']; ?></td>
            <td><? echo $row['conPrenom']; ?></td>
            <td><? echo $row['conNpa']; ?></td>
            <td><? echo $row['conLocaliter']; ?></td>
	        
	        <td><? echo $row['conTel1'] ." <br/> ".  $row['conTel2']." <br/> ".  $row['conTel3']." <br/> ".  $row['conTel4']; ?></td>
	        
	        <!--
	        <td><? echo $row['conTel1']; ?></td>
            <td><? echo $row['conTel2']; ?></td>
            <td><? echo $row['conTel3']; ?></td>
            <td><? echo $row['conTel4']; ?></td>
            -->
	        <td><? echo $row['conMarquage']; ?></td>
            <td><? echo '<a href="detContacte.php?conId=' . $row['conId'] . '">'.$mrp->getText("Détail").'</a>'; ?></td>
        </tr>

        <? $i = $i + 1;
    }
    //$req->closeCursor();
    ?>
</table>

<?php include ('../footer.php'); ?>
<style>
    .affichage{
        margin: auto;
    }
    #submits{
        width: 100%;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
    }
    #nbr{
        margin: auto;
        width: 80%;
        display: flex;
        justify-content: center;
        padding: 10px;
        margin-bottom: 10px;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }
</style>
<?php 
    // format POST data in WHERE condition pour the request 
    // ==> "name = :name AND name2 = :name2"
    function formatRequestCondition($givenData){
        try{
            if(empty($givenData)) throw new Exception();
            $lastKey = key(array_slice($givenData, -1, 1, true)); // dernier du tableau
            $conditionString = "";
            foreach($givenData as $name => $value){
                if($value === null || $value === 'null') $conditionString = $conditionString." $name IS NULL";
                else $conditionString = $conditionString . " $name = :$name";
                if($name !== $lastKey) $conditionString = $conditionString . " AND"; 
            }
            return $conditionString;
        }catch(Exception $e){
            return "1 = 1";
        }
    }

    // format POST data in params array for the request 
    // ==> [':name' => $value, ...]
    function createParamsOfRequest($givenData){
        try{    
            if(empty($givenData)) throw new Exception();
            $paramsArray = [];
            foreach($givenData as $name => $value){
                if($value === "") $paramsArray[":$name"] = null;
                else if(is_numeric($value)) $paramsArray[":$name"] = intval($value);
                else $paramsArray[":$name"] = $value;
            }
            return $paramsArray;
        }catch(Exception $e){
            return [];
        }
    }
    // sql query return all data 
    function getConcernedUsers($bdd, $recherche, $params){
        try{      
            $sql = "SELECT * from tblContact
            LEFT JOIN tblCiviliter on tblCiviliter_civId = civId 
            WHERE $recherche AND conStatu = 1 ORDER BY conNom,conPrenom";  
            $req = $bdd->prepare($sql);
            $req->execute($params);
            return $req->fetchAll();
        }catch(Exception $e){
            return null;
        }
    }
    function formatDataForExport($data){
        try{
            $formatedContacts = [];
            if(!isset($data) || empty($data)) throw new Exception();
            foreach ($data as $contact) {
                $formatedContacts[] = [
                    'Société'      => $contact['conSociete'] ?? '',
                    'Titre'        => $contact['civNom'] ?? '',
                    'Nom'          => $contact['conNom'] ?? '',
                    'Prénom'       => $contact['conPrenom'] ?? '',
                    'Date de naissance' => (empty($contact['conDateNaissance']) || $contact['conDateNaissance'] === '0000-00-00') ? '' : $contact['conDateNaissance'],
                    'Complément'   => $contact['conComplement'] ?? '',
                    'Adresse'      => $contact['conAdresse'] ?? '',
                    'Adresse2'     => $contact['conAdresse2'] ?? '',
                    'NPA'          => $contact['conNpa'] ?? '',
                    'Localité'     => $contact['conLocaliter'] ?? '',
                    'Téléphone1'   => $contact['conTel1'] ?? '',
                    'Téléphone2'   => $contact['conTel2'] ?? '',
                    'Téléphone3'   => $contact['conTel3'] ?? '',
                    'E-mail'       => $contact['conMail'] ?? '',
                    'Type'         => $contact['mTypNom'] ?? ''
                ];
            }
            return $formatedContacts;
        }catch(Exception $e){
            return null;    
        }
    }
?>