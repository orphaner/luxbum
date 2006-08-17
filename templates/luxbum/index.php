<?php echo '<?xml version="1.0" encoding="ISO-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <title><?php lbPageTitle(); ?></title>

    <?php lbFavicon();?>
    <?php lbPageStyle();?>

  </head>

  <body id="body">  
    <h1><span><?php lbGalleryH1();?></span></h1>

    <div id="liste_apercu">

      <?php lbMenuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="index.php"><strong>Accueil</strong></a></li>%s</ol></div>', 
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
            <?php lbGalleryNbPhotos();?> photos 
            pour <?php lbGalleryNiceSize();?>.
          </span>
          <?php endif;?>
          
          <div class="consulter">
            <ul>
              <?php lbGalleryLinkPrivate("<li>%s</li>", _('Private gallery'));?>
              <?php lbGalleryLinkSubGallery("<li>%s</li>", _('Sub gallery'));?>
              <?php lbGalleryLinkConsult("<li>%s</li>", _('Consult'));?>
              <?php lbGalleryLinkSlideshow("<li>%s</li>", _('Slideshow'));?>
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
</html>
