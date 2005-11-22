<div class="rapid_switch">
  <fieldset><legend>Saut vers galerie : </legend>
    <form mXattribut="action:action_rapid_switch" method="get">
      <input type="hidden" id="p" name="p" value="galerie" />
      <mx:select id="rapid_switch"/>
      <input type="hidden" id="page" name="page" value="0" />
      <input type="submit" value="Go" id="go_rapid_switch" class="submit" />
    </form>
  </fieldset>
</div>


<h1 id="h1_admin">Gestion de la galerie : <mx:text id="galerie_nom"/></h1>

<p class="message"><mx:text id="message"/></p>

<script  type="text/javascript" src="_javascript/add_upload.js"></script>

<h2>Ajouter photo</h2>
<form enctype="multipart/form-data" mXattribut="action:action_ajout_photo" method="post" name="upload_form" id="upload_form">
  <input type="hidden" name="MAX_FILE_SIZE" mXattribut="value:max_file_size" />
  <fieldset><legend>Ajouter une photo à la galerie courante </legend>
    <p>
      <div id="positionFile"></div>
      <noscript>
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
      </noscript>
      <input type="button" value="Ajouter une autre photo" id="etrombi" onclick="addPosition('positionFile');" />
    </p>
    <p><input class="submit" type="submit" value="Valider l'envoi des photos" /></p>
  </fieldset>
</form>


<h2>Vider cache</h2>
<form mXattribut="action:action_vider_cache" method="post">
  <fieldset><legend>Vider le cache </legend>
    <p><input class="submit" type="submit" value="Valider la suppression du cache" /></p>
  </fieldset>
</form>



<h2>Attribuer une date pour toutes les photos</h2>
<form mXattribut="action:action_meme_date" method="post">
  <fieldset><legend>Attribuer une date à toutes les photos </legend>
    <p>
      <!-- <label for="meme_date" class="float" style="width:35%"> -->Choisissez une date (jj/mm/aaaa): <!-- </label> -->
      <mx:select id="jour"/><mx:select id="mois"/>
      <input type="text" name="meme_date" id="meme_date" mXattribut="value:val_meme_date" style="width:50px;" />
      <span class="erreur"><mx:text id="err_meme_date"/></span>
    </p>
    <p>
      <input class="submit" type="submit" value="Valider la date" />
    </p>
  </fieldset>
</form>



<h2>Liste des photos</h2>

<mx:bloc id="liste_photos">
  <div id="liste_photo_div">
    <table id="liste_photo" summary="Liste des photos de la galerie">
      <tr>
        <th>Photo</th>
        <th>Date / Description</th>
        <th>Défaut</th>
        <th>Supprimer</th>
      </tr>
      <mx:bloc id="liste">
        <tr mXattribut="class:class_tr">
          <td class="liste_photo_td"><div mXattribut="id:id_photo"><mx:image id="photo"/></div></td>
          <td class="description_td">
            <p class="message"><mx:text id="message_date_desc"/></p>

            <form mXattribut="action:action_date_desc" method="post">
              <p>
                <label for="description" class="float"><strong>Description : </strong></label>
                <input type="text" name="description" id="description" mXattribut="value:val_description" style="width:340px;"/>
                <span class="erreur"><mx:text id="err_description"/></span>
              </p>
              <p>
                <label for="date" class="float"><strong>Date : </strong></label>
                <mx:select id="jour"/><mx:select id="mois"/>
                <input type="text" name="date" id="date" mXattribut="value:val_date" style="width:50px;" />
                <span class="erreur"><mx:text id="err_date"/></span>
              </p>
              <p>
                <input class="submit" type="submit" value="Valider" />
                <input class="submit" type="reset" value="Effacer" />
              <p>
            </form>

          </td>
          <td class="liste_photo_td">
            <p><em>Défaut:</em><strong><mx:text id="defaut_oui_non"/></strong></p>
            <p><a mXattribut="href:defaut">Image d'index</a> <img src="_images/manager/ico_set_as_home.png" alt="defaut" /></p>
          </td>
          <td class="liste_photo_td">
            <p><a mXattribut="href:del"><img src="_images/manager/delete.png" alt="DEL" /></a></p>
          </td>
        </tr>
      </mx:bloc id="liste">
    </table>

    <div id="aff_page">
      <mx:text id="aff_page"/>
    </div>
  </div>
</mx:bloc id="liste_photos">
