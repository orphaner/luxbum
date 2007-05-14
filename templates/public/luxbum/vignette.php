<?php include ('_fileheader.php');?>

<div id="navigPicture">
  <div id="picture">
    <a href="<?php lb::linkPhoto();?>" onclick="window.open(this.href,'',''); return false;"><?php lb::displayApercu();?></a>
  </div>
  
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="30"><?php lbfile::vignettePrev('<img src="'. lbconf::colorThemePath(true).'/images/back.png" alt="back" border="0"/>');?></td>
      <td class="pictureDescription">
        <?php lbfile::dateDescription('<span class="description">%s</span>');?><br />

        <?php if (lbconf::selectionEnabled()):?>
        + <?php lb::selectLink(); ?>
        <?php endif;?>

        <?php if (lbconf::metaEnabled()):?>
        + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkMeta();?>','Meta','width=350,height=400,scrollbars=yes,resizable=yes');"><?php echo __('Meta data');?></a>
        <?php endif;?>

        <?php if (lbconf::commentsEnabled()):?>
        + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
          (<?php lbfile::commentCount();?>)</a>
        <?php endif;?>

      </td>
      <td width="30"><?php lbfile::vignetteNext('<img src="'. lbconf::colorThemePath(true).'/images/forward.png" alt="forward" border="0"/>');?></td>
    </tr>
  </table>
</div>

<?php include ('_filefooter.php');?>
