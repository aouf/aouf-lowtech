<?php

if (isset($_SESSION['user_id'])&&(!(isset($_GET['logout'])))) {
    header('Location: accueil');
    exit;
}

session_unset('AOUF_SESS');
session_destroy();
session_name('AOUF_SESS');
session_start();

if (isset($_POST['login'])) {

    $login = strtolower($_POST['login']);
    $password = $_POST['password'];

    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);

    $req = "SELECT id,category,password,status,arrondissement FROM users where login='$login' LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();

    $hash_password = $data['password'];
    $category = $data['category'];
    $status = $data['status'];
    $user_id = $data['id'];
    $arrondissement = $data['arrondissement'];

    //if ($status != 'enabled') die("compte non actif");
    if ((password_verify($password,$hash_password)) && ($status == 'enabled')) {

        $_SESSION['user_login'] = $login;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_category'] = $category;
        $_SESSION['user_arrondissement'] = $arrondissement;
        header("Location: accueil");
        exit;
    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur de mot de passe ou compte inexistant/inactif&nbsp;!</div>";
    }
}

require_once 'head.php';

?>
<body class="bg-noir">
    <div class='home flex column center bg-noir'>
        <div class="content">
            <a href='/'>
                <div class='logo-accueil-deco'></div>
            </a><br>
            <!--<center><a class="small-text button bg-saumon blanc" href='/register'>Créer un compte</a></center>-->
            <div id='connexion-div' class='flex column center'>
                <form id='connexion-form' class='flex center column' method='post'>
                    <label class="vert" for='login'>IDENTIFIANT</label>
                    <input type='text' name='login' placeholder='' required>
                    <label class="vert" for='password'>MOT DE PASSE</label>
                    <input type='password' name='password' required>
                    <button class='bg-vert' type='submit' value='Connexion'>CONNEXION</button>
                </form>
                <center class="index-small-links"><a class="small-text under saumon" href='/register'>Créer un compte</a></center>
                <center class="index-small-links"><a class="small-text under saumon" href='/lostpassword'>Mot de passe oublié</a></center>
            </div>
        </div>
    </div>
</body>
