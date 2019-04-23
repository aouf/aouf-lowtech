<?php
require_once 'head.php';
require_once 'header.php';
?>

<h2>Mes parametres</h2>
<h3>Mon profil</h3>
Nom : <input type='text'>*<br>
Prénom : <input type='text'>*<br>
Numéro de téléphone : <input type='text'>*<br>
Email : <input type='text'><br>
Adresse : <input type='text'><br>
<h3>Mot de passe</h3>
Mot de passe actuel : <input type='password'><br>
Nouveau mot de passe : <input type='password'><br>
<h3>Mes notifications</h3>
<input type='checkbox'>email
<input type='checkbox'>SMS<br><br>
<input type='submit' value='Valider'>

<br>
<a href='/accueil'>Accueil</a><br>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>

<?php
require_once 'footer.php';
