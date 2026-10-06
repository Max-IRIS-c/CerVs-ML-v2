SELECT  traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau, tblContact_conId,conNom FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1
GROUP BY conNom;


SELECT conNom,conPrenom, medOfasPluri,artNom,hanNom,ofasNouveau,regConNom FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
  LEFT JOIN tblArt on medOfasReconnu = artId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1
GROUP BY conNom;




# heure par KGA
SELECT sum(traHeureTot)as totHeure,traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1
group by medOfasType;


# heure par KGA pury
SELECT sum(traHeureTot)as totHeure,traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND medOfasPluri = 1
group by medOfasType;


# heure par KGA articel 74
SELECT sum(traHeureTot)as totHeure,traCat3, traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 AND medOfasReconnu = 2
group by medOfasType;


# nombre de personne handicapée
SELECT traCat3, count(tblContact_conId), traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1
group by medOfasType;

# nombre de personne purhandicapé
SELECT traCat3, count(medOfasPluri), traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1


# nombre de personne art74
SELECT traCat3, count(medOfasReconnu), traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 group by medOfasReconnu

# nombre de personne art101
SELECT traCat3, count(medOfasReconnu), traId,traCat1,cat1Code,regConNom ,medOfasType, medOfasPluri,medOfasReconnu,
  ofasBesoin, hanNom,
  ofasNouveau FROM tblTravail
  LEFT JOIN tblTraCat1 on traCat1 = cat1Id
  LEFT JOIN tblContact on tblContact_conId = conId
  LEFT JOIN tblRegionCon on tblContact.conRegion = regConId
  LEFT JOIN tblMedical on tblTravail.tblContact_conId = medConId
  LEFT JOIN tblHandicape on tblMedical.medOfasType = hanId
where tblContact_conId is not null AND tblContact_conId != 2146190079 and traCat3 != 1 and medOfasReconnu = 2




