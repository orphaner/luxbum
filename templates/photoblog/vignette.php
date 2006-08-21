<?php include('header.php');?>

<div id="gallerytitle"><h1><?php lbGalleryH1();?></h1></div>
<h2>
  <a href="index.php"><strong><?php ___('Home');?></strong></a> 
  <?php lbMenuNav('%s', '%s', ' | '); ?>
</h2>


<div id="images">
  <?php while (!$res->EOP()):?>
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

<div class="pagelist">

  <ul class="pagelist">
    <?php if (lbIsFirstPage()): ?>
    <li class="prev"><span class="disabledlink"><?php echo '&laquo; '.__('prev');?></span></li>  
    <?php else: ?>
    <li class="prev"><?php lbVignettePrevPage('&laquo; '.__('prev'));?></li>
    <?php endif; ?>

    <?php while (!$affpage->EOF()): ?>
    <?php if (lbPaginatorIsCurrent()): ?>
    <li class="current"><a href="<?php lbPaginatorLinkVignette();?>" title="<?php ___('Page');lbPaginatorCurrentPage(); ?>"><?php lbPaginatorElementText();?></a></li>
    <?php else: ?>
    <li><a href="<?php lbPaginatorLinkVignette();?>" title="<?php ___('Page');lbPaginatorCurrentPage(); ?>"><?php lbPaginatorElementText();?></a></li>
    <?php endif;?>
    <?php $affpage->moveNext();
    endwhile;?>

    <?php if (lbIsLastPage()): ?>
    <li class="next"><span class="disabledlink"><?php echo __('next').' &raquo;';?></span></li>  
    <?php else: ?>
    <li class="next"><?php  lbVignetteNextPage(__('next').' &raquo;','');?><li>
    <?php endif; ?>

  </ul>
</div>

<?php include('footer.php');?>
