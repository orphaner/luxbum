<?php

/**
 * @package inc
 */
class files {
    
   /**
    * Ajoute un slash final si il n'y en a pas
    */
   function addTailSlash ($dir) {
      if (strlen($dir) > 1 && $dir[strlen ($dir) - 1] != '/') {
         $dir = $dir.'/';
      }
      return $dir;
   }

   /**
    * Ajoute le slash final si il n'y en a un
    */
   function removeTailSlash ($dir) {
      if (strlen ($dir) == 0) {
         return $dir;
      }
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
         if ($fd = fopen($file, 'a')) {
            fclose ($fd);
            return true;
         }
         return false;
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
    * Suppression r�cursive d'un r�pertoire (rm -rf
    */
   function delTree ($dir)  {
      if ($current_dir = opendir ($dir)) {
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
      return false;
   }

   /**
    *
    */
   function writeFile ($file, $content, $mode = 'wt') {
      if ($fd = fopen ($file, $mode)) {
         fwrite ($fd, $content);
         fclose ($fd);
         return true;
      }
      return false;
   }

   /**
    *
    */
   function deleteFile ($file) {
      if (files::isDeletable ($file)) {
         return unlink ($file);
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
            $return = @mkdir ($name, 0777) && @rename ($name, $path);
         }
         else {
            $return = @mkdir ($path, 0777);
         }
         /*$return = $return && */umask ($old_umask);
         return $return;
      }
      return false;
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
   
   function isFile($dir, $file) {
      return $file[0] != '.'
         && !is_dir (luxbum::getFilePath($dir, $file) );
   }
    
   /**
    *
    */
   function isPhotoFile ($dir, $file) {
      return $file[0] != '.'
         && !is_dir (luxbum::getImage ($dir, $file) )
         && eregi ('^.*(' . ALLOWED_FORMAT . ')$', $file);
   }
    
   /**
    *
    */
   function isFlvFile ($dir, $file) {
      return $file[0] != '.'
         && !is_dir (luxbum::getImage ($dir, $file) )
         && eregi ('^.*(flv)$', $file);
   }

   /**
    *
    */
   function getExtension($f) {
      $f = explode('.',basename($f));

      if (count($f) <= 1) {
         return '';
      }

      return strtolower($f[count($f)-1]);
   }

   /**
    *
    */
   function getMimeType($file) {
      $ext = files::getExtension($file);
      return files::getMimeTypeFromExtention($ext);
   }

   /**
    *
    */
   function getMimeTypeFromExtention($ext) {
      $types = files::mimeTypes();

      if (isset($types[$ext])) {
         return $types[$ext];
      }
      else {
         return 'text/plain';
      }
   }

   /**
    *
    */
   function mimeTypes() {
      return array(
	      'odt'  => 'application/vnd.oasis.opendocument.text',
	      'odp'  => 'application/vnd.oasis.opendocument.presentation',
	      'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
	
	      'sxw'  => 'application/vnd.sun.xml.writer',
	      'sxc'  => 'application/vnd.sun.xml.calc',
	      'sxi'  => 'application/vnd.sun.xml.impress',
	
	      'ppt'  => 'application/mspowerpoint',
	      'doc'  => 'application/msword',
	      'xls'  => 'application/msexcel',
	      'rtf'  => 'application/rtf',
	
	      'pdf'  => 'application/pdf',
	      'ps'   => 'application/postscript',
	      'ai'   => 'application/postscript',
	      'eps'  => 'application/postscript',
	
	      'bin'  => 'application/octet-stream',
	      'exe'  => 'application/octet-stream',
	
	      'deb'  => 'application/x-debian-package',
	      'gz'   => 'application/x-gzip',
	      'jar'  => 'application/x-java-archive',
	      'rar'  => 'application/rar',
	      'rpm'  => 'application/x-redhat-package-manager',
	      'tar'  => 'application/x-tar',
	      'tgz'  => 'application/x-gtar',
	      'zip'  => 'application/zip',
	
	      'aiff' => 'audio/x-aiff',
	      'ua'   => 'audio/basic',
	      'mp3'  => 'audio/mpeg3',
	      'mid'  => 'audio/x-midi',
	      'midi' => 'audio/x-midi',
	      'ogg'  => 'application/ogg',
	      'wav'  => 'audio/x-wav',
	
	      'swf'  => 'application/x-shockwave-flash',
	      'swfl' => 'application/x-shockwave-flash',
	
	      'bmp'  => 'image/bmp',
	      'gif'  => 'image/gif',
	      'jpeg' => 'image/jpeg',
	      'jpg'  => 'image/jpeg',
	      'jpe'  => 'image/jpeg',
	      'png'  => 'image/png',
	      'tiff' => 'image/tiff',
	      'tif'  => 'image/tiff',
	      'xbm'  => 'image/x-xbitmap',
	
	      'css'  => 'text/css',
	      'js'   => 'text/javascript',
	      'html' => 'text/html',
	      'htm'  => 'text/html',
	      'txt'  => 'text/plain',
	      'rtf'  => 'text/richtext',
	      'rtx'  => 'text/richtext',
	
	      'mpg'  => 'video/mpeg',
	      'mpeg' => 'video/mpeg',
	      'mpe'  => 'video/mpeg',
	      'viv'  => 'video/vnd.vivo',
	      'vivo' => 'video/vnd.vivo',
	      'qt'   => 'video/quicktime',
	      'mov'  => 'video/quicktime',
	      'avi'  => 'video/x-msvideo',
	      'flv'  => 'video/x-flv'
         );
   }
}


?>