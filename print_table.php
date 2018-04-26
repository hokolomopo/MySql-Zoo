<?php

function get_style_table()
{
	echo <<< EOT
	table
	{
		border-collapse: collapse;
	}

	td, th
	{
		border: 1px solid black;
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

?>