<body class="bg-noir">
    <header>
        <nav class="dr-menu">
            <div class="dr-trigger">
                <span class="dr-icon dr-icon-menu"></span>
                <a class="dr-label"><?php echo $_SESSION['user_login']; ?></a>
            </div>
            <ul class="flex column center">
                <li><a href='/accueil'>Accueil</a><br></li>
                <?php 
                    if ($_SESSION['user_category']=='admin') { ?>
                        <li><a href='/admin/register'>Créer un accès</a></li>
                        <li><a href='/admin/moderation'>Modération accès Délogé</a></li>
                
                <?php }
                    if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) { ?>
                        <li><a href="/offer/mylist">Mes offres</a></li>
                <?php } ?>
                <li><a href="/message/list">Messagerie</a></li>
                <li><a href="/parametres">Mes paramètres</a></li>
                <li><a href="/auth">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
