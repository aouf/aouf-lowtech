<?php

if (isset($_SESSION['user_id'])) {
    header('Location: accueil');
}

require_once 'head.php';

?>
<body>
    
<div class='home flex column center'>
    <div id='logo-accueil-deco'></div>
    <div id='connexion-div' class='flex column center'>
        <form id='connexion-form' class='flex center column' action='/auth' method='post'>
            <label for='login'>Identifiant</label>
            <input type='text' name='login' autofocus placeholder=''>
            <label for='password'>Mot de passe</label>
            <input type='password' name='password'>
            <button class='bg-green' type='submit' value='Connexion'>Connexion</button>
        </form>
        <a href='/register'>S'inscrire</a>
    </div>
</div>

</body>
