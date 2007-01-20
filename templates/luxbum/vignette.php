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




        <div id="navigPicture">
          <div id="picture">
            <a href="<?php lb::pathPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lb::displayApercu();?></a>
          </div>
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30"><?php lb::vignettePrev('<img src="'. lb::colorThemePath(true).'/images/back.gif" alt="back" border="0"/>');?></td>
              <td class="pictureDescription">
                <?php lb::photoDescription('<span class="description">%s</span>');?><br />

                <?php if (lb::metaEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkMeta();?>','Meta','width=350,height=400,scrollbars=yes,resizable=yes');"><?php echo __('Meta data');?></a>
                <?php endif;?>

                <?php if (lb::commentsEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
                  (<?php lb::commentCount();?>)</a>
                <?php endif;?>

              </td>
              <td width="30"><?php lb::vignetteNext('<img src="'. lb::colorThemePath(true).'/images/forward.gif" alt="forward" border="0"/>');?></td>
            </tr>
          </table>
        </div>


        <!-- END CONTENT -->
      </div>

    </div>
    <div id="footer"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  </div>
</body>
<?php include ('footer.php');?>
