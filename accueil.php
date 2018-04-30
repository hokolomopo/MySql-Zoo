<?php include 'overlay.php' ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<style>

<?php style_fond() ?>

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
    padding-top: 40px;
}

ul{
    padding-left: 40px;
}

</style>
</head>
<body>

<?php 
corps_fond(); 
debut_main();
?>

     <p> 
        Bienvenue dans ce tout nouveau zoo Ulg !<br><br>

        Le meilleur endroit pour observer des animaux sauvages, c'est encore dans les amhpithéatres de la faculté de sciences appliquées ! 
    </p>

<?php fin_main() ?>

</body>
</html> 
