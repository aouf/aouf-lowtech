<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['message'])) {
$message = $_POST['message'];
$req = "INSERT INTO messages(offer_id,from_id,to_id,message) VALUES (?,?,?,?)";
$statement = $pdo->prepare($req);
$statement->execute([42, 42, 42, $message]);
}

?>

<h2>Messagerie</h2>

<h3>ANNONCE XYZ</h3>

<?php
$req = "SELECT * FROM messages";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

print $data['date_create'].":<br>".$data['message']."<br><br>";
}
?>

<form action='/message/write' method='post'>
<textarea name='message'></textarea>
<br><br>
<input type='submit' value='Publier'>
</form>
