<?php
/*
 * Normalisation des valeurs de formulaire avant écriture en base.
 * MariaDB 10.11 (mode strict) refuse '' ou '->' dans une colonne INT/DATE/TIME,
 * alors que l'ancienne version les convertissait silencieusement.
 */

// Entier ou NULL (liste déroulante sur '->', champ vide, texte non numérique)
if (!function_exists('intOuNull')) {
    function intOuNull($valeur)
    {
        $valeur = trim((string)$valeur);
        return is_numeric($valeur) ? (int)$valeur : null;
    }
}

// Entier ou 0 (pour les colonnes INT NOT NULL)
if (!function_exists('intOuZero')) {
    function intOuZero($valeur)
    {
        return intOuNull($valeur) ?? 0;
    }
}

// Décimal ou NULL
if (!function_exists('decimalOuNull')) {
    function decimalOuNull($valeur)
    {
        $valeur = str_replace(',', '.', trim((string)$valeur));
        return is_numeric($valeur) ? (float)$valeur : null;
    }
}

// Date / heure ou NULL (champ vide)
if (!function_exists('dateOuNull')) {
    function dateOuNull($valeur)
    {
        $valeur = trim((string)$valeur);
        return $valeur === '' ? null : $valeur;
    }
}
