<?php

if ($_SESSION['user_category']=='admin') {
?>

<h2>Admin</h2>
<a href='/admin/register'>Créer un accès</a><br>
<a href='/admin/moderation'>Modération accès Délogé</a><br>

<?php
}

if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='deloge')) {
?>

<h2>Consulter les offres</h2>
<a href='/offer/list/restauration'>Restauration</a><br>
<a href='/offer/list/blanchisserie'>Blanchisserie</a><br>
<a href='/offer/list/mobilite'>Mobilité</a><br>
<a href='/offer/list/loisir'>Loisir</a><br>
<a href='/offer/list/don'>Dons</a><br>
<a href='/offer/list/autre'>Autres services</a><br>
<br>

<?php
}

if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) {
?>
<h2>Déposer une offre</h2>

<a href='/offer/new/restauration'>Restauration</a><br>
<a href='/offer/new/blanchisserie'>Blanchisserie</a><br>
<a href='/offer/new/mobilite'>Mobilité</a><br>
<a href='/offer/new/loisir'>Loisir</a><br>
<a href='/offer/new/don'>Don</a><br>
<a href='/offer/new/autre'>Autres services</a><br>

<br>
<a href='/offer/mylist'>Mes offres</a><br>
<?php
}
?>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>
