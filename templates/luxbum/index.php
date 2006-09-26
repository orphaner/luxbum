<?php include ('header.php');?>

<body id="body">  
  <h1><span><?php lb::galleryH1();?></span></h1>

  <div id="liste_apercu">

    <?php lb::menuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
          '<li>%s</li>'); ?>

    <?php while (!$res->EOF()):?>
    <div class="galerie">
      <div class="lg">
        <?php lb::defaultImage(); ?>
      </div>
      <div class="ld">
        <h2><?php lb::galleryNiceName();?></h2>
        <?php if (lb::hasPhotos()):?>
        <span class="infos">
          <?php lb::galleryNbPhotos();?>
          <?php ___(' pictures - ');?>
          <?php lb::galleryNiceSize();?>.
        </span> 
        <?php endif;?>
        
        <div class="consulter">
          <ul>
            <?php lb::galleryLinkPrivate("<li>%s</li>", __('Private gallery'));?>
            <?php lb::galleryLinkSubGallery("<li>%s</li>", __('Sub galleries'));?>
            <?php lb::galleryLinkConsult("<li>%s</li>", __('Consult'));?>
            <?php lb::galleryLinkSlideshow("<li>%s</li>", __('Slideshow'));?>
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
<?php include ('footer.php');?>
