<?php

function style_tableau()
{
	echo <<< EOT
	table
	{
		background-color: rgba(255,255,255,0.5);
		text-align: center;
		border-collapse: collapse;
	}

	td, th
	{
		border: 1px solid black;
		padding: 10px;
	}
EOT;
}

function affiche_cle_ligne($line)
{
	echo '<tr>';
	foreach($line as $key => $value)
	{
		if(is_string($key))
			echo '<th>' . $key . '</th>'; 
	}
	echo '</tr>';
}

function affiche_valeur_ligne($line)
{
	echo '<tr>';
	foreach($line as $key => $value)
	{
		if(is_string($key))
			echo '<td>' . $value . '</td>'; 
	}
	echo '</tr>';
}

function affiche_tableau($tableau, $titre) {
	echo '<center><p>' . $titre . '</p><table>';

   affiche_cle_ligne($tableau[0]);

    foreach($tableau as $données)
    {
        affiche_valeur_ligne($données);
    }    

    echo '</table></center></br>';
}

?>