<?php
$GLOBALS['startTime'] = microtime();

//@start upd dark 1.2
session_start();
//@end upd dark 1.2

function showDebugInfo() {
   echo "\n\n".'mem: '.(int)(memory_get_usage()/1024).' ko';
   echo ' - exec time (sec): '.((microtime() - $GLOBALS['startTime'])*1000).' ms';
   echo '<pre>';
//   print_r( $GLOBALS['_LB_render']['res']);
   echo '</pre>';
}

//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include ('common.php');
include_once(FONCTIONS_DIR.'views.php');
include_once(FONCTIONS_DIR.'lib.frontend.php');
include_once(FONCTIONS_DIR.'extinc/class.dispatcher.php');
include_once(FONCTIONS_DIR.'luxbum.class.php');


Dispatcher::Launch($_SERVER['QUERY_STRING']);
?>