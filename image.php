<?php
/*
 * Created on 11 f�vr. 2006
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
// Parsing des param�tres
//------------------------------------------------------------------------------
if (ereg ('^('.THUMB_DIR.'|'.PREVIEW_DIR.'|index|full)-(.+)-(.+)$', $_SERVER['QUERY_STRING'], $argv) ) {
   $type = $argv[1];
   $dir  = $argv[2];
   $file = $argv[3];
}
else  {
   exit ('manque des param�tres OFF');
}

verif::isImage ($dir, $file);


$luxAff = new luxBumImage ($dir, $file);
if ($type == 'vignette/') {
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


header('Content-Type: '.$luxAff->getTypeMime());
if ($fp = fopen($newfile, 'rb')) {
   fpassthru($fp);
}
@fclose ($fp);
?>