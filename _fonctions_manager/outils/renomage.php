<?php

  //------------------------------------------------------------------------------
  // Includes
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');



//------------------------------------------------------------------------------
// Classes
//------------------------------------------------------------------------------
class renomage {
   var $dirList  = array ();
   var $fileList = array ();

   function verifExtension ($name) {
      return eregi ('^.*(' . ALLOWED_FORMAT . ')$', $name);
   }

   function verifImageName ($name) {
      return ereg ('^([A-Za-z0-9_])+(\.)(.*)$', $name);
   }

   function findInvalidDirs () {
      
      if (($dh = @opendir (PHOTOS_DIR)) !== false) {
         while (($f = readdir ($dh)) !== false) {
            if (is_dir (luxbum::getDirPath ($f)) && !verif_dir ($f) && $f[0] != '.') {
               $this->dirList[] = $f;
            }
         }
         closedir ($dh);
      }
      return count ($this->dirList);
   }

   function findInvalidFiles () {
      
      // galery dirs
      if (($dh = @opendir (PHOTOS_DIR)) !== false) {
         while (($f = readdir ($dh)) !== false) {
            $dir = luxbum::getDirPath ($f);
            if (is_dir ($dir) && $f[0] != '.') {

               // galery photos
               if (($df = @opendir ($dir)) !== false) {
                  while (($file = readdir ($df)) !== false) {
                     if (!is_dir ($dir.$file) 
                         && ($this->verifExtension ($file))
                         && !$this->verifImageName ($file)) {
                        $this->fileList[$f] = $file;
                     }
                  }
                  closedir ($df);
               }
            }
         }
      }
      closedir ($dh);
      
      return count ($this->fileList);
   }

   function renameDirs () {
      $resultTab = array ();
      for ($i = 0 ; $i < count ($this->dirList) ; $i++) {
         $dir = $this->dirList[$i];
         $oldDir = luxbum::getDirPath ($dir);
         $newDir = luxbum::getDirPath ($this->newName ($dir));
         if (@rename ($oldDir, $newDir)) {
            $resultTab [$oldDir] = $newDir;
         }
         else {
            $resultTab [$oldDir] = false;
         }
      }
      return $resultTab;
   }

   function renameFiles () {
      $resultTab = array ();
      while (list ($dir, $file) = each ($this->fileList)) {
         $oldFile = luxbum::getImage ($dir, $file);
         $file = strrev ($file);
         list ($ext, $name) = explode ('.', $file, 2);
         $name =  $this->newName ($name);
         $file = strrev ($ext . '.' . $name);
         $newFile = luxbum::getImage ($dir, $file);
         if (@rename ($oldFile, $newFile)) {
            $resultTab [$oldFile] = $newFile;
         }
         else {
            $resultTab [$oldFile] = false;
         }
      }
      return $resultTab;
   }

   function newName ($url) {
      $pattern = array ( 
         "�" => "Y", "�" => "u", "�" => "A", "�" => "A",
         "�" => "A", "�" => "A", "�" => "A", "�" => "A",
         "�" => "A", "�" => "C", "�" => "E", "�" => "E",
         "�" => "E", "�" => "E", "�" => "I", "�" => "I",
         "�" => "I", "�" => "I", "�" => "D", "�" => "N",
         "�" => "O", "�" => "O", "�" => "O", "�" => "O",
         "�" => "O", "�" => "O", "�" => "U", "�" => "U",
         "�" => "U", "�" => "U", "�" => "Y", "�" => "s",
         "�" => "a", "�" => "a", "�" => "a", "�" => "a",
         "�" => "a", "�" => "a", "�" => "a", "�" => "c",
         "�" => "e", "�" => "e", "�" => "e", "�" => "e",
         "�" => "i", "�" => "i", "�" => "i", "�" => "i",
         "�" => "o", "�" => "n", "�" => "o", "�" => "o",
         "�" => "o", "�" => "o", "�" => "o", "�" => "o",
         "�" => "u", "�" => "u", "�" => "u", "�" => "u",
         "�" => "y", "�" => "y", "'" => "_", " " => "_",
         "," => "_" , ":" => "_" , ";" => "_" , "." => "_", 
         "\""=> "_" , "%" => "_" , "-" => "_" , "?" => "_",
         "[" => "_" , "]" => "_" , "!" => "_" , "/" => "_",
         "=" => "_" , "+" => "_" , "&" => "_" , "_" => "_",
         "(" => "_", ")" => "_", ">" => "_", "<" => "_",
         "." => "_", "*" => "_", "�" => "_", "-" => "_");

      return  str_replace ("__", "_", strtr("$url", $pattern));
   }

}



//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Outils : Renomage - LuxBum Manager');
$page->MxAttribut ('class_outils', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'outils/renomage.mxt');
$page->WithMxPath ('main', 'relative');





$ren = new renomage ();

// Dossiers
$badDir = $ren->findInvalidDirs ();
if ($badDir == 0) {
   $page->MxBloc ('dir', 'modify', '<img src="_images/manager/check_on.png" alt="" /> Tous les noms des dossiers sont corrects');
}
else {
   $resDir = $ren->renameDirs ();
   while (list ($oldDir, $newDir) = each ($resDir)) {
      if ($newDir == false) {
         $page->MxImage ('dir.img', '_images/manager/check_off.png');
         $page->MxText ('dir.message', 'Erreur de renomage : '.$oldDir);
      }
      else {
         $page->MxImage ('dir.img', '_images/manager/check_on.png');
         $page->MxText ('dir.message', $oldDir.' =&gt; '.$newDir);
      }
      $page->MxBloc ('dir', 'loop');
   }
}


// Fichiers
$badFile = $ren->findInvalidFiles ();
if ($badFile == 0) {
   $page->MxBloc ('file', 'modify', '<img src="_images/manager/check_on.png" alt="" /> Tous les noms des fichiers sont corrects');
}
else {
   $resFile = $ren->renameFiles ();
   while (list ($oldFile, $newFile) = each ($resFile)) {
      if ($newFile == false) {
         $page->MxImage ('file.img', '_images/manager/check_off.png');
         $page->MxText ('file.message', 'Erreur de renomage : '.$oldFile);
      }
      else {
         $page->MxImage ('file.img', '_images/manager/check_on.png');
         $page->MxText ('file.message', $oldFile.' =&gt; '.$newFile);
      }
   }
}

?>