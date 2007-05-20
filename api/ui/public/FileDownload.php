<?php
class ui_public_FileDownload {
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function image($request, $match) {
           
      $type = $match[1];
      $dir = files::addTailSlash($match[2]);
      $photo = $match[3];

      verif::isImage($dir, $photo);

      $luxAff = new luxBumImage($dir, $photo);
      if ($type == 'vignette') {
         $newfile = $luxAff->generateThumb(VIGNETTE_THUMB_W, VIGNETTE_THUMB_H, VIGNETTE_CROP);
      }
      else if ($type == 'index') {
         $newfile = $luxAff->generateThumb(INDEX_THUMB_W, INDEX_THUMB_H, INDEX_CROP);
      }
      else if ($type == 'full') {
         $newfile = $luxAff->getImagePath();
      }
      else if ($type == 'apercu') {
         $newfile = $luxAff->generatePreview(PREVIEW_W, PREVIEW_H, PREVIEW_CROP);
      }
      else {
         throw new Exception("ui_public_FileDownload->image: $type is not valid");
      }

      if (headers_sent()) {
         die ("fuck header");
      }

      header('Content-Encoding: none');
      header('Content-Type: '.$luxAff->getTypeMime());
      header('Cache-Control: maxage=3600');
      header('Pragma: public');
      header('Last-Modified: ' + HTTP::getHttpDate(filemtime($newfile)));
      header('Expires: ' . HTTP::getHttpDate(time() + 3600));
    
      if ($fp = fopen($newfile, 'rb')) {
         while (!feof($fp)) {
            print fread($fp, 4096);
         }
      }
      @fclose ($fp);
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function flv($request, $match) {
      $dir = files::addTailSlash($match[1]);
      $file = $match[2];

      // Check if the gallery is private
      if (PrivateManager::isLockedStatic($dir)) {
         return PrivateView::action($match);
      }

      if (headers_sent()) {
         die ("fuck header");
      }

      $flv = new LuxbumFlv($dir, $file);
      
      header('Content-Encoding: none');
      header('Content-Type: '.$flv->getTypeMime());
      header('Cache-Control: maxage=3600');
      header('Pragma: public');
      header('Last-Modified: ' + HTTP::getHttpDate(filemtime(luxbum::getFilePath($dir, $file))));
      header('Expires: ' . HTTP::getHttpDate(time() + 3600));
      
      
      if ($fp = fopen(luxbum::getFilePath($dir, $file), 'rb')) {
         while (!feof($fp)) {
            print fread($fp, 4096);
         }
      }
      @fclose ($fp);
   }
}
?>