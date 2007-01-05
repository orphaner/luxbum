<h1 id="h1_admin">Gestion des galeries</h1>


<p class="message"><mx:text id="message"/></p>

<h2>Ajouter une galerie</h2>
<form mXattribut="action:action_ajout_galerie" method="post">
  <fieldset><legend>Ajouter une nouvelle galerie </legend>
    <p><label for="ajout_galerie" class="float">Choisissez un nom : </label>
      <input type="text" name="ajout_galerie" id="ajout_galerie" mXattribut="value:val_ajout_galerie" />
      <span class="erreur"><mx:text id="err_ajout_galerie"/></span>
    </p>
    <p>
      <input class="submit" type="submit" value="Ajouter la galerie" />
    </p>
  </fieldset>
</form>


<h2>Opérations sur le cache</h2>
<ul>
  <li><a mXattribut="url:action_vider_cache">Vider le cache de toutes les galeries</a></li>
  <li><a mXattribut="url:action_generer_cache">Générer le cache de toutes les galeries (à venir)</a></li>
</ul>

<h2>Tri des galeries</h2>
<p><strong>Tri manuel</strong> : <a mXattribut="href:triUrl">Trier manuellement les galeries.</a></p>
<form mXattribut="action:action_tri" method="post">
  <fieldset><legend>Choisir un critère de tri pour les galeries</legend>
    <mx:select id="tri"/>
    <p><input class="submit" type="submit" value="Valider le choix du tri" /></p>
  </fieldset>
</form>

<h2>Liste des galeries</h2>
<div id="liste_photo_div">
  <table id="liste_photo" summary="Liste des galeries">
    <tr>
      <th>Galerie</th>
      <th>Gérer</th>
      <th>Renommer</th>
      <th>Supprimer</th>
    </tr>

    <mx:bloc id="liste">
      <tr id="tdde">
        <td class="liste_photo_td">
          <div mxAttribut="id:galerie_id"></div>
          <p>
            <a mXattribut="href:lien"><img mXattribut="src:apercu;alt:alt;title:title" /></a>
          </p>
        </td>
        <td class="description_td">
          <p>
            <h4><mx:text id="nom"/></h4>
            <span class="infos"><mx:text id="nb_photo"/> photos pour <mx:text id="taille"/></span>.
            <div class="consulter">
               <img src="_images/fleche1.png" alt="-" />&nbsp;<a mXattribut="href:lien">Gérer</a><br />
            </div>
          </p>
        </td>
        <td class="description_td">
          <p class="message"><mx:text id="message_modifier_galerie"/></p>

          <form mXattribut="action:action_modifier_galerie" method="post">
            <p>
              <label mXattribut="for:for_id" class="float"><strong>Nom : </strong></label>
              <input type="text" name="modifier_galerie" mXattribut="value:val_modifier_galerie;id:id_id" />
              <span class="erreur"><mx:text id="err_modifier_galerie"/></span>
            </p>
            <p>
              <input class="submit" type="submit" value="Changer le nom" />
            </p>
          </form>
        </td>
        <td class="liste_photo_td">
          <p><a mXattribut="href:del"><img src="_images/manager/delete.png" alt="DEL" /></a></p>
        </td>
      </tr>
    </mx:bloc id="liste">
  </table>
</div>
