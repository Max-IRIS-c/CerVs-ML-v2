<?php
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 30/08/2018
 * Time: 10:30
 */
include '../src/header.inc.php';
$nomDecompte = $mrp->getText('Conseil et support');

include 'menu.php'; ?>
    <h1><?= $nomDecompte?></h1>
    <br/><br/><br/>
    <!--
     somm = somme des heure consacrée
     tota = nombre de personne concernée
     handi = si la personne est handicapée (1) ou si c'est un proches (0)
     inte = le nombre d'intervention, visites
     -->
    <label> <?=$mrp->getText('Catégorie') ?></label>
    <select name="CatOfas" id="CatOfas">
        <option value="N/A">-></option>
        <option value="somm1,2&handi=1"><?=$mrp->getTextXL('Conseil social + aides aux handicapée Bref Conseil','CONSUP1') ?></option>
        <option value="somm3,4&handi=1"><?=$mrp->getTextXL('Conseil social + aides aux handicapée Conseil individuel','CONSUP2') ?></option>
        <option value="somm5,6&handi=1"><?=$mrp->getTextXL('Conseil social + aides aux handicapée Conseil en groupe','CONSUP3') ?></option>
        <option value="tota1,2,3,4,5,6&handi=1"><?=$mrp->getTextXL('Conseil social + aides aux handicapée Total nombre de handicapés','CONSUP4') ?></option>
        <option value="somm3,4&handi=0"><?=$mrp->getTextXL('Conseil aux proches et aux personnes de référence Conseil individuel','CONSUP5') ?></option>
        <option value="somm5,6&handi=0"><?=$mrp->getTextXL('Conseil aux proches et aux personnes de référence Conseil en groupe','CONSUP6') ?></option>
        <option value="tota1,2,3,4,5,6&handi=0"><?=$mrp->getTextXL('Conseil aux proches et aux personnes de référence Total nombre de personnes','CONSUP7') ?></option>
        <option value="somm7,8"><?=$mrp->getTextXL('Aide dans les lieux d\'accueil nombre d\'heure','CONSUP8') ?></option>
        <option value="inte7,8"><?=$mrp->getTextXL('Aide dans les lieux d\'accueil nombre de visite','CONSUP9') ?> ////</option>
        <option value="somm9,10"><?=$mrp->getTextXL('Conseil en matière de construcion bref conseil','CONSUP10') ?> </option>
        <option value="somm11,12"><?=$mrp->getTextXL('Conseil en matière de construcion bref conseil avec dossier','CONSUP11') ?> </option>
        <option value="tota11,12&handi=1"><?=$mrp->getTextXL('Conseil en matière de construcion Total nombre de personnes','CONSUP12') ?></option>
        <option value="somm13,14"><?=$mrp->getTextXL('Conseil juridique  bref conseil','CONSUP13') ?> </option>
        <option value="somm15,16"><?=$mrp->getTextXL('Conseil juridique bref conseil avec dossier','CONSUP14') ?> </option>
        <option value="tota15,16&handi=1"><?=$mrp->getTextXL('Conseil juridique Total nombre de personnes','CONSUP15') ?></option>
        <option value="somm17,18"><?=$mrp->getTextXL('Mise en relation avec des services d\'aide ou d\'interprèt nombre d\'heures','CONSUP16') ?> </option>
        <option value="inte17,18"><?=$mrp->getTextXL('Mise en relation avec des services d\'aide ou d\'interprèt nombre d\'intervention','CONSUP17') ?>///</option>
    </select>
    <div id="decompte"></div>


    <script>
        $.get( "ajax.decompteConSupp.php", function( data ) {
            $( "#decompte" ).html( data );
        });

        $("#CatOfas").change(function () {
            $.get( "ajax.decompteConSupp.php", { cat: $("#CatOfas").val() }, function( data ) {
                $( "#decompte" ).html( data );
            });
        });

    </script>

<?php
include_once '../src/footer.inc.php';