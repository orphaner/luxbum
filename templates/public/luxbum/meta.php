<?php include ('_header.php');?>
<body id="bodyMeta">
  <div id="metaData">
    <h2><?php echo __('Meta data');?></h2>
    <?php if (!lbmeta::metaExists(true)): ?>
    <?php ___('No meta Found');?>
    <?php else: ?>
    <table class="cleanTable">
      <tr>
        <th><?php ___('Metadata name'); ?></th>
        <th><?php ___('Metadata value'); ?></th>
      </tr>
      <?php while (!$meta->EOF()):?>
      <tr>
        <td><?php ___(lbmeta::getMetaName(true)); ?></td>
        <td><?php lbmeta::getMetaValue(); ?></td>
      </tr>
      <?php $meta->moveNext();
      endwhile;?>
    </table>
    <?php endif; ?>
    <p><a href="javascript:window.close();"><?php echo __('Close the window');?></a></p>
  </div>
</body>
<?php include ('_footer.php');?>
