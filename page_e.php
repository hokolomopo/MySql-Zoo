<?php 

session_start();

if(array_key_exists('connected', $_SESSION) and $_SESSION['connected']){
    echo <<< EOT
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<style>
body {
    padding: 0;
    margin: 0;
    font-family: "Lato", sans-serif;
    background-color: BurlyWood;
}

.sidenav {
    height: 100%;
    width: 200px;
    position: fixed;
    z-index: 1;
    top: 200px;
    left: 0;
    background-color: #111;
    padding-top: 20px;
}

.hoverable a {
    padding: 6px 6px 6px 32px;

    text-decoration: none;
    font-size: 25px;
    color: #818181;
}

.main {
    margin-left: 210px;
    margin-top: 210px;
}

.header {
    position: fixed;
    top :0;
    height : 200px;
    width: 100%;
    z-index: 1;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

.dropdown div{
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    left: 200px;
    top : 60px;
    background-color: #111;
    min-width: 160px;
    z-index: 0;
}

.dropdown:hover .dropdown-content {
    display: block;
}

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

.hoverable:hover{
    background-color: #818181;
}

.hoverable:hover .link{
    color: #F0F8FF;
}

.hoverable2:hover{
    background-color: #818181;
}

.hoverable2:hover a{
    color: #F0F8FF;
}

.hoverable2 a{
    padding-left: 10px;
}


input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}  

input[type=date], select {
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

.newInstitution {
    display: none;
}


</style>
</head>
<body>

<div class="header">
      <img src="ccs_banner.png" width="100%" height="100%">
</div>

<div class="sidenav">
    <ul >
        <li class="hoverable">
                <a href="accueil.html" class="link">Acceuil</a>
        </li>
        <li class="dropdown hoverable"> 
            <a href="#" class="link" >Services</a>
            <ul class="dropdown-content">
                <li class="hoverable2">
                    <a href="page_a.html" class="link2">Question a</a>
                </li>
                <li class="hoverable2">
                    <a href="page_b.html" class="link2">Question b</a>
                </li>
                <li class="hoverable2">
                    <a href="page_c.html" class="link2">Question c</a>
                </li>
                <li class="hoverable2">
                    <a href="page_d.html" class="link2">Question d</a>
                </li>
                <li class="hoverable2">
                    <a href="page_e.html" class="link2">Question e</a>
                </li>

            </ul>   

        </li>
        <li class="hoverable">
            <a href="credits.html" class="link">Crédits</a>
        </li>
    </ul>
</div>

<div class="main">

  <p> Vous pouvez ici ajouter un animal à la base de donnée. </p>
  <div class="form">
  <form action="action_page_e.php" method="post">

    <label for="nom_scientifique">Nom scientifique</label>
    <input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique...">

    <label for="n_puce">Numéro de puce</label>
    <input type="text" id="n_puce" name="n_puce" placeholder="Numéro de puce...">

    <label for="taille">Taille</label>
    <input type="text" id="taille" name="taille" placeholder="Taille en centimètres...">

    <label for="sexe">Sexe</label>
    <select id="sexe" name="sexe">
      <option value="M">M</option>
      <option value="F">F</option>
    </select>

    <label for="date_naissance">Date de naissance</label>
    <input type="date" id="date_naissance" name="date_naissance" placeholder="Date de naissance...">

    <label for="n_enclos">Numéro de l'enclos</label>
    <input type="text" id="n_enclos" name="n_enclos" placeholder="Numéro d'enclos...">

    <input type="hidden" id="avertissement_confirmé" name="avertissement_confirmé" value="faux">

    Nouvelle institution : <input type="checkbox" id="institutionCheck" name="institutionCheck" value="true" onclick="checkBoxClick()"><br><br>

    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" placeholder="Institution de provenance (facultatif)...">

    <label for="rue" class="newInstitution">Rue</label>
    <input type="text" id="rue" name="rue" placeholder="Rue..." class="newInstitution">

    <label for="code_postal" class="newInstitution">Code Postal</label>
    <input type="text" id="code_postal" name="code_postal" placeholder="Code postal..." class="newInstitution">

    <label for="pays" class="newInstitution">Pays</label>
    <input type="text" id="pays" name="pays" placeholder="Pays..." class="newInstitution">

    <input type="submit" value="Soumettre">
  </form>
</div>
</div>

<script>
function checkBoxClick() {
    var checkBox = document.getElementById("institutionCheck");

    var toChange = document.getElementsByClassName("newInstitution");

    if (checkBox.checked == true){
        for(i=0; i<toChange.length; i++) {
            toChange[i].style.display = "block";
        }
    } else {
        for(i=0; i<toChange.length; i++) {
            toChange[i].style.display = "none";
        }
    }
}
</script>

     
</body>
</html> 
EOT;
}

else{
    header('Location: connexion.php');
}

?>


