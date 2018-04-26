<?php 

session_start();

$_SESSION['lastVisited'] = 'page_b.php';

include 'overlay.php';

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    echo <<< EOT
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<style>
EOT;

get_style_overlay();

echo <<< EOT

ul
{
    margin: 0;
    padding: 0;
}

li
{
    list-style:none;
    padding-bottom: 5px;
    padding-top: 5px;
}


input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}  


input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

.form {
    position: :absolute;
    left : 100px;
    width: 60%;
    border-radius: 5px;
    padding: 20px;
}

p{
    padding-left: 20px;
}

</style>
</head>
<body>
EOT;

get_body_overlay();

begin_main();

echo <<< EOT
  <p> Vous pouvez ici retrouver les techniciens qui ont travaillé dans l'ensemble des enclos du parc animalier. </p>
  <div class="form"  method="post">
  <form action="action_page_c.php">

    <input type="submit" value="Trouver">
  </form>
</div>
EOT;

end_main();
     
echo <<< EOT
</body>
</html> 
EOT;
}

else{
    header('Location: connexion.php');
}

?>