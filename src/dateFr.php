<?php
/*
 * Remplacement de strftime() (dépréciée depuis PHP 8.1) avec les noms français
 * identiques à ceux que renvoyait strftime() avec la locale fr_FR.utf8.
 */
if (!function_exists('strftimeFr')) {
    function strftimeFr($format, $timestamp = null)
    {
        $timestamp = $timestamp ?? time();
        $joursCourts = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
        $joursLongs = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $moisCourts = [1 => 'janv.', 'févr.', 'mars', 'avril', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
        $moisLongs = [1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

        $codes = [
            '%a' => $joursCourts[(int)date('w', $timestamp)],
            '%A' => $joursLongs[(int)date('w', $timestamp)],
            '%d' => date('d', $timestamp),
            '%e' => sprintf('%2d', date('j', $timestamp)),
            '%m' => date('m', $timestamp),
            '%y' => date('y', $timestamp),
            '%Y' => date('Y', $timestamp),
            '%G' => date('o', $timestamp),
            '%b' => $moisCourts[(int)date('n', $timestamp)],
            '%B' => $moisLongs[(int)date('n', $timestamp)],
            '%H' => date('H', $timestamp),
            '%M' => date('i', $timestamp),
            '%S' => date('s', $timestamp),
            '%%' => '%',
        ];
        return strtr($format, $codes);
    }
}
