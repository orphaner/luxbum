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
  

<h1 id="h1_admin">Param�tres de Luxbum</h1>

<p class="message"><mx:text id="message"/></p>

<form mXattribut="action:action_parametres" method="post" name="parametres_form" id="parametres_form">

  <h2>Param�tres d'apparence</h2>
  <fieldset><legend>Apparence : </legend>
    <p>
      <label for="nom_galerie" class="float">Nom de la galerie : </label>
      <input type="text" name="nom_galerie" id="nom_galerie" mXattribut="value:val_nom_galerie" />
      <span class="erreur"><mx:text id="err_nom_galerie"/></span>
    </p>
    <p>
      <label for="template_theme" class="float">Nombre de colones des aper�us : </label>
      <mx:select id="template_theme"/>
    </p>
    <p>
      <label for="color_theme" class="float">Th�me de couleurs : </label>
      <mx:select id="color_theme"/>
    </p>
  </fieldset>
  <fieldset><legend>Cadres des images : </legend>
    <p>
      <label for="image_border_pixel" class="float">Taille du d�grad� en pixel : </label>
      <mx:select id="image_border_pixel"/>
      <span class="erreur"><mx:text id="err_image_border_pixel"/></span>
    </p>
<!--     <p> -->
<!--       <label for="image_border_max_nuance" class="float">Taille du d�grad� en pixel : </label> -->
<input type="hidden" name="image_border_max_nuance" id="image_border_max_nuance" mXattribut="value:val_image_border_max_nuance" />
<!--       <span class="erreur"><mx:text id="err_image_border_max_nuance"/></span> -->
<!--     </p> -->
    <p>
      <label for="image_border_hex_color" class="float">Couleur du d�grad� en hexa : </label>
      <input type="text" name="image_border_hex_color" id="image_border_hex_color" mXattribut="value:val_image_border_hex_color" maxlength="6" /><!-- <a href="javascript:TCP.popup(document.forms['parametres_form'].elements['image_border_hex_color'])"> -->
<!--  <img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="_librairies/img/sel.gif"></a> -->
      <span class="erreur"><mx:text id="err_image_border_hex_color"/></span>
    </p>
  </fieldset>

<!--
  <h2>Param�tres des dossiers</h2>
  <fieldset><legend>Dossiers : </legend>
    <p>
      <label for="photos_dir" class="float">Dossier des photos : </label>
      <input type="text" name="photos_dir" id="photos_dir" mXattribut="value:val_photos_dir" />
      <span class="erreur"><mx:text id="err_photos_dir"/></span>
    </p>
    <p>
      <label for="thumb_dir" class="float">Dossier des vignettes : </label>
      <input type="text" name="thumb_dir" id="thumb_dir" mXattribut="value:val_thumb_dir" />
      <span class="erreur"><mx:text id="err_thumb_dir"/></span>
    </p>
    <p>
      <label for="preview_dir" class="float">Dossier des aper�us : </label>
      <input type="text" name="preview_dir" id="preview_dir" mXattribut="value:val_preview_dir" />
      <span class="erreur"><mx:text id="err_preview_dir"/></span>
    </p>
  </fieldset>
-->

  <h2>Autres param�tres</h2>
  <fieldset><legend>Divers : </legend>
<!--
    <p>
      <label for="description_file" class="float">Fichier des descriptions : </label>
      <input type="text" name="description_file" id="description_file" mXattribut="value:val_description_file" />
      <span class="erreur"><mx:text id="err_description_file"/></span>
    </p>
    <p>
      <label for="default_index_file" class="float">Fichier de la vignette par d�faut : </label>
      <input type="text" name="default_index_file" id="default_index_file" mXattribut="value:val_default_index_file" />
      <span class="erreur"><mx:text id="err_default_index_file"/></span>
    </p>
    <p>
      <label for="allowed_format" class="float">Formats d'images autoris�s : </label>
      <input type="text" name="allowed_format" id="allowed_format" mXattribut="value:val_allowed_format" />
      <span class="erreur"><mx:text id="err_allowed_format"/></span>
    </p>
-->
    <p>
      <label for="use_rewrite" class="float">Utiliser des belles urls : </label>
      <mx:select id="use_rewrite"/>
    </p>
    <p>
      <label for="mkdir_safe_mode" class="float">Cr�ations des dossiers en safe mode : </label>
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
      <label for="max_file_size" class="float">Taille maximum en <strong>Ko</strong> d'une photo upload�e : </label>
      <input type="text" name="max_file_size" id="max_file_size" mXattribut="value:val_max_file_size" />
      <span class="erreur"><mx:text id="err_max_file_size"/></span>
    </p>
    <p>
      <label for="min_size_for_preview" class="float">Taille minimum en <strong>Ko</strong> pour g�n�rer une preview (Le cadre autour de l'image sera d�sactiv�) : </label>
      <input type="text" name="min_size_for_preview" id="min_size_for_preview" mXattribut="value:val_min_size_for_preview" />
      <span class="erreur"><mx:text id="err_min_size_for_preview"/></span>
    </p>
  </fieldset>


  <h2>Param�tres des Commentaires</h2>
  <fieldset><legend>Commentaires : </legend>
    <p>
      <label for="show_commentaire" class="float">Permettre les commentaires : </label>
      <mx:select id="show_commentaire"/>
    </p>
    <div id="paramCommentaires" mXattribut="class:commentaireDiv">
    <p>
      <label for="db_host" class="float">Hote MySQL : </label>
      <input type="text" name="db_host" id="db_host" mXattribut="value:val_db_host" />
      <span class="erreur"><mx:text id="err_db_host"/></span>
    </p>
    <p>
      <label for="db_name" class="float">Nom de la base MySQL : </label>
      <input type="text" name="db_name" id="db_name" mXattribut="value:val_db_name" />
      <span class="erreur"><mx:text id="err_db_name"/></span>
    </p>
    <p>
      <label for="db_login" class="float">Utilisateur MySQL : </label>
      <input type="text" name="db_login" id="db_login" mXattribut="value:val_db_login" />
      <span class="erreur"><mx:text id="err_db_login"/></span>
    </p>
    <p>
      <label for="db_password" class="float">Mot de passe MySQL : </label>
      <input type="password" name="db_password" id="db_password" mXattribut="value:val_db_password" />
      <span class="erreur"><mx:text id="err_db_password"/></span>
    </p>
    <p>
      <label for="db_login" class="float">Pr�fixe des tables : </label>
      <input type="text" name="db_prefix" id="db_prefix" mXattribut="value:val_db_prefix" />
      <span class="erreur"><mx:text id="err_db_prefix"/></span>
    </p>
    </div>
  </fieldset>


  <h2>Param�tres Diaporamas</h2>
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


  <h2>Param�tres S�lections</h2>
  <fieldset><legend>S�lection : </legend>
    <p>
      <label for="show_selection" class="float">Permettre les s�lections : </label>
      <mx:select id="show_selection"/>
    </p>
    <div id="paramSelection" mXattribut="class:selectionDiv">
    <p>
      <label for="allow_dl_selection" class="float">Activer le t�l�chargement en .zip de la s�lection : </label>
      <mx:select id="allow_dl_selection"/>
    </p>
    </div>
  </fieldset>


  <!--<h2>Param�tres du cadre des images</h2>-->

  <p>
    <input class="submit" type="submit" value="Valider les param�tres" />
    <input class="submit" type="reset" value="Effacer les changements" />
  </p>
</form>


<form mXattribut="action:action_parametres_mdp" method="post" id="form_mdp">
  <p class="message"><mx:text id="message_mdp"/></p>

  <h2>Param�tres de la zone d'administration</h2>
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
      <label for="manager_password_2" class="float">Nouveau mot de passe (v�rif) : </label>
      <input type="password" name="manager_password_2" id="manager_password_2" mXattribut="value:val_manager_password_2" />
      <span class="erreur"><mx:text id="err_manager_password_2"/></span>
    </p>
  </fieldset>

  <p>
    <input class="submit" type="submit" value="Valider le login / mot de passe" />
    <input class="submit" type="reset" value="Effacer les changements" />
  </p>
</form>
