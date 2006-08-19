<?php include ('header.php');?>
<body id="body">
  <div id="infos_exif">
    <h2><?php echo __('EXIF data');?></h2>
    <table class="clean-table">
      <tr>
        <td><?php ___('Camera');?></td>
        <td><?php lbExifCameraMaker();?> <?php lbExifCameraModel();?></td>
      </tr>
      <tr>
        <td><?php ___('Exposure');?></td>
        <td><?php lbExifExposureTime();?></td>
      </tr>
      <tr>
        <td><?php ___('Aperture');?></td>
        <td><?php lbExifAperture();?></td>
      </tr>
      <tr>
        <td><?php ___('Focal length');?></td>
        <td><?php lbExifFocalLength();?></td>
      </tr>
      <tr>
        <td><?php ___('Flash');?></td>
        <td><?php lbExifFlash();?></td>
      </tr>
      <tr>
        <td><?php ___('ISO');?></td>
        <td><?php lbExifISO();?></td>
      </tr>
      <tr>
        <td><?php ___('Date');?></td>
        <td><?php lbExifCaptureDate();?></td>
      </tr>
    </table>
    <p><a href="javascript:window.close();"><?php echo __('Close the window');?></a></p>
  </div>
</body>
<?php include ('footer.php');?>
