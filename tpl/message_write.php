<?php
require_once 'head.php';
require_once 'header.php';

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/message/write/(\d+)/(\d+)$#', $uri, $matches);
$offer_id = (int)$matches[1];
$with_id = (int)$matches[2];
if (!($offer_id>0)) {
    print "Erreur, offre inexistante";
    die();
}
if (!($with_id>0)) {
    print "Erreur, user inexistant";
    die();
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $to_id = $_POST['to'];
    $req = "INSERT INTO messages(offer_id,from_id,to_id,message) VALUES (?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$offer_id, $user_id, $to_id, $message]);

    // send email for notification
    $req = "SELECT email,phonenumber,notification FROM users WHERE id=$to_id LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();
    $email_addr = $data['email'];
    $phone_number = $data['phonenumber'];
    $notification = $data['notification'];
    if (($notification == 'email')&&($email_addr != '')) {
        $headers_mail = "MIME-Version: 1.0\n";
        $headers_mail .= 'From: '.$conf['mail']['from']."\n";
        $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
        $body_mail = "Bonjour,

Vous avez reçu un nouveau message via AOUF :

$message

Pour répondre :
https://low.aouf.fr/message/list

-- 
L'equipe Aouf
";
        mail($email_addr,'Nouveau message Aouf',$body_mail,$headers_mail);
    }
    if (($notification == 'sms')&&($phone_number != '')) {
        $body_sms = 'Nouveau+message+via+AOUF+:+https://low.aouf.fr/message/list';
        $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($ch);
        curl_close($ch);
    }
}

?>

<h2>Messagerie</h2>

<?php

$req = "SELECT * FROM offers where id = $offer_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$offer_title = $data['title'];
$offer_description = $data['description'];
$offer_userid = $data['user_id'];
$offer_arrondissement = $data['arrondissement'];
$offer_address = $data['address'];
$offer_date_start = $data['date_start'];
$offer_date_end = $data['date_end'];

print "<h3>Annonce : $offer_title</h3>";

print "<p>$offer_description</p>";

print "<p>Arrondissement : $offer_arrondissement</p>";
print "<p>Adresse : $offer_address</p>";
print "<p>Disponible entre $offer_date_start et $offer_date_end</p>";

if ($data['picture'] != 'NULL') {
    $picture = base64_encode($data['picture']);
    print "<img src='data:image/jpg;base64,$picture'><br><br>";
}

$req = "SELECT * FROM messages WHERE offer_id = $offer_id AND ( ( from_id=$user_id AND to_id=$with_id ) OR ( from_id=$with_id AND to_id=$user_id ) )";
$statement = $pdo->query($req);

while ($data2 = $statement->fetch()) {

print $data2['date_create'].":<br>".$data2['message']."<br><br>";
}
?>

<form method='post'>
<input type='submit' value='Signaler un contenu inapproprié'>
</form>

<form method='post'>
<textarea name='message'></textarea>
<br><br>
<input type='hidden' name='to' value='<?php print $with_id; ?>'>
<input type='submit' value='Publier'>
</form>

<?php
require_once 'footer.php';
