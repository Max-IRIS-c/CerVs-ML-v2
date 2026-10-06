<?php
$pageNum = 6;
include('../src/header.inc.php');

// tout sélectionner
$sQuery = 'SELECT conId,conNom,conPrenom,conHandicaper,conAccompagnant,conIntervenant,conNpa,conLocaliter,
                  conTel1,conTel2,conTel3,conTel4,conMail,conModif,conSociete
                  FROM tblContact 
          WHERE conStatu = 1 AND conId!=2146190079 
          ORDER BY conModif DESC';
$aResult = $db->query($sQuery);


?>

    <nav id="menu2">
        <ul> <?php if ($auth >=4){
                echo '<li class="textGauche"><a href="ajoutContact.php">'.$mrp->getText('Ajouter un contact').'</a></li>';
            }?>
            <?php if ($auth >=4 && $auth !=6){
                echo '<li class="textGauche"><a href="dons.php">'.$mrp->getText('Export des dons').'</a></li>';
            }?>

            <li class="textGauche"><a href="filtMarContact.php"><?=$mrp->getText('Filtre recherche marquage manuel'); ?></a></li>
            <li class="textGauche"><a href="filtContact.php"><?=$mrp->getText('Filtre recherche avancée'); ?></a></li>

        </ul>
    </nav>
    <h1><?=$mrp->getText('Liste des Adresses'); ?></h1>
    <form id="form-search" method="POST">
        <input id="tbl-search-val" class="tbl-search" type="text" placeholder="<?=$mrp->getText('Rechercher..'); ?>">
        <select id="tbl-search-col" class="tbl-search" name="search-3">
            <option value="">--</option>
            <option value="B"><?=$mrp->getText('Bénéficiaire') ?></option>
            <option value="A"><?=$mrp->getText('Accompagnant') ?></option>
            <option value="I"><?=$mrp->getText('Intervenant') ?></option>
        </select>
        <button class="btn-info btn-reset" type="reset"><?=$mrp->getText('Réinitialiser'); ?></button><br/><br/>
        <strong><?=$mrp->getText('Total') ?>:</strong><div id="tblcount-rows" class="tblcount-rows"><?php echo count($aResult); ?></div>
        <div class="tbl-scrolling">
            <table class="tbl-display scrolling" >
                <thead>
                <tr>
                    <th class="hide"></th>
                    <th><?=$mrp->getText('Nom') ?> </th>
                    <th><?=$mrp->getText('Prénom') ?> </th>
                    <th><?=$mrp->getText('Societé') ?> </th>
                    <th class="hide"></th>
                    <th><?=$mrp->getText('Psh') ?> </th>
                    <th><?=$mrp->getText('Acc') ?> </th>
                    <th><?=$mrp->getText('Int') ?></th>
                    <th><?=$mrp->getText('Npa') ?></th>
                    <th><?=$mrp->getText('Localité') ?></th>
                    <th><?=$mrp->getText('Tél. 1') ?></th>
                    <th><?=$mrp->getText('Dernière modif.') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach( $aResult as $aRow ) {?>
                    <tr>
                        <td id="conId_<?php echo $aRow['conId'];?>" class="hide">
                            <span class="hide"><?php echo $aRow['conId']?></span>
                        </td>
                        <td id="conNom_<?php echo $aRow['conId'];?>">
                            <?php echo $aRow['conNom']?>
                        </td>
                        <td id="conPrenom_<?php echo $aRow['conId'];?>">
                            <?php echo $aRow['conPrenom']?>
                        </td>
                        <td id="conPrenom_<?php echo $aRow['conId'];?>">
                            <?php echo $aRow['conSociete']?>
                        </td>
                        <td class="hide">
                            <span class="hide"><?php echo typeContact($aRow['conHandicaper'],$aRow['conAccompagnant'],$aRow['conIntervenant']);?></span>
                        </td>
                        <td>
                            <span class="hide"><?php echo $aRow['conHandicaper'];?></span>
                            <?php boolean($aRow['conHandicaper'],'conHandicaper') ?>
                        </td>
                        <td>
                            <span class="hide"><?php echo $aRow['conAccompagnant'];?></span>
                            <?php boolean($aRow['conAccompagnant'],'conAccompagnant') ?>
                        </td>
                        <td>
                            <span class="hide"><?php echo $aRow['conIntervenant'];?></span>
                            <?php boolean($aRow['conIntervenant'],'conIntervenant') ?>
                        </td>
                        <td id="conNpa_<?php echo $aRow['conId'];?>">
                            <?php echo $aRow['conNpa']?>
                        </td>
                        <td id="conLocaliter_<?php echo $aRow['conId'];?>">
                            <?php echo $aRow['conLocaliter']?>
                        </td>
                        <td id="conTel1_<?php echo $aRow['conId'];?>">
                            <span class="hide"><?php echo $aRow['conTel1'];?> <?php echo $aRow['conTel2'];?>
                                <?php echo $aRow['conTel3'];?> <?php echo $aRow['conTel4'];?> <?php echo $aRow['conMail']?></span>
                            <?php echo $aRow['conTel1']?>
                        </td>
                        <td id="conModif_<?php echo $aRow['conId'];?>">
                            <span class="hide"><?php echo $aRow['conModif'];?></span>
                            <?php echo dateToUser($aRow['conModif']);?>
                        </td>
                        <td>
                            <? echo '<a href="detContact.php?conId='.$aRow['conId'].'" class="btn-detail btn-small"> '.$mrp->getText('Détail').'</a>';?>
                        </td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </div>
    </form>

<?php
function typeContact($bene,$acc,$inter){
    $text='';
    if($bene==1){
        $text.='b ';
    }
    if($acc==1){
        $text.='a ';
    }
    if($inter==1){
        $text.='i ';
    }
    return $text;
}


include('../src/footer.inc.php'); ?>
<script src="../src/js/adminTable.js"></script>
