<?php

if (SHOW_SELECTION == 'off') {
   exit ('S�lection d�sactiv�e.');
}

if (ALLOW_DL_SELECTION == 'off') {
   exit ('T�l�chargement de la s�lection d�sactiv�.');
}

// appel de la classe
require_once('utils/zip.lib.php');
// cr�ation d'un objet 'zipfile'
$zip = new zipfile();
foreach($_SESSION['luxbum'] as $d => $s){
   foreach($_SESSION['luxbum'][$d] as $img => $ok){
        
      // nom du fichier � ajouter dans l'archive
      $filename = 'photos/'.$d.'/'.$img;
      // contenu du fichier
      $fp = fopen ($filename, 'r');
      $content = fread($fp, filesize($filename));
      fclose ($fp);
      // ajout du fichier dans cet objet
      $zip->addfile($content, $d.'-'.$img);
   }
}
// production de l'archive' Zip
$archive = $zip->file();
 
// ent�tes HTTP
header('Content-Type: application/x-zip');
// force le t�l�chargement
header('Content-Disposition: inline; filename=archive.zip');
 
// envoi du fichier au navigateur
echo $archive;
?>