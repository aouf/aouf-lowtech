<?php

require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

$couleur = $_SESSION['user_category'] == "benevole" || $_SESSION['user_category'] == "admin"? 'vert' : 'saumon';
?>

<div class="container bg-blanc noir">
<h2>Mes messages</h2>
<div class="bg-blanc list-messages">

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT DISTINCT offer_id,from_id,to_id FROM messages WHERE from_id=$user_id OR to_id=$user_id";
$statement = $pdo->query($req);

$msg[0][0] = FALSE;

while ($data = $statement->fetch()) {

$offer_id = $data['offer_id'];
$with_id = $data['from_id'];

$req2 = "SELECT * FROM offers WHERE id=$offer_id LIMIT 1;";
$statement2 = $pdo->query($req2);
$data2 = $statement2->fetch();
$titre = ucfirst($data2['title']);

$req3 = "SELECT * FROM messages WHERE offer_id=$offer_id ORDER BY id DESC;";
$statement3 = $pdo->query($req3);
$data3 = $statement3->fetch();
$message = $data3['message'];

if ($with_id == $user_id) $with_id = $data['to_id'];

$req4 = "SELECT * FROM users WHERE id=$with_id LIMIT 1;";
$statement4 = $pdo->query($req4);
$data4 = $statement4->fetch();
$prenom = ucfirst($data4['firstname']);
$nom = strtoupper(substr($data4['name'], 0, 1)).'.';
$nomComplet = $prenom.' '.$nom;

if (!isset($msg[$offer_id][$with_id])) {
    $msg[$offer_id][$with_id] = TRUE;
                echo "<div class='message-link border-$couleurflex border-$couleur'>";
                    echo "<a class='' href='/message/write/$offer_id/$with_id'>";
                        echo "<div class='full-size bg-blanc'>";
                            echo "<h3 class='noir'>$nomComplet ($titre)</h3>";
                            echo "<p class='message-excerpt noir'>$message</p>";
                        echo "</div>";
                    echo "</a>";
                echo "</div>";
    }
}

    ?>
    </div>
</div>
<?php
require_once 'footer.php';
