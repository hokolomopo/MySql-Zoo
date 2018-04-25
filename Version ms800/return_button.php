<?php

function get_style_return_button()
{
	echo <<< EOT
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

function get_body_return_button($page)
{
	echo '<a href="' . $page . '"> <input type="button" value="Effectuer de nouveau la requête"> </a>';
}

?>