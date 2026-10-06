<?php

$pageNum = 34;

include('../variables.php');
include('./class/locationObject.php');
$givenId = $_GET['Id'] ? intval($_GET['Id']) : null;
$locationObject = ($givenId && $givenId !== 0) ? new LocationObject($_GET['Id']) : null;
include('../heade.php'); 
?>
    <nav>
        <ul>
            <li class="textGauche"><?php echo '<a href="modifPavillon.php?Id=' . $givenId . '">'.$mrp->getText('Modifier').'</a>'; ?></li>
            <li class="textGauche"><?php echo '<a href="Location.php?Id=' . $givenId . '">'.$mrp->getText('Liste des locations').'</a>'; ?></li>
        </ul>
    </nav>
    <h1><?php echo $mrp->getText('Informations')." ".$mrp->getText('pour') ." ".$mrp->getText('objet')." : ".$locationObject->name;?></h1>
    <table>
        <tr>
            <th><?= $mrp->getText('Type') ?></th>
            <td><?php echo $locationObject->type === 1 ? 'Objet immobilier' : ($locationObject->type === 2 ? 'Véhicule' : 'Autre'); ?></td>
        </tr>
        <tr>
            <th><?= $mrp->getText('Nom') ?></th>
            <td><?php echo $locationObject->name; ?></td>
        </tr>
        <tr>
            <th><?= $mrp->getText('Propriétaire') ?></th>
            <td><?php echo $locationObject->propriety; ?></td>
        </tr>
        <tr>
            <th><?= $mrp->getText('Nombre de places') ?></th>
            <?php echo $locationObject->type === 2 ? '<td>Piétons</td>' : '<td>Min.</td>'; ?>
            <td><?php echo $locationObject->personMin; ?></td>     
            <?php echo $locationObject->type === 2 ? '<td>Chaises</td>' : '<td>Max.</td>'; ?>
            <td><?php echo $locationObject->personMax; ?></td>
        </tr>
        <?php 
            foreach($locationObject->prices as $price){
                echo '<tr>';
                echo '<th>'.$price['label'].'</th>';
                echo '<td>'.$price['price'].'</td>';
                echo '<td>'.$price['priceUnity'].'</td>';
                echo '</tr>';
            }
        ?>
        <tr>
            <th><?= $mrp->getText('Couleur') ?></th>
            <td>
                <input type="color" name="logCouleur" value="<?php echo $locationObject->color; ?>" />
            </td>
        </tr>
        <tr>
            <th><?= $mrp->getText('Couleur de la police') ?></th>
            <td>
                <?php echo $locationObject->fontColor === 'black' ? 'Noir' : 'Blanc' ; ?>
            </td>
        </tr>
        <?php if($locationObject->type === 2){ ?>
            <tr>
                <th><?= $mrp->getText('Immatriculation') ?></th>
                <td><?php echo $locationObject->immatriculation; ?></td>
            </tr>
        <?php } ?>
    </table>


<?php include('../footer.php');