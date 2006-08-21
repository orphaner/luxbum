<?php include ('header.php');?>
<body id="body">  
  <div id="page">
    <div id="center"> 
      <h1 class="vig_titre"><?php lbGalleryH1();?></h1>
      <div id="apercu">

        <?php lbMenuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="'.lbIndexLink().'"><strong>'.__('Home').'</strong></a></li>%s</ol></div>', 
              '<li>%s</li>'); ?>

        <div class="liste_apercu">
          <?php while (!$res->EOP()):?>
          <div class="vignette2col">
            <div class="num_photo">
              <?php lbResPosition();?> / <?php lbResTotal();?>
            </div>
            <div class="<?php lbVignetteStyle();?>">
              <a href="<?php lbLinkVignette();?>"><?php lbDisplayVignette();?></a>
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
                  <?php lbPaginatorCurrentPage(); ?>
                  <?php ___('on');?>
                  <?php lbPaginatorTotalPages(); ?>
                </td>
                <?php while (!$affpage->EOF()):?>
                <td class="<?php lbPaginatorAltClass();?>">
                  <a href="<?php lbPaginatorLinkVignette();?>"><?php lbPaginatorElementText();?></a>
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
            <a href="<?php lbPathPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lbDisplayApercu();?></a>
          </div>
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30"><?php lbVignettePrev('<img src="'. lbColorThemePath(true).'/images/back.gif" alt="back" border="0"/>');?></td>
              <td class="description_td">
                <?php lbPhotoDescription('<span class="description">%s</span>');?><br />

                <?php if (lbExifEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkExif();?>','Exif','width=350,height=400,scrollbars=yes,resizable=yes');"><?php echo __('EXIF data');?></a>
                <?php endif;?>

                <?php if (lbExifEnabled()):?>
                + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
                  (<?php lbCommentCount();?>)</a>
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
              <td width="30"><?php lbVignetteNext('<img src="'. lbColorThemePath(true).'/images/forward.gif" alt="forward" border="0"/>');?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div id="footer"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
  </div>
</body>
<?php include ('footer.php');?>
