<?php include ('header.php');?>
<body id="body">
  <div id="infos_exif">
    <h2><?php echo __('EXIF data');?></h2>
    <?php if (!lb::metaExists()): ?>
    <?php ___('No meta Found');?>
    <?php else: ?>
    <table class="clean-table">
      <tr>
        <td><?php ___('META');?></td>
        <td><?php lb::getMeta('META');?></td>
      </tr>
    </table>
    <?php endif; ?>
    <p><a href="javascript:window.close();"><?php echo __('Close the window');?></a></p>
  </div>
</body>
<?php include ('footer.php');?>
