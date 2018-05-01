LOAD DATA LOCAL INFILE '/home/greg/db2018/Espece.txt'
INTO TABLE Espece
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_courant, regime_alimentaire)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Climat.txt'
INTO TABLE Climat
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_climat)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Enclos.txt'
INTO TABLE Enclos
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_enclos, nom_climat, taille)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Animal.txt'
INTO TABLE Animal
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, taille, sexe, date_naissance, n_enclos)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Institution.txt'
INTO TABLE Institution
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom, rue, code_postal, pays)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Materiel.txt'
INTO TABLE Materiel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_materiel, etat, local)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Personnel.txt'
INTO TABLE Personnel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, nom, prenom)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Technicien.txt'
INTO TABLE Technicien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Veterinaire.txt'
INTO TABLE Veterinaire
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, n_license, specialite)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Entretien.txt'
INTO TABLE Entretien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_entretien, n_registre, n_materiel, date_entretien, n_enclos)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Provenance.txt'
INTO TABLE Provenance
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, nom_institution)
;

LOAD DATA LOCAL INFILE '/home/greg/db2018/Intervention.txt'
INTO TABLE Intervention
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_intervention, date_intervention, description, n_registre, nom_scientifique, n_puce)
;
