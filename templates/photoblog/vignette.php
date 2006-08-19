<?php include('header.php');?>

<div id="gallerytitle"><h1><?php lbGalleryH1();?></h1></div>
<h2>
  <a href="index.php"><strong><?php ___('Home');?></strong></a> 
  <?php lbMenuNav('%s', '%s', ' | '); ?>
</h2>


<div id="images">
  <?php while (!$res->EOF()):?>
  <div>
    <div class="image">
      <div class="imagethumb">
        <a href="<?php lbLinkAffichage();?>"><?php lbDisplayVignette();?></a>
      </div>
    </div>
  </div>
  <?php $res->moveNext();
  endwhile;?>
</div>
<p style="clear:both;"></p>

<?php include('footer.php');?>
