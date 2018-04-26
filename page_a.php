<?php 

session_start();

$_SESSION['lastVisited'] = 'page_a.php';

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

input[type=date], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
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

  <p> Vous pouvez ici choisir une table à afficher, avec éventuellement des contraintes. </p>
  <div class="form">
  <form action="action_page_a.php" method="post">
    <label for="table">Table</label>
    <select id="table" name="table">
      <option value="Institution" onclick="afficheContraintes('institution')">Institution</option>
      <option value="Espece" onclick="afficheContraintes('espece')">Espèce</option>
      <option value="Animal" onclick="afficheContraintes('animal')">Animal</option>
      <option value="Climat" onclick="afficheContraintes('climat')">Climat</option>
      <option value="Enclos" onclick="afficheContraintes('enclos')">Enclos</option>
      <option value="Materiel" onclick="afficheContraintes('materiel')">Materiel</option>
      <option value="Personne" onclick="afficheContraintes('personne')">Personne</option>
      <option value="Veterinaire" onclick="afficheContraintes('veterinaire')">Vétérinaire</option>
      <option value="Technicien" onclick="afficheContraintes('technicien')">Technicien</option>
      <option value="Intervention" onclick="afficheContraintes('intervention')">Intervention</option>
      <option value="Entretien" onclick="afficheContraintes('entretien')">Entretien</option>
      <option value="Provenance" onclick="afficheContraintes('provenance')">Provenance</option>
    </select>

    <ul class="test1" id="list1">
      <li><label for="champ">Nom</label>
          <input type="text" id="nom" name="nom" placeholder="Nom"></li>
      <li><label for="champ">Rue</label>
          <input type="text" id="rue" name="rue" placeholder="Rue"></li>
      <li><label for="champ">Code postal</label>
          <input type="text" id="code_postal" name="code_postal" placeholder="Code postal"></li>
      <li><label for="champ">Pays</label>
          <input type="text" id="pays" name="pays" placeholder="Pays"></li>
    </ul>

    <input type="submit" value="Soumettre">
  </form>
  </div>
EOT;

end_main();

echo <<< EOT
     
<script>
    function afficheContraintes(x) {
        var list = document.getElementById("list1");
        list.innerHTML = "";
        switch (x) {
        case "institution":
            list.innerHTML += '<li><label for="champ">Nom</label>' + 
                              '<input type="text" id="nom" name="nom" placeholder="Nom"></li>';
            list.innerHTML += '<li><label for="champ">Rue</label>' + 
                              '<input type="text" id="rue" name="rue" placeholder="Rue"></li>';
            list.innerHTML += '<li><label for="champ">Code postal</label>' + 
                              '<input type="text" id="code_postal" name="code_postal" placeholder="Code postal"></li>';
            list.innerHTML += '<li><label for="champ">Pays</label>' + 
                              '<input type="text" id="pays" name="pays" placeholder="Pays"></li>';
            break;

        case "espece":
            list.innerHTML += '<li><label for="champ">Nom scientifique</label>' + 
                              '<input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique"></li>';
            list.innerHTML += '<li><label for="champ">Nom courant</label>' + 
                              '<input type="text" id="nom_courant" name="nom_courant" placeholder="Nom courant"></li>';
            list.innerHTML += '<li><label for="champ">Régime alimentaire</label>' + 
                              '<input type="text" id="regime_alimentaire" name="regime_alimentaire" placeholder="Régime alimentaire"></li>';
            break;

        case "animal":
            list.innerHTML += '<li><label for="champ">Nom scientifique</label>' + 
                              '<input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de puce</label>' + 
                              '<input type="text" id="n_puce" name="n_puce" placeholder="Numéro scientifique"></li>';
            list.innerHTML += '<li><label for="champ">taille</label>' + 
                              '<input type="text" id="taille" name="taille" placeholder="taille"></li>';
            list.innerHTML += '<li><label for="champ">sexe</label>' + 
                              '<input type="text" id="sexe" name="sexe" placeholder="sexe"></li>';
            list.innerHTML += '<li><label for="champ">date de naissance</label>' + 
                              '<input type="date" id="date_naissance" name="date_naissance" placeholder="date de naissance"></li>';
            list.innerHTML += '<li><label for="champ">numéro de l\'enclos</label>' + 
                              '<input type="text" id="n_enclos" name="n_enclos" placeholder="numéro de l\'enclos"></li>';
            break;

        case "climat":
            list.innerHTML += '<li><label for="champ">Nom scientifique</label>' + 
                              '<input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique"></li>';
            list.innerHTML += '<li><label for="champ">Nom du climat</label>' + 
                              '<input type="text" id="nom_climat" name="nom_climat" placeholder="Nom du climat"></li>';
            break;

        case "enclos":
            list.innerHTML += '<li><label for="champ">Numéro de l\'enclos</label>' + 
                              '<input type="text" id="n_enclos" name="n_enclos" placeholder="Numéro de l\'enclos"></li>';
            list.innerHTML += '<li><label for="champ">Nom du climat</label>' + 
                              '<input type="text" id="nom_climat" name="nom_climat" placeholder="Nom du climat"></li>';
            list.innerHTML += '<li><label for="champ">Taille</label>' + 
                              '<input type="text" id="taille" name="taille" placeholder="Taille"></li>';
            break;

        case "materiel":
            list.innerHTML += '<li><label for="champ">Numéro du matériel</label>' + 
                              '<input type="text" id="n_materiel" name="n_materiel" placeholder="Numéro du matériel"></li>';
            list.innerHTML += '<li><label for="champ">État</label>' + 
                              '<input type="text" id="etat" name="etat" placeholder="État"></li>';
            list.innerHTML += '<li><label for="champ">Local</label>' + 
                              '<input type="text" id="local" name="local" placeholder="Local"></li>';
            break;

        case "personne":
            list.innerHTML += '<li><label for="champ">Numéro de registre</label>' + 
                              '<input type="text" id="n_registre" name="n_registre" placeholder="Numéro de registre"></li>';
            list.innerHTML += '<li><label for="champ">Nom</label>' + 
                              '<input type="text" id="nom" name="nom" placeholder="Nom"></li>';
            list.innerHTML += '<li><label for="champ">Prénom</label>' + 
                              '<input type="text" id="prenom" name="prenom" placeholder="Prénom"></li>';
            break;

        case "veterinaire":
            list.innerHTML += '<li><label for="champ">Numéro de registre</label>' + 
                              '<input type="text" id="n_registre" name="n_registre" placeholder="Numéro de registre"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de license</label>' + 
                              '<input type="text" id="n_license" name="n_license" placeholder="Numéro de license"></li>';
            list.innerHTML += '<li><label for="champ">Spécialité</label>' + 
                              '<input type="text" id="specialite" name="specialite" placeholder="Spécialité"></li>';
            break;

        case "technicien":
            list.innerHTML += '<li><label for="champ">Numéro de registre</label>' + 
                              '<input type="text" id="n_registre" name="n_registre" placeholder="Numéro de registre"></li>';
            break;

        case "intervention":
            list.innerHTML += '<li><label for="champ">Numéro de l\'intervention</label>' + 
                              '<input type="text" id="n_intervention" name="n_intervention" placeholder="Numéro de l\'intervention"></li>';
            list.innerHTML += '<li><label for="champ">Date de l\'intervention</label>' + 
                              '<input type="date" id="date_intervention" name="date_intervention" placeholder="Date de l\'intervention"></li>';
            list.innerHTML += '<li><label for="champ">Description</label>' + 
                              '<input type="text" id="description" name="description" placeholder="Description"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de registre</label>' + 
                              '<input type="text" id="n_registre" name="n_registre" placeholder="Numéro de registre"></li>';
            list.innerHTML += '<li><label for="champ">Nom scientifique</label>' + 
                              '<input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de puce</label>' + 
                              '<input type="text" id="n_puce" name="n_puce" placeholder="Numéro de puce"></li>';
            break;

        case "entretien":
            list.innerHTML += '<li><label for="champ">Numéro de l\'entretien</label>' + 
                              '<input type="text" id="n_entretien" name="n_entretien" placeholder="Numéro de l\'entretien"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de registre</label>' + 
                              '<input type="text" id="n_registre" name="n_registre" placeholder="Numéro de registre"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de matériel</label>' + 
                              '<input type="text" id="n_materiel" name="n_materiel" placeholder="Numéro de matériel"></li>';
            list.innerHTML += '<li><label for="champ">Date de l\'entretien</label>' + 
                              '<input type="date" id="date_entretien" name="date_entretien" placeholder="Date de l\'entretien"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de l\'enclos</label>' + 
                              '<input type="text" id="n_enclos" name="n_enclos" placeholder="Numéro de l\'enclos"></li>';
            break;

        case "provenance":
            list.innerHTML += '<li><label for="champ">Nom scientifique</label>' + 
                              '<input type="text" id="nom_scientifique" name="nom_scientifique" placeholder="Nom scientifique"></li>';
            list.innerHTML += '<li><label for="champ">Numéro de puce</label>' + 
                              '<input type="text" id="n_puce" name="n_puce" placeholder="Numéro de puce"></li>';
            list.innerHTML += '<li><label for="champ">Nom de l\'institution</label>' + 
                              '<input type="text" id="nom_institution" name="nom_institution" placeholder="Nom de l\'institution"></li>';
            break;
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





