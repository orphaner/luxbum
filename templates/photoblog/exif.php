<?php echo '<?xml version="1.0" encoding="ISO-8859-1"?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <title><?php lbPageTitle(); ?></title>

    <?php lbFavicon();?>
    <?php lbPageStyle();?>
  </head>

  <body id="body" style="padding:15px;">
    <div id="infos_exif">
      <h2><?php echo _('EXIF data');?></h2>
      <table class="clean-table">
        <tr>
          <td><?php echo _('Camera');?></td>
          <td><?php lbExifCameraMaker();?> <?php lbExifCameraModel();?></td>
        </tr>
        <tr>
          <td><?php echo _('Exposure');?></td>
          <td><?php lbExifExposureTime();?></td>
        </tr>
        <tr>
          <td><?php echo _('Aperture');?></td>
          <td><?php lbExifAperture();?></td>
        </tr>
        <tr>
          <td><?php echo _('Focal length');?></td>
          <td><?php lbExifFocalLength();?></td>
        </tr>
        <tr>
          <td><?php echo _('Flash');?></td>
          <td><?php lbExifFlash();?></td>
        </tr>
        <tr>
          <td><?php echo _('ISO');?></td>
          <td><?php lbExifISO();?></td>
        </tr>
        <tr>
          <td><?php echo _('Date');?></td>
          <td><?php lbExifCaptureDate();?></td>
        </tr>
      </table>
      <p><a href="javascript:window.close();"><?php echo _('Close the window');?></a></p>
    </div>
  </body>
</html>
