<?php include('../header.php');
$id = $_GET["conId"];
$_SESSION['contact'] = $id;
$bdd = new PDO($dsn, $user, $password);
$contact = $bdd->query("SELECT * FROM tblContact
LEFT JOIN tblCiviliter on tblCiviliter_civId = civId
LEFT JOIN tblMembre on conMembre = memId
LEFT JOIN tblMembreType on conTypeMembre = mTypId
LEFT JOIN tblangues on conLangues = lanId
LEFT JOIN tblRegionCon on conRegion = regConId
LEFT JOIN tblEtatCivile on conEtatcivil = etaCivId
LEFT JOIN tblPermisSejour on conPermisejour = pSejourId
LEFT JOIN tblNationaliter on conNationalite = natId WHERE
conId ='$id'");
$contact = $contact->fetch();
$tel1 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel1T]'");
$tel1 = $tel1->fetch();
$tel2 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel2T]'");
$tel2 = $tel2->fetch();
$tel3 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel3T]'");
$tel3 = $tel3->fetch();
$tel4 = $bdd->query("SELECT tTelNom FROM tblTypeTelephone WHERE tTelId ='$contact[conTel4T]'");
$tel4 = $tel4->fetch();
$don = $bdd->query("SELECT max(cotiDate) FROM tblCotisation where tblContact_conId = $id");
$don = $don->fetch();

if (isset($_POST['Valider'])) // Si le formulaire a été validé
{
    $marquage = $_POST['Marquage'];


    $insert = $bdd->prepare("UPDATE tblContact SET
conMarquage=:marquage

 WHERE conId = '$id'");

    $insert->execute(array(
        'marquage' => $marquage,

    ));


    header("location: detContacte.php?conId=" . $id);
}
?>
<!-- le menu vas être gérer par le PHP selon critaire à regarder avec cerebral -->

<nav id="menu2">
    <ul>
        <li class="textGauche"><?php echo '<a href="contact.php?conId=' . $id . '">'.$mrp->getText('Tous les contacts').'</a>'; ?></li>
        <?php if ($auth >= 3) { ?>

            <li class="textGauche"><?php echo '<a href="modifContact.php?conId=' . $id . '">'.$mrp->getText('Modifier').'</a>'; ?></li>
        <?php }

        if ($auth >= 4)  { ?>
            <li class="textGauche"><?php echo '<a href="supContact.php?Id=' . $id . '"> '.$mrp->getText('Supprimer').'</a>'; ?></li>
            <?php if ($contact['conTypeMembre']>=1){?>
                <li><?php echo '<a href="cotisation.php?Id=' . $id . '"> '.$mrp->getText('Contributions').'</a>'; ?></li>

            <?php } ?>


        <?php }

        if (($contact['conAccompagnant'] == 1) AND ($contact['conSecondaire'] == 0) AND $auth >=3) { ?>
            <li class="textGauche"><?php echo '<a href="contratAcc.php?Id=' . $id . '">'.$mrp->getText('Contrat accompagnant').'</a>'; ?></li>
        <?php }
        if (($contact['conHandicaper'] == 1) AND ($contact['conSecondaire'] == 0)) { ?>
            <li class="textGauche"><?php echo '<a href="../social/social.php?Id=' . $id . '">'.$mrp->getText('Infos. sociales').'</a>'; ?></li>
            <li class="textGauche"><?php echo '<a href="../medical/medical.php?Id=' . $id . '">'.$mrp->getText('Infos. médicales').'</a>'; ?></li>
            <?php if ($auth >=3 ){

                echo '<li><a href="../Releve/releve.php?Id=' . $id . '"> Service de relève</a> </li>';
            }?>

        <?php }
        if (($contact['conIntervenant'] == 1) AND ($contact['conSecondaire'] == 0)AND $auth >=3) { ?>
            <li class="textGauche"><?php echo '<a href="../intervention/disponibilite.php?Id=' . $id . '">'.$mrp->getText('Disponibilités').'</a>'; ?></li>
            <li class="textGauche"><?php echo '<a href="../intervention/intervention.php?Id=' . $id . '"> '.$mrp->getText('Interventions').'</a>'; ?></li>
            <li class="textGauche"><?php echo '<a href="contrat.php?Id=' . $id . '"> '.$mrp->getText('Contrat intervenant').'</a>'; ?></li>
        <?php } ?>


    </ul>
</nav>

<!-- Début colonne gauche-->
<div id="cordonee" style="width: 48%; float: left;">
    <h2><?php echo $mrp->getText("Coordonnées du contact") ?></h2>
    <table class="noMargin" style="border: solid 1px; width: 100%">
        <tr>
            <th><?php echo $mrp->getText("Titre") ?></th>
            <td><?php echo $contact['civNom'] ?></td>
            <th><?php echo $mrp->getText("Adresse secondaire") ?></th>
            <td><?php CheckBox($contact['conSecondaire']); ?></td>

        </tr>
        <tr>
            <th><?php echo $mrp->getText("Nom") ?></th>
            <td><?php echo $contact['conNom'] ?></td>
            <th><?php echo $mrp->getText("Prénom") ?></th>
            <td><?php echo $contact['conPrenom'] ?></td>


        </tr>

        <tr>
            <th><?php echo $mrp->getText("Complément") ?></th>
            <td colspan="3"><?php echo $contact['conComplement'] ?></td>

        </tr>
        <tr>
            <th><?php echo $mrp->getText("Adresse") ?></th>
            <td colspan="3"><?php echo $contact['conAdresse'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Adresse2") ?></th>
            <td colspan="3"><?php echo $contact['conAdresse2'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("NPA") ?></th>
            <td><?php echo $contact['conNpa'] ?></td>
            <th><?php echo $mrp->getText("Localité") ?></th>
            <td><?php echo $contact['conLocaliter'] ?></td>
        </tr>

        <tr>
            <th><?php echo $tel1[0] ?></th>
            <td><?php echo $contact['conTel1'] ?></td>
            <th><?php echo $tel2[0] ?></th>
            <td><?php echo $contact['conTel2'] ?></td>
        </tr>
        <tr>
            <th><?php echo $tel3[0] ?></th>
            <td><?php echo $contact['conTel3'] ?></td>
            <th><?php echo $tel4[0] ?></th>
            <td><?php echo $contact['conTel4'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("e-mail") ?></th>
            <td colspan="3"><?php echo $contact['conMail'] ?></td>
        </tr>

    </table>
    <h2><?php echo $mrp->getText("Infos diverses sur le contact") ?></h2>
    <table class="noMargin" style="border: solid 1px">
        <tr>
            <th><?php echo $mrp->getText("Date de naissance") ?></th>
            <td><?php echo dateToUser($contact['conDateNaissance']) ?></td>
            <th><?php echo $mrp->getText("Numéro AVS") ?></th>
            <td><?php echo $contact['conAvs']; ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Résident dans la région") ?></th>
            <td><?php echo $contact['regConNom']; ?></td>
            <th><?php echo $mrp->getText("Parlant (langue)") ?></th>
            <td><?php echo $contact['lanNom']; ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Création du contact") ?></th>
            <td><?php echo dateToUser($contact['conCreation']) ?></td>
            <th><?php echo $mrp->getText("Modifié le") ?></th>
            <td><?php echo dateToUser($contact['conModif']) ?></td>
        </tr>
    </table>
    <h2>Infos spécifiques accompagnant</h2>
    <table class="noMargin" style="border: solid 1px; width:100%;">
        <tr>
            <th><?php echo $mrp->getText("Permis de conduire voiture (B)") ?></th>
            <td><?php CheckBox($contact['conPermisB']) ?></td>
            <th>Bus 8-16 pl (D1)</th>
            <td><?php CheckBox($contact['conPermiD1']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Etat civil") ?></th>
            <td><?php echo $contact['eatCivNom'] ?></td>

        </tr>
        <tr>
            <th><?php echo $mrp->getText("Nationalité") ?></th>
            <td><?php echo $contact['natNom'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Permis de séjour") ?></th>
            <td><?php echo $contact['pSejourNom'] ?></td>
            <th> <?php echo $mrp->getText("Validité") ?></th>
            <td><?php echo dateToUser($contact['conValidPermSejour']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Banque") ?></th>
            <td colspan="5"><?php echo $contact['conBanque'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Agence de") ?></th>
            <td colspan="5"><?php echo $contact['conAgence'] ?> </td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("IBAN/cpte") ?></th>
            <td colspan="5"><?php echo $contact['conIban'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Responsable") ?></th>
            <td><?php CheckBox($contact['conResponsable']) ?></td>
            <th><?php echo $mrp->getText("Co-responsable") ?></th>
            <td><?php CheckBox($contact['conCoResponsable']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Accompagnant chauffeur") ?></th>
            <td><?php CheckBox($contact['conAccompagnantChauffeur']) ?></td>
            <th><?php echo $mrp->getText("Resp. des soins") ?></th>
            <td><?php CheckBox($contact['conInfirmiere']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Cuisinier") ?></th>
            <td><?php CheckBox($contact['conCuisinier']) ?></td>
            <th><?php echo $mrp->getText("Intendant (e)") ?></th>
            <td><?php CheckBox($contact['conIntendant']) ?></td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <th> <?php echo $mrp->getText("Commentaire(s)") ?></th>
        </tr>
        <tr>
            <td colspan="3"><?php echo nl2br($contact['conCommentaire']) ?></td>
        </tr>

    </table>
</div>
<!-- Début colonne droite-->
<div id="infoDroit" style="width: 48%; margin-left: 52%;">
    <h2><?php echo $mrp->getText("Qualité de contact") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Parent/ tuteur") ?></th>
            <td><?php CheckBox($contact['conParent']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Personne en situation de handicap") ?></th>
            <td><?php CheckBox($contact['conHandicaper']); ?></td>
            <td><?php if ($contact['conAdulte'] == 1) {
                    echo " ".$mrp->getText('Adulte');
                } else {
                    echo $mrp->getText('Enfant');
                } ?></td>
        </tr>
    </table>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th colspan="6"><?php echo $mrp->getText("Pour l'association") ?></th>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Comité") ?></th>
            <td><?php CheckBox($contact['conComiter']); ?></td>
            <th><?php echo $mrp->getText("Employé") ?></th>
            <td><?php CheckBox($contact['conEmploye']); ?></td>
            <th><?php echo $mrp->getText("Bénévole") ?></th>
            <td><?php CheckBox($contact['conBenevole']); ?></td>
        </tr>
        <th><?php echo $mrp->getText("Accompagnant") ?></th>
        <td><?php CheckBox($contact['conAccompagnant']); ?></td>
        <th><?php echo $mrp->getText("Intervenant") ?></th>
        <td><?php CheckBox($contact['conIntervenant']); ?></td>
        <tr>
            <th colspan="6">Proche de l'association (partenaires et clients)</th>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Partenaire") ?></th>
            <td><?php CheckBox($contact['conEntreprise']); ?></td>
            <th><?php echo $mrp->getText("Institution") ?></th>
            <td><?php CheckBox($contact['conInstitution']); ?></td>
            <th><?php echo $mrp->getText("Association") ?></th>
            <td><?php CheckBox($contact['conAssociation']); ?></td>
        <tr>
            <th><?php echo $mrp->getText("Ami, bienfaiteur") ?></th>
            <td><?php CheckBox($contact['conInviAmi']); ?></td>
            <th><?php echo $mrp->getText("VIP") ?></th>
            <td><?php CheckBox($contact['conInviVip']); ?></td>

        </tr>
        <tr>
            <th><?php echo $mrp->getText("Médecin") ?></th>
            <td><?php CheckBox($contact['conMedecin']); ?></td>
            <th><?php echo $mrp->getText("Assurance") ?></th>
            <td><?php CheckBox($contact['conAssurance']); ?></td>
            <th><?php echo $mrp->getText("Autre") ?></th>
            <td><?php CheckBox($contact['conAutresTypes']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Client pavillons") ?></th>
            <td><?php CheckBox($contact['conClientPavillons']); ?></td>
        </tr>

    </table>
    <h2><?php echo $mrp->getText("Qualité de contributeur") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Membre") ?></th>
            <td><?php echo $contact['memNom'] ?></td>
            <th><?php echo $mrp->getText("Type") ?></th>
            <td><?php echo $contact['mTypNom'] ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Donateur") ?></th>
            <td><?php CheckBox($contact['conDonateur']); ?></td>
            <th><?php echo $mrp->getText("Dernier don") ?></th>
            <td> <?php echo dateToUser($don[0]) ?></td>
        </tr>
        <tr>
            <th colspan="3"><?php echo $mrp->getText("Membre d'honneur de l'association") ?></th>
            <td><?php CheckBox($contact['conMembHoneur']); ?></td>
        </tr>
    </table>
    <h2><?php echo $mrp->getText("Abonnement") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th><?php echo $mrp->getText("Programme des activités") ?></th>
            <td><?php CheckBox($contact['conProgramme']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Bulletin CONNAITRE") ?></th>
            <td><?php CheckBox($contact['conConnaitre']); ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Journal CEREBRAL Suisse") ?></th>
            <td><?php CheckBox($contact['conCerebral']); ?></td>
        </tr>

    </table>
    <h2><?php echo $mrp->getText("Intéressé(e) aux cours / activités / services et autre") ?></h2>
    <table class="noMargin" style="border: solid 1px;">
        <tr>
            <th colspan="2"><?php echo $mrp->getText("ACTIVITES") ?></th>
            <th colspan="2"><?php echo $mrp->getText("SERVICES") ?></th>
            <td></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Terrifics") ?></th>
            <td><?php CheckBox($contact['conGJ']) ?></td>
            <th><?php echo $mrp->getText("Aide à domicile") ?></th>
            <td><?php CheckBox($contact['conAideDomicile']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Week-ends") ?></th>
            <td><?php CheckBox($contact['conWK']) ?></td>
            <th><?php echo $mrp->getText("Service de relève") ?></th>
            <td><?php CheckBox($contact['conServiceReleve']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Camps") ?></th>
            <td><?php CheckBox($contact['conCAMP']) ?></td>
            <th><?php echo $mrp->getText("Contribution assistance") ?></th>
            <td><?php CheckBox($contact['conContribAssistant']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <th><?php echo $mrp->getText("Ecole") ?></th>
            <td><?php CheckBox($contact['conReleveScolaire']) ?></td>
        </tr>
        <tr>
            <th><?php echo $mrp->getText("Grp. Parents") ?></th>
            <td><?php CheckBox($contact['conGM']) ?></td>
            <th><?php echo $mrp->getText("UAT") ?></th>
            <td><?php CheckBox($contact['conUat']) ?></td>
        </tr>
        <tr>
            <th colspan="4">Autres intérêts</th>
        </tr><!-- Affiche le champs marquage si le contact est un employé,un handicapé,un accompagnant ou un intervenant-->
        <?php if (($contact['conHandicaper'] == 1) OR ($contact['conAccompagnant'] == 1)
            Or ($contact['conIntervenant'] == 1)Or ($contact['conEmploye'] == 1))
        {?>
            <form action="#" method="post">
                <tr>
                    <th><?php echo $mrp->getText("Client loto") ?></th>
                    <td><?php CheckBox($contact['conLoto']) ?></td>
                    <th><?php echo $mrp->getText("Marquage manuel") ?></th>

                    <td><input style="width: 20mm" value="<?php echo $contact['conMarquage'] ?>" name="Marquage"><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td><input style="width: 20mm" type="submit" value="Valider" name="Valider" class="ValiderPetit"></td>
                </tr>
            </form>
       <? }?>

            <tr>
                <th><?php echo $mrp->getText("Client loto") ?></th>
                <td><?php CheckBox($contact['conLoto']) ?></td>
            </tr>
    </table>
</div>


<?php include '../footer.php'; ?>