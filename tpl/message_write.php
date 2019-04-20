<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$login_id = $_SESSION['login_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/message/write/(\d+)$#', $uri, $matches);
$annonce_id = (int)$matches[1];
if (!is_int($annonce_id)) {
    print "Erreur, annonce inexistante";
    die();
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $to = $_POST['to'];
    $req = "INSERT INTO messages(offer_id,from_id,to_id,message) VALUES (?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$annonce_id, $login_id, $to, $message]);
}

?>

<h2>Messagerie</h2>

<?php

$req = "SELECT * FROM offers where id = $annonce_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$offer_title = $data['title'];
$offer_description = $data['description'];
$offer_userid = $data['user_id'];

print "<h3>Annonce $offer_title</h3>";

$req = "SELECT * FROM messages WHERE offer_id = $annonce_id AND ( from_id=$login_id OR to_id=$login_id )";
$statement = $pdo->query($req);

while ($data2 = $statement->fetch()) {

print $data2['date_create'].":<br>".$data2['message']."<br><br>";
}
?>

<form method='post'>
<textarea name='message'></textarea>
<br><br>
<input type='hidden' name='to' value='<?php print $offer_userid; ?>'>
<input type='submit' value='Publier'>
</form>
