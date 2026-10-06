<?php
    include('../src/class/Db.class.php');
    include('./class/locationObject.php');
    $allTypePresta = LocationObject::getListOfTypePresta();
    $prestaToUpdate = intval($_GET['prestaToUpdate']) !== 0 ? intval($_GET['prestaToUpdate']) : null;
    $successUpdate = intval($_GET['success']) !== 0 ? $_GET['success'] : null;
    $errorUpdate = intval($_GET['err']) !== 0 ? $_GET['err'] : null;

    /******** [CREATE & UPDATE presta] click sur bouton annuler  ****/
    if((isset($_POST['submit-presta']) && $_POST['submit-presta'] === 'cancel') || 
        (isset($_POST['submit-add-presta']) && $_POST['submit-add-presta'] === 'cancel') ){
        header("Location: ./gestion-typePresta.php?prestaToUpdate=0");
    }
    /******** [CREATE presta] click sur bouton valider ****/
    if(isset($_POST['submit-add-presta']) && $_POST['submit-add-presta'] === 'validated'){
        try{
            $givenName = $_POST['prestaName'];
            if(!$givenName) throw new Exception();
            $db = new Db();
            $insertQuery = $db->query("INSERT INTO tblTypePresta (TprestaNom) VALUES ('$givenName')");
            if(!$insertQuery) throw new Exception();
            header("Location: ./gestion-typePresta.php?success=1");
        }catch(Exception $e){       
            header("Location: ./gestion-typePresta.php?err=1");
        }
    }
    /******** [UPDATE presta] click sur bouton valider ****/
    if(isset($_POST['submit-presta']) && $_POST['submit-presta'] === 'update-presta'){
        try{
            $idPresta = intval($_POST['idTypePresta']) !== 0 ? intval($_POST['idTypePresta']) : null;
            $prestaName = $_POST['nameTypePresta'];
            if(!$idPresta || $prestaName === '') throw new Exception();
            $db = new Db(); 
            $updateReq = $db->query("UPDATE tblTypePresta 
                                        SET TprestaNom = '$prestaName' 
                                        WHERE TprestaId = $idPresta  
                                    "); 
            if(!$updateReq) throw new Exception(); 
            header("Location: ./gestion-typePresta.php?success=1"); 
        }catch(Exception $e){ 
            header("Location: ./gestion-typePresta.php?err=1"); 
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
        </ul>
    </nav>
    <h1>Types de préstations</h1> 
    <button id="add-presta-bt">Ajouter un nouveau type de prestations</button> 
    <form id="add-presta"  method="post"> 
        <tr> 
            <input 
                type="text" 
                name="prestaName" 
                placeHolder="Nom du type de prestation" 
                style="width: 50%;" 
            /> 
            <div> 
                <button type="submit" name="submit-add-presta" value="validated" class="btnn green">Valider</button>
                <button type="submit" name="submit-add-presta" value="cancel" class="btnn red">Annuler</button> 
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
            foreach($allTypePresta as $presta){ 
                $idPresta = intval($presta['idTypePresta']);
                if($idPresta === 0) continue; ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="idTypePresta" value="<?php echo $presta['idTypePresta']; ?>"/>
                        <!-- input text si prestaToUpdate initialisé -->
                        <?php if($prestaToUpdate && $prestaToUpdate === $idPresta){ ?>
                            <td>
                                <input type="text" name="nameTypePresta" value="<?php echo $presta['nameTypePresta']; ?>"/>
                            </td>
                        <!-- text si prestaToUpdate pas initialisé -->
                        <?php } else { ?>
                            <td><?php echo $newName ? $newName : $presta['nameTypePresta']; ?></td>
                        <?php } ?>
                        <td>
                            <!-- boutons submit si prestaToUpdate initialisé -->
                            <?php if($prestaToUpdate && $prestaToUpdate === $idPresta){ ?>
                                <button type="submit" name="submit-presta" value="update-presta">Valider</button>
                                <button type="submit" name="submit-presta" value="cancel">Annuler</button>
                            <?php } else { ?>       
                            <!-- bouton "modifier" pour recharger la page avec prestaToUpdate initialisé -->
                                <a href="./gestion-typePresta.php?prestaToUpdate=<?php echo $presta['idTypePresta'] ?>">Modifier</a>
                            <?php } ?>
                        </td>
                    </form>
                </tr>
            <?php } ?>
    </table>
    <style>
        #add-presta{
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
        const prestaBt = document.getElementById('add-presta-bt')
        const prestaForm = document.getElementById('add-presta')

        prestaBt.addEventListener('click', () => {
            prestaForm.style.display = "flex";
            prestaBt.style.display="none";
        })
    })
</script>