<?php include ('_header.php');?>
<body id="body"> 
  <h1><span><?php lb::galleryH1();?></span></h1>

  <div id="galleryList">

    <?php lb::menuNav('<div id="navigBar"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
          '<li>%s</li>'); ?>

    <div id="privateForm">

      <div id="messageOk">
        <?php ___('You need to fill in the form bellow to consult the selected gallery');?>
      </div>

      <form method="post" id="login" action="">
        <?php lbprivate::privateFormAction(); ?>
        
        <fieldset>
          <legend><?php ___('Authentication required');?></legend>
          <p>
            <label for="login" class="float"><strong><?php ___('Login');?></strong> : </label>
            <input type="text" name="login" id="login" value="<?php lbprivate::privatePostLogin();?>"/>
            <?php lbprivate::privatePostError('login');?>
          </p>
          <p>
            <label for="password" class="float"><strong><?php ___('Password');?></strong> : </label>
            <input type="password" name="password" id="password" value="<?php lbprivate::privatePostPassword();?>"/>
            <?php lbprivate::privatePostError('password');?>
          </p>
        </fieldset>

        <p style="text-align:center">
          <input type="submit" value="<?php ___('Submit');?>"/>
          <input type="reset" value="<?php ___('Clear');?>"/>
        </p>
      </form>
    </div>

    <div class="spacer"></div>
  </div>

  <div id="footerIndex"><a href="http://blog.luxbum.net/"><img src="http://www.luxbum.net/luxbum.png" alt="Powered By LuxBum"/></a><br />
    Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>

</body>
<?php include ('_footer.php');?>
