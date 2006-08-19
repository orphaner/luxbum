<?php include('header.php');?>

<div class="imgnav">
  <div class="imgprevious"><?php lbAffichagePrev('&laquo; '.__('prev'),'<a></a>');?></div>
  <div class="imgnext"><?php lbAffichageNext(__('next').' &raquo;','');?></div>
</div>
<div id="gallerytitle">
  <h2>
    <a href="index.php"><strong><?php ___('Home');?></strong></a> 
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
    <strong><?php echo __('Camera');?></strong> :
    <?php lbExifCameraMaker();?> <?php lbExifCameraModel();?><br />
    
    <strong><?php echo __('Exposure');?></strong> :
    <?php lbExifExposureTime();?><br />
    
    <strong><?php echo __('Aperture');?></strong> :
    <?php lbExifAperture();?><br />
    
    <strong><?php echo __('Focal length');?></strong> :
    <?php lbExifFocalLength();?><br />
    
    <strong><?php echo __('Flash');?></strong> :
    <?php lbExifFlash();?><br />
    
    <strong><?php echo __('ISO');?></strong> :
    <?php lbExifISO();?><br />
    
    <strong><?php echo __('Date');?></strong> :
    <?php lbExifCaptureDate();?><br />
  </div>
  <?php endif;?>
  
  <?php if (!lbExifEnabled()):?>
  + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
    (<?php lbCommentCount();?>)</a>
  <?php endif;?>
</div>
<?php include('footer.php');?>

