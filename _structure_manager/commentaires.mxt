<h1 id="h1_admin">Gestion des commentaires</h1>

<p class="message"><mx:text id="message"/></p>

<mx:bloc id="filtre">
   <form mXattribut="action:action" method="post">
     <input type="hidden" name="filtre" value="1"/>
     <label for="galerie">Filtrer par galerie</label>
     <mx:select id="galerie"/>
     <input type="submit" value="Valider"/>
   </form>
</mx:bloc id="filtre">

<div class="admcomment">
  <mx:bloc id="comments">
    <h2><mx:text id="photo"/></h2>
    <p class="comment_info">
      <mx:image id="status"/>
      <strong>Auteur</strong> : <mx:text id="auteur"/> - 
      <mx:bloc id="email">
        <strong>Email</strong> : <a mXattribut="href:email"><mx:text id="email"/></a> - 
      </mx:bloc id="email">
      <mx:bloc id="site">
        <strong>Site</strong> : <a mXattribut="href:site"><mx:text id="site"/></a> - 
      </mx:bloc id="site">
      <strong>Adresse IP</strong> : <mx:text id="ip"/> - 
      <strong>Date</strong> : <mx:text id="date"/><br />
      <strong>
         Actions : [<a mXattribut="href:supprimer">Supprimer</a>] - 
         [<a mXattribut="href:editer">Editer</a>] -
         [<a mXattribut="href:pub"><mx:text id="pub_text"/></a>]
      </strong>
    </p>
    <div class="contenu">
      <mx:text id="contenu"/>
    </div>
  </mx:bloc id="comments">
</div>

