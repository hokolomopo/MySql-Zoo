<?php

function get_style_table()
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

function print_key_line($line)
{
	echo '<tr>';
	foreach($line as $key => $value)
	{
		if(is_string($key))
			echo '<th>' . $key . '</th>'; 
	}
	echo '</tr>';
}

function print_value_line($line)
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

    print_key_line($tableau[0]);

    foreach($tableau as $données)
    {
        print_value_line($données);
    }    

    echo '</table></center></br>';
}

?>