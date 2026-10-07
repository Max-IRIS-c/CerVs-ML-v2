<?php
include('../variables.php');
include_once('../src/fonctionsSql.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];

$responsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conResponsable =1  order by conNom ASC ");
$Coresponsable = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conCoResponsable =1 order by conNom ASC ");
$cuisiniere = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conCuisinier =1 order by conNom ASC");
$infirmier = $bdd->query("SELECT conId,conNom,conPrenom FROM tblContact WHERE conStatu = 1 AND conInfirmiere =1 order by conNom ASC");
$type = $bdd->query("SELECT * FROM tblTypeActivite");
$CodeOFas = $bdd->query("SELECT * FROM tblTraCat1 WHERE cat1Id BETWEEN 21 AND 24");
$CodeTypeOfas = $bdd->query("SELECT * FROM tblOfasType ");
$CodeCatOfas = $bdd->query("SELECT * FROM tblOfasCategorie ");

$activite = $bdd->query("SELECT * FROM tblActivites LEFT JOIN tblTypeActivite on actType = tActId WHERE actId = '$id'");
$activite = $activite->fetch();



if (isset($_POST['Valider'])) {
	
    if (($_POST['Type'] == 1)OR ($_POST['Type']== 3 )){
        $Ofas = 21;
    }
    elseif (($_POST['Type'] == 2)OR ($_POST['Type']== 4 )){
        $Ofas = 23;
    }
    $insert = $bdd->prepare("UPDATE tblActivites SET
        actNom =:actNom, 
        actTheme =:actTheme,
        actFin =:actFin,
        actDebut =:actDebut,
        actDec =:actDec,
        actType =:actType,
        actLieu =:actLieu,
        actResponsable =:responsable,
        actCoResponsable=:coresponsable,
        actCuisiniere =:actCuisiniere,
        actInfirmier =:actinfirmier,
        actCodeOfas = :Ofas,
        actOfaCat = :catOfas,
        actOfasType = :typeOfas
        WHERE actId =  $id
    ");

    $insert->execute(array(
        'actNom' => $_POST['Nom'],
        'actTheme' => $_POST['Theme'],
        'actFin' => dateOuNull($_POST['Fin']),
        'actDebut' => dateOuNull($_POST['Debut']),
        'actDec' => $_POST['editeur'],
        'actType' => intOuZero($_POST['Type']),
        'actLieu' => $_POST['Lieu'],
        'responsable' => intOuNull($_POST['Responsable']),
        'coresponsable' => intOuNull($_POST['CoResponsable']),
        'actCuisiniere' => intOuNull($_POST['Cuissinier']),
        'actinfirmier' => intOuZero($_POST['Infirmier']),
        'Ofas' => intOuNull($_POST['CodeOfas']),
        'catOfas' => intOuNull($_POST['codeCatOfas']),
        'typeOfas' => intOuNull($_POST['codeTypeOfas'])

    ));

   header("location: participants.php?Id=".$id );
}

include('../heade.php');
?>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>

    <nav>
        <ul> <?php if($auth >=3){?>
            <li><?php echo '<a href="supprimerActiviter.php?id='.$id.'">'.$mrp->getText("Supprimer l'activité").'</a>'; ?></li>
    <?php }?>
            <li><?php echo '<a href="modifParticipant.php?id='.$id.'">'.$mrp->getText("Modification participants").'</a>'?></li>
        </ul>
    </nav>
    <h1><?= $mrp->getText("Modification activité") ?> : <?php echo $activite['actNom'] ?></h1>
    <form  method="post">
        <table>
            <tr>
                <th><?= $mrp->getText("Type d'activité") ?></th>
                <th><?= $mrp->getText("Nom de l'activité") ?></th>
                <th><?= $mrp->getText("Date de début") ?></th>
                <th><?= $mrp->getText("Date de fin") ?></th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="Type"><?php ListeModif($type, $activite['actType'], 'tActId', 'tActNom') ?></select></td>
                <td ><input style="width: 238px;" name="Nom" value="<?php echo $activite['actNom'] ?>"></td>
                <td><input class="input150" type="Date" name="Debut" value="<?php echo $activite['actDebut'] ?>"></td>
                <td><input class="input150" type="Date" name="Fin" value="<?php echo $activite['actFin'] ?>"></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?= $mrp->getText("Lieu") ?></th>
                <th colspan="3"><?= $mrp->getText("Thème") ?></th>
            </tr>
            <tr>
                <td><input style="width: 238px;" name="Lieu" VALUE="<?php echo $activite['actLieu']; ?>"></td>
                <td colspan="3"><input style="width: 730px;" name="Theme" VALUE="<?php echo $activite['actTheme']; ?>"></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?= $mrp->getText("Code Ofas") ?></th>
                <th><?= $mrp->getText("Type de cours") ?></th>
                <th><?= $mrp->getText("Critères d'attribution") ?></th>
            </tr>
            <tr>
                <td><select style="width: 238px;" name="CodeOfas">
                        <option> -></option><?php ListeModif2($CodeOFas, $activite['actCodeOfas'],'cat1Id', 'cat1Code','cat1Nom') ?>
                    </select></td>

                <td><select style="width: 238px;" name="codeTypeOfas">
                        <option> -></option><?php  ListeModif($CodeTypeOfas,$activite['actOfasType'], 'ofaTypId', 'ofaTypNom') ?>
                    </select></td>
                <td><select style="width: 238px;" name="codeCatOfas">
                        <option> -></option><?php ListeModif($CodeCatOfas,$activite['actOfaCat'], 'ofaCatId', 'ofaCatNom') ?>
                    </select></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th><?= $mrp->getText("Responsable") ?></th>
                <th><?= $mrp->getText("Co-Responsable") ?></th>
                <th><?= $mrp->getText("Cuisinier") ?></th>
                <th><?= $mrp->getText("Resp. des soins") ?></th>
            </tr>

            <tr>
                <td><select style="width:238px;" name="Responsable" ><?php ListeModif2($responsable,$activite['actResponsable'],'conId','conNom','conPrenom')?></select></td>
                <td><select style="width:238px;" name="CoResponsable"><?php ListeModif2($Coresponsable,$activite['actCoResponsable'],'conId','conNom','conPrenom')?></select></td>
                <td><select style="width:238px;" name="Cuissinier" ><?php ListeModif2($cuisiniere,$activite['actCuisiniere'],'conId','conNom','conPrenom')?></select></td>
                <td><select style="width:239px;" name="Infirmier" ><?php ListeModif2($infirmier,$activite['actInfirmier'],'conId','conNom','conPrenom')?></select></td>
            </tr>
            <tr>
                <td style="padding-bottom: 10px"></td>
            </tr>
            <tr>
                <th COLSPAN="6"><?= $mrp->getText("Description de l'activité") ?></th>
            </tr>
            <tr>
                <td colspan="6" >
                    <textarea cols="75" class="ckeditor" id="editeur" name="editeur" rows="10"><?php echo $activite['actDec'] ?></textarea>

            </tr>
            <tr>
                <td><input type="submit" value="Valider" name="Valider" class="ValiderPetit"></td>
            </tr>
        </table>
    </form>
    <table>
        <tr>
            <td><?= $mrp->getText("Photo") ?></td>
        </tr>
        <tr>
            <td style="border: solid 1px;">
                <?php

                $photo1 = "img/$id-photo1.jpg";

                if (file_exists($photo1)) { ?>

                    <img src="img/<?php echo $id;?>-photo1.jpg" style="width: 150px;"> <?php
                }
                else
                {
                    echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=photo1&src=Modif"> Ajouter une photo </a>';
                }

                ?>

            </td>
        </tr>
        <tr>
            <td>
               <? if (file_exists($photo1)) {

    echo '<a class="valider" href="ajoutPhoto.php?Id=' . $id . '&genre=photo1&src=Modif">'.$mrp->getText("Modifier la photo").'</a>';
                }
                ?>

            </td>
        </tr>
    </table>
<?php
include('../footer.php');