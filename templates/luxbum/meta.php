<?php include ('header.php');?>
<body id="body">
  <div id="infos_meta">
    <h2><?php echo __('Meta data');?></h2>
    <?php if (!lb::metaExists(true)): ?>
    <?php ___('No meta Found');?>
    <?php else: ?>
    <table class="clean-table">
      <?php while (!$meta->EOF()):?>
      <tr>
        <td><?php ___(lb::getMetaName(true)); ?></td>
        <td><?php lb::getMetaValue(); ?></td>
      </tr>
      <?php $meta->moveNext();
      endwhile;?>
    </table>
    <?php endif; ?>
    <p><a href="javascript:window.close();"><?php echo __('Close the window');?></a></p>
  </div>
</body>
<?php include ('footer.php');?>
