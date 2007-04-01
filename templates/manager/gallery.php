<?php include ('header.php');?>
<script type="text/javascript">
function toggleBigImage(id, largepath) {
  var imageobj = document.getElementById(id);
  if (!imageobj.sizedlarge) {
	  imageobj.src2 = imageobj.src;
	  imageobj.src = largepath;
	  imageobj.style.position = 'absolute';
	  imageobj.style.zIndex = '1000';
	  imageobj.sizedlarge = true;
  } 
  else {
	  imageobj.style.position = 'relative';
	  imageobj.style.zIndex = '0';
	  imageobj.src = imageobj.src2;
	  imageobj.sizedlarge = false;
  }
}

</script>

<div class="rapid_switch">
  <fieldset><legend><?php ___('Jump to gallery');?> : </legend>
    <form mXattribut="action:action_rapid_switch" method="get">
      <input type="hidden" id="p" name="p" value="galerie" />
      <mx:select id="rapid_switch"/>
      <input type="hidden" id="page" name="page" value="0" />
      <input type="submit" value="<?php ___('Go');?>" id="go_rapid_switch" class="submit" />
    </form>
  </fieldset>
</div>


<h1 id="h1_admin"><?php ___('Gallery management');?> : <?php lbm::galleryNiceName(false); ?></h1>

<p class="message"><?php lbm::headerMessage(); ?></p>

<script  type="text/javascript" src="_javascript/add_upload.js"></script>

<h2><?php ___('Add a photo');?></h2>
<form enctype="multipart/form-data" mXattribut="action:action_ajout_photo" method="post" name="upload_form" id="upload_form">
  <input type="hidden" name="MAX_FILE_SIZE" mXattribut="value:max_file_size" />
  <fieldset><legend><?php ___('Add a photo to the current gallery');?> </legend>
    <p>
      <div id="positionFile"></div>
      <noscript>
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
        <input type="file" name="userfile[]" /><br />
      </noscript>
      <input type="button" value="<?php ___('Add an other picture');?>" id="etrombi" onclick="addPosition('positionFile');" />
    </p>
    <p><input class="submit" type="submit" value="<?php ___('Send the photos');?>" /></p>
  </fieldset>
</form>


<h2><?php ___('Gallery cache');?></h2>
<ul>
  <li><a mXattribut="url:action_vider_cache"><?php ___('Delete the cache for this gallery');?></a></li>
  <li><a mXattribut="url:action_generer_cache"><?php ___('Generate the cache for this gallery');?></a></li>
</ul>


<h2><?php ___('Set a date for all photos');?></h2>
<form mXattribut="action:action_meme_date" method="post">
  <fieldset><legend><?php ___('Set a date for all photos');?> </legend>
    <p>
      <?php ___('Choose a date (jj/mm/aaaa)');?>
      <mx:select id="jour"/><mx:select id="mois"/>
      <input type="text" name="meme_date" id="meme_date" mXattribut="value:val_meme_date" style="width:80px;" />
      <span class="erreur"><mx:text id="err_meme_date"/></span>
    </p>
    <p>
      <input class="submit" type="submit" value="<?php ___('Submit the date');?>" />
    </p>
  </fieldset>
</form>

<h2><?php ___('Photo sort');?></h2>
<p><strong><?php ___('Manual sort');?></strong> : <a mXattribut="href:triUrl"><?php ___('Sort manually the gallery');?></a></p>
<form mXattribut="action:action_tri" method="post">
  <fieldset><legend><?php ___('Choose a sort criteria');?></legend>
    <mx:select id="tri"/>
    <p><input class="submit" type="submit" value="<?php ___('Submit the sort criteria');?>" /></p>
  </fieldset>
</form>


<h2><?php ___('Photo list');?></h2>

<?php if ($res->EOP()): ?>
<?php ___('There is no photo to consult.');?>
<?php else: ?>

<p><?php ___('Clic on the photo to enlarge');?></p>
<div id="liste_photo_div">
  <form mXattribut="action:action_date_desc" method="post">
    <table id="liste_photo" summary="<?php ___('Gallery photo list');?>">
      <tr>
        <th><?php ___('Photo');?></th>
        <th><?php ___('Date / Description');?></th>
        <th><?php ___('Default');?></th>
        <th><?php ___('Delete');?></th>
      </tr>
      <?php while (!$res->EOP()):?>
      <tr mXattribut="class:class_tr">
        <td class="liste_photo_td"><div mXattribut="id:id_poto"><?php lb::displayVignette();?></div></td>
        <td class="description_td">
          <p class="message"><mx:text id="message_date_desc"/></p>

          <p>
            <label for="description" class="float"><strong><?php ___('Description');?> : </strong></label>
            <input type="text" name="description" id="description" mXattribut="value:val_description" style="width:340px;"/>
            <span class="erreur"><mx:text id="err_description"/></span>
          </p>
          <p>
            <label for="date" class="float"><strong><?php ___('Date');?> : </strong></label>
            <mx:select id="jour"/><mx:select id="mois"/>
            <input type="text" name="date" id="date" mXattribut="value:val_date" style="width:80px;" />
            <span class="erreur"><mx:text id="err_date"/></span>
          </p>

        </td>
        <td class="liste_photo_td">
          <p><em><?php ___('Default');?>:</em><strong><mx:text id="defaut_oui_non"/></strong></p>
          <p><a mXattribut="href:defaut"><?php ___('Index image');?></a> <img src="_images/manager/ico_set_as_home.png" alt="defaut" /></p>
        </td>
        <td class="liste_photo_td">
          <p><a mXattribut="href:del"><img src="<?php lbm::imgSrc('delete.png');?>" alt="<?php ___('Delete');?>" /></a></p>
        </td>
      </tr>
      <?php $res->moveNext();
      endwhile;?>
    </table>

    <p>
      <input class="submit" type="submit" value="<?php ___('Submit');?>" />
      <input class="submit" type="reset" value="<?php ___('Clear');?>" />
    <p>
  </form>

</div>

<form method="post" mXattribut="action:actionLimitPage">
  <label for="limitPage" class="float"><strong><?php ___('Number of photos on the page');?></strong></label>
  <input id="limitPage" name="limitPage" type="text" maxlength="3" style="width:40px;" mXattribut="value:limitPage"/>
  <input class="submit" type="submit" value="<?php ___(' OK ');?>"/>
</fieldset>
</form>

<div id="paginator">
  <table class="tborder" cellpadding="3" cellspacing="1" border="0">
    <tr>
      <td class="affpage">
        <?php ___('Page'); ?>
        <?php lb::paginatorCurrentPage(); ?>
        <?php ___('on');?>
        <?php lb::paginatorTotalPages(); ?>
      </td>
      <?php while (!$affpage->EOF()):?>
      <td class="<?php lb::paginatorAltClass();?>">
        <a href="<?php lb::paginatorLinkVignette();?>"><?php lb::paginatorElementText();?></a>
      </td>
      <?php $affpage->moveNext();
      endwhile;?>
    </tr>
  </table>
</div>
<?php endif;?>
<?php include ('footer.php');?>
