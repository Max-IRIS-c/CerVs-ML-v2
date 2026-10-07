<?php
    include "./../src/class/Db.class.php";
    $updateEnabled = $_GET['update'] ?? null;
    $idContact = intval($_GET['fkContact'] ?? 0) ? intval($_GET['fkContact']) : (intval($_POST['fkContact'] ?? 0) ? intval($_POST['fkContact']) : null);
    $searchedParam = ($_GET['searchedParam'] ?? null) ?: (($_POST['searchedParam'] ?? null) ?: null); 
    $updateError = null;

    // validation update
    if( isset($_POST['validateReleveInfosModif'])
    && $_POST['validateReleveInfosModif'] === 'Valider') {
        $updateData = updateInfos(
            $idContact,
            $_POST['infoGeneral'] ?? null,
            $_POST['besoins'] ?? null,
            $_POST['location'] ?? null,
            $_POST['externes'] ?? null
        );
        if(!$updateData) $updateError = true;
        else{
            $updateEnabled = 'no';
            $updateError = 'success';
        }
        
    } 
    
    $infos = getInfos($idContact);

  
    include('../header.php');
?>
<nav>
    <ul>
        <li>
            <?php echo '<a href="releve.php?Id='.$idContact.'&searchedParam='.$searchedParam.'"> Retour vers onglet Relève </a>';?>
        </li>
    </ul>
</nav>
<h1>Informations du bénéficiaire </h1>
<?php
    if($updateError === true){
        echo '<p id="error"> Il y a eu un soucis avec les nouvelles données.
        </br> Les champs n\'acceptent pas plus de 2048 caractères (espaces compris).
        </br> Si le problème persiste, contactez le développeur.</p>'; 
    }else if($updateError === 'success'){
        echo '<p id="success">✅ La modification est bien enregistrée ✅</p>'; 
    }
?>
<form method="post" action="./releveInfos.php">
    <input type="hidden" name="fkContact" value="<?php echo $idContact; ?>"/>
    <input type="hidden" name="searchedParam" value="<?php echo $searchedParam; ?>"/>
    <table style="width: 100%;">
        <tr>
            <th class="thh">Informations général</th>
            <th class="thh">Besoins</th>
            <th class="thh">Lieu et durée</th>
            <th class="thh">Intervenants externes</th>
            <th class="thh"></th>
        </tr>
        <tr>
            <td style="padding: 10px;">
                <?php 
                    if($updateEnabled === 'yes') echo '<textarea name="infoGeneral" class="field">'.$infos['releveInfoGeneral'].'</textarea>';
                    else echo $infos['releveInfoGeneral'];
               ?>
            </td>
            <td style="padding: 10px;">
               <?php 
                    if($updateEnabled === 'yes') echo '<textarea name="besoins" class="field">'.$infos['releveBesoins'].'</textarea>';
                    else echo $infos['releveBesoins'];
               ?>
            </td>
            <td style="padding: 10px;">
               <?php 
                    if($updateEnabled === 'yes') echo '<textarea name="location" class="field">'.$infos['releveLocation'].'</textarea>';
                    else echo $infos['releveLocation'];
               ?>
            </td>
            <td style="padding: 10px;">
               <?php 
                    if($updateEnabled === 'yes') echo '<textarea name="externes" class="field">'.$infos['releveIntervenantsExternes'].'</textarea>';
                    else echo $infos['releveIntervenantsExternes'];
               ?>
            </td>
            <td style="padding: 10px;">
                <?php 
                    if($updateEnabled === 'yes'){
                        echo '<input type="submit" value="Valider" name="validateReleveInfosModif" value="Valider"/>'.' ' //validation du fomulaire d'update
                              .'<a href="./releveInfos.php?update=false&fkContact='.$idContact.'&searchedParam='.$searchedParam.'">Annuler</a>'; // annuler formulaire d'update
                    }else{
                        echo '<a id="modifier" href="./releveInfos.php?update=yes&fkContact='.$idContact.'&searchedParam='.$searchedParam.'">Modifier</a>';
                    }               
                ?>                
            </td>       
        </tr>
    </table>
</form>
<?php
    function getInfos($idContact){
        try{
            if(intval($idContact) == 0) throw new Exception();
            $db = new Db();
            $req = $db->query("SELECT conNom, conPrenom, releveInfoGeneral, 
                                    releveBesoins, releveLocation, releveIntervenantsExternes 
                                FROM tblContact 
                                WHERE conId = $idContact"); 
            if(!$req && count($req) <= 0) throw new Exception(); 
            return $req[0]; 
        }catch(Exception $e){ 
            return null; 
        } 
    }
    function updateInfos(
        $idContact, 
        $infoGeneral, 
        $besoins, 
        $location,
        $intervenantsExternes
    ){
        try{
            if(intval($idContact) == 0) throw new Exception();
            $db = new Db();
            $db->bindTxt('releveInfoGeneral', $infoGeneral);
            $db->bindTxt('releveBesoins', $besoins);
            $db->bindTxt('releveLocation', $location);
            $db->bindTxt('releveIntervenantsExternes', $intervenantsExternes);
            $db->bindInt('idContact', $idContact); 

            $sqlQuery = "UPDATE tblContact
                SET releveInfoGeneral = :releveInfoGeneral, 
                    releveBesoins = :releveBesoins, 
                    releveLocation = :releveLocation, 
                    releveIntervenantsExternes = :releveIntervenantsExternes
                WHERE conId = :idContact";
            
            // 3. Exécution de la requête préparée
            $aLine = $db->query($sqlQuery);
            
            return true;
        }catch(Exception $e){
            return null;
        }
    }
?>
<style>
    .field{
        width: 100%;
        height: 150px;
    }
    .thh{
        padding: 15px;
    }
    #validate{
        display: none;
    }
    #error{
        width: 100%;
        padding: 20px;
        font-weight: bold;
        background-color: #ef5350;
    }
    #success{      
        width: 100%;
        padding: 20px;
        text-align: center;
        font-weight: bold;
    }
    #modifier{
        background-color: green;
        color: white;
        padding: 6px;
        text-decoration: none;
    }
    #modifier:hover{
        cursor: pointer;
    }
</style>