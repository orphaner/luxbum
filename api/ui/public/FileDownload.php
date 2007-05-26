<?php

/**
 * @package ui
 */
class ui_public_FileDownload extends ui_CommonView {
   
   /**
    * Check access list
    * 
    * @return boolean
    */
   function checkACL() {
      return true;
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   public function image($request, $match) {
           
      $type = $match[1];
      $dir = files::addTailSlash($match[2]);
      $photo = $match[3];

      // Check if the gallery is private
      $this->checkPrivate($dir);
      
      
      verif::isImage($dir, $photo);

      $luxAff = new luxBumImage($dir, $photo);
      try {
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
      }
      catch(Pluf_HTTP_ImageException $e) {
         if (file_exists(Pluf::f('color_theme_path').'/images/file_error.png')) {
            $newfile = Pluf::f('color_theme_path').'/images/file_error.png';
         }
         else {
            $newfile = 'templates/common/images/file_error.png';
         }
      }

      if (headers_sent()) {
         die ("fuck header");
      }
      
      $response = new Pluf_HTTP_Response_Binary();
      $response->fileName = $newfile;
      $response->mimeType = $luxAff->getTypeMime();
      $response->addHttpHeader('Content-Encoding', 'none');
      $response->addHttpHeader('Pragma', 'public');
      $response->addHttpHeader('Cache-Control', 'maxage=3600');
      $response->addHttpHeader('Last-Modified',  HTTP::getHttpDate(filemtime($newfile)));
      $response->addHttpHeader('Expires', HTTP::getHttpDate(time() + 3600));
      
      return $response;
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
      $this->checkPrivate($dir);
      

      if (headers_sent()) {
         die ("fuck header");
      }

      $flv = new LuxbumFlv($dir, $file);
      
      $response = new Pluf_HTTP_Response_Binary();
      $response->fileName = luxbum::getFilePath($dir, $file);
      $response->mimeType = $flv->getTypeMime();
      $response->addHttpHeader('Content-Encoding', 'none');
      $response->addHttpHeader('Pragma', 'public');
      $response->addHttpHeader('Cache-Control', 'maxage=3600');
      $response->addHttpHeader('Last-Modified',  HTTP::getHttpDate(filemtime($newfile)));
      $response->addHttpHeader('Expires', HTTP::getHttpDate(time() + 3600));
      
      return $response;
   }
}
?>