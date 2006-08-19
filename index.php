<?php
$GLOBALS['startTime'] = microtime();
session_start();



function showDebugInfo() {
   if ($GLOBALS['debug'] === true) {
      echo "\n\n".'mem: '.(int)(memory_get_usage()/1024).' ko';
      echo ' - exec time (sec): '.((microtime() - $GLOBALS['startTime'])*1000).' ms';
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
include_once(FONCTIONS_DIR.'lib.frontend.php');
include_once(FONCTIONS_DIR.'class/link.php');
include_once(FONCTIONS_DIR.'extinc/class.dispatcher.php');
include_once(FONCTIONS_DIR.'luxbum.class.php');

include_once(FONCTIONS_DIR.'extinc/class.recordset.php');
include_once(FONCTIONS_DIR.'class/luxbumgallery.class.php');
include_once(FONCTIONS_DIR.'class/luxbumimage.class.php');
include_once(FONCTIONS_DIR.'class/luxbumindex.class.php');
include_once(FONCTIONS_DIR.'class/files.php');
include_once(FONCTIONS_DIR.'class/commentaire.class.php');
include_once(FONCTIONS_DIR.'private.php');




Dispatcher::Launch($_SERVER['QUERY_STRING']);
?>