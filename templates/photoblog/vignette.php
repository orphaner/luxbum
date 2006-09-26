<?php include('header.php');?>

<div id="gallerytitle"><h1><?php lb::galleryH1();?></h1></div>
<h2>
  <a href="<?php echo lb::indexLink();?>"><strong><?php ___('Home');?></strong></a> 
  <?php lb::menuNav('%s', '%s', ' | '); ?>
</h2>


<div id="images">
  <?php while (!$res->EOP()):?>
  <div>
    <div class="image">
      <div class="imagethumb">
        <a href="<?php lb::linkAffichage();?>"><?php lb::displayVignette();?></a>
      </div>
    </div>
  </div>
  <?php $res->moveNext();
  endwhile;?>
</div>
<p style="clear:both;"></p>

<div class="pagelist">

  <ul class="pagelist">
    <?php if (lb::isFirstPage()): ?>
    <li class="prev"><span class="disabledlink"><?php echo '&laquo; '.__('prev');?></span></li>  
    <?php else: ?>
    <li class="prev"><?php lb::vignettePrevPage('&laquo; '.__('prev'));?></li>
    <?php endif; ?>

    <?php while (!$affpage->EOF()): ?>
    <?php if (lb::paginatorIsCurrent()): ?>
    <li class="current"><a href="<?php lb::paginatorLinkVignette();?>" title="<?php ___('Page');lb::paginatorCurrentPage(); ?>"><?php lb::paginatorElementText();?></a></li>
    <?php else: ?>
    <li><a href="<?php lb::paginatorLinkVignette();?>" title="<?php ___('Page');lb::paginatorCurrentPage(); ?>"><?php lb::paginatorElementText();?></a></li>
    <?php endif;?>
    <?php $affpage->moveNext();
    endwhile;?>

    <?php if (lb::isLastPage()): ?>
    <li class="next"><span class="disabledlink"><?php echo __('next').' &raquo;';?></span></li>  
    <?php else: ?>
    <li class="next"><?php  lb::vignetteNextPage(__('next').' &raquo;','');?><li>
    <?php endif; ?>

  </ul>
</div>

<?php include('footer.php');?>
