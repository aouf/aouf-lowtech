<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/message/write/(\d+)/(\d+)$#', $uri, $matches);
$offer_id = (int)$matches[1];
$with_id = (int)$matches[2];
if (!($offer_id>0)) {
    echo "Erreur, offre inexistante";
    die();
}
if (!($with_id>0)) {
    echo "Erreur, user inexistant";
    die();
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $to_id = $_POST['to'];
    $column = "messages";
    
    $categoryOfferReq = "SELECT category FROM offers WHERE id = $offer_id;";
    $categoryOfferstatement = $pdo->query($categoryOfferReq);
    $categoryOfferdata = $categoryOfferstatement->fetch();
    $categoryOffer = $categoryOfferdata[0];

    $verifreq = "SELECT count(id) FROM messages WHERE id = (SELECT MAX(id) FROM messages) AND offer_id=$offer_id AND from_id=$user_id AND message =".$pdo->quote($message)." AND to_id=$to_id;";
    $verifstatement = $pdo->query($verifreq);
    $verifdata = $verifstatement->fetch();

    if($verifdata["count(id)"] == 0)
    {
        $req = "INSERT INTO messages(offer_id,from_id,to_id,message) VALUES (?,?,?,?)";
        $statement = $pdo->prepare($req);
        $statement->execute([$offer_id, $user_id, $to_id, $message]);

        // on met a jour le lastactivity/modify de l'utilisateur
        $lastactivity = date('Y-m-d H:i:s');
        $req = "UPDATE users set date_lastactivity = '$lastactivity', date_modify = '$lastactivity' WHERE id = $user_id";
        $statement = $pdo->prepare($req);
        $statement->execute();

        // send email for notification
        $req = "SELECT email,phonenumber,notification,category FROM users WHERE id=$to_id LIMIT 1";
        $statement = $pdo->query($req);
        $data = $statement->fetch();
        $email_addr = $data['email'];
        $phone_number = $data['phonenumber'];
        $notification = $data['notification'];
        $category = $data['category'];
        if ((($notification == 'email')||($notification == 'email+sms'))&&($email_addr != '')) {
            $headers_mail = "MIME-Version: 1.0\n";
            $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
            $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
            $body_mail = "Bonjour,

    Vous avez reçu un nouveau message via AOUF :

    $message

    Pour répondre :
    https://beta.aouf.fr/message/list

    -- 
    L'equipe Aouf
    ";
            if ($categoryOffer!='couches') {
                mail($email_addr,'Nouveau message Aouf',$body_mail,$headers_mail);
            }
        }

        // Notification SMS
        if ($categoryOffer!='couches') {
            if ((($notification == 'sms')||($notification == 'email+sms'))&&($phone_number != '')) {
                $body_sms = 'Nouveau+message+via+AOUF+:+https://beta.aouf.fr/message/list';
                $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_exec($ch);
                curl_close($ch);
            }
        }
    }
    else {
        echo "<div class='erreur noir bg-saumon center'>Ce message a déjà été posté.</div>";
    }
}

$req = "SELECT * FROM offers where id = $offer_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$offer_title = $data['title'];

$req = "SELECT * FROM users WHERE id=$with_id limit 1";
$statement = $pdo->query($req);
$data1 = $statement->fetch();

$prenom = ucfirst($data1['firstname']);
$nom = strtoupper(substr($data1['name'], 0, 1)).'.';
$nomComplet = $prenom.' '.$nom;

$req = "SELECT * FROM messages WHERE offer_id = $offer_id AND ( ( from_id=$user_id AND to_id=$with_id ) OR ( from_id=$with_id AND to_id=$user_id ) )";
$statement = $pdo->query($req);
?>

<h2 class="bg-saumon"><?php echo $nomComplet ?></h2>
<section class="conversation bg-blanc">

    <div class="header2 bg-blanc flex center">
        <h2 class="saumon margin-right"><?php echo $offer_title ; ?></h2>
    </div>

<?php 
while ($data2 = $statement->fetch()) {
    if ($data2['from_id'] == $user_id) {
        echo "<div class='right'>";
            echo "<div class='message-div message-div-droite border-vert'>";
                echo "<p class='message left noir'>".$data2['message']."</p>";
                echo "<p class='message-date right vert'>".date("H:i (d.m.Y)",strtotime($data2['date_create']))."</p>";
                echo "<div class='fleche fleche-droite'></div>";
            echo "</div>";
        echo "</div>";
    } else {
        echo "<div class='left'>";
            echo "<div class='message-div message-div-gauche border-saumon'>";
                echo "<p class='message left noir'>".$data2['message']."</p>";
                echo "<p class='message-date right saumon'>".date("H:i (d.m.Y)",strtotime($data2['date_create']))."</p>";
                echo "<div class='fleche fleche-gauche'></div>";
            echo "</div>";
        echo "</div>";
    }
}
?>
<p><a class="small-text saumon" href='/report'><image class='ico-mini' src='/images/attention.png' /> <span class="under">Signaler un problème</span></a></p>
</section>
<section class="message-form bg-saumon">
    <form id="sendMessageForm" class="flex center" method='post'>
        <input type='hidden' name='to' value='<?php echo $with_id; ?>'>
        <section class="relative">
            <textarea class='message-input bg-blanc' name='message'></textarea>
            <button class="submit-message" type='submit' value='Ok'>ok</button>
        </section>
    </form>
</section>

<?php
require_once 'footer.php';
