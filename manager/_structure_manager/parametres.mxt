<!-- <script language="JavaScript" src="_javascript/picker.js"></script> -->
<script type="text/javascript">
var actif="";
function afficheDiv (divId) {
   actif=divId;
   if (document.getElementById && document.getElementById(divId) != null) {
      var laDiv = document.getElementById (divId);
      laDiv.style.visibility='visible';
      laDiv.style.display='block';
   }
}

function cacheDiv (divId) {
   if (document.getElementById && document.getElementById(divId) != null) {
      var laDiv = document.getElementById (divId);
      laDiv.style.visiblity='hidden';
      laDiv.style.display='none';
   }
}

function swapDiv (divId, selectId) {
   if (document.getElementById && document.getElementById(selectId) != null) {
      var laDiv = document.getElementById (selectId);
      if (laDiv.value == 'on') {
         afficheDiv(divId);
      }
      else if (laDiv.value == 'off') {
         cacheDiv(divId);
      }
   }
}
</script>
  

<h1 id="h1_admin">Paramètres de Luxbum</h1>

<p class="message"><mx:text id="message"/></p>

<form mXattribut="action:action_parametres" method="post" name="parametres_form" id="parametres_form">

  <h2>Paramètres d'apparence</h2>
  <fieldset><legend>Apparence : </legend>
    <p>
      <label for="nom_galerie" class="float">Nom de la galerie : </label>
      <input type="text" name="nom_galerie" id="nom_galerie" mXattribut="value:val_nom_galerie" />
      <span class="erreur"><mx:text id="err_nom_galerie"/></span>
    </p>
    <p>
      <label for="template_theme" class="float">Nombre de colones des aperçus : </label>
      <mx:select id="template_theme"/>
    </p>
    <p>
      <label for="color_theme" class="float">Thème de couleurs : </label>
      <mx:select id="color_theme"/>
    </p>
  </fieldset>
  <fieldset><legend>Cadres des images : </legend>
    <p>
      <label for="image_border_pixel" class="float">Taille du dégradé en pixel : </label>
      <mx:select id="image_border_pixel"/>
      <span class="erreur"><mx:text id="err_image_border_pixel"/></span>
    </p>
<!--     <p> -->
<!--       <label for="image_border_max_nuance" class="float">Taille du dégradé en pixel : </label> -->
<input type="hidden" name="image_border_max_nuance" id="image_border_max_nuance" mXattribut="value:val_image_border_max_nuance" />
<!--       <span class="erreur"><mx:text id="err_image_border_max_nuance"/></span> -->
<!--     </p> -->
    <p>
      <label for="image_border_hex_color" class="float">Couleur du dégradé en hexa : </label>
      <input type="text" name="image_border_hex_color" id="image_border_hex_color" mXattribut="value:val_image_border_hex_color" maxlength="6" /><!-- <a href="javascript:TCP.popup(document.forms['parametres_form'].elements['image_border_hex_color'])"> -->
<!--  <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="_librairies/img/sel.gif"></a> -->
      <span class="erreur"><mx:text id="err_image_border_hex_color"/></span>
    </p>
  </fieldset>

  <h2>Autres paramètres</h2>
  <fieldset><legend>Divers : </legend>
    <p>
      <label for="use_rewrite" class="float">Utiliser des belles urls : </label>
      <mx:select id="use_rewrite"/>
    </p>
    <p>
      <label for="mkdir_safe_mode" class="float">Créations des dossiers en safe mode : </label>
      <mx:select id="mkdir_safe_mode"/>
    </p>
    <p>
      <label for="use_rewrite" class="float">Afficher les informations exif : </label>
      <mx:select id="show_exif"/>
    </p>
    <p>
      <label for="description_file" class="float">Format de la date : </label>
      <input type="text" name="date_format" id="date_format" mXattribut="value:val_date_format" />
      <span class="erreur"><mx:text id="err_date_format"/></span>
    </p>
    <p>
      <label for="max_file_size" class="float">Taille maximum en <strong>Ko</strong> d'une photo uploadée : </label>
      <input type="text" name="max_file_size" id="max_file_size" mXattribut="value:val_max_file_size" />
      <span class="erreur"><mx:text id="err_max_file_size"/></span>
    </p>
    <p>
      <label for="min_size_for_preview" class="float">Taille minimum en <strong>Ko</strong> pour générer une preview (Le cadre autour de l'image sera désactivé) : </label>
      <input type="text" name="min_size_for_preview" id="min_size_for_preview" mXattribut="value:val_min_size_for_preview" />
      <span class="erreur"><mx:text id="err_min_size_for_preview"/></span>
    </p>
  </fieldset>


  <h2>Paramètres des Commentaires</h2>
  <fieldset><legend>Commentaires : </legend>
    <p>
      <label for="show_commentaire" class="float">Permettre les commentaires : </label>
      <mx:select id="show_commentaire"/>
    </p>
    <div id="paramCommentaires" mXattribut="class:commentaireDiv">
    <p><strong>Attention: Si tous les paramètres sont corrects, 
      la table des commentaires sera automatiquement créée. 
      Le nom de table sera "prefixe+commentaire".</strong></p>
    <p>
      <label for="dbl_host" class="float">Hote MySQL : </label>
      <input type="text" name="dbl_host" id="dbl_host" mXattribut="value:val_dbl_host" />
      <span class="erreur"><mx:text id="err_dbl_host"/></span>
    </p>
    <p>
      <label for="dbl_name" class="float">Nom de la base MySQL : </label>
      <input type="text" name="dbl_name" id="dbl_name" mXattribut="value:val_dbl_name" />
      <span class="erreur"><mx:text id="err_dbl_name"/></span>
    </p>
    <p>
      <label for="dbl_login" class="float">Utilisateur MySQL : </label>
      <input type="text" name="dbl_login" id="dbl_login" mXattribut="value:val_dbl_login" />
      <span class="erreur"><mx:text id="err_dbl_login"/></span>
    </p>
    <p>
      <label for="dbl_password" class="float">Mot de passe MySQL : </label>
      <input type="password" name="dbl_password" id="dbl_password" mXattribut="value:val_dbl_password" />
      <span class="erreur"><mx:text id="err_dbl_password"/></span>
    </p>
    <p>
      <label for="dbl_login" class="float">Préfixe des tables : </label>
      <input type="text" name="dbl_prefix" id="dbl_prefix" mXattribut="value:val_dbl_prefix" />
      <span class="erreur"><mx:text id="err_dbl_prefix"/></span>
    </p>
    </div>
  </fieldset>


  <h2>Paramètres des Diaporamas</h2>
  <fieldset><legend>Diaporamas : </legend>
    <p>
      <label for="show_slideshow" class="float">Permettre les diaporamas : </label>
      <mx:select id="show_slideshow"/>
    </p>
    <div id="paramSlideshow" mXattribut="class:slideshowDiv">
    <p>
      <label for="slideshow_time" class="float">Temps en secondes entre les images : </label>
      <input type="text" name="slideshow_time" id="slideshow_time" maxlength="1" mXattribut="value:val_slideshow_time" />
      <span class="erreur"><mx:text id="err_slideshow_time"/></span>
    </p>
    <p>
      <label for="slideshow_fading" class="float">Activer le fondu entre images : </label>
      <mx:select id="slideshow_fading"/>
    </p>
    </div>
  </fieldset>


  <h2>Paramètres des Sélections</h2>
  <fieldset><legend>Sélections : </legend>
    <p>
      <label for="show_selection" class="float">Permettre les sélections : </label>
      <mx:select id="show_selection"/>
    </p>
    <div id="paramSelection" mXattribut="class:selectionDiv">
    <p>
      <label for="allow_dl_selection" class="float">Activer le téléchargement en .zip de la sélection : </label>
      <mx:select id="allow_dl_selection"/>
    </p>
    </div>
  </fieldset>

  <p>
    <input class="submit" type="submit" value="Valider les paramètres" />
    <input class="submit" type="reset" value="Effacer les changements" />
  </p>
</form>


<form enctype="multipart/form-data" mXattribut="action:action_favicon" method="post" id="form_favicon">
  <input type="hidden" name="MAX_FILE_SIZE" mXattribut="value:max_file_size" />

  <h2>Ajouter son favicon</h2>
  <p class="message"><mx:text id="message_favicon"/></p>
  <fieldset><legend>Favicon : </legend>
    <p>
      <input type="file" name="userfile[]" /><br />
      <input class="submit" type="submit" value="Valider l'envoi du favicon" />
    </p>
  </fieldset>
</form>

<form mXattribut="action:action_parametres_mdp" method="post" id="form_mdp">
  <p class="message"><mx:text id="message_mdp"/></p>

  <h2>Paramètres de la zone d'administration</h2>
  <fieldset><legend>Zone d'administration : </legend>
    <p>
      <label for="manager_username" class="float">Nom du manager : </label>
      <input type="text" name="manager_username" id="manager_username" mXattribut="value:val_manager_username" />
      <span class="erreur"><mx:text id="err_manager_username"/></span>
    </p>
    <p>
      <label for="manager_password" class="float">Ancien mot de passe : </label>
      <input type="password" name="manager_password" id="manager_password" mXattribut="value:val_manager_password" />
      <span class="erreur"><mx:text id="err_manager_password"/></span>
    </p>
    <p>
      <label for="manager_password_1" class="float">Nouveau mot de passe : </label>
      <input type="password" name="manager_password_1" id="manager_password_1" mXattribut="value:val_manager_password_1" />
      <span class="erreur"><mx:text id="err_manager_password_1"/></span>
    </p>
    <p>
      <label for="manager_password_2" class="float">Nouveau mot de passe (vérif) : </label>
      <input type="password" name="manager_password_2" id="manager_password_2" mXattribut="value:val_manager_password_2" />
      <span class="erreur"><mx:text id="err_manager_password_2"/></span>
    </p>
  </fieldset>

  <p>
    <input class="submit" type="submit" value="Valider le login / mot de passe" />
    <input class="submit" type="reset" value="Effacer les changements" />
  </p>
</form>
