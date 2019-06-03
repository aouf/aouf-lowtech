<?php
require_once 'head.php';

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['login'])) {
    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);
    $login = $_POST['login'];
    // TODO: à sécuriser contre les XSS
    $req = "SELECT id,status,email,phonenumber,category FROM users WHERE login='".$login."' LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();

    $id = $data['id'];
    $status = $data['status'];
    $email_addr = $data['email'];
    $phone_number = $data['phonenumber'];
    $category = $data['category'];

    $token = sha1(random_bytes(128));

    if ($status == 'enabled') {

        $req = "UPDATE users set create_token='$token' WHERE id = $id";
        $statement = $pdo->prepare($req);
        $statement->execute();

        if ($email_addr != '') { 
            // send email for reset password
            $headers_mail = "MIME-Version: 1.0\n";
            $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
            $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
            $body_mail = "Bonjour,

Vous pouvez changer le mot de passe de votre compte en cliquant sur ce lien :

https://beta.aouf.fr/reset/$token

(valable pendant 24h)

--
L'equipe Aouf
";
            mail($email_addr,'Changement de mot de passe de votre compte Aouf',$body_mail,$headers_mail);
        }

        if (($category=='deloge')&&($phone_number != '')) {
            $body_sms = "Changer+votre+mot+de+passe+AOUF+:+https://beta.aouf.fr/reset/$token";
            $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_exec($ch);
            curl_close($ch);
        }

    }
    echo "<div class='erreur noir bg-saumon center'>Si vous avez un compte valide, vous allez recevoir un message pour modifier votre mot de passe&nbsp;!</div>";
}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Mot de passe oublié</h2>
        </div>
        <form class='full-size flex center column' method='post'>
            <label for="login">Identifiant&nbsp;:</label>
            <input type='text' name='login' placeholder="Votre identifiant" required>
                        <button class='bg-vert noir' type="submit" value="Réinitialiser mon mot de passe">Réinitialiser mon mot de passe</button>
        </form>
        <center class="index-small-links"><a class="small-text under saumon" href='/lostlogin'>Identifiant oublié</a></center>
        <center class="register-small-links"><a class="small-text under vert" href='/'>Retour</a></center>
</div>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
