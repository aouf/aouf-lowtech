<?php
require_once 'head.php';

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/reset/(\w+)$#', $uri, $matches);
$token = $matches[1];

if (isset($_POST['login'])) {

    $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

    $login = $_POST['login'];

    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);
    // TODO: à sécuriser contre les XSS
    $req = "SELECT id,status FROM users WHERE create_token='$token' AND login='$login' LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();

    $status = $data['status'];
    $newpassword_id = $data['id'];

    if ($status == 'enabled') {

        $newpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $req = "UPDATE users set password='$newpassword',create_token=NULL WHERE id = $newpassword_id"; 
        $statement = $pdo->prepare($req);
        $statement->execute();

        echo "<div class='erreur noir bg-saumon center'>Mot de passe modifié&nbsp;!</div>";
    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur de changement de mot de passe&nbsp;!</div>";
    }

}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Changer son mot de passe</h2>
        </div>
        <form id='connexion-form' class='flex center column' method='post'>
        <label class="vert" for='login'>Identifiant actuel</label>
        <input type='text' name='login' autofocus placeholder='' required>
        <label class="vert" for='password'>Nouveau mot de passe</label>
        <input type='password' name='password' required>
        <input type='hidden' name='token' value='<?php echo $token ?>'>
        <button class='bg-vert' type='submit' value='Reset'>Changer mon mot de passe</button>
        </form>
        <center class="register-small-links"><a class="small-text under vert" href='/'>Retour</a></center>
</div>
<?php 
    require_once 'footer.php';
