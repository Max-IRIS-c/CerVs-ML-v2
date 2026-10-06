<?php

  function logement($bdd,$mrp,$date, $idLogement)
{

   // $bdd = new PDO('mysql:host=localhost:3307;dbname=alunis-CerVs17', 'root');
    $dateSQL = date('Ymd', $date);

    $logement = $bdd->query("SELECT * FROM tblLogement where logId = $idLogement");
    $logement = $logement->fetch();


    $req = $bdd->query("SELECT locDateDep,locDateEnt,locStatu,locId,logCouleur,conNom, logtype FROM tblLocation
        LEFT JOIN tblLogement on locPavId = logId
        LEFT JOIN tblContact on locConId = conId 
    WHERE  locDateDep >= $dateSQL AND locDateEnt <= $dateSQL AND locPavId = $idLogement ");
    $etat = $req->fetch();
    $statu = $etat['locStatu'];

    if (!empty($statu)) {
        $nom = substr($etat['conNom'],0,15);
        if ($statu == 1)//provisoir

            {
                $font = "italic";
        $text = "<a href='DetLocation.php?Id=$etat[locId]' target='_blank'>Provisoire <br> $nom </a> ";
        $reserver = 1;
        $backgroudColor = $etat['logCouleur'];


        } elseif ($statu == 2) //Reservé
        {
            $font = "bold";
            $text = "<a href='DetLocation.php?Id=$etat[locId]' target='_blank'>Fixe <br>$nom </a> ";
            $reserver = 1;
            $backgroudColor =$etat['logCouleur'];
        }


        return array($text,$font,$reserver,$backgroudColor);
    }
    else
    {
       if ($logement['logtype'] == 2)
       {
           $font = "bold";
           $text = "";
           $reserver = '';
           $backgroudColor = "#DDD";

           return array($text,$font,$reserver,$backgroudColor);
       }
    }
}