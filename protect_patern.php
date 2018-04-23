<?php 

session_start();

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    echo <<< EOT
    mettre le code de la page ici
EOT;
}

else{
    header('Location: connexion.php');
}

?>
