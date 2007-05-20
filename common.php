<?php


//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('API_DIR', '_fonctions/');
define ('CONF_DIR', 'conf/');
define ('INDEX_FILE', 'index.php');
define ('PHOTOS_DIR', 'photos/');
define ('THUMB_DIR', '.thumb/');
define ('COMMENT_DIR', '.comment/');
define ('PREVIEW_DIR', '.preview/');
define ('DESCRIPTION_FILE', 'description.txt');
define ('ORDER_FILE', 'ordre.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');
define ('PASS_FILE', 'pass.php');
define ('LOCALE_DIR', 'locales/');
define ('TEMPLATE_DIR', 'templates/public/');
define ('TEMPLATE_COMMON_DIR', 'templates/common/');
define ('TEMPLATE_MANAGER_DIR', 'templates/manager/');



//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------

include('api/Pluf/Pluf.php');

/**
 * Autoload function.
 *
 * @param string Class name.
 */
function __autoload($class_name)
{
    try {
        Pluf::loadClass($class_name);
    } catch (Exception $e) {
        return eval ('class '.$class_name.' {' .
                     '  function '.$class_name.'() {' .
                     '    throw new Exception("Class not found: '.$class_name.'");' .
                     '  }' .
                     '}');
    }
}


include('api/inc/l10n.php');

include('api/process/selection.php');
include('api/process/commongallery.php');
include('api/process/luxbumgallery.php');
include('api/process/luxbumselectiongallery.php');
include('api/process/luxbumindex.php');
include('api/process/private.php');
include('api/process/commentaire.php');
include('api/process/commonfile.php');
include('api/process/luxbumimage.php');
include('api/process/luxbumflv.php');

include('api/inc/formulaires.php');
include('api/inc/paginator.php');
include('api/inc/imagetoolkit.php');
include('api/inc/imagetoolkit.imagemagick.php');
include('api/inc/imagetoolkit.gd.php');
include('api/inc/aff_page.inc.php');
include('api/inc/files.php');
include('api/inc/verif.php');
include('api/inc/image.meta.php');
include('api/inc/dispatcher.php');
include('api/inc/HTTP.php');

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
//$l->optimizeLocale('locales/fr/luxbum.lang');
//$l->optimizeLocale('locales/fr/manager.lang');

$dispatcher = new Dispatcher();


?>