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

  <div id="voircomment">
    <h2><?php ___('Comments');?></h2>
    <?php if( lb::commentCount(true)==0):?>
    <?php ___('No comments');?>
    <?php else:?>
    <?php while (lb::ctHasNext()):?>
    <div id="c-content">
      <div class="comment-post">
        <div class="comment-info">Le DATE!!,
          <strong><?php lb::ctAuthor();?></strong>
          <?php lb::ctWebsite('- <a href="%s" ref="nofollow">'.__('Web site').'</a>');?>
          <?php lb::ctEmail('- <a href="mailto:%s">'.__('Email').'</a>');?>
        </div>
        <div id="co1" class="comment-content">
          <?php lb::ctContent();?>
        </div>
      </div>
    </div>
    <?php lb::ctMoveNext();
          endwhile;?>
    <?php endif;?>
  </div>


  <div id="addcomment">
    <h2><?php ___('Add a comment');?></h2>
    
    <div id="ac-content">        
      <form method="post" id="ajout_commentaire">
        <?php lb::ctFormAction(); ?>

        <fieldset>
          <legend><?php ___('Add a comment');?></legend>
          <p>
            <label for="author" class="float"><strong><?php ___('Name or nickname');?></strong> : </label>
            <input type="text" name="author" id="author" value="<?php lb::ctPostAuthor();?>"/>
            <span class="erreur"><?php lb::ctPostError('author');?></span>
          </p>
          <p>
            <label for="website" class="float"><?php ___('Web site');?> : </label>
            <input type="text" name="website" id="website" value="<?php lb::ctPostWebsite();?>"/>
            <span class="erreur"><?php lb::ctPostError('website');?></span>
          </p>
          <p>
            <label for="email" class="float"><?php ___('Email');?> : </label>
            <input type="text" name="email" id="email" value="<?php lb::ctPostEmail();?>"/>
            <span class="erreur"><?php lb::ctPostError('email');?></span>
          </p>
          <p>
            <label for="content" class="float"><strong><?php ___('Comment');?></strong> : </label>
            <textarea name="content" id="content" cols="40" rows="5"><?php lb::ctPostContent();?></textarea>
            <span class="erreur"><?php lb::ctPostError('author');?></span>
          </p>
        </fieldset>

        <p>
          <input type="submit" value="<?php ___('Add');?>"/>
          <input type="reset" value="<?php ___('Clear');?>"/>
        </p>
      </form>
    </div>
  </div>
  <?php include('footer.php');?>

