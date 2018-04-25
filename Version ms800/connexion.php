<?php
session_start(); 

include 'overlay.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<style>

<?php get_style_overlay(); ?>

input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}  

input[type=password], select {
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

<?php
get_body_overlay();
begin_main();

$right_uname = "moi";
$right_password = "lolmdr";

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    header('Location: menu.php');
}

elseif(array_key_exists('uname', $_POST) and array_key_exists('password', $_POST) and htmlspecialchars($_POST['uname']) == $right_uname and htmlspecialchars($_POST['password']) == $right_password){

    $_SESSION['connected'] = true;
    header('Location: menu.php');
}

else{
    echo <<< EOT
            <div class="form">
            <form action="connexion.php" method="post">

                <label for="uname">Nom d'utilisateur</label>
                <input type="text" id="uname" name="uname" placeholder="Nom d'utilisateur...">

                <label for="champ">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe...">
                
                <input type="submit" value="Connexion">
            </form>
            </div>
EOT;
}

end_main();

?>

     
</body>
</html> 
