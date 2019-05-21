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
    echo "<div class='erreur noir bg-saumon center'>Compte n° <strong>$moderation_id</strong> activé&nbsp;!</div>";
}

?>
<div class="container bg-blanc noir">
<h2>Liste des comptes à modérer</h2>
<div class="bg-blanc list-messages">

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
<?php
echo "<div class='message-link border-vertflex border-vert'>";
echo "- Compte <strong>$moderation_prenom $moderation_nom</strong> ($moderation_login) : $moderation_phonenumber (numéro de téléphone) / $moderation_email (email) : <a href='/admin/moderation/$moderation_id'>Activer</a>";
echo "</div>";
?>
<?php
}
    ?>
    </div>
</div>
<?php
require_once 'footer.php';
?>
