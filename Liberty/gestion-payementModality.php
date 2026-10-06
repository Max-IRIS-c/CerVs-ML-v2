<?php    
    include('../src/class/Db.class.php');
    include('./class/locationObject.php');

    $allPriceUnity = LocationObject::getListOfPriceUnity();
    $unityToUpdate = intval($_GET['unityToUpdate']) !== 0 ? intval($_GET['unityToUpdate']) : null;
    $successUpdate = intval($_GET['success']) !== 0 ? $_GET['success'] : null;
    $errorUpdate = intval($_GET['err']) !== 0 ? $_GET['err'] : null;

    /******** [CREATE & UPDATE unity] click sur bouton annuler  ****/
    if((isset($_POST['submit-unity']) && $_POST['submit-unity'] === 'cancel') || 
        (isset($_POST['submit-add-unity']) && $_POST['submit-add-unity'] === 'cancel') ){
        header("Location: ./gestion-payementModality.php?unityToUpdate=0");
    }
    /******** [CREATE presta] click sur bouton valider ****/
    if(isset($_POST['submit-add-unity']) && $_POST['submit-add-unity'] === 'validated'){
        try{
            $givenName = $_POST['unityName'];
            if(!$givenName) throw new Exception();
            $db = new Db();
            $insertQuery = $db->query("INSERT INTO tblUnite (uniNom) VALUES ('$givenName')");
            if(!$insertQuery) throw new Exception();
            header("Location: ./gestion-payementModality.php?success=1");
        }catch(Exception $e){       
            header("Location: ./gestion-payementModality.php?err=1");
        }
    }
    /******** [UPDATE presta] click sur bouton valider ****/
    if(isset($_POST['submit-unity']) && $_POST['submit-unity'] === 'update-unity'){
        try{
            $idUnity = intval($_POST['idUnity']) !== 0 ? intval($_POST['idUnity']) : null;
            $unityName = $_POST['uniNom'];
            if(!$idUnity || $unityName === '') throw new Exception();
            $db = new Db(); 
            $updateReq = $db->query("UPDATE tblUnite 
                                        SET uniNom = '$unityName' 
                                        WHERE uniId = $idUnity  
                                    "); 
            if(!$updateReq) throw new Exception(); 
            header("Location: ./gestion-payementModality.php?success=1"); 
        }catch(Exception $e){ 
            header("Location: ./gestion-payementModality.php?err=1"); 
        } 
    } 
    include('../header.php'); 
    ?> 
    <nav>
        <ul>
            <li><a href="liberty.php">Objets en location</a></li>
            <li><a href="Location.php"><?php echo $mrp->getText('Liste des locations') ?> </a></li>
            <li><a href="planning.php"><?php echo $mrp->getText(' Planning') ?></a></li>
            <li><a href="optionLocation.php"><?php echo $mrp->getText(' Options (pour contrats)') ?></a></li>
            <li><a href="gestion-typePresta.php">Types de prestations</a></li>
        </ul>
    </nav>
    <h1>Modalités des prix</h1> 
    <button id="add-unity-bt">Ajouter un nouveau type de prestations</button> 
    <form id="add-unity"  method="post"> 
        <tr> 
            <input 
                type="text" 
                name="unityName" 
                placeHolder="Nom de la nouvelle modalité" 
                style="width: 50%;" 
            /> 
            <div> 
                <button type="submit" name="submit-add-unity" value="validated" class="btnn green">Valider</button>
                <button type="submit" name="submit-add-unity" value="cancel" class="btnn red">Annuler</button> 
            </div>
        </tr>
    </form>
    <?php
        if($errorUpdate) echo '<p class="warn">⚠️ Il y a eu un problème durant la modification, réessayez et contactez le développeur si le problème pérsiste';       
        if($successUpdate) echo '<p class="warn">✅ La modification a été effectuée avec succès</p>'; 
    ?>
    <table style="margin: auto;">
        <tr>
            <th>Nom</th>
            <th>Modifier</th>
        </tr>
        <?php 
            foreach($allPriceUnity as $unity){ 
                $idUnity = intval($unity['uniId']);
                if($idUnity === 0) continue; ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="idUnity" value="<?php echo $unity['uniId']; ?>"/>
                        <!-- input text si unityToUpdate initialisé -->
                        <?php if($unityToUpdate && $unityToUpdate === $idUnity){ ?>
                            <td>
                                <input type="text" name="uniNom" value="<?php echo $unity['uniNom']; ?>"/>
                            </td>
                        <!-- text si unityToUpdate pas initialisé -->
                        <?php } else { ?>
                            <td><?php echo $unity['uniNom']; ?></td>
                        <?php } ?>
                        <td>
                            <!-- boutons submit si unityToUpdate initialisé -->
                            <?php if($unityToUpdate && $unityToUpdate === $idUnity){ ?>
                                <button type="submit" name="submit-unity" value="update-unity">Valider</button>
                                <button type="submit" name="submit-unity" value="cancel">Annuler</button>
                            <?php } else { ?>       
                            <!-- bouton "modifier" pour recharger la page avec unityToUpdate initialisé -->
                                <a href="./gestion-payementModality.php?unityToUpdate=<?php echo $unity['uniId'] ?>">Modifier</a>
                            <?php } ?>
                        </td>
                    </form>
                </tr>
            <?php } ?>
    </table>
    <style>
        #add-unity{
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items:center;
        }
        .btnn{
            padding: 7px;
            border: none;
            color: white;
        }
        .red{
            background-color: #e53935;
        }
        .green{
            background-color: #43a047 ;
        }
        .warn{
            text-align: center;
            padding: 20px;
        }
    </style>
<script>

    addEventListener("load", (event) => { 
        const unityBt = document.getElementById('add-unity-bt')
        const unityForm = document.getElementById('add-unity')

        unityBt.addEventListener('click', () => {
            unityForm.style.display = "flex";
            unityBt.style.display="none";
        })
    })
</script>
