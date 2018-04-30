<?php

/*Cette fonction affiche la requête qui a causé l'exception ainsi que le message de l'exception, et quitte ensuite le programme.*/
function erreur_exception($requête, $exception) {
	echo "Erreur inattendue lors de l'exécution de la requête.</br></br>Requête: ";
	echo $requête . "</br> Message: ";
	echo $exception->getmMssage();
	exit(1);
}

/*Cette fonction exécute une requête sql passée en argument sous forme d'une chaîne de caractères, en utilisant la base de donnée bdd
* et le tableau d'arguments tableau_arguments. Elle renvoie un tableau contenant les résultats.*/
function execute_requête_string($bdd, $requête, $tableau_arguments) {
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$executable = $bdd->prepare($requête);
	    $executable->execute($tableau_arguments);
	    return $executable->fetchAll();
	} catch (Exception $e) {
		erreur_exception($requête, $e);
	}
}

/*Cette fonction exécute le fichier sql nom_fichier, en utilisant la base de donnée bdd et le tableau d'argument tableau_arguments.
* Elle renvoie un tableau contenant les résultats.*/
function execute_sql_classique($bdd, $nom_fichier, $tableau_arguments) {
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$executable = $bdd->prepare(file_get_contents($nom_fichier));
	    $executable->execute($tableau_arguments);
	    return $executable->fetchAll();
	} catch (Exception $e) {
		erreur_exception(file_get_contents($nom_fichier), $e);
	}
}

/*Cette fonction exécute le fichier sql nom_fichier, en utilisant la base de donnée bdd et le tableau d'argument tableau_arguments.*/
function execute_sql_insert($bdd, $nom_fichier, $tableau_arguments) {
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$executable = $bdd->prepare(file_get_contents($nom_fichier));
	    $executable->execute($tableau_arguments);
	} catch (Exception $e) {
		erreur_exception(file_get_contents($nom_fichier), $e);
	}
}

/*Cette fonction exécute le fichier sql nom_fichier, en utilisant la base de donnée bdd et le tableau d'argument tableau_arguments.
* Elle renvoie vrai si le premier résultat est différent de 0, et faux sinon. Elle est donc utile pour exécuter des fichiers sql
* qui vérifient l'existence d'un tuple dans une table.*/
function execute_vérification_existence($bdd, $nom_fichier, $tableau_arguments) {
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$résultats = (execute_sql_classique($bdd, $nom_fichier, $tableau_arguments));
		return ($résultats[0][0] != 0);
	} catch (Exception $e) {
		erreur_exception(file_get_contents($nom_fichier), $e);
	}
}

?>