<?php
    require_once 'head.php';
    require_once 'header.php';
?>
<a href="/offer/mylist">
    <div class="header2 bg-blanc flex center">
        <h2 class="saumon">
            <?php 
                if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') 
                {
                    echo 'Mes offres';
                }
                else
                {
                    echo 'Offres disponibles';
                }
            ?>
        </h2>
        <img class="fleche-droite" src="../images/fleche-droite.png" alt="">
    </div>
</a>

<?php
    if ($_SESSION['user_category']=='admin') {
?>
<div class="container bg-<?php echo $couleur ; ?> full-size">
    <div class="content">
<section class="flex center wrap">
    <a class="flex center" href='/admin/register'><div>Créer un accès</div></a>
    <a class="flex center" href='/admin/moderation'><div>Modération accès Délogé</div></a>
</section>
    
<?php }


if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='deloge')) {
    ?>
    <div id="container-accueil" class="container bg-saumon full-size">
        <div class="content">
    <h2 class="blanc">Consulter les offres</h2>
    <section class="tableau-de-bord flex center wrap">
        <a class="flex center column" href='/offer/list/restauration'>
            <img class="icone" src="./images/restauration.png" alt="">
            <h3>Restauration</h3>
        </a>
        <a class="flex center column" href='/offer/list/blanchisserie'>
            <img class="icone" src="./images/blanchisserie.png" alt="">
            <h3>Blanchisserie</h3>
        </a>
        <a class="flex center column" href='/offer/list/mobilite'>
            <img class="icone" src="./images/mobilite.png" alt="">
            <h3>Mobilté</h3>
        </a>
        <a class="flex center column" href='/offer/list/loisir'>
            <img class="icone" src="./images/loisirs.png" alt="">
            <h3>Loisir</h3>
        </a>
        <a class="flex center column" href='/offer/list/don'>
            <img class="icone" src="./images/dons.png" alt="">
            <h3>Dons</h3>
        </a>
        <a class="flex center column" href='/offer/list/autre'>
            <img class="icone" src="./images/autre.png" alt="">
            <h3>Autre</h3>
        </a>
    </section>

    <?php
    }
    if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) {
        ?>
        <div id="container-accueil" class="container bg-saumon full-size">
            <div class="content">
        <h2 class="blanc">Déposer une offre</h2>
        <section class="tableau-de-bord flex center wrap">
            <a class="flex center column" href='/offer/new/restauration'>
                <img class="icone" src="./images/restauration-ajout.png" alt="">
                <h3>Restauration</h3>
            </a>
            <a class="flex center column" href='/offer/new/blanchisserie'>
                <img class="icone" src="./images/blanchisserie-ajout.png" alt="">
                <h3>Blanchisserie</h3>
            </a>
            <a class="flex center column" href='/offer/new/mobilite'>
                <img class="icone" src="./images/mobilite-ajout.png" alt="">
                <h3>Mobilté</h3>
            </a>
            <a class="flex center column" href='/offer/new/loisir'>
                <img class="icone" src="./images/loisirs-ajout.png" alt="">
                <h3>Loisir</h3>
            </a>
            <a class="flex center column" href='/offer/new/don'>
                <img class="icone" src="./images/dons-ajout.png" alt="">
                <h3>Dons</h3>
            </a>
            <a class="flex center column" href='/offer/new/autre'>
                <img class="icone" src="./images/autre-ajout.png" alt="">
                <h3>Autre</h3>
            </a>
        </section>
        
        <br>
<?php } ?>
    </div>
</div>
<?php require_once 'footer.php'; ?>
