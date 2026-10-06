<?php
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 30/08/2018
 * Time: 10:30
 */
include '../src/header.inc.php';
$nomDecompte = 'Type de cours';

include 'menu.php'; ?>
    <h1><?= $nomDecompte?></h1>
    <br/><br/><br/>
    <!--
     somm = somme des heure consacrée
     tota = nombre de personne concernée
     handi = si la personne est handicapée (1) ou si c'est un proches (0)
     inte = le nombre d'intervention, visites
     -->
    <label> Catégorie</label>
    <select name="CatOfas" id="CatOfas">
        <option value="N/A">-></option>
        <option value="totaCours">Nombre de cours</option>
        <option value="totaParti">Nombre de participants</option>
        <option value="tota1&handi=1">Bénéficiaire art 74 Handicapés</option>
        <option value="tota1&handi=0">Bénéficiaire art 74 Proches</option>
        <option value="tota2&handi=1">Bénéficiaire art 101bis Handicapés</option>
        <option value="tota2&handi=0">Bénéficiaire art 101bis Proches</option>
        <option value="totaNonR">Non reconnus</option>


    </select>
    <div id="decompte"></div>


    <script>
        $.get( "ajax.decompteTypesCours.php", function( data ) {
            $( "#decompte" ).html( data );
        });

        $("#CatOfas").change(function () {
            $.get( "ajax.decompteTypesCours.php", { cat: $("#CatOfas").val() }, function( data ) {
                $( "#decompte" ).html( data );
            });
        });

    </script>

<?php
include_once '../src/footer.inc.php';