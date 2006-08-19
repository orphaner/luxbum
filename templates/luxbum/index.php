<?php include ('header.php');?>

<body id="body">  
  <h1><span><?php lbGalleryH1();?></span></h1>

  <div id="liste_apercu">

    <?php lbMenuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="index.php"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
          '<li>%s</li>'); ?>

    <?php while (!$res->EOF()):?>
    <div class="galerie">
      <div class="lg">
        <?php lbDefaultImage(); ?>
      </div>
      <div class="ld">
        <h2><?php lbGalleryNiceName();?></h2>
        <?php if (lbHasPhotos()):?>
        <span class="infos">
          <?php lbGalleryNbPhotos();?>
          <?php ___(' pictures - ');?>
          <?php lbGalleryNiceSize();?>.
        </span> 
        <?php endif;?>
        
        <div class="consulter">
          <ul>
            <?php lbGalleryLinkPrivate("<li>%s</li>", __('Private gallery'));?>
            <?php lbGalleryLinkSubGallery("<li>%s</li>", __('Sub galleries'));?>
            <?php lbGalleryLinkConsult("<li>%s</li>", __('Consult'));?>
            <?php lbGalleryLinkSlideshow("<li>%s</li>", __('Slideshow'));?>
          </ul>
        </div>

      </div>
    </div>
    <?php $res->moveNext();
    endwhile;?>
    <div class="spacer"></div>
  </div>

  <div id="footer2"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum"><img src="_images/luxbum.png" alt="Powered By LuxBum"/></a><br />
    Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  
</body>
<?php include ('header.php');?>
