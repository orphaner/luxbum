<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />


    <title><mx:text id="titre_page"/></title>
    <mx:bloc id="stylesheet">
      <link mXattribut="rel:rel;href:href;title:title" type="text/css" />
    </mx:bloc id="stylesheet">
  </head>

  <body id="body_commentaire">


    <div id="voircomment">
    <h2>Commentaires</h2>
    <mx:bloc id="comments">
      <div id="c-content">
        <div class="comment-post">
          <div class="comment-info">Le <mx:text id="date"/>, 
            <strong><mx:text id="auteur"/></strong>
            <mx:bloc id="site">:: <a mXattribut="href:lien" ref="nofollow" target="_blank">site</a></mx:bloc id="site">
            <mx:bloc id="email">:: <a mXattribut="href:lien">email</a></mx:bloc id="email">
          </div>
          <div id="co1" class="comment-content">
            <mx:text id="content"/>
          </div>
        </div>
      </div>
    </mx:bloc id="comments">
    </div>


    <div id="addcomment">
      <h2>Ajouter un commentaire</h2>
      
      <div id="ac-content">        
        <form method="post" id="ajout_commentaire" mXattribut="action:action">

          <fieldset>
            <legend>Ajouter un commentaire</legend>
            <p>
              <label for="auteur" class="float"><strong>Nom ou pseudo</strong> : </label>
              <input type="text" name="auteur" id="auteur" mXattribut="value:val_auteur"/>
              <span class="erreur"><mx:text id="err_auteur"/></span>
            </p>
            <p>
              <label for="site" class="float">Site Web : </label>
              <input type="text" name="site" id="site" mXattribut="value:val_site"/>
              <span class="erreur"><mx:text id="err_site"/></span>
            </p>
            <p>
              <label for="email" class="float">Email : </label>
              <input type="text" name="email" id="email" mXattribut="value:val_email"/>
              <span class="erreur"><mx:text id="err_email"/></span>
            </p>
            <p>
              <label for="content" class="float"><strong>Commentaire</strong> : </label>
              <textarea name="content" id="content" cols="40" rows="5"><mx:text id="val_content"/></textarea>
              <span class="erreur"><mx:text id="err_content"/></span>
            </p>
          </fieldset>

          <p>
            <input type="submit" value="Ajouter"/>
            <input type="reset" value="Effacer"/>
          </p>
        </form>
      </div>
    </div>

    <p><a href="javascript:window.close();">Fermer la fenêtre</a></p>
  </body>
</html>
