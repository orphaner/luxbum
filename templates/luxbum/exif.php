<?php include ('header.php');?>
<body id="body">
  <div id="infos_exif">
    <h2><?php echo __('EXIF data');?></h2>
    <table class="clean-table">
      <tr>
        <td><?php ___('Camera');?></td>
        <td><?php lb::exifCameraMaker();?> <?php lb::exifCameraModel();?></td>
      </tr>
      <tr>
        <td><?php ___('Exposure');?></td>
        <td><?php lb::exifExposureTime();?></td>
      </tr>
      <tr>
        <td><?php ___('Aperture');?></td>
        <td><?php lb::exifAperture();?></td>
      </tr>
      <tr>
        <td><?php ___('Focal length');?></td>
        <td><?php lb::exifFocalLength();?></td>
      </tr>
      <tr>
        <td><?php ___('Flash');?></td>
        <td><?php lb::exifFlash();?></td>
      </tr>
      <tr>
        <td><?php ___('ISO');?></td>
        <td><?php lb::exifISO();?></td>
      </tr>
      <tr>
        <td><?php ___('Date');?></td>
        <td><?php lb::exifCaptureDate();?></td>
      </tr>
    </table>
    <p><a href="javascript:window.close();"><?php echo __('Close the window');?></a></p>
  </div>
</body>
<?php include ('footer.php');?>
