<?php 
    if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') {
        $couleur = 'vert';
    }
    elseif ($_SESSION['user_category']=='deloge') 
    {
        $couleur = 'saumon';
    }
?>
<body class="bg-noir">
    <header>
        <nav class="dr-menu">
            <div class="dr-trigger">
                <a class="dr-label <?php echo $couleur ; ?>"><?php echo $_SESSION['user_login']; ?></a>
                <span class="dr-icon dr-icon-menu <?php echo $couleur ; ?>"></span>
            </div>
            <ul class="flex column center">
                <li><a class="<?php echo $couleur ; ?>" href='/accueil'>Accueil</a><br></li>
                <?php 
                    if ($_SESSION['user_category']=='admin') { ?>
                        <li><a class="<?php echo $couleur ; ?>" href='/admin/register'>Créer un accès</a></li>
                        <li><a class="<?php echo $couleur ; ?>" href='/admin/moderation'>Modérer un accès</a></li>
                
                <?php }
                    if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) { ?>
                        <li><a class="<?php echo $couleur ; ?>" href="/offer/mylist">Mes offres</a></li>
                <?php } ?>
                <li><a class="<?php echo $couleur ; ?>" href="/message/list">Messagerie</a></li>
                <li><a class="<?php echo $couleur ; ?>" href="/parametres">Mes paramètres</a></li>
                <li><a class="<?php echo $couleur ; ?>" href="/?logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
