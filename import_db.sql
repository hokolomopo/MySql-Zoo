LOAD DATA LOCAL INFILE '/tmp/db2018/Espece.txt'
INTO TABLE Espece
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_courant, regime_alimentaire)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Climat.txt'
INTO TABLE Climat
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_climat)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Enclos.txt'
INTO TABLE Enclos
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_enclos, nom_climat, taille)
;

ALTER TABLE Animal ADD date_naissance_txt VARCHAR(40);
LOAD DATA LOCAL INFILE '/tmp/db2018/Animal.txt'
INTO TABLE Animal
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, taille, sexe, date_naissance_txt, n_enclos)
;
UPDATE Animal SET date_naissance = STR_TO_DATE(date_naissance_txt, '%d/%m/%Y');
ALTER TABLE Animal DROP date_naissance_txt;

LOAD DATA LOCAL INFILE '/tmp/db2018/Institution.txt'
INTO TABLE Institution
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom, rue, code_postal, pays)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Materiel.txt'
INTO TABLE Materiel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_materiel, etat, local)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Personnel.txt'
INTO TABLE Personnel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, nom, prenom)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Technicien.txt'
INTO TABLE Technicien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre)
;

LOAD DATA LOCAL INFILE '/tmp/db2018/Veterinaire.txt'
INTO TABLE Veterinaire
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, n_license, specialite)
;

ALTER TABLE Entretien ADD date_txt VARCHAR(40);
LOAD DATA LOCAL INFILE '/tmp/db2018/Entretien.txt'
INTO TABLE Entretien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_entretien, n_registre, n_materiel, date_txt, n_enclos)
;
UPDATE Entretien SET date_entretien = STR_TO_DATE(date_txt, '%d/%m/%Y');
ALTER TABLE Entretien DROP date_txt;

LOAD DATA LOCAL INFILE '/tmp/db2018/Provenance.txt'
INTO TABLE Provenance
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, nom_institution)
;

ALTER TABLE Intervention ADD date_txt VARCHAR(40);
LOAD DATA LOCAL INFILE '/tmp/db2018/Intervention.txt'
INTO TABLE Intervention
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_intervention, date_txt, description, n_registre, nom_scientifique, n_puce)
;
UPDATE Intervention SET date_intervention = STR_TO_DATE(date_txt, '%d/%m/%Y');
ALTER TABLE Intervention DROP date_txt;
