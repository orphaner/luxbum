<?php include ('_header.php');?>

<body id="bodyCommentaire">  

  <div id="commentList">
    <h2><?php ___('Comments');?></h2>
    <?php if( lbfile::commentCount(true)==0):?>
    <?php ___('No comments');?>
    <?php else:?>
    <?php while (lbct::ctHasNext()):?>
    <div id="comment">
      <div class="info">Le DATE!!,
        <strong><?php lbct::ctAuthor();?></strong>
        <?php lbct::ctWebsite('- <a href="%s" ref="nofollow">'.__('Web site').'</a>');?>
        <?php lbct::ctEmail('- <a href="mailto:%s">'.__('Email').'</a>');?>
      </div>
      <div id="co1" class="content">
        <?php lbct::ctContent();?>
      </div>
    </div>
    <?php lbct::ctMoveNext();
          endwhile;?>
    <?php endif;?>
  </div>


  <div id="commentAdd">
    <h2><?php ___('Add a comment');?></h2>
    
    <form method="post" action="">
      <?php lbct::ctFormAction(); ?>

      <fieldset>
        <legend><?php ___('Add a comment');?></legend>
        <p>
          <label for="author" class="float"><strong><?php ___('Name or nickname');?></strong> : </label>
          <input type="text" name="author" id="author" value="<?php lbct::ctPostAuthor();?>"/>
          <?php lbct::ctPostError('author');?>
        </p>
        <p>
          <label for="website" class="float"><?php ___('Web site');?> : </label>
          <input type="text" name="website" id="website" value="<?php lbct::ctPostWebsite();?>"/>
          <?php lbct::ctPostError('website');?>
        </p>
        <p>
          <label for="email" class="float"><?php ___('Email');?> : </label>
          <input type="text" name="email" id="email" value="<?php lbct::ctPostEmail();?>"/>
          <?php lbct::ctPostError('email');?>
        </p>
        <p>
          <label for="content" class="float"><strong><?php ___('Comment');?></strong> : </label>
          <textarea name="content" id="content" cols="40" rows="5"><?php lbct::ctPostContent();?></textarea>
          <?php lbct::ctPostError('content');?>
        </p>
      </fieldset>

      <p>
        <input type="submit" value="<?php ___('Add');?>"/>
        <input type="reset" value="<?php ___('Clear');?>"/>
      </p>
    </form>
  </div>

  <p><a href="javascript:window.close();"><?php ___('Close the window');?></a></p>
</body>
<?php include ('_footer.php');?>
