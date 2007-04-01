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
  <?php if (lb::metaEnabled()):?>
  <?php if (!lb::metaExists(true)): ?>
  <?php ___('No meta Found');?>
  <?php else: ?>
  <h2><?php ___('Meta Data');?></h2>
  <table class="clean-table">
    <?php while (!$meta->EOF()):?>
    <tr>
      <td><?php ___(lb::getMetaName(true)); ?></td>
      <td><?php lb::getMetaValue(); ?></td>
    </tr>
    <?php $meta->moveNext();
    endwhile;?>
  </table>
  <?php endif; ?>
  <?php endif;?>
  
  <?php if (!lb::commentsEnabled()):?>
  + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
    (<?php lb::commentCount();?>)</a>
  <?php endif;?>

  <div id="comments">
    <h2><?php ___('Comments');?></h2>
    <?php if( lb::commentCount(true)==0):?>
    <?php ___('No comments');?>
    <?php else:?>
    <?php while (lb::ctHasNext()):?>
    <div class="comment">
      <div class="commentmeta">
        <span class="commentauthor"><?php lb::ctAuthor();?></span>
        <?php lb::ctWebsite('- <a href="%s" ref="nofollow">'.__('Web site').'</a>');?>
        <?php lb::ctEmail('- <a href="mailto:%s">'.__('Email').'</a>');?>
      </div>
      <div class="commentbody">
        <?php lb::ctContent();?>
      </div>
      <div class="commentdate">
        DATE !!
      </div>
    </div>
    <?php lb::ctMoveNext();
          endwhile;?>
    <?php endif;?>
  </div>


  <div class="imgcommentform">
    <h2><?php ___('Add a comment');?></h2>
    
    <div id="ac-content">        
      <form method="post" id="ajout_commentaire">
        <?php lb::ctFormAction(); ?>

        <fieldset>
          <legend><?php ___('Add a comment');?></legend>
          <p>
            <label for="author" class="float"><strong><?php ___('Name or nickname');?></strong> : </label>
            <input type="text" name="author" id="author" class="inputbox" value="<?php lb::ctPostAuthor();?>"/>
            <?php lb::ctPostError('author');?>
          </p>
          <p>
            <label for="website" class="float"><?php ___('Web site');?> : </label>
            <input type="text" name="website" id="website" class="inputbox" value="<?php lb::ctPostWebsite();?>"/>
            <?php lb::ctPostError('website');?>
          </p>
          <p>
            <label for="email" class="float"><?php ___('Email');?> : </label>
            <input type="text" name="email" id="email" class="inputbox" value="<?php lb::ctPostEmail();?>"/>
            <?php lb::ctPostError('email');?>
          </p>
          <p>
            <label for="content" class="float"><strong><?php ___('Comment');?></strong> : </label>
            <textarea name="content" id="content" cols="40" rows="5"><?php lb::ctPostContent();?></textarea>
            <?php lb::ctPostError('author');?>
          </p>
        </fieldset>

        <p>
          <input type="submit" value="<?php ___('Add');?>"/>
          <input type="reset" value="<?php ___('Clear');?>"/>
        </p>
      </form>
    </div>
  </div>
</div>
<?php include('footer.php');?>

