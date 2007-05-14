<?php include ('_header.php');?>

<body id="body">  
  <h1><span><?php lb::galleryH1();?></span></h1>

  <div id="galleryList">

    <?php lb::menuNav('<div id="navigBar"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
          '<li>%s</li>'); ?>

    <?php if ($res->EOF()):?>
    <?php ___('There is no picture to consult.');?>
    <?php endif;?>


    <?php while (!$res->EOF()):?>
    <div class="gallery">
      <div class="lg">
        <?php lbgal::defaultImage(); ?>
      </div>
      <div class="ld">
      
        <h2><?php lbgal::niceName();?></h2>
        
        <?php if (lbgal::hasElements() && !lbgal::isPrivateAndLocked()):?>
        <span class="infos">
        
          <?php if (lbgal::hasImage()): ?>
            <?php lbgal::imageCount();?>
            <?php ___(' pictures - ');?>
            <?php lbgal::imageNiceSize();?>.
          <?php endif;?>
          
          <?php if (lbgal::hasFlv()): ?>
            <?php lbgal::flvCount();?>
            <?php ___(' videos - ');?>
            <?php lbgal::flvNiceSize();?>.
          <?php endif;?>
          
        </span> 
        <?php endif;?>
        
        <div class="actions">
          <ul>
            <?php if (lbgal::isPrivateAndLocked()):?>
            <?php lbgal::linkPrivate(   "<li>%s</li>", __('Private gallery'));?>
            <?php else:?>
            <?php lbgal::linkSubGallery("<li>%s</li>", __('Sub galleries'));?>
            <?php lbgal::linkConsult(   "<li>%s</li>", __('Consult'));?>
            <?php lbgal::linkSlideshow( "<li>%s</li>", __('Slideshow'));?>
            <?php endif;?>
          </ul>
        </div>

      </div>
    </div>
    <?php $res->moveNext();
    endwhile;?>
    <div class="spacer"></div>
  </div>

  <div id="footerIndex"><a href="http://blog.luxbum.net/"><img src="http://www.luxbum.net/luxbum.png" alt="Powered By LuxBum"/></a><br />
    Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  
</body>
<?php include ('_footer.php');?>
