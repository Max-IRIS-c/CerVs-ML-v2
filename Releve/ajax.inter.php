<?php
include "../variables.php";

$searchedParam = $_GET['searchedParam'];
$bdd = new PDO($dsn, $user, $password);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$region = $bdd ->query("SELECT * FROM tblRegionInter");

if($_GET['statu']=='aloIntA'){ $compatibilite = 'habituel'; }
elseif($_GET['statu']=='aloIntB'){ $compatibilite = 'occasionnel';}
elseif($_GET['statu']=='aloIntC'){ $compatibilite = 'dépannage';}

if (isset($_GET['statu'])) {
    $intStat = $_GET['statu'];
} else {
    $intStat = "aloIntA";
    $compatibilite = 'habituel';
}

// si le statu est égale à "Recherche" les informations sont caché et une liste déroutante est affichée
// la nouvelle liste déroulante avec le nom des intervenant ce trouve dans ajax.interFiltre

if ($_GET['statu'] != 'autres') {
    try{
        $status = $_GET['statu'];
        $benef = $_GET['Id'];
        $intervenantReq = $bdd->prepare("SELECT * FROM tblAlocIntervenant WHERE aloIntBenefic = :beneficiaire");
        $intervenantReq->execute(array(
                'beneficiaire' => $benef));
        $intervenantReq = $intervenantReq->fetch();
        $intervenant = $intervenantReq[$status];
        if (!$intervenant){
            $textItervenant = "L'intervenant $compatibilite n'a pas encore été défini";
        }
        else
        {
            $contact = $bdd->query("SELECT * FROM tblContact WHERE conId='$intervenant'");
            $contact = $contact->fetch();
            $textItervenant= 'Info concernant l\'intervenant'.' '.$compatibilite.' '. $contact['conNom'] . ' ' . $contact['conPrenom'];
        }
    }catch(Exception $e){
        $textItervenant = '...';
    }




    $diponibiliter1 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion1 = regId WHERE tblContact_conId = '$intervenant'");
    $diponibiliter1 = $diponibiliter1->fetch();

    $diponibiliter2 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion2 = regId WHERE tblContact_conId = '$intervenant'");
    $diponibiliter2 = $diponibiliter2->fetch();

    $diponibiliter3 = $bdd->query("SELECT regNom FROM tblDiponibiliter
LEFT JOIN tblRegionInter on dispRegion3 = regId WHERE tblContact_conId = '$intervenant'");
    $diponibiliter3 = $diponibiliter3->fetch();

    $infoGeneral = $bdd->query("SELECT * FROM tblDiponibiliter WHERE tblContact_conId ='$intervenant'");
    $infoGeneral = $infoGeneral->fetch(PDO::FETCH_ASSOC);

    ?>
    <div style="margin-top:-47px;margin-left:200px;" id="infoIntervenant2">
        <?php echo '<a class="modif"  href="modifIntervenant.php?beneficiaire='.$_GET['Id'].'&statu='.$intStat.'&searchedParam='.$searchedParam.'"> Modifier  </a>';?>
    </div>
    <h1><?php echo $textItervenant ?></h1>
    
        <table style="margin-bottom: 10px;padding-bottom: 0px;" class="affichage">
            <tr>
                <th colspan="2"> Coordonnées</th>
            </tr>
            <tr>
                <td>e-mail:</td>
                <td><?php echo $contact['conMail']; ?></td>
            </tr>
            <tr>
                <td>Tel. mobile</td>
                <td><?php echo $contact['conNathl']; ?></td>
            </tr>
            <tr>
                <td>Tel. privé</td>
                <td><?php echo $contact['conTelPriver']; ?></td>
            </tr>
            <tr>
                <td>Tel. professionnel</td>
                <td><?php echo $contact['conTelProf']; ?></td>
            </tr>

        </table>
        <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
            <tr>
                <th style="width: 80px;"> Région</th>
                <th> Profil, expériences et compétences</th>
                <th>Commentaires</th>
            </tr>
            <tr>
                <!-- Région -->
                <td style="width: 80px; vertical-align: top;"><?php echo $diponibiliter1[0]; ?></td>
                <!-- profil, experiences et compétences -->
                <td style="vertical-align: top;"><?php if ($infoGeneral['dispExperiance'] == "NULL") {
                        echo "";
                    } else {
                        echo $infoGeneral['dispExperiance'];
                    } ?>
                </td>
                    <!-- commentaires -->
                <td  style="vertical-align: top; width: 200px;"><?php if ($infoGeneral['dispReference'] == "NULL") {
                        echo "";
                    } else {
                        echo $infoGeneral['dispReference'];
                    } ?>
                </td>
            </tr>
        </table>
        <?php /*div id="zoneDroite">

            <table class="affichage">
                <tr>
                    <td></td>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>
                <tr>
                    <td>Matin (07-12)</td>
                    <td> <?php CheckBox($infoGeneral['dispLMatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMmatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMEmatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispJmatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispVmatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispSmatin']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispDmatin']); ?></td>

                </tr>
                <tr>
                    <td>Midi (repas)</td>
                    <td><?php CheckBox($infoGeneral['dispLMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMEMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispJMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispVMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispSMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispDMidi']); ?></td>
                </tr>
                <tr>
                    <td>Après-midi</td>
                    <td><?php CheckBox($infoGeneral['dispLaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMEaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispJaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispVaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispSaMidi']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispDaMidi']); ?></td>

                </tr>
                <tr>
                    <td>Soir (repas)</td>
                    <td><?php CheckBox($infoGeneral['dispLSouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMSouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMESouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispJSouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispVSouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispSSouper']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispDSouper']); ?></td>
                </tr>
                <tr>
                    <td>Soirée</td>
                    <td><?php CheckBox($infoGeneral['dispLSoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMSoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispMESoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispJSoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispVSoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispSSoir']); ?></td>
                    <td><?php CheckBox($infoGeneral['dispDSoir']); ?></td>

                </tr>
            </table>
        </div */ ?>
<?php }else{
?>
    <form>
    <table style="padding-bottom:0px; ">
        <tr>
            <th> Zone d'intervention</th>

        </tr>
        <tr>
            <td>
    <select name="" id="RechercheRegion">
        <option value="N/A"> Toutes régions</option>
        <?php  ListeDeroulante($region,'regId','regNom')?></select>
            </td>
            <td>

            </td>
        </tr>
    </table>
    </form>
    <table style="margin-bottom: 0px; padding-bottom: 0px">
        <tr>
             <th>Intervenant</th>
        </tr>
    </table>
     <div id="intervenant"></div>


        <?
}
?>

<script src="../jquery-3.1.1.min.js"></script>

<script>

    $.get("ajax.interFiltre.php", function (data) {
        $("#intervenant").html(data);
    });

    $("#RechercheRegion").change(function () {
        $.get("ajax.interFiltre.php", {lieu: $("#RechercheRegion").val()}, function (data) {
            $("#intervenant").html(data);
        });
    });
</script>
<style>
    #infoIntervenant2{
        display: flex;
        align-items: center;
        justify-content: space-around;
    }
</style>
