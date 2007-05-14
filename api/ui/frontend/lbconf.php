<?php

class lbconf {


   /*------------------------------------------------------------------------------
    OPTIONS STATUS
    -----------------------------------------------------------------------------*/

   /**
    *
    *
    */
   function metaEnabled() {
      return (SHOW_META == 'on');
   }

   /**
    *
    *
    */
   function commentsEnabled() {
      return (SHOW_COMMENTAIRE == 'on');
   }

   /**
    *
    *
    */
   function slideshowEnabled() {
      return (SHOW_SLIDESHOW == 'on');
   }

   /**
    *
    *
    */
   function selectionEnabled() {
      return (SHOW_SELECTION == 'on');
   }

   /**
    *
    *
    */
   function slideshowFadingEnabled() {
      return (SLIDESHOW_FADING == 'on');
   }
   

   /*------------------------------------------------------------------------------
    Templates options
    -----------------------------------------------------------------------------*/
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function pageStyle ($return = false) {
      global $themes_css;
      if (!array_key_exists (TEMPLATE_THEME, $themes_css)) {
         $default = DEFAULT_CSS;
      }
      else {
         $default = TEMPLATE_THEME;
      }

      $result = '';
      $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
      TEMPLATE_DIR. TEMPLATE.'/themes/'.$default.'/'.$default.'.css', '');
      while (list ($theme, $title) = each ($themes_css)) {
         if ($theme != $default) {
            $result .= sprintf('<link rel="alternate stylesheet" href="%s" title="%s" type="text/css"/>',
            TEMPLATE_DIR. TEMPLATE.'/themes/'.$theme.'/'.$theme.'.css', $title);
         }
         else {
            $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>',
            TEMPLATE_DIR. TEMPLATE.'/themes/'.$default.'/'.$default.'.css', $title);
         }
      }
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function colorThemePath ($return = false) {
      $result = TEMPLATE_DIR. TEMPLATE.'/themes/'.TEMPLATE_THEME;
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function getVideoPlayer($return = false) {
      $result = TEMPLATE_COMMON_DIR.'/flash/video/'.$GLOBALS['video_player'][TEMPLATE_THEME].'.swf';
      if ($return) return $result;
      echo $result;
   }
   
   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function flashPlayerBgcolor($return = false) {
      $result = $GLOBALS['video_player_bgcolor'][TEMPLATE_THEME];
      if ($return) return $result;
      echo $result;
   }

   /**
    *
    *
    * @param boolean return Type of return : true return result as a string, false (default) print in stdout
    */
   function favicon($return = false) {
      if (is_file(PHOTOS_DIR.'favicon.ico')) {
         $favicon = PHOTOS_DIR.'favicon.ico';
         $result = sprintf('<link rel="shortcut icon" href="%s"/>', $favicon);
         if ($return) return $result;
         echo $result;
      }
   }
   
}

?>