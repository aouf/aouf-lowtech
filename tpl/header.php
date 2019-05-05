<?php 
    if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') {
        $class = 'vert';
    }
    elseif ($_SESSION['user_category']=='deloge') 
    {
        $class = 'saumon';
    }
?>
<body class="bg-noir">
    <header>
        <nav class="dr-menu">
            <div class="dr-trigger">
                <span class="dr-icon dr-icon-menu <?php echo $class ; ?>"></span>
                <a class="dr-label <?php echo $class ; ?>"><?php echo $_SESSION['user_login']; ?></a>
            </div>
            <ul class="flex column center">
                <li><a class="<?php echo $class ; ?>" href='/accueil'>Accueil</a><br></li>
                <?php 
                    if ($_SESSION['user_category']=='admin') { ?>
                        <li><a class="<?php echo $class ; ?>" href='/admin/register'>Créer un accès</a></li>
                        <li><a class="<?php echo $class ; ?>" href='/admin/moderation'>Modération accès Délogé</a></li>
                
                <?php }
                    if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) { ?>
                        <li><a class="<?php echo $class ; ?>" href="/offer/mylist">Mes offres</a></li>
                <?php } ?>
                <li><a class="<?php echo $class ; ?>" href="/message/list">Messagerie</a></li>
                <li><a class="<?php echo $class ; ?>" href="/parametres">Mes paramètres</a></li>
                <li><a class="<?php echo $class ; ?>" href="/?logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
