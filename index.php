<?php
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/connexion.inc.php';
include_once 'includes/header.inc.php';

//Si l'utilisateur est connecté
if ($_COOKIE['connecte'] == TRUE) {

    //Si l'utilisateur vient de se connecter, afficher un msg de bienvenue
    if (isset($_SESSION['statut_connexion'])) {
        ?>
        <div class="alert alert-success" role="alert">
            <strong>Bienvenue !</strong> Vous êtes connecté.
        </div>

        <?php
        unset($_SESSION['statut_connexion']);
    }

    //Message en cas de suppression d'un article après la rediction sur la page index
    if (isset($_SESSION['supprimer'])) {
        ?>
        <div class="alert alert-success" role="alert">
            <strong>Et voilà !</strong> Article supprimé.
        </div>

        <?php
        unset($_SESSION['supprimer']);
    }

    //Si le formulaire pour poster un commentaire a été valider, on insert dans la base le commentaire
    if (isset($_POST['Valider'])) {

        $sth = $bdd->prepare("INSERT INTO commentaires (pseudo, mail, commentaire, id_article) VALUES (:pseudo, :mail, :commentaire, :id_article )"); //requete préparer
        $sth->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR); //Sécurise la requete
        $sth->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR); //Sécurise la requete
        $sth->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_INT); //Sécurise la requete
        $sth->bindValue(':id_article', $_POST['id_article'], PDO::PARAM_STR); //Sécurise la requete
        $sth->execute();
    }

    //Fonction pour les pages
    function returnIndex($nombre_article, $page) {

        $debut = ($page - 1) * $nombre_article; //Calcule l'index principal
        return $debut;
    }

    $nombre_article = 2; //Définit le nombre d'article par page
    $page = isset($_GET['p']) ? $_GET['p'] : 1; //S'il n'existe pas de paramètre "p" dans l'URL, on le met d'office à 1

    $IndexDepart = returnIndex($nombre_article, $page);

    //Affiche les articles dont la publication est sur 1
    $sth = $bdd->prepare("SELECT id, titre, texte, DATE_FORMAT(date, '%d/%m/%Y') as date_fr FROM articles WHERE publie = :publie LIMIT $IndexDepart, $nombre_article"); //Préparation de la requête
    $sth->bindValue(':publie', 1, PDO::PARAM_INT); //Enlève tout ce qui n'est pas numérique
    $sth->execute();

    $tab_articles = $sth->fetchAll(PDO::FETCH_ASSOC);
//print_r($tab_articles);

    //Séléction le nombre d'article a afficher pour définir le nombre de pages
    $sql = $bdd->prepare("SELECT COUNT(*) as nbarticles FROM articles WHERE publie = :publie"); //Préparation de la requête
    $sql->bindValue(':publie', 1, PDO::PARAM_INT); //Enlève tout ce qui n'est pas numérique
    $sql->execute(); // Execute la requête

    $tab_articles_2 = $sql->fetchAll(PDO::FETCH_ASSOC); // Crée un tableau avec les champs
//print_r($tab_articles_2);

    $total_article = $tab_articles_2[0]['nbarticles']; //Recherche la donnée "nbarticles" dans le tableau 
//echo "Total d'articles dans la base : ".$total_article."<br>";

    $nb_page = ceil($total_article / $nombre_article); //Calcule le nombre de page qui sera créé pour afficher les articles
//echo "Nombre de pages : ".$nb_page."<br>";
    ?>

    <div class="span8">

        <?php
        //Boucle permettant d'afficher les articles sur la page
        foreach ($tab_articles as $value) {
            ?>
            <h2><?php echo $value['titre'] ?> <a href="article.php?id=<?= $value['id'] ?>"><img src="img/modifier.PNG" width="35xp"></a></h2>

            <img src="img/<?php echo $value['id'] ?>.jpg" width="100xp" alt ="<?php echo $value['titre'] ?>" />
            <p style="text-align: justify;"><?php echo $value['texte'] ?></p>
            <p><em><u>Publié le : <?php echo $value['date_fr'] ?></u></em></p>

            <!Fonction JavaScript permettant d'afficher/cacher le formulaire et les commentaires d'un article!>
            <script type="Text/JavaScript">

                function hideThis(_div){

                var obj = document.getElementById(_div);
                if(obj.style.display == "block")
                obj.style.display = "none";
                else
                obj.style.display = "block";
                }
            </script>
            
            <!Bouton permettant de cacher ou non le formulaire!>
            <input type="button" value="Afficher/Cacher le formulaire et les commentaires" onclick="hideThis('form<?php echo $value['id'] ?>')" />

            <!De base, le formulaire est caché!>
            <form style="display: none;" id="form<?php echo $value['id'] ?>" method="POST" action="index.php"><br />

                <p>

                    Votre pseudo :</label>
                    <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudo" required/>

                    <br />
                    Votre mail :</label>
                    <input type="text" name="mail" id="mail" placeholder="Entrez votre adresse mail" required/>

                    <br />
                    Commentaire :</label>
                    <textarea type="text" name="commentaire" id="commentaire" placeholder="Commentez l'article" required></textarea>    

                    <input type="hidden" name="id_article" value="<?php echo $value['id'] ?>" />

                    <input type="submit" name="Valider" value="Valider" />
                </p>


                <!Va chercher les commentaires correspondant à l'article à partir de son ID!>
                <?php
                $com = $bdd->prepare("SELECT * FROM commentaires WHERE id_article = :id_article"); //Préparation de la requête
                $com->bindValue(':id_article', $value['id'], PDO::PARAM_INT); //Enlève tout ce qui n'est pas numérique
                $com->execute();

                $tab_com = $com->fetchAll(PDO::FETCH_ASSOC);
                $count = $com->rowCount();
                
                //Si l'article dispose de commentaire...
                if($count>=1){
                ?>
                <table border="1" cellpadding="10">
                    <tr>
                        <td><b>Auteur</b></td>
                        <td><b>Commentaire</b></td>
                    </tr>
                    <?php
                    foreach ($tab_com as $value) {
                        ?>
                        <tr>
                            <td><?php echo $value['pseudo'] ?></td>
                            <td><?php echo $value['commentaire'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                
                <!Si l'article ne dispose pas de commentaire!>
                <?php } else { echo "<b>Aucun commentaire</b>";} ?>
            </form>

            </br>
            <?php
        }
        ?>

        <!Crée la pagination!>
        <div class="pagination">
            <ul>
                <li><a>Page :</a></li>

                <?php
                for ($i = 1; $i <= $nb_page; $i++) {
                    ?>

                    <li<?php if ($i == $page) { ?> class='active'<?php } ?>><a href=index.php?p=<?= $i ?>><?= $i ?></a></li>

                    <?php
                }
                ?>
        </div>

    </div>

    <?php
} else {
    //Si l'utilisateur n'est pas connecté, renvoie sur la page connexion avec une alerte.
    $_SESSION['co_requise'] = TRUE;
    header("Location: connexion.php");
}
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';
?>