<?php

if ($_SESSION['login_type']=='admin') {
?>

<h2>Admin</h2>
<a href='/admin/register'>Créer un accès</a><br>
<a href='/admin/deloges'>Modération accès Délogé</a><br>

<?php
}

if (($_SESSION['login_type']=='admin')||($_SESSION['login_type']=='deloge')) {
?>

<h2>Consulter les offres</h2>
<a href='/annonce/list/restauration'>Restauration</a><br>
<a href='/annonce/list/blanchisserie'>Blanchisserie</a><br>
<a href='/annonce/list/mobilite'>Mobilité</a><br>
<a href='/annonce/list/loisir'>Loisir</a><br>
<a href='/annonce/list/don'>Dons</a><br>
<a href='/annonce/list/autre'>Autres services</a><br>
<br>

<?php
}

if (($_SESSION['login_type']=='admin')||($_SESSION['login_type']=='benevole')) {
?>
<h2>Déposer une annonce</h2>

<a href='/annonce/new/restauration'>Restauration</a><br>
<a href='/annonce/new/blanchisserie'>Blanchisserie</a><br>
<a href='/annonce/new/mobilite'>Mobilité</a><br>
<a href='/annonce/new/loisir'>Loisir</a><br>
<a href='/annonce/new/don'>Don</a><br>
<a href='/annonce/new/autre'>Autres services</a><br>

<br>
<a href='/annonce/mylist'>Mes offres</a><br>
<?php
}
?>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>
