<?php



/**
 * @package process
 */
class LuxbumFlv extends commonFile
{

   /**
    * Constructeur par défaut
    * @param String $dir le nom de la galerie
    * @param String $img le nom de l'image
    */
   function LuxbumFlv ($dir, $file) {
      $this->dir = $dir;
      $this->file = $file;
      $this->setAllDescription ('', '');
      $this->type = TYPE_FLV_FILE;
   }

   /**
    * Retourne le type mime de l'image
    * @return Type mime de l'image
    */
   function getTypeMime () {
      return files::getMimeType($this->file);
   }

   //function getVignettePageUrl() {
   //   return link::fileFlv($this->dir, $this->file);
   //}
   
   function getUrlPath() {
      return link::fileFlvDL($this->dir, $this->file);//URL_BASE.$this->getFilePath();
   }


   /**-----------------------------------------------------------------------**/
   /** Fonctions pour créer les thumbs / preview */
   /**-----------------------------------------------------------------------**/  

   function getThumbUrl() {
      return "http://www.ctsnet.org/graphics/icons/flash_icon.gif";
   }
   
   function getPreviewUrl() {
      return "";
   }
   /**
    * 
    * @return String 
    */
   function generateThumb ($dst_w = 85, $dst_h = 85) {
      // TODO
   }
   
   /**
    * Retourne la chaine de taille de la vignette pour la balise &gt;img&lt;
    * @return String Taille de la vignette pour la balise &gt;img&lt;
    */
   function getThumbResizeSize () {
      return "";
   }

   /**
    * Retourne la chaine de taille de l'aper�u pour la balise &gt;img&lt;
    * @return String Taille de l'aper�u pour la balise &gt;img&lt;
    */
   function getPreviewResizeSize () {
      return "";
   }

   /**-----------------------------------------------------------------------**/
   /** Fonctions pr le cache des images */
   /**-----------------------------------------------------------------------**/

   /**
    * Supprime la photo ainsi que tout son cache et les commentaires associés.
    * @return Boolean
    */
   function delete () {
      $this->clearCache ();
      commentaire::deletePhoto($this->dir, $this->file);
      return files::deleteFile(luxbum::getFsPath ($this->dir) . $this->file);
   }


   /**
    * Supprime le cache de l'image
    */
   function clearCache () {
      // TODO
   }
   
   function getVideoPlayer() {
      return TEMPLATE_COMMON_DIR.'/flash/video/'.$GLOBALS['video_player'][Pluf::f('template_theme')].'.swf';
   }
   function getFlashPlayerBgcolor() {
      return $GLOBALS['video_player_bgcolor'][Pluf::f('template_theme')];
   }
}

?>