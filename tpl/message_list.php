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
$req = "SELECT DISTINCT offer_id,from_id,to_id FROM messages WHERE from_id=$user_id OR to_id=$user_id";
$statement = $pdo->query($req);

$msg[0][0] = FALSE;

while ($data = $statement->fetch()) {

$offer_id = $data['offer_id'];
$with_id = $data['from_id'];
if ($with_id == $user_id) $with_id = $data['to_id'];

$req_offer = "SELECT * FROM offers WHERE id=$offer_id LIMIT 1";
$statement_tmp = $pdo->query($req_offer);
$data_offer = $statement_tmp->fetch();
$titre = ucfirst($data_offer['title']);

$req_thread = "SELECT * FROM messages WHERE offer_id=$offer_id AND ((from_id=$user_id AND to_id=$with_id) OR (from_id=$with_id AND to_id=$user_id )) ORDER BY id DESC";
$statement_tmp = $pdo->query($req_thread);
$data_thread = $statement_tmp->fetch();
$message = $data_thread['message'];

$req_with_user = "SELECT * FROM users WHERE id=$with_id LIMIT 1";
$statement_tmp = $pdo->query($req_with_user);
$data_with_user = $statement_tmp->fetch();
$prenom = ucfirst($data_with_user['firstname']);
$nom = strtoupper(substr($data_with_user['name'], 0, 1)).'.';
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
