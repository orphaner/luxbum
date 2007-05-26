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
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      $selection->addFile($dir, $file);
      Selection::saveSelection($selection);

      $redirect = link::gallery($dir, $file);
      header('Location: '.$redirect);
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
      $this->checkPrivate($dir);

      $selection = Selection::getInstance();
      $selection->removeFile($dir, $file);
      Selection::saveSelection($selection);

      $redirect = link::gallery($dir, $file);
      header('Location: '.$redirect);
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