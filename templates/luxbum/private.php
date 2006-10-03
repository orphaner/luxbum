<?php include ('header.php');?>
<body id="body"> 
  <h1><span><?php lb::galleryH1();?></span></h1>

  <div id="liste_apercu">

    <?php lb::menuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
          '<li>%s</li>'); ?>

    <form method="post" id="login" action="">
      <?php lb::privateAction(); ?>
      
      <fieldset>
        <legend><?php ___('Add a comment');?></legend>
        <p>
          <label for="login" class="float"><strong><?php ___('Login');?></strong> : </label>
          <input type="text" name="login" id="login" value="<?php lb::privateLogin();?>"/>
          <?php lb::privatePostError('login');?>
        </p>
        <p>
          <label for="password" class="float"><strong><?php ___('Password');?></strong> : </label>
          <input type="text" name="password" id="password" value="<?php lb::loginPassword();?>"/>
          <?php lb::privatePostError('password');?>
        </p>
      </fieldset>
    </form>

    <div class="spacer"></div>
  </div>

  <div id="footer2"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum"><img src="_images/luxbum.png" alt="Powered By LuxBum"/></a><br />
    Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>

</body>
<?php include ('footer.php');?>
