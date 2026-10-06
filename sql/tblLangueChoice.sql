CREATE TABLE `tblLangueChoice` (
  `employer` bigint(22) NOT NULL,
  `lang` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `tblLangueChoice`
  ADD UNIQUE KEY `employer_2` (`employer`,`lang`);
