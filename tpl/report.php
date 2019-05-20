<?php
require_once 'head.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['description'])) {
    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);

    $description = $_POST['description'];

    // notification par email
    $headers_mail = "MIME-Version: 1.0\r\n";
    $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\r\n";

    if ($_FILES['picture']['tmp_name']) {
        $picture = chunk_split(base64_encode(file_get_contents($_FILES['picture']['tmp_name'])));
        $separator = md5(time());

        $headers_mail .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"\r\n";
        $headers_mail .= "Content-Transfer-Encoding: 7bit\r\n";
        $headers_mail .= "This is a MIME encoded message.\r\n";

        $body_mail = "--" . $separator . "\r\n";
        $body_mail .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
        $body_mail .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body_mail .= "Bonjour,

Signalement posté par l'utilisateur $user_id :
    
$description

Voir pièce jointe
    
--  
L'equipe Aouf\r\n\r\n";
        $body_mail .= "--" . $separator . "\r\n";
        $body_mail .= "Content-Type: application/octet-stream; name=screenshot.png\r\n";
        $body_mail .= "Content-Transfer-Encoding: base64\r\n";
        $body_mail .= "Content-Disposition: attachment\r\n\r\n";
        $body_mail .= $picture . "\r\n\r\n";
        $body_mail .= "--" . $separator . "--";

    } else {
        $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\r\n";

        $body_mail = "Bonjour,

Signalement posté par l'utilisateur $user_id :

$description

--
L'equipe Aouf
";
    }

    mail($conf['mail']['admin'],'[aouf] Signalement',$body_mail,$headers_mail);
    echo "<div class='erreur noir bg-saumon center'>Signalement envoyé, merci&nbsp;!</div>";

}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Signaler un problème</h2>
        </div>
        <center><a class="small-text" href='/'>Retour</a></center>
        <form class='full-size flex center column' method='post' enctype='multipart/form-data'>
            <label for="signalement">Signalez un problème (contenu ou échange inapproprié)&nbsp;:</label>
            <textarea name='description' placeholder="Bonjour. Je vous signale que…" required></textarea>
            <label for="">Capture d'écran du problème</label><input type='file' name='picture'>
            <button class='bg-vert noir' type="submit" value="Envoyer mon signalement">Envoyer mon signalement</button>
        </form>
</div>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
