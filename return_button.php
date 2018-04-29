<?php

function get_style_return_button()
{
	echo <<< EOT
	input[type=submit] {
        display: block;
        width: 50%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 0 auto;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type=button] {
        display: block;
        width: 50%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 0 auto;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    a{
        text-decoration: none;
    }
EOT;
}

/*Cette fonction renvoie un bouton permettant de rediriger à la page $page. Les données contenues dans $post seront réenvoyées à la page cible avec une méthode POST.
* Cela permet notamment d'auto-compléter certains champs précédemment remplis par l'utilisateur, afin qu'il puisse facilement exécuter plusieurs requêtes similaires
* ou corriger une requête erronnée.*/
function get_body_return_button_with_post($page, $post)
{
    $echo = "<form action='" . $page . "' method='post'>";
    foreach ($post as $clé => $valeur) {
        $echo .= "<input type='hidden' name='" . $clé . "' value='" . $valeur . "'>";
    }
    $echo .= "<input type='submit' value='Effectuer de nouveau la requête'></form>";
    echo $echo;
}

/*Cette fonction renvoie un bouton permettant de rediriger à la page $page.*/
function get_body_return_button($page)
{
    echo '<a href="' . $page . '"> <input type="button" value="Effectuer de nouveau la requête"> </a>';
}

/*Cette fonction renvoie un bouton permettant de rediriger à la page $page, le début de ce bouton ($gradient étant un pourcentage)
* possède une couleur différente du reste, de telle sorte qu'il ressemble à une barre de chargement.*/
function get_body_return_button_gradient($page, $gradient) {
    $echo = '<a href="' . $page . '"> <input type="button" value="Effectuer de nouveau la requête"';
    $echo .= ' style="background: linear-gradient(to right, blue 0%, blue ' . $gradient . '%, #4CAF50 ' . $gradient . '%, #4CAF50 100%);"> </a>';
    echo $echo;
}

?>