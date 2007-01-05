<?php

if (!isAdmin ()) {
   $_SESSION = array ();
   session_destroy ();
   die ("On respecte ses admins");
}

include FONCTIONS_DIR.'utils/formulaires.php';
include FONCTIONS_DIR.'luxbum.class.php';
include (FONCTIONS_DIR.'class/upload.class.php');

$str_critere = ADMIN_FILE.'?p=parametres';

// Page modelixe
definir_titre ($page, 'Paramètres - LuxBum Manager');
$page->MxAttribut ('isadmin.class_parametres', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'parametres.mxt');
$page->WithMxPath ('main', 'relative');

// Paramètrage de l'upload Photo
$upload = new Upload ();
$upload->MaxFilesize = MAX_FILE_SIZE;
$upload->InitForm ();
$upload->DirUpload = PHOTOS_DIR;
$upload->WriteMode = 2;
$upload->Required = true;
$upload->Filename = 'favicon';
$upload->Extension = '.ico';//;.gif;.jpg;.jpeg;.png';
//$upload->MimeType = 'image/x-icon;image/gif;image/pjpeg;image/jpeg;image/x-png;image/png';


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
$param['use_rewrite']          = USE_REWRITE;
$param['mkdir_safe_mode']      = MKDIR_SAFE_MODE;
$param['date_format']          = DATE_FORMAT;
$param['min_size_for_preview'] = MIN_SIZE_FOR_PREVIEW;
$param['max_file_size']        = MAX_FILE_SIZE;
$param['show_exif']            = SHOW_EXIF;

$param['show_commentaire']     = SHOW_COMMENTAIRE;
$param['dbl_host']             = DBL_HOST;
$param['dbl_name']             = DBL_NAME;
$param['dbl_login']            = DBL_LOGIN;
$param['dbl_password']         = DBL_PASSWORD;
$param['dbl_prefix']           = DBL_PREFIX;

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
      if (substr ($key, 0,4) == 'dbl_') {
         if ($param['show_commentaire'] == 'on') {
            if ($key != 'dbl_prefix') {
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
      $mysqlParam = new mysqlInc($param['dbl_host'], $param['dbl_login'], $param['dbl_password'], $param['dbl_name']);
      if (!$mysqlParam->testDbConnect()) {
         $err_vide = true;
         $page->MxText('err_dbl_host', $mysqlParam->mysqlErr());
      }
      else {
         if (commentaire::tableExists($param['dbl_prefix']) == false) {
            commentaire::createTable($param['dbl_prefix']);
         }
         $mysqlParam->DbClose();
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
/* Validation du formulaire du favicon */
/**-----------------------------------------------------------------------**/
else if (isset ($_GET['valid']) && $_GET['valid'] == 3) {
   @chmod (PHOTOS_DIR.'favicon.ico', 0777);
   files::deleteFile(PHOTOS_DIR.'favicon.ico');
   $upload-> Execute();
   

   if ($UploadError) {
      $mess = '';
      $errors = $upload->GetError (1);
      print_r($errors);
      while (list (, $err) = each ($errors)) {
         $mess .= $err.'<br />';
      }
      $page->MxText ('message_favicon', $mess);
   }
   else {
      @chmod (PHOTOS_DIR.'favicon.ico', 0755);
      $page->MxText ('message_favicon', 'Le fichier a été correctement envoyé');
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
$page->MxAttribut ('val_dbl_host', $param['dbl_host'] );
$page->MxAttribut ('val_dbl_name', $param['dbl_name'] );
$page->MxAttribut ('val_dbl_login', $param['dbl_login'] );
$page->MxAttribut ('val_dbl_password', $param['dbl_password'] );
$page->MxAttribut ('val_dbl_prefix', $param['dbl_prefix'] );

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


//-----------------
// données pour le changement de mot de passe
$page->MxAttribut ('action_parametres_mdp', $str_critere.'&amp;valid=2#form_mdp');
$page->MxAttribut ('val_manager_username', $param2['manager_username']);
$page->MxAttribut ('val_manager_password_1', $param2['manager_password_1']);
$page->MxAttribut ('val_manager_password_2', $param2['manager_password_2']);


//-----------------
// Données pour l'upload du favicon
$page->MxAttribut ('action_favicon', $str_critere.'&amp;valid=3#form_favicon');
$page->MxAttribut ('max_file_size', $upload->MaxFilesize);

?>