<?php
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/connexion.inc.php';

//Si l'utilisateur est connecté

if ($_COOKIE['connecte'] == TRUE ) {

    //S'il s'ajout d'un ajout d'article
    if (isset($_POST['ajouter'])) {

        //Date française

        $date_ajout = date("Y-m-d");

        $_POST['date_ajout'] = $date_ajout;

        //Mettre 0 ou 1 dans Publié

        if (isset($_POST['publie'])) {
            $_POST['publie'] = 1;
        } else {
            $_POST['publie'] = 0;
        }


        //print_r($_POST); 
        //print_r($_FILES);
        
        //Teste l'image
        if (isset($_FILES['image']['error'])) {
            if ($_FILES['image']['error'] == 0) {
                echo "Image chargée";

                $sth = $bdd->prepare("INSERT INTO articles (titre, texte, date, publie) VALUES (:titre,:texte,:date,:publie)");

                $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
                $sth->bindValue(':date', $_POST['date_ajout'], PDO::PARAM_STR);
                $sth->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT);

                $sth->execute();

                $last_id = $bdd->lastInsertId();

                move_uploaded_file($_FILES['image']['tmp_name'], dirname(__FILE__) . "/img/$last_id.jpg");

                //echo "Dernier ID ".$last_id;

                $_SESSION['ajout_article'] = TRUE;

                header("Location: article.php");
            } else {
                echo "Une erreur est survenue";
            }
        }

        //$_POST['publie'] = isset($_POST['publie']) ? 1 : 0; //If en 1 ligne
        
        //S'il s'agit d'une modification d'article
    } elseif (isset($_POST['modifier'])) {
        
        if (isset($_POST['publie'])) {
            $_POST['publie'] = 1;
        } else {
            $_POST['publie'] = 0;
        }

        $sth = $bdd->prepare("UPDATE articles SET titre = :titre, texte = :texte, publie = :publie WHERE id = :id ");

        $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
        $sth->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT);
        $sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);


        $sth->execute();

        $id = $_POST['id'];

        if (isset($_FILES['image']['error'])) {
            if ($_FILES['image']['error'] == 0) {
                echo "Image chargée";

                $sth = $bdd->prepare("INSERT INTO articles (titre, texte, date, publie) VALUES (:titre,:texte,:date,:publie)");

                $sth->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
                $sth->bindValue(':texte', $_POST['texte'], PDO::PARAM_STR);
                $sth->bindValue(':date', $_POST['date_ajout'], PDO::PARAM_STR);
                $sth->bindValue(':publie', $_POST['publie'], PDO::PARAM_INT);

                $sth->execute();

                $last_id = $bdd->lastInsertId();

                move_uploaded_file($_FILES['image']['tmp_name'], dirname(__FILE__) . "/img/$last_id.jpg");

                //echo "Dernier ID ".$last_id;

                $_SESSION['ajout_article'] = TRUE;

                header("Location: article.php");
            } else {
                echo "Une erreur est survenue";
            }
        }

        $_SESSION['modif_article'] = TRUE;
        header("Location: article.php?id=$id");
    } else {

        include_once 'includes/header.inc.php';

        if (isset($_GET['id'])) {

            $sth = $bdd->prepare("SELECT * FROM articles WHERE id = :id"); //Préparation de la requête
            $sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT); //Enlève tout ce qui n'est pas numérique
            $sth->execute();

            $tab_article = $sth->fetchAll(PDO::FETCH_ASSOC);

//print_r($tab_article);

            $titre = $tab_article[0]['titre'];
            $texte = $tab_article[0]['texte'];
            $publie = $tab_article[0]['publie'];
        }

        $publie = isset($publie) ? $publie : 0;

        //Si l'article a bien été ajouté
        if (isset($_SESSION['ajout_article'])) {
            ?>

            <div class="alert alert-success" role="alert">
                <strong>Bien joué !</strong> Article ajouté.
            </div>

            <?php
            unset($_SESSION['ajout_article']);
            
            //Si l'article a bien été modifié
        } elseif (isset($_SESSION['modif_article'])) {
            ?>

            <div class="alert alert-success" role="alert">
                <strong>Oh !</strong> Article modifié.
            </div>
            <?php
            unset($_SESSION['modif_article']);
        }
        ?>

        <div class="span8">

            <?php
            //Modification du titre de la page en fonction d'un ajout ou d'une modification
            if (isset($_GET['id'])) {

                echo "<h3>Modification d'un article</h3>";
            } else {
                echo "<h3>Création d'un article</h3>";
            }
            ?>

            <form action="article.php" method="post" enctype="multipart/form-data" id="form_article" name="form_article">

                <div class="clearfix">
                    <label for="titre">Titre</label>
                    <div class="input"><input type="text" name="titre" id="titre" value="<?php
                        if (isset($titre)) {
                            echo $titre;
                        }
                        ?> "></div>
                </div>

                <div class="clearfix">
                    <label for="texte">Texte</label>
                    <div class="input"><textarea type="text" name="texte" id="texte"><?php
                            if (isset($texte)) {
                                echo $texte;
                            }
                            ?></textarea></div>
                </div>

                <!Ne demande pas d'ajouter un fichier IMAGE s'il s'agit d'une modification d'article!>
                <?php if (!isset($_GET['id'])){?> 
                <div class="clearfix">
                    <label for="image">Image</label>
                    <div class="input"><input type="file" name="image" id="image" ></div>
                </div>
                <?php }?>
                <div class="clearfix">
                    <label for="publie">Publier</label>
                    <div class="input"><input type="checkbox" name="publie" id="publie" <?php
                        if ($publie == 1) {
                            echo "checked";
                        }
                        ?>></div>
                </div>

                <?php
                //Passe en paramètre caché l'ID de l'article en cours de modification
                if (isset($_GET['id'])) {
                    ?>

                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />

                    <div class="form-actions">
                        <input type="submit" name="modifier" value="Modifier" class="btn btn-large btn-primary">
                        <a href="supprimer.php?id=<?php echo $_GET['id'] ?>" class="btn btn-large btn-danger">Supprimer</a>
                    </div>

                    <?php
                } else {
                    ?>

                    <div class="form-actions">
                        <input type="submit" name="ajouter" value="Ajouter" class="btn btn-large btn-primary">
                    </div>
                    <?php
                }
                ?>

            </form>

        </div>

        <?php
    }
} else {
    //Si l'utilisateur n'est pas connecté, renvoie sur la page connexion avec une alerte.
    $_SESSION['co_requise'] = TRUE;
    header("Location: connexion.php");
}
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';
?>
