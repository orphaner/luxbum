<?php
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}


if ($GLOBALS['debug'] === true) {
   xdebug_enable();
}

$GLOBALS['startTime'] = microtime_float();
session_start();



function showDebugInfo() {
   if ($GLOBALS['debug'] === true) {
      echo "\n\n".'mem: '.(int)(memory_get_usage()/1024).' ko';
      echo ' - exec time (sec): '.((microtime_float() - $GLOBALS['startTime'])*1000).' ms';
      echo '<br><strong>Untranslated:</strong> <br>'; 
      if (!isset($GLOBALS['_PX_debug_data']['untranslated'])) {
         echo 'Toutes les chaines sont ok';
      }
      else {
         while (list(,$k) = each($GLOBALS['_PX_debug_data']['untranslated'])) {
            echo ';'.$k.'<br><br><br>';
         }
      }
      echo '<pre>';
      echo '</pre>';
   }
}

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include ('common.php');
include(FONCTIONS_DIR.'lib.frontend.php');
include(FONCTIONS_DIR.'class/link.php');
include(FONCTIONS_DIR.'luxbum.class.php');

include(FONCTIONS_DIR.'class/luxbumgallery.class.php');
include(FONCTIONS_DIR.'class/luxbumimage.class.php');
include(FONCTIONS_DIR.'class/imagetoolkit.class.php');
include(FONCTIONS_DIR.'class/imagetoolkit.imagick.class.php');
include(FONCTIONS_DIR.'class/luxbumindex.class.php');
include(FONCTIONS_DIR.'class/commentaire.class.php');
include(FONCTIONS_DIR.'extinc/class.dispatcher.php');





Dispatcher::Launch($_SERVER['QUERY_STRING']);

?>