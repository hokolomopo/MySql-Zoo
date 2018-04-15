CREATE DATABASE IF NOT EXISTS zoo CHARACTER SET 'utf8';
USE zoo;

CREATE TABLE IF NOT EXISTS Espece (
	nom_scientifique VARCHAR(40) NOT NULL,
    nom_courant VARCHAR(40) NOT NULL,
    regime_alimentaire VARCHAR(40) NOT NULL,
    PRIMARY KEY (nom_scientifique)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Climat(
 	nom_scientifique VARCHAR(40) NOT NULL,
 	nom_climat VARCHAR(40) NOT NULL,
 	PRIMARY KEY (nom_scientifique, nom_climat),
 	FOREIGN KEY (nom_scientifique)             
        REFERENCES Espece(nom_scientifique)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Enclos(
 	n_enclos SMALLINT NOT NULL AUTO_INCREMENT,
 	nom_climat VARCHAR(40) NOT NULL,
 	taille SMALLINT NOT NULL,
 	PRIMARY KEY (n_enclos)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Animal (
	nom_scientifique VARCHAR(40) NOT NULL,
    n_puce SMALLINT UNSIGNED NOT NULL,
    taille INT NOT NULL,
    sexe CHAR(1) NOT NULL,
    date_naissance DATETIME NOT NULL,
    n_enclos SMALLINT NOT NULL,
    PRIMARY KEY (n_puce, nom_scientifique),
    FOREIGN KEY (nom_scientifique)             
        REFERENCES Espece(nom_scientifique),
    FOREIGN KEY (n_enclos)             
        REFERENCES Enclos(n_enclos)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Institution(
	nom VARCHAR(40) NOT NULL,
	rue VARCHAR(40) NOT NULL,
	code_postal VARCHAR(40) NOT NULL,
	pays VARCHAR(40) NOT NULL,
	PRIMARY KEY (nom)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Materiel(
	n_materiel SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	etat VARCHAR(40) NOT NULL, 
 	LOCAL VARCHAR(10) NOT NULL,
 	PRIMARY KEY (n_materiel)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Personnel(
	n_registre INT UNSIGNED NOT NULL AUTO_INCREMENT,
	nom VARCHAR(40) NOT NULL, 
 	prenom VARCHAR(40) NOT NULL,
 	PRIMARY KEY (n_registre)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Technicien(
	n_registre INT UNSIGNED NOT NULL,
 	PRIMARY KEY (n_registre),
 	FOREIGN KEY (n_registre)             
        REFERENCES Personnel(n_registre)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Veterinaire(
	n_registre INT UNSIGNED NOT NULL,
 	n_license INT UNSIGNED NOT NULL,
	specialite VARCHAR(40) NOT NULL,
 	PRIMARY KEY (n_registre),
 	FOREIGN KEY (n_registre)             
        REFERENCES Personnel(n_registre)
)ENGINE=INNODB;
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Entretien (
	n_entretien INT UNSIGNED NOT NULL AUTO_INCREMENT,
    n_registre INT UNSIGNED NOT NULL,
    n_materiel SMALLINT UNSIGNED NOT NULL,
    date_entretien DATETIME NOT NULL,
    n_enclos SMALLINT(10) NOT NULL,
    PRIMARY KEY (n_entretien),
    FOREIGN KEY (n_enclos)
    	REFERENCES Enclos(n_enclos),
    FOREIGN KEY (n_materiel)
    	REFERENCES Materiel(n_materiel),
    FOREIGN KEY (n_registre)
    	REFERENCES Technicien(n_registre)
)ENGINE=INNODB;
SHOW TABLES;

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
SHOW TABLES;

CREATE TABLE IF NOT EXISTS Intervention(
	n_intervention INT UNSIGNED NOT NULL AUTO_INCREMENT,
	date_intervention DATETIME NOT NULL,
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
SHOW TABLES;
