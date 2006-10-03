<?php include ('header.php');?>

<body id="body_commentaire">  


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
      <form method="post" id="ajout_commentaire" action="">
        <?php lb::ctFormAction(); ?>

        <fieldset>
          <legend><?php ___('Add a comment');?></legend>
          <p>
            <label for="author" class="float"><strong><?php ___('Name or nickname');?></strong> : </label>
            <input type="text" name="author" id="author" value="<?php lb::ctPostAuthor();?>"/>
            <?php lb::ctPostError('author');?>
          </p>
          <p>
            <label for="website" class="float"><?php ___('Web site');?> : </label>
            <input type="text" name="website" id="website" value="<?php lb::ctPostWebsite();?>"/>
            <?php lb::ctPostError('website');?>
          </p>
          <p>
            <label for="email" class="float"><?php ___('Email');?> : </label>
            <input type="text" name="email" id="email" value="<?php lb::ctPostEmail();?>"/>
            <?php lb::ctPostError('email');?>
          </p>
          <p>
            <label for="content" class="float"><strong><?php ___('Comment');?></strong> : </label>
            <textarea name="content" id="content" cols="40" rows="5"><?php lb::ctPostContent();?></textarea>
            <?php lb::ctPostError('content');?>
          </p>
        </fieldset>

        <p>
          <input type="submit" value="<?php ___('Add');?>"/>
          <input type="reset" value="<?php ___('Clear');?>"/>
        </p>
      </form>
    </div>
  </div>

  <p><a href="javascript:window.close();"><?php ___('Close the window');?></a></p>
</body>
<?php include ('footer.php');?>
