<?php 

// Au revoir les erreurs
error_reporting (E_ALL);

//------------------------------------------------------------------------------
// Constantes
//------------------------------------------------------------------------------
define ('FONCTIONS_DIR',       '_fonctions/');
define ('ADMIN_DIR',           '_fonctions_manager/');
define ('CONF_DIR',            '_conf/');
define ('LIB_DIR',             '_librairies/');
define ('STYLE_DIR',           '_styles/');
define ('STRUCTURE_DIR',       '_structure/');
define ('ADMIN_STRUCTURE_DIR', '_structure_manager/');
define ('INDEX_FILE',          'index.php');
define ('ADMIN_FILE',          'manager.php');

define ('PHOTOS_DIR', 'photos/');
define ('THUMB_DIR', 'vignette/');
define ('PREVIEW_DIR', 'apercu/');
define ('DESCRIPTION_FILE', 'description.txt');
define ('ORDER_FILE', 'ordre.txt');
define ('DEFAULT_INDEX_FILE', 'defaut.txt');
define ('ALLOWED_FORMAT', 'jpg|jpeg|png|gif');
define ('PASS_FILE', 'pass.php');

define ('TEMPLATE_DIR', 'templates/');
define ('TEMPLATE', 'photoblog');
define('LOCALE_DIR', 'locale/');

$GLOBALS['debug'] = true;


include (TEMPLATE_DIR.TEMPLATE.'/css/themes_css.php');
include (TEMPLATE_DIR.TEMPLATE.'/conf_'.TEMPLATE.'.php');


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (CONF_DIR.'config.php');
include_once(FONCTIONS_DIR.'views.php');
include_once(FONCTIONS_DIR.'class/verif.php');

include_once(FONCTIONS_DIR.'extinc/class.l10n.php');

$locales = l10n::getAvailableLocales();

if (!empty($_COOKIE['lang']) && in_array($_COOKIE['lang'], $locales)) {
   $lang = $_COOKIE['lang'];
}
else {
   $lang = l10n::getAcceptedLanguage($locales);
}


$l = new l10n($lang);



?>