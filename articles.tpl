    {if(isset($_SESSION['ajout_article']))}

    <div class="alert alert-success" role="alert">
      <strong>Bien joué !</strong> Article ajouté.
    </div>

     unset($_SESSION['ajout_article']);

    {elseif(isset($_SESSION['modif_article']))}

    <div class="alert alert-success" role="alert">
      <strong>Oh !</strong> Article modifié.
    </div>
    

        unset($_SESSION['modif_article']);
        
    {/if}


<div class="span8">

{if(isset($_GET['id']))}
    
 <h3>Modification d'un article</h3>
    
{else}
        
 <h3>Création d'un article</h3>

{/if}
    
<form action="article.php" method="post" enctype="multipart/form-data" id="form_article" name="form_article">

    <div class="clearfix">
        <label for="titre">Titre</label>
        <div class="input"><input type="text" name="titre" id="titre" value="{if(isset($titre))}{$titre;}{/if}?> "></div>
    </div>
    
    <div class="clearfix">
        <label for="texte">Texte</label>
        <div class="input"><textarea type="text" name="texte" id="texte"><?php if(isset($texte)){echo $texte;}?></textarea></div>
    </div>
    
    <div class="clearfix">
        <label for="image">Image</label>
        <div class="input"><input type="file" name="image" id="image" ></div>
    </div>
    
    <div class="clearfix">
        <label for="publie">Publier</label>
        <div class="input"><input type="checkbox" name="publie" id="publie" <?php if($publie==1){echo "checked";}?>></div>
    </div>
    
    <?php 
    if(isset($_GET['id']))
    {
    ?>
    
    <input type="hidden" name="id" value="<?php echo $_GET['id']?>" />

    <div class="form-actions">
        <input type="submit" name="modifier" value="Modifier" class="btn btn-large btn-primary">
    </div>
    
    <?php
    }  
    else
    {
    ?>
    
    <div class="form-actions">
        <input type="submit" name="ajouter" value="Ajouter" class="btn btn-large btn-primary">
    </div>
    <?php
    }
    ?>
    
</form>

 </div>