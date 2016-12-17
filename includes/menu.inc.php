<nav class="span4">
    <h3>Menu</h3>

    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="article.php">Rédiger un article</a></li>
        
        <!Si l'utilisateur est connecté, afficher le bouton Déconnexion...!>
        <?php if ($_COOKIE['connecte'] == TRUE ) { ?>

            <li><a href="deconnexion.php">Déconnexion</a></li>

            <!Sinon, afficher le bouton S'insrire!>
        <?php } else { ?>

            <li><a href="inscription.php">S'inscire</a></li>

        <?php } ?>
    </ul>

</nav>
</div>