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
      
      <div class="imgnav">
        <div class="imgprevious"><?php lbAffichagePrev('&laquo; '._('prev'),'<a></a>');?></div>
        <div class="imgnext"><?php lbAffichageNext(_('next').' &raquo;','');?></div>
      </div>
      <div id="gallerytitle">
        <h2>
          <a href="index.php"><strong>Accueil</strong></a> 
          <?php lbMenuNav('%s', '%s', ' | '); ?>
          | <?php lbImageName();?>
        </h2>
      </div>
      
      <div id="image">
        <a href="<?php lbPathPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lbDisplayApercu();?></a>
      </div>


      <div id="narrow">
        <?php lbPhotoDescription('<span class="description">%s</span>');?><br />
        <?php if (lbExifEnabled()):?>
        <div id="exif">
          <strong><?php echo _('Camera');?></strong> :
          <?php lbExifCameraMaker();?> <?php lbExifCameraModel();?><br />
          
          <strong><?php echo _('Exposure');?></strong> :
          <?php lbExifExposureTime();?><br />
          
          <strong><?php echo _('Aperture');?></strong> :
          <?php lbExifAperture();?><br />
          
          <strong><?php echo _('Focal length');?></strong> :
          <?php lbExifFocalLength();?><br />
          
          <strong><?php echo _('Flash');?></strong> :
          <?php lbExifFlash();?><br />
          
          <strong><?php echo _('ISO');?></strong> :
          <?php lbExifISO();?><br />
          
          <strong><?php echo _('Date');?></strong> :
          <?php lbExifCaptureDate();?><br />
        </div>
        <?php endif;?>
        
        <?php if (!lbExifEnabled()):?>
        + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo _('Commentaires');?> 
          (<?php lbCommentCount();?>)</a>
        <?php endif;?>
        
      </div>
    </div> 
    <div id="credit">Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  
  </body>
</html>
