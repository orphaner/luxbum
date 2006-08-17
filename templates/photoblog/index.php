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

    <div id="main">
      <div id="gallerytitle"><h1><?php lbGalleryH1();?></h1></div>

      <h2>
        <a href="index.php"><strong>Accueil</strong></a> 
        <?php lbMenuNav('%s', '%s', ' | '); ?>
      </h2>

      <div id="albums">
        <?php while (!$res->EOF()):?>
        <div class="album">
          <?php lbDefaultImage(); ?>
          
          <div class="albumdesc">
            <?php lbGalleryLinkPrivate("<h3>%s</h3>", _('Private gallery'));?>
            <?php echo  ; 
                  lbGalleryLinkSubGallery('<h3>'._('Sub gallery').'&nbsp;:&nbsp;'."%s</h3>",lbGalleryNiceName(true));?>
            <?php lbGalleryLinkConsult("<h3>%s</h3>", lbGalleryNiceName(true));?>
          </div>
          <p style="clear:both;"></p>

        </div>

        <?php $res->moveNext();
        endwhile;?>
      </div>
    </div>

    <div id="credit">Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
    
  </body>
</html>
