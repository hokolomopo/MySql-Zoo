CREATE TABLE IF NOT EXISTS Espece (
	nom_scientifique VARCHAR(40) NOT NULL,
    nom_courant VARCHAR(40) NOT NULL,
    regime_alimentaire VARCHAR(40) NOT NULL,
    PRIMARY KEY (nom_scientifique)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Climat(
 	nom_scientifique VARCHAR(40) NOT NULL,
 	nom_climat VARCHAR(40) NOT NULL,
 	PRIMARY KEY (nom_scientifique, nom_climat),
 	FOREIGN KEY (nom_scientifique)             
        REFERENCES Espece(nom_scientifique)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Enclos(
 	n_enclos SMALLINT NOT NULL AUTO_INCREMENT,
 	nom_climat VARCHAR(40) NOT NULL,
 	taille SMALLINT NOT NULL,
 	PRIMARY KEY (n_enclos)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Animal (
	nom_scientifique VARCHAR(40) NOT NULL,
    n_puce SMALLINT UNSIGNED NOT NULL,
    taille INT NOT NULL,
    sexe CHAR(1) NOT NULL,
    date_naissance VARCHAR(40) NOT NULL,
    n_enclos SMALLINT NOT NULL,
    PRIMARY KEY (n_puce, nom_scientifique),
    FOREIGN KEY (nom_scientifique)             
        REFERENCES Espece(nom_scientifique),
    FOREIGN KEY (n_enclos)             
        REFERENCES Enclos(n_enclos)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Institution(
	nom VARCHAR(40) NOT NULL,
	rue VARCHAR(40) NOT NULL,
	code_postal VARCHAR(40) NOT NULL,
	pays VARCHAR(40) NOT NULL,
	PRIMARY KEY (nom)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Materiel(
	n_materiel SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	etat VARCHAR(40) NOT NULL, 
 	local VARCHAR(10) NOT NULL,
 	PRIMARY KEY (n_materiel)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Personnel(
	n_registre INT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(40) NOT NULL, 
 	prenom VARCHAR(40) NOT NULL,
 	PRIMARY KEY (n_registre)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Technicien(
	n_registre INT UNSIGNED NOT NULL,
 	PRIMARY KEY (n_registre),
 	FOREIGN KEY (n_registre)             
        REFERENCES Personnel(n_registre)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Veterinaire(
	n_registre INT UNSIGNED NOT NULL,
 	n_license INT UNSIGNED NOT NULL,
	specialite VARCHAR(40) NOT NULL,
 	PRIMARY KEY (n_registre),
 	FOREIGN KEY (n_registre)             
        REFERENCES Personnel(n_registre)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Entretien (
	n_entretien INT UNSIGNED NOT NULL AUTO_INCREMENT,
    n_registre INT UNSIGNED NOT NULL,
    n_materiel SMALLINT UNSIGNED NOT NULL,
    date_entretien VARCHAR(40) NOT NULL,
    n_enclos SMALLINT(10) NOT NULL,
    PRIMARY KEY (n_entretien),
    FOREIGN KEY (n_enclos)
    	REFERENCES Enclos(n_enclos),
    FOREIGN KEY (n_materiel)
    	REFERENCES Materiel(n_materiel),
    FOREIGN KEY (n_registre)
    	REFERENCES Technicien(n_registre)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Provenance(
 	nom_scientifique VARCHAR(40) NOT NULL,
 	n_puce SMALLINT UNSIGNED NOT NULL,
	nom_institution VARCHAR(40) NOT NULL,
 	PRIMARY KEY (nom_scientifique, n_puce),
 	FOREIGN KEY (n_puce)             
        REFERENCES Animal(n_puce),
    FOREIGN KEY (nom_scientifique)             
        REFERENCES Animal(nom_scientifique),
   	FOREIGN KEY (nom_institution)
    	REFERENCES Institution(nom)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS Intervention(
	n_intervention INT UNSIGNED NOT NULL AUTO_INCREMENT,
	date_intervention VARCHAR(40) NOT NULL,
	description VARCHAR(40), 
	n_registre INT UNSIGNED NOT NULL,
 	nom_scientifique VARCHAR(40) NOT NULL,
 	n_puce SMALLINT UNSIGNED NOT NULL,
 	PRIMARY KEY (n_intervention),
 	FOREIGN KEY (n_puce)             
    	REFERENCES Animal(n_puce),
    FOREIGN KEY (nom_scientifique)             
        REFERENCES Animal(nom_scientifique),	
   	FOREIGN KEY (n_registre)
    	REFERENCES Veterinaire(n_registre)
)ENGINE=INNODB;

LOAD DATA LOCAL INFILE 'WWW/db2018/Espece.txt'
INTO TABLE Espece
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_courant, regime_alimentaire)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Climat.txt'
INTO TABLE Climat
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, nom_climat)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Enclos.txt'
INTO TABLE Enclos
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_enclos, nom_climat, taille)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Animal.txt'
INTO TABLE Animal
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, taille, sexe, date_naissance, n_enclos)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Institution.txt'
INTO TABLE Institution
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom, rue, code_postal, pays)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Materiel.txt'
INTO TABLE Materiel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_materiel, etat, local)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Personnel.txt'
INTO TABLE Personnel
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, nom, prenom)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Technicien.txt'
INTO TABLE Technicien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Veterinaire.txt'
INTO TABLE Veterinaire
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_registre, n_license, specialite)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Entretien.txt'
INTO TABLE Entretien
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_entretien, n_registre, n_materiel, date_entretien, n_enclos)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Provenance.txt'
INTO TABLE Provenance
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(nom_scientifique, n_puce, nom_institution)
;

LOAD DATA LOCAL INFILE 'WWW/db2018/Intervention.txt'
INTO TABLE Intervention
FIELDS
    TERMINATED BY ','
IGNORE 1 LINES
(n_intervention, date_intervention, description, n_registre, nom_scientifique, n_puce)
;
