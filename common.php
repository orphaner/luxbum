<?php


/*----------------------------------------------------------------------------*/
/* Brin du d�but pour le d�veloppement */

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$GLOBALS['startTime'] = microtime_float();




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
define ('TEMPLATE_DIR', 'templates/public/');
define ('TEMPLATE_COMMON_DIR', 'templates/common/');
define ('TEMPLATE_MANAGER_DIR', 'templates/manager/');



include (CONF_DIR.'config.php');
include (TEMPLATE_DIR.TEMPLATE.'/conf_'.TEMPLATE.'.php');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------

//include('api/inc/exception.php');
include('api/inc/HTTP.php');
/*include('api/inc/HTTP/Error404.php');
include('api/inc/HTTP/Error500.php');
include('api/inc/HTTP/Request.php');
include('api/inc/HTTP/Response.php');
include('api/inc/HTTP/URL.php');
include('api/inc/HTTP/Response/Forbidden.php');
include('api/inc/HTTP/Response/NotFound.php');
include('api/inc/HTTP/Response/Redirect.php');
include('api/inc/HTTP/Response/RedirectToLogin.php');
include('api/inc/HTTP/Response/ServerErrorDebug.php');
include('api/inc/HTTP/Response/ServerError.php');*/


include('api/inc/recordset.php');
include('api/inc/sortablerecordset.php');
include('api/inc/l10n.php');

include('api/process/selection.php');
include('api/process/processFactory.php');
include('api/process/commongallery.php');
include('api/process/luxbumgallery.php');
include('api/process/luxbumselectiongallery.php');
include('api/process/luxbumindex.php');
include('api/process/private.php');
include('api/process/commentaire.php');
include('api/process/commonfile.php');
include('api/process/luxbumimage.php');
include('api/process/luxbumflv.php');

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
session_start();


$locales = l10n::getAvailableLocales();

if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], $locales)) {
   $lang = $_COOKIE['lang'];
}
else {
   $lang = l10n::getAcceptedLanguage($locales);
}

$l = new l10n($lang);

$dispatcher = new Dispatcher();

?>