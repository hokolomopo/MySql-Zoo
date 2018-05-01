<?php

function style_fond()
{
    echo <<< EOT
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

    .sidenav ul
    {
        margin: 0;
        padding: 0;
    }

    .sidenav li
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

    .menu_arrow{
    width:15px;
    height: 20px;
    padding-left: 37px;
}

    .img_trace_pas_vertical{
        width: 50px;
        heigth: 50px;
        position: absolute;
        opacity: 0.0;
        transform: rotate(180deg);
}

    .img_trace_pas_horizontal{
        width: 50px;
        heigth: 50px;
        position: absolute;
        opacity: 0.0;
        transform: rotate(90deg);
}

    .images_pas_vertical{
        position: relative;
        display: inline;
}
    .images_pas_horizontal{
        position: relative;
        height: 130px;
}

EOT;
}

function corps_fond()
{
    echo <<< EOT
    <div class="header">
        <img src="ccs_banner.png" width="100%" height="100%">
    </div>

    <div class="sidenav">
        <ul >
            <li class="hoverable">
                <a href="accueil.php" class="link">Acceuil</a>
            </li>
                <li class="dropdown hoverable"> 
                <a href="#" class="link" >Services</a>
                <img src="menu_arrow.png" class="menu_arrow">
                <ul class="dropdown-content">
                    <li class="hoverable2">
                        <a href="page_a.php" class="link2">Question a</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_b.php" class="link2">Question b</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_c.php" class="link2">Question c</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_d.php" class="link2">Question d</a>
                    </li>
                    <li class="hoverable2">
                        <a href="page_e.php" class="link2">Question e</a>
                    </li>

                </ul>   

            </li>
            <li class="hoverable">
                <a href="credits.php" class="link">Crédits</a>
            </li>
        </ul>
    </div>
EOT;
}

function debut_main()
{
    echo '<div class="main">';
}

function fin_main()
{
    echo '</div>';
}

$nb_traces = 0;

function anime_trace_pas($nb_traces) {
    if ($nb_traces > $GLOBALS['nb_traces']) {
        $nb_traces = $GLOBALS['nb_traces'];
    }
    if ($nb_traces < 2) {
        return;
    }

    echo 'var nb_traces = ' . $nb_traces . ';';
    echo <<< EOT
        var index_courant = -1;
        var image_style1;
        var image_style2;
        var transparence;
        var sens = 1;   //sens vaut 1 quand on augmente les indexs, -1 quand on les décrémente
                        //dans les 2 cas, il suffit donc d'ajouter sens à l'index : on évite une condition

        function anime_aux() {
            transparence += 2;
            //On travaille avec des entiers et on divise par 10 car l'addition de double souffre de problèmes d'approximation
            image_style1.opacity = 1.0 - transparence / 10.0;
            image_style2.opacity = transparence / 10.0;

            if (transparence < 10) {
                setTimeout(anime_aux, 100);
            } else {
                setTimeout(anime, 1200);
            }
        }

        function anime() {
            if (index_courant == nb_traces - 1) {
                rotation = (rotation + 180) % 360;
                for (var i = 0; i < nb_traces; i++) {
                    document.getElementById("img_trace_pas_" + i).style.transform = "rotate(" + rotation + "deg)";
                }
                sens = -1;
            }
            if (index_courant == 0) {
                rotation = (rotation + 180) % 360;
                for (var i = 0; i < nb_traces; i++) {
                    document.getElementById("img_trace_pas_" + i).style.transform = "rotate(" + rotation + "deg)";
                }
                sens = 1;
            }

            if (index_courant == -1) {
                index_courant = 0;
            }

            image_style1 = document.getElementById("img_trace_pas_" + index_courant).style;
            index_courant = index_courant + sens;
            image_style2 = document.getElementById("img_trace_pas_" + index_courant).style;
            transparence = 0;
            anime_aux();
        }

        setTimeout(anime, 2000);
EOT;
}

//$direction_affichage : true pour vertical, false pour horizontal
function trace_pas($nb_traces, $direction_affichage) {
    $GLOBALS['nb_traces'] = $nb_traces;
    if($direction_affichage) {
        $fin_nom_classe = "_vertical";
        $padding_principal = "top";
        $padding_secondaire = "left";
        echo "<script>var rotation = 180</script>";
    } else {
        $fin_nom_classe = "_horizontal";
        $padding_principal = "left";
        $padding_secondaire = "top";
        echo "<script>var rotation = 90</script>";
    }
    echo '<div class="images_pas' . $fin_nom_classe . '">';
    $padding_valeur = 20;
    for ($i = 0; $i < $nb_traces; $i++) {
        $echo = '<img class="img_trace_pas' . $fin_nom_classe . '" id="img_trace_pas_' . $i . '" src="img_trace_de_pas.png" alt="trace de pas" style="' . $padding_principal . ': ' . $padding_valeur . 'px;';

        //le 2éme, 4éme, 6éme, ... élément
        if ( ($i % 2) != 0) {
            $echo .= ' ' . $padding_secondaire . ': 50px;';
        }

        if( $i == 0) {
            $echo .= ' opacity: 1.0;';
        }
        $echo .= '">';
        echo $echo;
        $padding_valeur += 70;
    }
    echo '</div>';
}

?>