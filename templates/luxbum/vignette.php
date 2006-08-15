<?php echo '<?xml version="1.0" encoding="ISO-8859-1"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <title><?php lbPageTitle(); ?></title>

    <?php lbFavicon();?>
    <?php lbPageStyle();?>
  </head>

  <body id="body">  
    <div id="page">
      <div id="center"> 
        <h1 class="vig_titre"><mx:text id="nom_dossier"/></h1>
        <div id="apercu">

          <?php lbMenuNav('<div id="menunav"><ol class="tree"><li>&#187; <a href="index.php"><strong>Accueil</strong></a></li>%s</ol></div>', 
                '<li>%s</li>'); ?>

          <div class="liste_apercu">
            <?php while (!$res->EOP()):?>
            <div class="<?php lbColStyle();?>">
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
              <mx:text id="aff_page"/>
            </div>
          </div>
        </div>

        <div id="iframeaffichage">
          <div id="affichage_photo">
            <div id="laphoto">
              <a href="<?php lbPathPhoto();?>" onclick="window.open(this.href,'',''); return false;"  mXattribut="class:photo_selection"><?php lbDisplayApercu();?></a>
            </div>
            
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="30px"><?php lbImagePrev();?></td>
                <td class="description_td">
                  <?php lbPhotoDescription('<span class="description">%s</span>');?><br />

                  <?php if (lbExifEnabled()):?>
                  + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkExif();?>','Exif','width=350,height=400,scrollbars=yes,resizable=yes');"><?php echo _('Informations EXIF');?></a>
                  <?php endif;?>

                  <?php if (lbExifEnabled()):?>
                  + <a href="javascript:void(0);" onclick="window.open('<?php lbLinkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo _('Commentaires');?> 
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
                <td width="30px"><?php lbImageNext();?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div id="footer"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
    </div>
  </body>
</html>
