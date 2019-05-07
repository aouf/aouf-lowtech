<?php

require_once 'head.php';
require_once 'header.php';

$user_id = $_SESSION['user_id'];
?>

<div class="container bg-blanc noir full-size">
<h2>Mes messages</h2>
<div class="bg-saumon full-size list-offres">

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT DISTINCT offer_id,from_id,to_id FROM messages WHERE from_id=$user_id OR to_id=$user_id";
$statement = $pdo->query($req);

$msg[0][0] = FALSE;

while ($data = $statement->fetch()) {

$message = "blabla";
$offer_id = $data['offer_id'];
$with_id = $data['from_id'];
if ($with_id == $user_id) $with_id = $data['to_id'];

if (!isset($msg[$offer_id][$with_id])) {
    $msg[$offer_id][$with_id] = TRUE;
            echo "<a class='offre' href='/message/write/$offer_id/$with_id'>";
                    echo "<div class='flex'>";
                        echo "<div class='full-size bg-blanc'>";
                            echo "<h3 class='noir'>$with_id (offre $offer_id)</h3>";
                            echo "<p class='description noir'>$message</p>";
                        echo "</div>";
                    echo "</div>";
                echo "</a>";
        }
    }

    ?>
    </div>
</div>
<?php
require_once 'footer.php';
