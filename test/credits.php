<?php include 'overlay.php' ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<style>

<?php get_style_overlay() ?>

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
get_body_overlay();
begin_main();
?>

    <p> 
        Ce merveilleux site a été créé par :
        <ul>
            <li>Adrien Minne, s154340</li>
            <li>Arnaud Delaunoy, s153059</li>
            <li>Grégory Maréchal, s150958</li>
        </ul>
        <br>
        <img src="https://78.media.tumblr.com/22733c59c1ddfd4b4dc5ffd17c2b745f/tumblr_njax2wztDY1rdlo21o1_1280.jpg">
    </p>

<?php end_main() ?>
     
</body>
</html> 
