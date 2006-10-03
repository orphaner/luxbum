<?php
  /*
   * Created on 11 févr. 2006
   *
   */

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include ('common.php');
include (FONCTIONS_DIR.'luxbum.class.php');
include_once(FONCTIONS_DIR.'class/luxbumimage.class.php');
include_once(FONCTIONS_DIR.'class/files.php');
include_once(FONCTIONS_DIR.'class/imagetoolkit.class.php');


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------

if (ereg ('^('.files::removeTailSlash(THUMB_DIR).'|'.files::removeTailSlash(PREVIEW_DIR).'|index|full)/(.+)/(.+)$', $_SERVER['QUERY_STRING'], $argv) ) {
   $type = $argv[1];
   $dir  = $argv[2];
   $file = $argv[3];
}
else  {
   exit ('manque des paramètres OFF');
}

verif::isImage ($dir, $file);


$luxAff = new luxBumImage ($dir, $file);
if ($type == 'vignette') {
   $newfile = $luxAff->getAsThumb(VIGNETTE_THUMB_W, VIGNETTE_THUMB_H);
}
else if ($type == 'index') {
   $newfile = $luxAff->getAsThumb(INDEX_THUMB_W, INDEX_THUMB_H);
}
else if ($type == 'full') {
}
else {
   $newfile = $luxAff->getAsPreview(PREVIEW_W, PREVIEW_H);
}

if( headers_sent($file,$lineno) ) {
   die ("fuck header");
}

header("Content-Encoding: none");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header('Content-Type: '.$luxAff->getTypeMime());
if ($fp = fopen($newfile, 'rb')) {
   header("Content-Length: " . filesize($newfile));
   fpassthru($fp);
}
@fclose ($fp);
?>