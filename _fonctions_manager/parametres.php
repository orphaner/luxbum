<?php

if (!isAdmin ()) {
   $_SESSION = array ();
   session_destroy ();
   die ("On respecte ses admins");
}

include FONCTIONS_DIR.'utils/formulaires.php';
include FONCTIONS_DIR.'luxbum.class.php';
$str_critere = ADMIN_FILE.'?p=parametres';

// Page modelixe
definir_titre ($page, 'Paramètres - LuxBum Manager');
$page->MxAttribut ('isadmin.class_parametres', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'parametres.mxt');
$page->WithMxPath ('main', 'relative');



function template_theme_select () {
   return array ('2COL' => '2 colones','3COL' => '3 colones');
}
function on_off_select () {
   return array ('on' => 'Oui','off' => 'Non');
}
function border_pixel_select () {
   $tab = array ();
   for ($i=0 ; $i <= 6 ; $i++) {
      $tab [$i] = $i;
   }
   return $tab;
}


$param['nom_galerie']          = NOM_GALERIE;
$param['template_theme']       = TEMPLATE_THEME;
$param['color_theme']          = COLOR_THEME;
/*$param['photos_dir']           = PHOTOS_DIR;
$param['thumb_dir']            = THUMB_DIR;
$param['preview_dir']          = PREVIEW_DIR;
$param['description_file']     = DESCRIPTION_FILE;
$param['default_index_file']   = DEFAULT_INDEX_FILE;
$param['allowed_format']       = ALLOWED_FORMAT;*/
$param['use_rewrite']          = USE_REWRITE;
$param['mkdir_safe_mode']      = MKDIR_SAFE_MODE;
$param['date_format']          = DATE_FORMAT;
$param['min_size_for_preview'] = MIN_SIZE_FOR_PREVIEW;
$param['max_file_size']        = MAX_FILE_SIZE;
$param['show_exif']            = SHOW_EXIF;

$param['show_commentaire']     = SHOW_COMMENTAIRE;
$param['db_host']              = DB_HOST;
$param['db_name']              = DB_NAME;
$param['db_login']             = DB_LOGIN;
$param['db_password']          = DB_PASSWORD;
$param['db_prefix']            = DB_PREFIX;

$param['show_slideshow']       = SHOW_SLIDESHOW;
$param['slideshow_time']       = SLIDESHOW_TIME;
$param['slideshow_fading']     = SLIDESHOW_FADING;

$param['show_selection']       = SHOW_SELECTION;
$param['allow_dl_selection']   = ALLOW_DL_SELECTION;

$param['image_border_pixel']      = IMAGE_BORDER_PIXEL;
$param['image_border_max_nuance'] = IMAGE_BORDER_MAX_NUANCE;
$param['image_border_hex_color']  = IMAGE_BORDER_HEX_COLOR;

$param2['manager_username']  = MANAGER_USERNAME;
$param2['manager_password']  = MANAGER_PASSWORD;
$param2['manager_password_1'] = '';
$param2['manager_password_2'] = '';


/**-----------------------------------------------------------------------**/
/* Fonctions de vérification de formulaires */
/**-----------------------------------------------------------------------**/
/**
 * Vérifie qu'un nom de dossier est correct (avec / autorisé)
 */
function verif_dir_slash ($dir) {
   if (! ereg ("^[A-Za-z0-9_/]+$", $dir)) {
      return false;
   }
   return true;
}


/**
 * Vérifie les paramètres luxbum
 */
function verif_parametres () {
   global $page;
   global $param;
   $err_vide = false;
   $errCom = false;

   $cpt = 0;
   while (list ($key, $val) = each ($param)) {
      get_post ($key, $param[$key]);
      if (substr ($key, 0,3) == 'db_') {
         if ($param['show_commentaire'] == 'on') {
            if ($key != 'db_prefix') {
               $testErrCom = verif_non_vide ($key, $param[$key]);
            }
            if ($testErrCom > 0) {
               $cpt++;
               $errCom = true;
            }
         }
      }
      else {
         $cpt += verif_non_vide ($key, $param[$key]);
      }
   }

   if ($cpt > 0) {
      $err_vide = true;
   }

   // Vérification du dossier des photos
   /*if ($param['photos_dir'] != '') {
      $param['photos_dir'] = files::addTailSlash ($param['photos_dir']);

      if (!verif_dir_slash ($param['photos_dir'])) {
         $page->MxText ('err_photos_dir', unprotege_input ($param['photos_dir']).' n\'est pas un nom de dossier correct.');
         $err_vide = true;
      }
      else if (!is_dir ($param['photos_dir'])) {
         $page->MxText ('err_photos_dir', unprotege_input ($param['photos_dir']).' n\'existe pas.');
         $err_vide = true;
      }
      else if (!files::isWritable ($param['photos_dir'])) {
         $page->MxText ('err_photos_dir', 'Impossible d\'écrire dans '.unprotege_input ($param['photos_dir']));
         $err_vide = true;
      }
   }

   // Vérif du dossier des vignettes
   if ($param['thumb_dir'] != '') {
      $param['thumb_dir'] = files::addTailSlash ($param['thumb_dir']);
      if (!verif_dir_slash ($param['thumb_dir'])) {
         $page->MxText ('err_thumb_dir', unprotege_input ($param['thumb_dir']).' n\'est pas un nom de dossier correct.');
         $err_vide = true;
      }
   }

   // Vérif du dossier des previews
   if ($param['preview_dir'] != '') {
      $param['preview_dir'] = files::addTailSlash ($param['preview_dir']);
      if (!verif_dir_slash ($param['preview_dir'])) {
         $page->MxText ('err_preview_dir', unprotege_input ($param['preview_dir']).' n\'est pas un nom de dossier correct.');
         $err_vide = true;
      }
   }*/

   // Vérification de la couleur
   if ($param['image_border_hex_color'] != '' && !is_hex_color ($param['image_border_hex_color'])) {
      $err_vide = true;
      $page->MxText ('err_image_border_hex_color', 'Code hexa non valide !!');
   }

   // Vérification de la taille minie
   if (is_numeric ($param['min_size_for_preview'])) {
      if ($param['min_size_for_preview'] < 0) {
         $page->MxText ('err_min_size_for_preview', 'Doit être supérieur à zéro !!');
         $err_vide = true;
      }
   }
   else {
      $err_vide = true;
      $page->MxText ('err_min_size_for_preview', 'Non numérique !!');
   }

   // Vérification de la taille minie
   if (is_numeric ($param['max_file_size'])) {
      if ($param['max_file_size'] < 0) {
         $page->MxText ('err_max_file_size', 'Doit être supérieur à zéro !!');
         $err_vide = true;
      }
   }
   else {
      $err_vide = true;
      $page->MxText ('err_max_file_size', 'Non numérique !!');
   }
   
   // Vérification de la connection à la base de données
   if ($param['show_commentaire'] == 'on' && $errCom == false) {
      $mysql = new mysqlInc($param['db_host'], $param['db_login'], $param['db_password'], $param['db_name']);
      if (!$mysql->testDbConnect()) {
         $err_vide = true;
         $page->MxText('err_db_host', $mysql->mysqlErr());
      }
      else {
         if (commentaire::tableExists($param['db_prefix']) == false) {
            echo "création table<br>";
            commentaire::createTable($param['db_prefix']);
         }
         $mysql->DbClose();
      }
   }

   return !$err_vide;
}


/**
 * Vérifie les paramètres luxbum
 */
function verif_parametres_mdp () {
   global $page;
   global $param2;
   $err_vide = false;

   $cpt = 0;
   while (list ($key, $val) = each ($param2)) {
      get_post ($key, $param2[$key]);
      $cpt += verif_non_vide ($key, $param2[$key]);
   }

   if ($cpt > 0) {
      $err_vide = true;
   }
   if (md5 ($param2['manager_password']) != MANAGER_PASSWORD) {
      $page->MxText ('err_manager_password', 'Le mot de passe rentré ne correspond pas avec l\'actuel');
      $err_vide = true;
   }
   if ($param2['manager_password_1'] != '' && $param2['manager_password_2'] != '') {
      if ($param2['manager_password_1'] != $param2['manager_password_2']) {
         $page->MxText ('err_manager_password_1', 'Les deux mots de passe ne correspondent pas');
         $page->MxText ('err_manager_password_2', 'Les deux mots de passe ne correspondent pas');
         $err_vide = true;
      }
   }
   $page->MxAttribut ('val_manager_password', $param2['manager_password']);
   return !$err_vide;
}



/**-----------------------------------------------------------------------**/
/* Validation du formulaire de paramètres */
/**-----------------------------------------------------------------------**/
if (isset ($_GET['valid']) && $_GET['valid'] == 1) {
   if (verif_parametres () == true) {
      if (!files::isWritable (CONF_DIR.'config.php')) {
         $page->MxText ('message', 'Impossible d\'écrire dans le fichier '.CONF_DIR.'config.php');
      }
      else {
         $content = "<?php\n";
         reset ($param);
         while (list ($key, $val) = each ($param)) {
            $key = strtoupper ($key);
            $content .= "define ('$key', '$val');\n";
         }
         $content .= "?>";
         files::writeFile (CONF_DIR.'config.php', $content);
         $page->MxText ('message', 'Paramètres sauvegardés');
      }
   }
   else {
      $page->MxText ('message', 'Le formulaire contient des erreurs');
   }
}

/**-----------------------------------------------------------------------**/
/* Validation du formulaire de paramètres login/pass */
/**-----------------------------------------------------------------------**/
else if (isset ($_GET['valid']) && $_GET['valid'] == 2) {
   if (verif_parametres_mdp () == true) {
      if (!files::isWritable (CONF_DIR.'config_manager.php')) {
         $page->MxText ('message_mdp', 'Impossible d\'écrire dans le fichier '.CONF_DIR.'config_manager.php');
      }
      else {
         $content = "<?php\n";
         $content .= "define ('MANAGER_USERNAME', '".$param2['manager_username']."');\n";
         $content .= "define ('MANAGER_PASSWORD', '". md5 ($param2['manager_password_1']) ."');\n";
         $content .= "?>";
         files::writeFile (CONF_DIR.'config_manager.php', $content);
         $page->MxText ('message_mdp', 'Paramètres sauvegardés');
      }
   }
   else {
      $page->MxText ('message_mdp', 'Le formulaire contient des erreurs');
   }
}



/**-----------------------------------------------------------------------**/
/* Affichage de la page principale */
/**-----------------------------------------------------------------------**/
$page->MxAttribut ('action_parametres', $str_critere.'&amp;valid=1');

reset ($param);
while (list ($key, $val) = each ($param)) {
   $param[$key] = unprotege_input ($val);
}

$page->MxAttribut ('val_nom_galerie', $param['nom_galerie']);
$page->MxSelect ('template_theme', 'template_theme', $param['template_theme'], template_theme_select ());
$page->MxSelect ('color_theme', 'color_theme', $param['color_theme'], $themes_css);
/*
$page->MxAttribut ('val_photos_dir', $param['photos_dir']);
$page->MxAttribut ('val_thumb_dir', $param['thumb_dir']);
$page->MxAttribut ('val_preview_dir', $param['preview_dir']);

$page->MxAttribut ('val_description_file', $param['description_file']);
$page->MxAttribut ('val_default_index_file', $param['default_index_file']);
$page->MxAttribut ('val_allowed_format', $param['allowed_format']);
*/
$page->MxSelect ('use_rewrite', 'use_rewrite', $param['use_rewrite'], on_off_select ());
$page->MxSelect ('mkdir_safe_mode', 'mkdir_safe_mode', $param['mkdir_safe_mode'], on_off_select ());
$page->MxAttribut ('val_date_format', $param['date_format']);

$page->MxSelect ('image_border_pixel', 'image_border_pixel', $param['image_border_pixel'], border_pixel_select ());
$page->MxAttribut ('val_image_border_max_nuance', $param['image_border_max_nuance'] );
$page->MxAttribut ('val_image_border_hex_color', $param['image_border_hex_color'] );

$page->MxAttribut ('val_min_size_for_preview', $param['min_size_for_preview'] );
$page->MxAttribut ('val_max_file_size', $param['max_file_size'] );
$page->MxSelect ('show_exif', 'show_exif', $param['show_exif'], on_off_select ());

// Commentaires
$page->MxSelect ('show_commentaire', 'show_commentaire', $param['show_commentaire'],
                   on_off_select (), '', '', 'onchange="javascript:swapDiv(\'paramCommentaires\', \'show_commentaire\')"');
if ($param['show_commentaire'] == 'off') {
   $page->MxAttribut('commentaireDiv', 'invisible');
}
$page->MxAttribut ('val_db_host', $param['db_host'] );
$page->MxAttribut ('val_db_name', $param['db_name'] );
$page->MxAttribut ('val_db_login', $param['db_login'] );
$page->MxAttribut ('val_db_password', $param['db_password'] );
$page->MxAttribut ('val_db_prefix', $param['db_prefix'] );

//Diaporamas
$page->MxSelect ('show_slideshow', 'show_slideshow', $param['show_slideshow'],
                   on_off_select (), '', '', 'onchange="javascript:swapDiv(\'paramSlideshow\', \'show_slideshow\')"');
if ($param['show_slideshow'] == 'off') {
   $page->MxAttribut('slideshowDiv', 'invisible');
}
$page->MxAttribut ('val_slideshow_time', $param['slideshow_time'] );
$page->MxSelect ('slideshow_fading', 'slideshow_fading', $param['slideshow_fading'], on_off_select ());

// Sélection
$page->MxSelect ('show_selection', 'show_selection', $param['show_selection'],
                   on_off_select (), '', '', 'onchange="javascript:swapDiv(\'paramSelection\', \'show_selection\')"');
if ($param['show_selection'] == 'off') {
   $page->MxAttribut('selectionDiv', 'invisible');
}
$page->MxSelect ('allow_dl_selection', 'allow_dl_selection', $param['allow_dl_selection'], on_off_select ());


$page->MxAttribut ('action_parametres_mdp', $str_critere.'&amp;valid=2#form_mdp');
$page->MxAttribut ('val_manager_username', $param2['manager_username']);
$page->MxAttribut ('val_manager_password_1', $param2['manager_password_1']);
$page->MxAttribut ('val_manager_password_2', $param2['manager_password_2']);

?>