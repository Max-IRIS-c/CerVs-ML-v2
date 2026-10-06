
<script type="text/javascript" src="../jquery-3.1.1.min.js"></script>
<script type="text/javascript">

   $(function () {
        $("#submit").click(function () {
            // ne pas prendre en compte les select sans valeurs dans la requete finale
            // pour les <SELECT> : suppression du name si champs avec valeurs non-définie (value="no-data")
            deleteUnsetValuesOfSelects()
        })
    })
    function deleteUnsetValuesOfSelects(){
        const conAdulte = document.getElementById('conAdulte')
        const conAccompagnant = document.getElementById('conAccompagnant')
        const conIntervenant = document.getElementById('conIntervenant')
        const accompagnantParenthese = document.getElementById('accompagnantParenthese')
        const intervenantParenthese = document.getElementById('intervenantParenthese')
        const intervenantCA = document.getElementById('intervenantCA')
        const conMembre = document.getElementById('conMembre')
        const conTypeMembre = document.getElementById('conTypeMembre')
        const conMembLaPar = document.getElementById('conMembLaPar')
        const allSelects = [
            conAdulte, conAccompagnant, conIntervenant, accompagnantParenthese, intervenantParenthese,
            intervenantCA, conMembre, conTypeMembre, conMembLaPar
        ]
        allSelects.map((element) => deleteAttributeIfNoData(element))
    }
    function deleteAttributeIfNoData(element){ 
        console.log("el : ", element)
        console.log("val : ", element.value)
        if(element.value === "no-data") element.removeAttribute("name") 
    }
</script>
<?php

include ('../variables.php');
include ('../heade.php');
?>


<nav id="menu2">
    <ul>
        <li class="textGauche"><a href="contact.php"><?php echo $mrp->getText("Tous les contacts") ?></a></li>
        <li class="textGauche"><a href="filtMarContact.php"><?php echo $mrp->getText("Filtre recherche marquage manuel") ?></a></li>
    </ul>
</nav>
<h1><?php echo $mrp->getText("Recherche avancée") ?></h1>
<h2 id="erreurType" style="display: none; color: #ff9025;"><?php echo $mrp->getText("( ! un seul choix possible )") ?></h2>

<form method="post" action="./exportAvance.php" >
<!-- div de droit -->
    <div style="float: right; width: 49%">
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="4" style="background-color: #98FB98;color: #000000">Intéressé(e) aux cours / activités / services et autre</th>
            </tr>

                <tr>
                    <th colspan="2"><?php echo $mrp->getText("ACTIVITES") ?></th>
                    <th colspan="2"><?php echo $mrp->getText("SERVICES") ?></th>
                    <td></td>
                </tr>
                <tr>
                    <th>Terrifics</th>
                    <td><input type="checkbox" name="conGJ" value="1"></td>
                    <th><?php echo $mrp->getText("Aide à domicile") ?></th>
                    <td><input type="checkbox" name="conAideDomicile" value="1"></td>
                </tr>
                <tr>
                    <th><?php echo $mrp->getText("Week-ends") ?></th>
                    <td><input type="checkbox" name="conWK" value="1"></td>
                    <th><?php echo $mrp->getText("Service de relève") ?></th>
                    <td><input type="checkbox" name="conServiceReleve" value="1"></td>
                </tr>
                <tr>
                    <th><?php echo $mrp->getText("Camps") ?></th>
                    <td><input type="checkbox" name="conCAMP" value="1"></td>
                    <th><?php echo $mrp->getText("Contribution assistance") ?></th>
                    <td><input type="checkbox" name="conContribAssistant" value="1"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <th><?php echo $mrp->getText("Ecole") ?></th>
                    <td><input type="checkbox" name="conReleveScolaire" value="1"></td>
                </tr>
                <tr>
                    <th><?php echo $mrp->getText("Grp. Parents") ?></th>
                    <td><input type="checkbox" name="conGM" value="1"></td>
                    <th><?php echo $mrp->getText("UAT") ?></th>
                    <td><input type="checkbox" name="conUat" value="1"></td>
                </tr>
                <tr>
                    <th colspan="4"><?php echo $mrp->getText("Autres intérêts") ?></th>
                </tr>

                <tr>
                    <th><?php echo $mrp->getText("Client lotos") ?></th>
                    <td><input type="checkbox" name="conLoto" value="1"></td>
                </tr>

<table class="noMargin" style="border: solid 1px;">
            <tr>
                <th colspan="2" style="background-color: #98FB98;color: #000000"><?php echo $mrp->getText("Qualité de contributeur") ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Membre la parenthèse") ?></th>
                <td>
                    <select name="conMembLaPar" id="conMembLaPar">
                        <option value="no-data" selected></option>
                        <option value="null">Non</option>
                        <option value="0">NC</option>
                        <option value="1">Interessé</option>
                        <option value="2">Actif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Donateur la parenthèse"); ?> </th>
                <td><input id="membre4" type="checkbox" name="conDonatLaPar" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Membre Cerebral"); ?></th>
                <td>
                    <select name="conMembre" id="conMembre">
                        <option value="no-data" selected></option>
                        <option value="1">Actif</option>
                        <option value="2">Passif</option>
                        <option value="3">Collectif</option>
                        <option value="4">Non</option>
                    </select>    
                <td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Type")?></th>
                <td>
                    <select name="conTypeMembre" id="conTypeMembre">
                        <option value="no-data" selected></option>
                        <option value="1">Payant</option>
                        <option value="2">Tutelle</option>
                        <option value="3">Intéressé</option>
                        <option value="4">Soutiens</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Donateu") ?>r</th>
                <td><input type="checkbox" name="conDonateur" value="1"></td>
            </tr>

        </table>

                </table>

        <!-- Cadre filtre la parenth -->

        <table class="noMargin" style="border: solid 1px;">
            <tr>

            </tr>
            <tr>
                <th colspan="4"><?php echo $mrp->getText("Filtres")." la parenthèse <i>".$mrp->getText("(un seul choix possible)")."</i></th>"; ?>
            </tr>
            <tr>
                <td class="membre"><?php echo $mrp->getText("Adresse"); ?> la parenthèse</td>
                <td class="membre"><input id="membre1" type="checkbox" name="ConParenthese" value="1"></td>
            <tr>
                <td class="membre"><?php echo $mrp->getText("Client"); ?></td>
                <td class="membre"><input id="membre4" type="checkbox" name="conClieLaPar" value="1"></td>
            </tr>
        </table>

    </div>

    <!-- div de gauche -->
    <div style="width: 49%" >
        <table class="noMargin" style="border: solid 1px;">
            <tr>
                <th style="background-color: #98FB98;color: #000000" colspan="6"><?php echo $mrp->getText("Qualité du contact") ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Parent/Tuteur") ?></th>
                <td><input type="checkbox" name="conParent" value="1" ></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Personne en situation de handicap") ?></th>
                <td><input type="checkbox" name="conHandicaper" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Type")?></th>
                <td>
                    <select name="conAdulte" id="conAdulte">
                        <option value="no-data" selected></option>
                        <option value="1">Adulte</option>
                        <option value="0">Enfant</option>
                    </select>
                </td>
                <?php /*<td class="adulte"><?php echo $mrp->getText("Adulte") ?></td>
                <td class="adulte"><input id="adulte" type="checkbox" name="conAdulte" value="conAdulte"></td>
                <td class="adulte"><?php echo $mrp->getText("Enfant") ?></td>
                <td class="adulte"><input id="enfant" type="checkbox" name="conadulte " value="conadulte "></td>
                */ ?>    
            </tr>
            <tr>
                <th style="background-color: #98FB98;color: #000000" colspan="6"><?php echo $mrp->getText("Pour l'association") ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Comité") ?></th>
                <td><input type="checkbox" name="conComiter" value="1"> </td>
                <th><?php echo $mrp->getText("Employé") ?></th>
                <td><input type="checkbox" name="conEmploye" value="1"></td>
                <th><?php echo $mrp->getText("Bénévole") ?></th>
                <td><input type="checkbox" name="conBenevole" value="1"></td>
            </tr>
            <tr>
                <th colspan="4"><?php echo $mrp->getText("Disponible comme")?> </th>
            <tr>
            <tr>
                <th> <?php echo $mrp->getText("Accompagnant pour") ?>: </th>
                <td></td>
                <th><?php echo $mrp->getText("Intervenant pour") ?>:</th>
                <td></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Cerebral") ?></th>
                <td>
                    <select name="conAccompagnant" id="conAccompagnant">                        
                        <option value="no-data" selected></option>
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
                <th><?php echo $mrp->getText("Relève") ?></th>
                <td>
                    <select name="conIntervenant" id="conIntervenant">                        
                        <option value="no-data" selected></option>
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Parenthèse</th>
                <td>
                    <select name="accompagnantParenthese" id="accompagnantParenthese">   
                        <option value="no-data" selected></option>
                        <option value="">Non</option>
                        <option value="1">>Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
                <th>Parenthèse</th>
                <td>
                    <select name="intervenantParenthese" id="intervenantParenthese">   
                        <option value="no-data" selected></option>
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>             
                <th></th>
                <td></td>
                <th><?php echo $mrp->getText("Cont. Assist.") ?></th>
                <td>
                    <select name="intervenantCA" id="intervenantCA">   
                        <option value="no-data" selected></option>
                        <option value="">Non</option>
                        <option value="1">Oui</option>
                        <option value="0">Inactif</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="6"><?php echo $mrp->getText("Proche de l'association (partenaires et clients)"); ?></th>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Partenaire"); ?></th>
                <td><input type="checkbox" name="conEntreprise" value="1"></td>
                <th><?php echo $mrp->getText("Institution"); ?></th>
                <td><input type="checkbox" name="conInstitution" value="1"></td>
                <th><?php echo $mrp->getText("Association"); ?></th>
                <td><input type="checkbox" name="conAssociation" value="1"></td>
            <tr>
                <th><?php echo $mrp->getText("Ami, bienfaiteur"); ?></th>
                <td><input type="checkbox" name="conInviAmi" value="1"></td>
                <th><?php echo $mrp->getText("VIP"); ?></th>
                <td><input type="checkbox" name="conInviVip" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Médecin"); ?></th>
                <td><input type="checkbox" name="conMedecin" value="1"></td>
                <th><?php echo $mrp->getText("Assurance"); ?></th>
                <td><input type="checkbox" name="conAssurance" value="1"></td>
                <th><?php echo $mrp->getText("Autre"); ?></th>
                <td><input type="checkbox" name="conAutresTypes" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Client pavillons"); ?></th>
                <td><input type="checkbox" name="conClientPavillons" value="1"></td>
            </tr>
        </table>
        <table class="noMargin" style="border: solid 1px;">

            <tr><th colspan="2" style="background-color: #98FB98;color: #000000"><?php echo $mrp->getText("Abonnement") ?></th></tr>
            <tr>
                <th><?php echo $mrp->getText("Programme des activités"); ?></th>
                <td><input type="checkbox" name="conProgramme" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Bulletin CONNAITRE"); ?></th>
                <td><input type="checkbox" name="conConnaitre" value="1"></td>
            </tr>
            <tr>
                <th><?php echo $mrp->getText("Journal CEREBRAL Suisse"); ?></th>
                <td><input type="checkbox" name="conCerebral" value="1"></td>
            </tr>

        </table>
        <table>
            <tr>
                <td>
                    <input type="submit" id="submit" value="Valider" class="valider"></td>
                </td>
            </tr>
        </table>

    </div>



</form>



<?php
include ('../footer.php');
