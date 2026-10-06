SELECT * FROM tblIntervention
LEFT JOIN tblContact on intBeneficiaire = conId
LEFT JOIN tblGenreServices on intGenre = genSerId
LEFT JOIN tblTypeServices on intType = tServicesId WHERE intIntervenant