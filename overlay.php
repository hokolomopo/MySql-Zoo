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

?>