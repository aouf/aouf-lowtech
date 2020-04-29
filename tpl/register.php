<?php
require_once 'head.php';
?>
<body>
    <div class='home flex column center bg-noir'>
        <div class="content flex column center">
            <div class="content">
                <a href='/'>
                    <div class='logo-accueil-deco'></div>
                </a>
                <div class="formulaires flex column center">
                    <form class="flex column center" action="/benevole/register" method="post">
                        <button class='bg-vert' type='submit' value='Connexion'>Je veux aider</button>
                        <center>Je veux proposer mon aide, répondre a un besoin</center>
                    </form>
                    <form class="flex column center" action="/coordinateur/register" method="post">
                        <button class='bg-saumon noir' type='submit' value='Connexion'>Je veux coordonner de l'aide/MCS</button>
                        <center>Collectif, asso, travailleur social… je souhaite commander couches/lait pour des familles ou poster un besoin</center>
                    </form>
                    <form class="flex column center" action="/deloge/register" method="post">
                        <button class='bg-saumon noir' type="submit"value='Connexion'>J'ai besoin d'aide</button>
                        <center>J'ai un besoin d'urgence</center>
                    </form>
                    <center class="register-small-links"><a class="small-text under vert" href='/'>Retour</a></center>
                </div>
            </div>
        </div>
    </div>
</body>
