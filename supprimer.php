<?php
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/connexion.inc.php';
include_once 'includes/header.inc.php';

//Si l'utilisateur est connecté

if ($_COOKIE['connecte'] == TRUE ) {

    //Si dans l'url il existe la variable supprimer qui confirme la suppresion...
    if (isset($_GET['supp'])) {
        
        //Suppresion de l'article à partir de son ID renseigné en GET
        $del = $bdd->prepare("DELETE FROM articles WHERE id = :id"); //requete préparer
        $del->bindValue(':id', $_GET['id'], PDO::PARAM_INT); //Sécurise la requete
        $del->execute();
        
        ////Suppresion des commentaires de l'article à partir de son ID renseigné en GET
        $del2 = $bdd->prepare("DELETE FROM commentaires WHERE id_article = :id"); //requete préparer
        $del2->bindValue(':id', $_GET['id'], PDO::PARAM_INT); //Sécurise la requete
        $del2->execute();

        //Renvoie sur la page index & création d'une variable de session qui permettra d'afficher un message de confirmation de suppresion
        $_SESSION['supprimer'] = TRUE;
        header("Location: index.php");
    }
    ?>
    <div class="span8">

        <!Demande de confirmation de suppresion!>
        <div class="alert alert-danger" role="alert">
            <strong>Êtes-vous sûr de vouloir supprimer cet article ?</strong>
        </div>

        <div style="text-align:center">
            <!Si oui, renvoie sur la même page avec une variable en GET qui permettra d'éxecuter le code de suppresion!>
            <a href="supprimer.php?supp=oui&id=<?php echo $_GET['id'] ?>" class="btn btn-large btn-danger">Oui</a>
            <!Si non, renvoie sur la page précedente, c'est à dire celle de modification de l'article!>
            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-large btn-primary">Non</a>
        </div>

    </div>
    <?php
} else {
    //Si l'utilisateur n'est pas connecté, renvoie sur la page connexion
    header("Location: connexion.php");
}
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';
?>