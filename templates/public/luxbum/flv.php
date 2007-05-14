<?php include ('_fileheader.php');?>

<div id="navigPicture">
  <div id="picture">
  </div>
  
  <script type="text/javascript" src="templates/common/flash/video/swfobject.js"></script>
  <script type="text/javascript">
    // <![CDATA[
   var fo = new SWFObject("<?php lbconf::getVideoPlayer(); ?>?config={menuItems: [ true, true, true, true, false, false], autoPlay:false, loop:false,videoFile: '<?php lb::urlFlvFile(); ?>'}", "FlowPlayer", "640", "480", "7", "<?php lbconf::flashPlayerBgcolor(); ?>", true);
fo.addParam("AllowScriptAccess", "always");
fo.write("picture");
// ]]>
  </script>
  
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="30"><?php lbfile::vignettePrev('<img src="'. lbconf::colorThemePath(true).'/images/back.png" alt="back" border="0"/>');?></td>
      <td class="pictureDescription">
        <?php lbfile::dateDescription('<span class="description">%s</span>');?><br />

        + <?php lb::selectLink(); ?>
        
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
