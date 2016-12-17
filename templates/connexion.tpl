    <div class="span8">
        
        {if isset($statut_connexion) AND $statut_connexion == FALSE}
            
        {* Affiche un message d'erreur si l'email ou le mdp entré est faux *}
        <div class="alert alert-error" role="alert">
        
      <strong>E-mail ou mot de passe incorrects !</strong> Réessayez.
    </div>
            {/if}
        
        {if isset($co_requise)}
            
        {* Affiche un message d'erreur si la personne essaye d'accéder à l'accueil ou à la page Article sans être connectée *}
        <div class="alert alert-error" role="alert">
        
      <strong>Pas si vite !</strong> Connectez-vous.
    </div>
            {/if}
            
        {if isset($inscri)}
            
        {* Affiche un message de réussite si l'inscription s'est déroulée sans problème *}
        <div class="alert alert-success" role="alert">
        
      <strong>Inscription réussie !</strong> Connectez-vous.
    </div>
            {/if}


    <!Formulaire de connexion!>
    <form action="connexion.php" method="post" enctype="multipart/form-data" id="form_connexion" name="form_connexion">

        <div class="clearfix">
            <label for="titre">E-mail</label>
            <div class="input"><input type="text" name="email" id="email"></div>
        </div>

        <div class="clearfix">
            <label for="texte">Mot de passe</label>
            <div class="input"><input type="password" name="mdp" id="mdp"></div>
        </div>

        <div class="form-actions">
            <input type="submit" name="Valider" value="Valider" class="btn btn-large btn-primary">
        </div>
    
    </form>
    
</div>