<?php

require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
require_once('libs/Smarty.class.php');
include_once 'includes/connexion.inc.php';

//Si le formulaire de connexion a été posté...
if (isset($_POST['Valider'])) {

    //Va chercher les information de l'utilisateur dans la base à partir de l'email et du mdp renseignés
    $sth = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = :email AND mdp = :mdp");
    $sth->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $sth->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
    $sth->execute();

    $tab_users = $sth->fetchAll(PDO::FETCH_ASSOC);
//print_r($tab_users);

    $count = $sth->rowCount();

    //Si un utilisateur correspond aux données renseignés...
    if ($count == 1) {

        $sid = md5($email . time());
        setcookie('sid', $sid, time() + 3600);

        echo $sid;
        $upd = $bdd->prepare("UPDATE utilisateurs SET sid = :sid WHERE id = :id");//MAJ de son SID permettant de le définir comme Connecté
        $upd->bindValue(':sid', $sid, PDO::PARAM_INT);
        $upd->bindValue(':id', $tab_users[0]['id'], PDO::PARAM_STR);
        $upd->execute();

        //Création d'une variable de session qui permettra d'afficher un message de connexion & renvoie sur la page d'accueil
        $_SESSION['statut_connexion'] = TRUE;
        header("Location: index.php");
    } else {

        //Si aucune utilisateur ne correspond à ses données, renvoie sur la page connexion avec un message d'erreur à partir d'une variable de session
        $_SESSION['statut_connexion'] = FALSE;

        header("Location: connexion.php");
    }
} else {

    //Smarty
    $smarty = new Smarty();

    $smarty->template_dir = 'templates/';
    $smarty->compile_dir = 'templates_c/';
    //$smarty->config_dir   = '/web/www.example.com/guestbook/configs/';
    //$smarty->cache_dir    = '/web/www.example.com/guestbook/cache/';

    if (isset($_SESSION['statut_connexion'])) {
        $smarty->assign('statut_connexion', $_SESSION['statut_connexion']);
    }

    if (isset($_SESSION['co_requise'])) {
        $smarty->assign('co_requise', $_SESSION['co_requise']);
    }

    if (isset($_SESSION['inscri'])) {
        $smarty->assign('inscri', $_SESSION['inscri']);
    }

    unset($_SESSION['statut_connexion']);
    unset($_SESSION['co_requise']);
    unset($_SESSION['inscri']);

    //** un-comment the following line to show the debug console
    $smarty->debugging = true;

    include_once 'includes/header.inc.php';

    $smarty->display('connexion.tpl');

    include_once 'includes/menu.inc.php';
    include_once 'includes/footer.inc.php';
}
?>