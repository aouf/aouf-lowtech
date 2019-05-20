<?php
require_once 'head.php';
require_once 'header.php';

if ($_SESSION['user_category']!='admin') {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/admin/moderation/(\d+)$#', $uri, $matches);
$moderation_id = (int)$matches[1];

if ($moderation_id>0) {
    $req = "UPDATE users set status='enabled' WHERE id = $moderation_id";
    $statement = $pdo->prepare($req);
    $statement->execute();
    echo "Compte n° <strong>$moderation_id</strong> activé&nbsp;!<br>";
}

?>



<?php
$req = "SELECT * from users WHERE status='unvalidated'";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$moderation_id = $data['id'];
$moderation_login = $data['login'];
$moderation_nom = $data['name'];
$moderation_prenom = $data['firstname'];
$moderation_email = $data['email'];
$moderation_phonenumber = $data['phonenumber'];
?>
<div class="bg-noir">
    <h2>Liste des comptes à modérer</h2>
<?php
echo "- Compte $moderation_prenom $moderation_nom ($moderation_login) : $moderation_phonenumber / $moderation_email <a href='/admin/moderation/$moderation_id'>Activer</a><br>";
?>
</div>
<?php
}

require_once 'footer.php';
?>
