<?php
/**
 * Created by PhpStorm.
 * User: Lionel
 * Date: 30/08/2018
 * Time: 10:30
 */
include '../src/header.inc.php';
$nomDecompte = 'Statistique des bénéficiaires (personnes reconnus art.74 et LAVS 101bis)';

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
        <option value="tota21&handi=1">Cours en bloc, nombre jour participants personne handicpés</option>
        <option value="tota21&handi=0">Cours en bloc, nombre jour participants personne proches</option>
        <option value="tota25&handi=1">Cours semestriels/annuels, nombre jour participants personne handicpés</option>
        <option value="tota25&handi=0">Cours semestriels/annuels, nombre jour participants personne proches</option>


    </select>
    <div id="decompte"></div>


    <script>
        $.get( "ajax.decompteStatClient.php", function( data ) {
            $( "#decompte" ).html( data );
        });

        $("#CatOfas").change(function () {
            $.get( "ajax.decompteStatClient.php", { cat: $("#CatOfas").val() }, function( data ) {
                $( "#decompte" ).html( data );
            });
        });

    </script>

<?php
include_once '../src/footer.inc.php';