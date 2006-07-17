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


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^('.THUMB_DIR.'|'.PREVIEW_DIR.'|index)-(.+)-(.+)$', $_SERVER['QUERY_STRING'], $argv) ) {
   $type = $argv[1];
   $dir  = $argv[2];
   $file = $argv[3];
}
else  {
   exit ('manque des paramètres OFF');
}

verif::isImage ($dir, $file);


$luxAff = new luxBumImage ($dir, $file);
if ($type == 'vignette/') {
    $newfile = $luxAff->getAsThumb(IMG_W, IMG_H);
}
else if ($type == 'index') {
       $newfile = $luxAff->getAsThumb(85, 85);
}
else {
    $newfile = $luxAff->getAsPreview();
}


header('Content-Type: '.$luxAff->getTypeMime());
if ($fp = fopen($newfile, 'rb')) {
   fpassthru($fp);
}
@fclose ($fp);
?>