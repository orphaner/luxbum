<!-- <script language="JavaScript" src="_javascript/picker.js"></script> -->


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


  <h2>Paramètres des dossiers</h2>
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
      <label for="preview_dir" class="float">Dossier des aperçus : </label>
      <input type="text" name="preview_dir" id="preview_dir" mXattribut="value:val_preview_dir" />
      <span class="erreur"><mx:text id="err_preview_dir"/></span>
    </p>
  </fieldset>


  <h2>Autres paramètres</h2>
  <fieldset><legend>Divers : </legend>
    <p>
      <label for="description_file" class="float">Fichier des descriptions : </label>
      <input type="text" name="description_file" id="description_file" mXattribut="value:val_description_file" />
      <span class="erreur"><mx:text id="err_description_file"/></span>
    </p>
    <p>
      <label for="default_index_file" class="float">Fichier de la vignette par défaut : </label>
      <input type="text" name="default_index_file" id="default_index_file" mXattribut="value:val_default_index_file" />
      <span class="erreur"><mx:text id="err_default_index_file"/></span>
    </p>
    <p>
      <label for="allowed_format" class="float">Formats d'images autorisés : </label>
      <input type="text" name="allowed_format" id="allowed_format" mXattribut="value:val_allowed_format" />
      <span class="erreur"><mx:text id="err_allowed_format"/></span>
    </p>
    <p>
      <label for="use_rewrite" class="float">Utiliser des belles urls : </label>
      <mx:select id="use_rewrite"/>
    </p>
    <p>
      <label for="mkdir_safe_mode" class="float">Créations des dossiers en safe mode : </label>
      <mx:select id="mkdir_safe_mode"/>
    </p>
    <p>
      <label for="description_file" class="float">Format de la date : </label>
      <input type="text" name="date_format" id="date_format" mXattribut="value:val_date_format" />
      <span class="erreur"><mx:text id="err_date_format"/></span>
    </p>
    <p>
      <label for="min_size_for_preview" class="float">Taille minimum pour générer une preview (Ko) : </label>
      <input type="text" name="min_size_for_preview" id="min_size_for_preview" mXattribut="value:val_min_size_for_preview" />
      <span class="erreur"><mx:text id="err_min_size_for_preview"/></span>
    </p>
  </fieldset>


  <h2>Paramètres du cadre des images</h2>
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
<!-- 	<img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="_librairies/img/sel.gif"></a> -->
      <span class="erreur"><mx:text id="err_image_border_hex_color"/></span>
    </p>
  </fieldset>

  <p>
    <input class="submit" type="submit" value="Valider les paramètres" />
    <input class="submit" type="reset" value="Effacer les changements" />
  </p>
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
