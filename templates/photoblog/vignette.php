<?php echo '<?xml version="1.0" encoding="ISO-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
      
      
      <div id="images">
        <?php while (!$res->EOF()):?>
        <div>
          <div class="image">
            <div class="imagethumb">
              <a href="<?php lbLinkAffichage();?>"><?php lbDisplayVignette();?></a>
            </div>
          </div>
        </div>
        <?php $res->moveNext();
        endwhile;?>
      </div>
      <p style="clear:both;"></p>
    </div>
    <div id="credit">Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  

  </body>
</html>
