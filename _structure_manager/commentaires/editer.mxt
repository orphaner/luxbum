<h1 id="h1_admin">Gestion des commentaires</h1>
<p class="message"><mx:text id="message"/></p>
<h2>Editer un commentaire</h2>

<form method="post" id="ajout_commentaire" mXattribut="action:action">
 <p><input type="hidden" mXattribut="value:id" id="id" name="id"/></p>
 <fieldset>
   <legend>Editer un commentaire</legend>
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
   <input type="submit" value="Modifier"/>
   <input type="submit" value="Effacer"/>
 </p>
</form>