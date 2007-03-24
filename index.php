<?php

if (!is_file('conf/config.php')) {
   exit('<a href="install.php">please install luxbum</a>');
}


/*----------------------------------------------------------------------------*/
/* Brin du début pour le développement */

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$GLOBALS['startTime'] = microtime_float();
// Le préfixe des variables de session
session_start();



if ($GLOBALS['debug'] === true) {
   xdebug_enable();
}


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

// Au revoir les erreurs
$GLOBALS['debug'] = true;
error_reporting (E_ALL);

/* </brin> */
/*----------------------------------------------------------------------------*/


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('API_DIR', '_fonctions/');
define ('CONF_DIR', 'conf/');
define ('INDEX_FILE', 'index.php');
define ('PHOTOS_DIR', 'photos/');
define ('THUMB_DIR', 'vignette/');
define ('COMMENT_DIR', '.comment/');
define ('PREVIEW_DIR', 'apercu/');
define ('DESCRIPTION_FILE', 'description.txt');
define ('ORDER_FILE', 'ordre.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');
define ('PASS_FILE', 'pass.php');
define ('LOCALE_DIR', 'locales/');
define ('TEMPLATE_DIR', 'templates/');



include (CONF_DIR.'config.php');
include (TEMPLATE_DIR.TEMPLATE.'/conf_'.TEMPLATE.'.php');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------

include('api/inc/recordset.php');
include('api/inc/sortablerecordset.php');
include('api/inc/l10n.php');

include('api/process/processFactory.php');
include('api/process/luxbumgallery.php');
include('api/process/luxbumindex.php');
include('api/process/private.php');
include('api/process/commentaire.php');
include('api/process/luxbumimage.php');

include('api/inc/paginator.php');
include('api/inc/imagetoolkit.php');
include('api/inc/imagetoolkit.imagemagick.php');
include('api/inc/imagetoolkit.gd.php');
include('api/inc/aff_page.inc.php');
include('api/inc/formulaires.php');
include('api/inc/files.php');
include('api/inc/verif.php');
include('api/inc/image.meta.php');
include('api/inc/zip.php');
include('api/inc/upload.php');
include('api/inc/dispatcher.php');

include('api/ui/lib.frontend.php');
include('api/ui/views.php');
include('api/ui/link.php');
include('api/process/luxbum.php');


$locales = l10n::getAvailableLocales();

if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], $locales)) {
   $lang = $_COOKIE['lang'];
}
else {
   $lang = l10n::getAcceptedLanguage($locales);
}

$l = new l10n($lang);

$dispatcher = new Dispatcher();

$dispatcher->registerController('IndexView', '#^/$#i');
$dispatcher->registerController('IndexView', '#^/folder/(.*)/$#i');
$dispatcher->registerController('ImageView', '#^/image/('.files::removeTailSlash(THUMB_DIR).'|'.files::removeTailSlash(PREVIEW_DIR).'|index|full)/(.+)/(.+)$#i');
$dispatcher->registerController('PrivateView', '#^/private/(.*)/$#i');
$dispatcher->registerController('VignetteView', '#^/private/(.*)/(.*)$#i');
$dispatcher->registerController('VignetteView', '#^/album/(.*)/$#i');
$dispatcher->registerController('VignetteView', '#^/album/(.*)/(.*)$#i');
$dispatcher->registerController('AffichageView', '#^/photo/(.*)/(.*)$#i');
$dispatcher->registerController('InfosMetaView', '#^/meta/(.*)/(.*)$#i');
$dispatcher->registerController('CommentaireView', '#^/comments/(.*)/(.*)$#i');
$dispatcher->registerController('SlideShowView', '#^/slide\-show/(.*)$#i');
$dispatcher->registerController('SlideShowView', '#^/slide\-show/(.*)/([0-9]+)$#i');


$dispatcher->Launch($_SERVER['QUERY_STRING']);

?>