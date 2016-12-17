<?php

require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
include_once 'includes/connexion.inc.php';
include_once 'includes/header.inc.php';

//Si le formulaire d'inscription a été validé...
if (isset($_POST['Valider'])) {

    //On vérifie que les deux mots de passe renseignés sont identiques, s'ils le sont...
    if ($_POST['mdp1'] == $_POST['mdp2']) {

        //On va chercher tous les emails des utilisateurs de la base pour vérifier que l'adresse email renseignée n'est pas déjà utilisée afin d'éviter les erreurs de connexion
        $sth = $bdd->prepare("SELECT email FROM utilisateurs");
        $sth->execute();

        $tab_email = $sth->fetchAll(PDO::FETCH_ASSOC);
        print_r($tab_email);
        
        //Utilisation d'une boucle qui, en cas d'adresse identique à une déjà dans la base, incrémente une variable $erreur
        foreach ($tab_email as $value) {

            if ($value['email'] == $_POST['email']) {

                $erreur++;
            }
        }

        //Si la variable $erreur est vide, c-à-d qu'aucune adresse n'a été trouvée, alors on peut valider l'inscription
        if ($erreur == 0) {

            $ins = $bdd->prepare('INSERT INTO utilisateurs (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)'); //Préparation de la requête
            $ins->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $ins->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $ins->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $ins->bindValue(':mdp', $_POST['mdp1'], PDO::PARAM_STR);
            $ins->execute(); // Execute la requête
            $_SESSION['inscri'] = TRUE;
            header("Location: connexion.php");
        } else {
            
            //Si $erreur est différent de 0, c'est à dire qu'un compte existe déjà avec cette adresse mail, 
            //alors renvoie sur la page d'inscription avec une variable de session permettant d'afficher une erreur.
            $_SESSION['email_error'] = TRUE;
            header("Location: inscription.php");
        }
    } else {

        //Si les mots de passe ne sont pas identiques, 
        //alors renvoie sur la page d'inscription avec une variable de session permettant d'afficher une erreur.
        $_SESSION['mdp_error'] = TRUE;
        header("Location: inscription.php");
    }
}

//Affiche le message d'erreur des mots de passe
if (isset($_SESSION['mdp_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        <strong>Les mots de passe sont différents !</strong> Recommencez.
    </div>

    <?php
}

//Affiche le message d'érreur dû au mail
if (isset($_SESSION['email_error'])) {
    ?>
    <div class="alert alert-danger" role="alert">
        <strong>Cet email est déjà utilisé !</strong> Merci d'en utiliser un différent.
    </div>

    <?php
}
?>

<div class="span8">

    <!Formulaire d'inscription | Si l'utilisateur se trompe, les données Nom, Prénom et Email sont automatiquement réafficher dans les champs pour éviter de devoir les retaper!>
    <form action="inscription.php" method="post" enctype="multipart/form-data" id="form_connexion" name="form_inscri">

        <div class="clearfix">

            <label for="titre">Nom</label>
            <div class="input"><input type="text" name="nom" id="nom" value="<?php
if (isset($_POST['nom'])) {
    echo $_POST['nom'];
}
?>" required></div>
        </div>

        <label for="titre">Prénom</label>
        <div class="input"><input type="text" name="prenom" id="prenom" value="<?php
                if (isset($_POST['prenom'])) {
                    echo $_POST['prenom'];
                }
?>" required></div>

        <div class="clearfix">
            <label for="texte">Email</label>
            <div class="input"><input type="text" name="email" id="email" value="<?php
            if (isset($_POST['email'])) {
                echo $_POST['email'];
            }
?>" required></div>
        </div>

        <div class="clearfix">
            <label for="texte">Mot de passe</label>
            <div class="input"><input type="password" name="mdp1" id="mdp1" required></div>
        </div>

        <div class="clearfix">
            <label for="texte">Retaper le mot de passe</label>
            <div class="input"><input type="password" name="mdp2" id="mdp2" required></div>
        </div>

        <div class="form-actions">
            <input type="submit" name="Valider" value="Valider" class="btn btn-large btn-primary">
        </div>

</div>
<?php
include_once 'includes/menu.inc.php';
include_once 'includes/footer.inc.php';
?>