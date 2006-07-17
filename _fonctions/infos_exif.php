<?php

//------------------------------------------------------------------------------
// Include
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (!isset($_GET['photo']) || !isset($_GET['d'])) {
   exit('manque des paramètres ON');
}
$file          = $_GET['photo'];
$dir           = $_GET['d'];


verif::photo ($dir, $file);

//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
$page = new ModeliXe ('infos_exif.mxt');
$page->SetModeliXe();
remplir_style ($page);
definir_titre ($page, 'Informations EXIF');


//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------
$lux = new luxBumImage ($dir, $file);
$lux->exifInit ();


if ($lux->exifExists ()) {
   $page->MxText ('exposure_time', $lux->getExifExposureTime ());
   $page->MxText ('aperture',      $lux->getExifAperture ());
   $page->MxText ('focal_length',  $lux->getExifFocalLength ());
   $page->MxText ('camera_make',   $lux->getExifCameraMaker ());
   $page->MxText ('camera_model',  $lux->getExifCameraModel ());
   $page->MxText ('iso',           $lux->getExifISO ());
   $page->MxText ('date',          $lux->getExifCaptureDate ());
   $page->MxText ('flash',         $lux->getExifFlash ());
}
else {
   exit ('Pas d\'infos exif');
}


$page->MxWrite ();

?>