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
                    </form>
                    <form class="flex column center" action="/deloge/register" method="post">
                        <button class='bg-saumon noir' type="submit"value='Connexion'>Je suis délogé(e)</button>
                    </form>
                    <center class="register-small-links"><a class="small-text under vert" href='/'>Retour</a></center>
                </div>
            </div>
        </div>
    </div>
</body>
