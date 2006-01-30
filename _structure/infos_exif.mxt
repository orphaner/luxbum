<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />


    <title><mx:text id="titre_page"/></title>
    <mx:bloc id="stylesheet">
      <link mXattribut="rel:rel;href:href;title:title" type="text/css" />
    </mx:bloc id="stylesheet">
  </head>

  <body id="body" style="padding:15px;">
    <div id="infos_exif">
      <h2>Informations EXIF</h2>
      <table class="clean-table">
        <tr><td>Appareil photo</td><td><mx:text id="camera_make"/> <mx:text id="camera_model"/></td></tr>
        <tr><td>Exposition</td><td><mx:text id="exposure_time"/></td></tr>
        <tr><td>Ouverture</td><td><mx:text id="aperture"/></td></tr>
        <tr><td>Distance focale</td><td><mx:text id="focal_length"/></td></tr>
        <tr><td>Flash</td><td><mx:text id="flash"/></td></tr>
        <tr><td>ISO</td><td><mx:text id="iso"/></td></tr>
        <tr><td>Date</td><td><mx:text id="date"/></td></tr>
      </table>
      <p><a href="javascript:window.close();">Fermer la fenêtre</a></p>
    </div>
  </body>
</html>
