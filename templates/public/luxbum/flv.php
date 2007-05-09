<?php include ('fileheader.php');?>

<div id="navigPicture">
  <div id="picture">
  </div>
  
  <script type="text/javascript" src="templates/common/flash/video/swfobject.js"></script>
  <script type="text/javascript">
    // <![CDATA[
   var fo = new SWFObject("<?php lb::getVideoPlayer(); ?>?config={menuItems: [ true, true, true, true, false, false], autoPlay:false, loop:false,videoFile: '<?php lb::urlFlvFile(); ?>'}", "FlowPlayer", "640", "480", "7", "<?php lb::flashPlayerBgcolor(); ?>", true);
fo.addParam("AllowScriptAccess", "always");
fo.write("picture");
// ]]>
  </script>
  
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="30"><?php lb::vignettePrev('<img src="'. lb::colorThemePath(true).'/images/back.png" alt="back" border="0"/>');?></td>
      <td class="pictureDescription">
        <?php lb::photoDescription('<span class="description">%s</span>');?><br />

        <?php if (lb::commentsEnabled()):?>
        + <a href="javascript:void(0);" onclick="window.open('<?php lb::linkComment();?>','Comments','width=480,height=540,scrollbars=yes,resizable=yes');"><?php echo __('Comments');?> 
          (<?php lb::commentCount();?>)</a>
        <?php endif;?>

      </td>
      <td width="30"><?php lb::vignetteNext('<img src="'. lb::colorThemePath(true).'/images/forward.png" alt="forward" border="0"/>');?></td>
    </tr>
  </table>
</div>

<?php include ('filefooter.php');?>
