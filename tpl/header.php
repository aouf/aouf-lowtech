<?php 
    if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') {
        $couleur = 'vert';
    }
    elseif ($_SESSION['user_category']=='deloge') 
    {
        $couleur = 'saumon';
    }
?>
<body>
    <header class="bg-noir flex">
        <section>
            <a href="/accueil">
                <div class="logo-menu"></div>
            </a>
        </section>
        <div class="compte-et-message flex">
            <section>
                <a href="/message/list">
                    <div class="message-menu"></div>
                </a>
            </section>
            <section>
                <a href="/parametres">
                    <div class="compte-menu"></div>
                </a>
            </section>
        </div>
    </header>
    <div class="header2 bg-blanc flex center">
        <h2 class="saumon">
            <?php 
                if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') {
                    echo 'Mes offres';
                }
                elseif ($_SESSION['user_category']=='deloge') 
                {
                    $couleur = 'saumon';
                }
             ?>
        </h2>
        <img class="fleche-droite" src="../images/fleche-droite.png" alt="">
    </div>
