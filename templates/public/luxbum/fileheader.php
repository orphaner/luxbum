<?php include ('header.php');?>
<body id="body">  
  <div id="centerV">
    <div id="centerH"> 

      <h1 class="vig_titre"><?php lb::galleryH1();?></h1>
      <div id="viewPage">

        <?php lb::menuNav('<div id="navigBar"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
              '<li>%s</li>'); ?>


        <div id="navigThumb">
          <div id="thumbList">
            <?php while (!$res->EOP()):?>
            <div class="thumb">
              <div class="thumbNumber">
                <?php lb::resPosition();?> / <?php lb::resTotal();?>
              </div>
              <div class="<?php lb::vignetteStyle();?>">
                <a href="<?php lb::linkVignette();?>"><?php lb::displayVignette();?></a>
              </div>
            </div>
            <?php $res->moveNext();
            endwhile;?>
          </div>

          <div id="paginator">
            <table class="tborder" cellpadding="3" cellspacing="1" border="0">
              <tr>
                <td class="affpage">
                  <?php ___('Page'); ?>
                  <?php lb::paginatorCurrentPage(); ?>
                  <?php ___('on');?>
                  <?php lb::paginatorTotalPages(); ?>
                </td>
                <?php while (!$affpage->EOF()):?>
                <td class="<?php lb::paginatorAltClass();?>">
                  <a href="<?php lb::paginatorLinkVignette();?>"><?php lb::paginatorElementText();?></a>
                </td>
                <?php $affpage->moveNext();
                endwhile;?>
              </tr>
            </table>
          </div>
        </div>