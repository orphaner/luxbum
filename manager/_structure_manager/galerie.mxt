<script type="text/javascript">
function toggleBigImage(id, largepath) {
  var imageobj = document.getElementById(id);
  if (!imageobj.sizedlarge) {
    imageobj.src2 = imageobj.src;
    imageobj.src = largepath;
    imageobj.style.position = 'absolute';
    imageobj.style.zIndex = '1000';
    imageobj.sizedlarge = true;
  } else {
    imageobj.style.position = 'relative';
    imageobj.style.zIndex = '0';
    imageobj.src = imageobj.src2;
    imageobj.sizedlarge = false;
  }
}

</script>

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


<h2>Opérations sur le cache</h2>
<ul>
  <li><a mXattribut="href:action_vider_cache">Vider le cache de cette galerie</a></li>
  <li><a mXattribut="href:action_generer_cache">Générer le cache de cette galerie</a></li>
</ul>


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

<h2>Tri des photos</h2>
<p><strong>Tri manuel</strong> : <a mXattribut="href:triUrl">Trier manuellement la galerie.</a></p>
<form mXattribut="action:action_tri" method="post">
  <fieldset><legend>Choisir un critère de tri pour les photos</legend>
    <mx:select id="tri"/>
    <p><input class="submit" type="submit" value="Valider le choix du tri" /></p>
  </fieldset>
</form>


<h2>Liste des photos</h2>

<mx:bloc id="liste_photos">

    <form method="post" mXattribut="action:actionLimitPage">
      <label for="limitPage" class="float"><strong>Nombre de photos sur la page</strong></label>
        <input id="limitPage" name="limitPage" type="text" maxlength="3" style="width:40px;" mXattribut="value:limitPage"/>
        <input class="submit" type="submit" value=" OK "/>
      </fieldset>
    </form>
    <p>Cliquer sur les images pour les agrandir</p>
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
          <td class="liste_photo_td"><div mXattribut="id:id_poto"><mx:image id="photo"/></div></td>
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
