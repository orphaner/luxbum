<?php
class ui_public_Selection {
   /**
    *
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function select($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];

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
      $archive = $zip->file();

      // HTTP Headers
      header('Pragma: no-cache');
      header('Content-Type: application/x-zip');
      
      // Force download
      header('Content-Disposition: inline; filename=archive.zip');

      // Send data to the browser
      echo $archive;
   }
}

?>