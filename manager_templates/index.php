<?php include ('header.php');?>

<h1 id="h1_admin"><?php ___('Gallery management'); ?></h1>


<p class="message"><?php lbm::headerMessage(); ?></p>

<h2><?php ___('Add a gallery');?></h2>
<form mXattribut="action:action_ajout_galerie" method="post">
  <fieldset><legend><?php ___('Add a new gallery');?> </legend>
    <p><label for="ajout_galerie" class="float"><?php ___('Choose a name : ');?> </label>
      <input type="text" name="ajout_galerie" id="ajout_galerie" mXattribut="value:val_ajout_galerie" />
      <span class="erreur"><mx:text id="err_ajout_galerie"/></span>
    </p>
    <p>
      <input class="submit" type="submit" value="Ajouter la galerie" />
    </p>
  </fieldset>
</form>


<h2><?php ___('Gallery cache');?></h2>
<ul>
  <li><a mXattribut="url:action_vider_cache"><?php ___('Delete the cache for all galleries');?></a></li>
  <li><a mXattribut="url:action_generer_cache"><?php ___('Generate the cache for all galleries');?></a></li>
</ul>

<h2><?php ___('Gallery sort');?></h2>
<p><strong><?php ___('Manual sort');?></strong> : <a mXattribut="href:triUrl"><?php ___('Sort manually the galleries.');?></a></p>
<form mXattribut="action:action_tri" method="post">
  <fieldset><legend><?php ___('Choose a sort criteria :');?></legend>
    <mx:select id="tri"/>
    <p><input class="submit" type="submit" value="<?php ___('Submit the sort criteria');?>" /></p>
  </fieldset>
</form>

<h2><?php ___('Gallery list');?></h2>



<?php if ($res->EOF()):?>
<?php ___('There is no gallery to consult.');?>

<?php else: ?>
<div id="liste_photo_div">
  <table id="liste_photo" summary="<?php ___('Gallery list');?>">
    <tr>
      <th><?php ___('Gallery');?></th>
      <th><?php ___('Manage');?></th>
      <th><?php ___('Rename');?></th>
      <th><?php ___('Private');?></th>
      <th><?php ___('Delete');?></th>
    </tr>

    <?php while (!$res->EOF()):?>
    <tr id="tdde">
      <td class="liste_photo_td">
        <div mxAttribut="id:galerie_id"></div>
        <p><?php  lbm::defaultImage();?></p>
      </td>
      <td class="description_td">
        <p>
          <h4><?php lbm::galleryNiceName();?></h4>
          <?php if (lbm::hasPhotos()):?>
          <span class="infos">
            <?php lbm::galleryNbPhotos();?>
            <?php ___(' pictures - ');?>
            <?php lbm::galleryNiceSize();?>.
          </span> 
          <?php endif;?>

          <div class="consulter">
            <ul>
              <?php lbm::linkManageGallery("<li>%s</li>", __('Manage'));?>
              <?php lbm::linkSubGallery("<li>%s</li>", __('Sub galleries'));?>
            </ul>
          </div>
        </p>
      </td>
      <td class="description_td">
        <p class="message"><mx:text id="message_modifier_galerie"/></p>

        <form mXattribut="action:action_modifier_galerie" method="post">
          <p>
            <label mXattribut="for:for_id" class="float"><strong><?php ___('New name');?> : </strong></label>
            <input type="text" name="modifier_galerie" mXattribut="value:val_modifier_galerie;id:id_id" />
            <span class="erreur"><mx:text id="err_modifier_galerie"/></span>
          </p>
          <p>
            <input class="submit" type="submit" value="<?php ___('Rename');?>" />
          </p>
        </form>
      </td>
      <td class="liste_photo_td">
        <p><strong><?php ___('Private');?> : </strong>
          <?php if(lbm::isPrivate()):?>
          <?php ___('yes');?><br />
          <a href=""><?php ___('set as private');?></a>
          <?php else:?>
          <?php ___('no');?><br />
          <a href=""><?php ___('set as public');?></a>
          <?php endif;?>
          </p>
      </td>
      <td class="liste_photo_td">
        <p><a mXattribut="href:del"><img src="<?php lbm::imgSrc('delete.png');?>" alt="<?php ___('Delete');?>" /></a></p>
      </td>
    </tr>
    <?php $res->moveNext();
    endwhile;?>
  </table>
</div>

<?php endif;?>





<?php include ('footer.php');?>
