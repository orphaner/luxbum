<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css"/>
    <meta http-equiv="Content-Language" content="fr"/>

    <script  type="text/javascript" src="template-common/scripts/mootools.js"></script>
    <script  type="text/javascript" src="template-common/scripts/jd.gallery.js"></script>
    <title><?php lb::pageTitle(); ?></title>

    <?php lb::favicon();?>
    <?php lb::pageStyle();?>
    <link rel="stylesheet" href="template-common/css/jd.gallery.css" type="text/css" media="screen" charset="utf-8" />
<style>
#myGallery
{
width: 650px !important;
height: 510px !important;
}
</style>
  </head>
  <body onload="startGallery();">

    <script type="text/javascript">

var lbSize = <?php lb::resTotal(); ?>;
var lbImage = new Array();
var lbTitle = new Array();
var lbDescription = new Array();
var lbLink = new Array();
var lbThumb = new Array();
        <?php 
      $i=0;
      while (!$res->EOF()) {
        echo "lbImage[$i] = '".(lb::displayApercu('%s', true))."';\n";
        echo "lbThumb[$i] = '".(lb::displayVignette('%s', true))."';\n";
        echo "lbLink[$i] = false;\n";
        echo "lbTitle[$i] = 'Title $i';\n";
        echo "lbDescription[$i] = 'Description $i';\n";
        $i++;
        $res->moveNext();
      }flush();
        ?>

    </script>
    <div id="myGallery">
    </div>

    <script type="text/javascript">
      function startGallery() {
      var myGallery = new gallery($('myGallery'), {
      timed: true,
      embedLinks: false,
      preloader:false
      });
      }
      //window.onDomReady(startGallery);
    </script>
  </body>
</html>
