<?php

/**
 * @package ui
 */
class ui_public_Selection extends ui_CommonView  {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return Pluf::f('show_selection');
   }
   
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function select($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];
      
      // Check if the gallery is private
	  $this->checkFile($dir, $file);
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      $selection->addFile($dir, $file);
      Selection::saveSelection($selection);
      
      return new Pluf_HTTP_Response_Redirect($_SERVER['HTTP_REFERER']);
   }

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function unselect($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];
      
      // Check if the gallery is private
	  $this->checkFile($dir, $file);
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      $selection->removeFile($dir, $file);
      Selection::saveSelection($selection);
      
      return new Pluf_HTTP_Response_Redirect($_SERVER['HTTP_REFERER']);
   }
   
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function selectall($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];
      
      // Check if the gallery is private
	  $this->checkDir($dir);
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      
      $gallery = luxBumGallery::getInstance($dir);
      $gallery->moveStart();
      while (!$gallery->EOF()) {
         $cFile = $gallery->f();
         $selection->addFile($dir, $cFile->getFile());
         $gallery->moveNext();
      }
      
      Selection::saveSelection($selection);

      return new Pluf_HTTP_Response_Redirect($_SERVER['HTTP_REFERER']);
   }
   
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function unselectall($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];
      
      // Check if the gallery is private
	  $this->checkDir($dir);
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      
      $gallery = luxBumGallery::getInstance($dir);
      $gallery->moveStart();
      while (!$gallery->EOF()) {
         $cFile = $gallery->f();
         $selection->removeFile($dir, $cFile->getFile());
         $gallery->moveNext();
      }
      
      Selection::saveSelection($selection);

      return new Pluf_HTTP_Response_Redirect($_SERVER['HTTP_REFERER']);
   }

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function deleteSelection($request, $match) {

      Selection::deleteSelection();
      header('Location: '.Pluf::f('url_base'));
   }

   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function downloadSelection() {
      // create an objet 'zipfile'
      $zip = new inc_ZipFile();

      $selection = Selection::getInstance();
      $array = $selection->toArray();
      for ($i = 0 ; $i < $selection->getCount() ; $i++) {
         $dir = files::addTailSlash($array[$i]['dir']);
         $file = $array[$i]['file'];
          
         // file path to add in the zip file
         $theFile = luxbum::getFilePath($dir, $file);
         
         // file content
         $fp = fopen($theFile, 'r');
         $content = fread($fp, filesize($theFile));
         fclose ($fp);
         
         // ajout du fichier dans cet objet
         $zip->addfile($content, $dir.'/'.$file);
      }

      // Generation the zip archive
      $content = $zip->file();
      
      $response = new Pluf_HTTP_Response_Binary();
      $response->content = $content;
      $response->mimeType = 'application/x-zip';
      $response->fileName = 'archive.zip';
      $response->doDownload = true;
      
      return $response;
   }
}

?>