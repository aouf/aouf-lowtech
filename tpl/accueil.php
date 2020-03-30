<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

if ($_SESSION['user_category']=='admin') 
{
    echo '<a href="/admin/moderation">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Modération</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';
}
if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') 
{
    echo '<a href="/offer/mylist">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Mes offres</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';
    echo '<a href="/offer/yourlist">
        <div class="header2 bg-blanc flex center">
            <h2 class="saumon margin-left">Besoins exprimés</h2>
            <img class="fleche-droite" src="../images/fleche-droite-saumon.png" alt="">
        </div>
    </a>';
}
else
{
    echo '<a href="/offer/mylist">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Mes besoins</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';

    echo '<a href="/offer/yourlist">
        <div class="header2 bg-blanc flex center">
            <h2 class="saumon margin-left">Besoins exprimés</h2>
            <img class="fleche-droite" src="../images/fleche-droite-saumon.png" alt="">
        </div>
    </a>';

    echo '<div class="header2 bg-saumon flex center">
            <h2 class="blanc">Offres disponibles</h2>
        </div>';
}
?>

<?php
    // if ($_SESSION['user_category']=='admin') {
?>
    <!-- <a class="flex center" href='/admin/register'><div>Créer un accès</div></a> -->
    
<?php 
// }

if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) {
?>
    
    <div class="header2 bg-saumon flex center">
            <h2 class="blanc">Déposer une offre</h2>
    </div>
    <div id="container-accueil" class="container bg-saumon">
        <div class="content">
            <section class="tableau-de-bord flex wrap">
                <a class="flex center column" href='/offer/new/restauration'>
                    <img class="icone" src="./images/restauration-ajout.png" alt="">
                    <h3>Restauration</h3>
                </a>
                <a class="flex center column" href='/offer/new/course'>
                    <img class="icone" src="./images/courses-ajout.png" alt="">
                    <h3>Course</h3>
                </a>
                <a class="flex center column" href='/offer/new/pret'>
                    <img class="icone" src="./images/prets-ajout.png" alt="">
                    <h3>Prêt</h3>
                </a>
                <a class="flex center column" href='/offer/new/loisir'>
                    <img class="icone" src="./images/eloisirs-ajout.png" alt="">
                    <h3>e-Loisir</h3>
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

<?php } ?>
<?php
if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='deloge')) 
{
    if ($_SESSION['user_category']=='admin') 
    {
        echo '<div class="header2 bg-blanc flex center">
                <h2 class="saumon">Offres disponibles</h2>
            </div>';
    }
?>
    <div id="container-accueil" class="container bg-saumon">
        <div class="content">
            <section class="tableau-de-bord flex wrap">
                <a class="flex center column" href='/offer/list/restauration'>
                    <img class="icone" src="./images/restauration.png" alt="">
                    <h3>Restauration</h3>
                </a>
                <a class="flex center column" href='/offer/list/course'>
                    <img class="icone" src="./images/courses.png" alt="">
                    <h3>Course</h3>
                </a>
                <a class="flex center column" href='/offer/list/pret'>
                    <img class="icone" src="./images/prets.png" alt="">
                    <h3>Prêt</h3>
                </a>
                <a class="flex center column" href='/offer/list/loisir'>
                    <img class="icone" src="./images/eloisirs.png" alt="">
                    <h3>e-Loisir</h3>
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

<!--    <a class="feedback-link blanc" href="/whatineed">Pas trouvé d'annonce pour votre besoin&nbsp;? Dites le nous&nbsp;!</a> -->

<?php
}?>

    </div>
</div>
<?php require_once 'footer.php'; ?>
