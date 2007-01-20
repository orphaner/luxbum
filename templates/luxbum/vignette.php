<?php include ('header.php');?>
<body id="body">  
  <div id="page">
    <div id="center"> 
      <h1 class="vig_titre"><?php lb::galleryH1();?></h1>
      <div id="apercu">

        <?php lb::menuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="'.lb::indexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
              '<li>%s</li>'); ?>

        <div class="liste_apercu">
          <?php while (!$res->EOP()):?>
          <div class="vignette2col">
            <div class="num_photo">
              <?php lb::resPosition();?> / <?php lb::resTotal();?>
            </div>
            <div class="<?php lb::vignetteStyle();?>">
              <a href="<?php lb::linkVignette();?>"><?php lb::displayVignette();?></a>
            </div>
          </div>
          <?php $res->moveNext();
          endwhile;?>

          <div class="spacer"></div>
          <div id="aff_page">
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
      </div>

      <div id="iframeaffichage">
        <div id="affichage_photo">
          <div id="laphoto">
            <a href="<?php lb::pathPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lb::displayApercu();?></a>
          </div>
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30"><?php lb::vignettePrev('<img src="'. lb::colorThemePath(true).'/images/back.gif" alt="back" border="0"/>');?></td>
              <td class="description_td">
                <?php lb::photoDescription('<span class="description">%s</span>');?><br />

                <?php if (lb::metaEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkMeta();?>','Meta','width=350,height=400,scrollbars=yes,resizable=yes');"><?php echo __('Meta data');?></a>
                <?php endif;?>

                <?php if (lb::commentsEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
                  (<?php lb::commentCount();?>)</a>
                <?php endif;?>

                <!-- start upd dark 1 -->
                <!--                   <mx:bloc id="selection"> -->
                <!--                     + <a mXattribut="href:lien_selection" target="_parent"><mx:text id="info_selection"/></a> -->
                <!--                     + -->
                <!--                     <mx:bloc id="selection_empty"> -->
                <!--                       <a mXattribut="href:gestion_selection" target="_parent"><mx:text id="action_selection"/></a> -->
                <!--                       <mx:bloc id="selection_dl_ok"> -->
                <!--                         + <a mXattribut="href:dl_selection" target="_parent"><mx:text id="telecharge_selection"/></a> -->
                <!--                       </mx:bloc id="selection_dl_ok"> -->
                <!--                     </mx:bloc id="selection_empty"> -->
                <!--                   </mx:bloc id="selection"> -->
                <!-- end upd dark 1 -->
              </td>
              <td width="30"><?php lb::vignetteNext('<img src="'. lb::colorThemePath(true).'/images/forward.gif" alt="forward" border="0"/>');?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div id="footer"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  </div>
</body>
<?php include ('footer.php');?>
