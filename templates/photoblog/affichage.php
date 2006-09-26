<?php include('header.php');?>

<div class="imgnav">
  <div class="imgprevious"><?php lb::affichagePrev('&laquo; '.__('prev'),'<a></a>');?></div>
  <div class="imgnext"><?php lb::affichageNext(__('next').' &raquo;','');?></div>
</div>
<div id="gallerytitle">
  <h2>
    <a href="<?php echo lb::indexLink(); ?>"><strong><?php ___('Home');?></strong></a> 
    <?php lb::menuNav('%s', '%s', ' | '); ?>
    | <?php lb::imageName();?>
  </h2>
</div>

<div id="image">
  <a href="<?php lb::pathPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lb::displayApercu();?></a>
</div>


<div id="narrow">
  <?php lb::photoDescription('<span class="description">%s</span>');?><br />
  <?php if (lb::exifEnabled()):?>
  <div id="exif">
    <strong><?php echo __('Camera');?></strong> :
    <?php lb::exifCameraMaker();?> <?php lb::exifCameraModel();?><br />
    
    <strong><?php echo __('Exposure');?></strong> :
    <?php lb::exifExposureTime();?><br />
    
    <strong><?php echo __('Aperture');?></strong> :
    <?php lb::exifAperture();?><br />
    
    <strong><?php echo __('Focal length');?></strong> :
    <?php lb::exifFocalLength();?><br />
    
    <strong><?php echo __('Flash');?></strong> :
    <?php lb::exifFlash();?><br />
    
    <strong><?php echo __('ISO');?></strong> :
    <?php lb::exifISO();?><br />
    
    <strong><?php echo __('Date');?></strong> :
    <?php lb::exifCaptureDate();?><br />
  </div>
  <?php endif;?>
  
  <?php if (!lb::exifEnabled()):?>
  + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
    (<?php lb::commentCount();?>)</a>
  <?php endif;?>
</div>
<?php include('footer.php');?>

