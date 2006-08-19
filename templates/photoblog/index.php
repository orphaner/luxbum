<?php include('header.php');?>
<div id="gallerytitle"><h1><?php lbGalleryH1();?></h1></div>

<h2>
  <a href="index.php"><strong><?php ___('Home');?></strong></a> 
  <?php lbMenuNav('%s', '%s', ' | '); ?>
</h2>

<div id="albums">
  <?php while (!$res->EOF()):?>
  <div class="album">
    <?php lbDefaultImage(); ?>
    
    <div class="albumdesc">
      <?php lbGalleryLinkPrivate("<h3>%s</h3>", __('Private gallery'));?>
      <?php echo  ; 
            lbGalleryLinkSubGallery('<h3>'.__('Sub galleries').'&nbsp;:&nbsp;'."%s</h3>",lbGalleryNiceName(true));?>
      <?php lbGalleryLinkConsult("<h3>%s</h3>", lbGalleryNiceName(true));?>
    </div>
    <p style="clear:both;"></p>

  </div>

  <?php $res->moveNext();
  endwhile;?>
</div>
<?php include('footer.php');?>
