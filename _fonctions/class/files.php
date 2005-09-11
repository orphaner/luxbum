<?php

class files {
   
   /**
    * Ajoute un slash final si il n'y en a pas
    */
   function addTailSlash ($dir) {
      if ($dir[strlen ($dir) - 1] != '/') {
         $dir = $dir.'/';
      }
      return $dir;
   }

   /**
    * Ajoute le slash final si il n'y en a un
    */
   function removeTailSlash ($dir) {
      if ($dir[strlen ($dir) - 1] == '/') {
         $dir = substr ($dir, 0, strlen ($dir) - 1);
      }
      return $dir;
   }

   /**
    *
    */
   function scandir ($d, $order = 0) {
      $res = array();
      if (($dh = @opendir($d)) !== false) {
         while (($f = readdir($dh)) !== false) {
            $res[] = $f;
         }
         closedir($dh);

         sort($res);
         if ($order == 1) {
            rsort($res);
         }

         return $res;
      } 
      else {
         return false;
      }
   }

   /**
    *
    */
   function isDeletable ($file) {
      if (is_file ($file)) {
         return is_writable (dirname ($file));
      } 
      else if (is_dir ($file)) {
         return (is_writable (dirname ($file)));// && count (files::scandir ($file)) <= 2);
      }
   }

   /**
    *
    */
   function isWritable ($file) {
      return files::isDeletable ($file);
   }

   /**
    * Suppression récursive d'un répertoire (rm -rf)
    */
   function delTree ($dir)  {
      $current_dir = opendir ($dir);
      while ($entryname = readdir ($current_dir)) {
         if (is_dir ($dir.'/'.$entryname) && ($entryname != '.' && $entryname!='..')) {
            if (!files::deltree ($dir.'/'.$entryname)) {
               return false;
            }
         }
         else if ($entryname != '.' && $entryname!='..') {
            if (!@unlink ($dir.'/'.$entryname)) {
               return false;
            }
         }
      }
      closedir ($current_dir);
      return @rmdir ($dir);
   }

   /**
    *
    */
   function writeFile ($file, $content, $mode = 'wt') {
      $fd = fopen ($file, 'wt');
      fwrite ($fd, $content);
      fclose ($fd);
   }

   /**
    *
    */
   function deleteFile ($file) {
      if (files::isDeletable ($file)) {
         unlink ($file);
         return true;
      }
      return false;
   }

   /**
    *
    */
   function createDir ($path) {
      if (!is_dir ($path)) {
         $old_umask = umask (0);
         if (MKDIR_SAFE_MODE == 'on') {
            $name = files::removeTailSlash ($path);
            $tab = explode ('/', $name);
            $name = $tab[count($tab) - 1];
            mkdir ($name, 0777);
            rename ($name, $path);
         } 
         else {
            mkdir ($path, 0777);
         }
         umask ($old_umask);
      }
   }

   /**
    *
    */
   function renameDir ($oldPath, $newPath) {
      if (is_dir ($newPath)) {
         return false;
      }
      rename ($oldPath, $newPath);
      return true;
   }
}


?>