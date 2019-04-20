<?php

if (isset($_SESSION['user_id'])) {
    header("Location: accueil");
}

?>

<h2>Aouf low-tech</h2>
<form action='/auth' method='post'>
Identifiant : <input type='text' name='login'><br>
Mot de passe : <input type='password' name='password'><br>
<input type='submit' value='Connexion'><br>
</form>
<a href='/register'>S'inscrire</a><br><br>
