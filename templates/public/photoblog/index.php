<?php include('header.php');?>
<div id="gallerytitle"><h1><?php lb::galleryH1();?></h1></div>

<h2>
  <a href="<?php echo lb::indexLink();?>"><strong><?php ___('Home');?></strong></a> 
  <?php lb::menuNav('%s', '%s', ' | '); ?>
</h2>

<div id="albums">
  <?php if ($res->EOF()):?>
  <?php ___('There is no picture to consult.');?>
  <?php endif;?>


  <?php while (!$res->EOF()):?>
  <div class="album">
    <?php lb::defaultImage(); ?>
    
    <div class="albumdesc">
      <?php lb::galleryLinkPrivate("<h3>%s</h3>", __('Private gallery'));?>
      <?php lb::galleryLinkSubGallery('<h3>'.__('Sub galleries').'&nbsp;:&nbsp;'."%s</h3>",lb::galleryNiceName(true, true));?>
      <?php lb::galleryLinkConsult("<h3>%s</h3>", lb::galleryNiceName(true, true));?>

        <?php if (lb::hasElements() && !lb::isPrivateAndLocked()):?>
        <span class="infos">
          <?php if (lb::hasImage()): ?>
            <?php lb::galleryImageCount();?>
            <?php ___(' pictures - ');?>
            <?php lb::galleryImageNiceSize();?>.
          <?php endif;?>
          <?php if (lb::hasFlv()): ?>
            <?php lb::galleryFlvCount();?>
            <?php ___(' videos - ');?>
            <?php lb::galleryFlvNiceSize();?>.
          <?php endif;?>
        </span> 
        <?php endif;?>
    </div>
    <p style="clear:both;"></p>

  </div>

  <?php $res->moveNext();
  endwhile;?>
</div>
<?php include('footer.php');?>