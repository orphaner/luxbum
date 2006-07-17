<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');


//------------------------------------------------------------------------------
// Parsing des paramètres
//------------------------------------------------------------------------------
if (ereg ('^/affichage-([0-9]+)-(.+)-(.+)\.html$', $_SERVER['QUERY_STRING'], $argv) ) {
   $page_courante = $argv[1];
   $dir           = $argv[2];
   $file          = $argv[3];
}
else  {
   exit ('manque des paramètres OFF');
}

verif::isImage ($dir, $file);



function getScriptURL () {
   $url = '';
   if ($_SERVER['SERVER_PORT'] == 80) {
      $url .= 'http://';
   }
   elseif ($_SERVER['SERVER_PORT'] == 443) {
      $url .= 'https://';
   }
   $url .= $_SERVER['SERVER_NAME'];

   $path = split ($_SERVER['REQUEST_URI'], '/');
   for ($i=0 ; $i < count ($path)-1 ; $i++) {
      $url .= '/'.$path[$i];
   }
   $url .= '/';
   return $url;
}


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------

// Page modelixe
$page = new ModeliXe('affichage.mxt');
$page->SetModeliXe();
remplir_style ($page);



//------------------------------------------------------------------------------
// Code principal
//------------------------------------------------------------------------------

$luxAff = new luxBumImage ($dir, $file);
$luxAff->exifInit ();


//----------------
// Affichage de la photo
// $page->MxAttribut ('photo', $luxAff->getAsPreview ());
// $page->MxAttribut ('alt',   $luxAff->getImageName ());

if ($luxAff -> findDescription () == true) {
   $page->MxText ('desc', $luxAff->getDateDesc ());
}


$altTitle = luxbum::niceName ($luxAff -> getImageName()) .' - '
   . ucfirst ($luxAff -> getDescription ());

$page->MxImage ('photo', $luxAff->getPreviewLink(), $altTitle, 
                'title="'.$altTitle.'" '. ($luxAff->getPreviewResizeSize ()), true);
$page->MxUrl      ('lien',  $luxAff->getImagePath ());

//@start upd dark 1.3 : changement du style dans les vignettes si photo selectionnee
//@implique : ajout style "view_photo_selected" dans css et attribut "style" dans page modeliXe
// Si la sélection est désactivée, on dégage le bloc
if (SHOW_SELECTION == 'off') {
   $page->MxBloc ('selection', 'delete');
}
else {
   if (isSet($_SESSION['luxbum'][$dir][$file])){
      $page->MxUrl('selection.lien_selection', lien_apercu_unselect($dir, $file, ($page_courante+1)));
      $page->MxText('selection.info_selection', 'Désélectionner');
      $page->MxAttribut('photo_selection', 'photo_selected');
   }
   else{
      $page->MxUrl('selection.lien_selection', lien_apercu_select($dir, $file, ($page_courante+1)));
      $page->MxText('selection.info_selection', 'Sélectionner');
      $page->MxAttribut('photo_selection', '');
   }
   
   // Si la sélection est non vide
   if (isSet($_SESSION['luxbum_selection_size']) && $_SESSION['luxbum_selection_size'] > 0){
      $page->MxUrl('selection.selection_empty.gestion_selection', INDEX_FILE.'?p=selection_list');
      $page->MxText('selection.selection_empty.action_selection', 'Voir la sélection ('.$_SESSION['luxbum_selection_size'].')');
      
      // Si on ne peut pas télécharger la sélection, on dégage le bloc !
      if (ALLOW_DL_SELECTION == 'off') {
         $page->MxBloc('selection.selection_empty.selection_dl_ok', 'delete');
      }
      else {
         $page->MxUrl('selection.selection_empty.selection_dl_ok.dl_selection', INDEX_FILE.'?p=dl_selection');
         $page->MxText('selection.selection_empty.selection_dl_ok.telecharge_selection', 'Télécharger la selection');
      }
   }
   
   // La sélection est vide, on dégage le bloc !
   else{
      $page->MxBloc ('selection.selection_empty', 'modify', 'La sélection est vide !');
   }
}
//@end upd dark 1.3



//----------------
// Liens quand la page est hors de sa frame
$lien_redirect = (getScriptURL()).lien_vignette_redirect ($page_courante, $dir, $luxAff->getImageName ());
$page->MxText ('redirect_script',   $lien_redirect);
$page->MxUrl  ('redirect_noscript', $lien_redirect);

if (SHOW_EXIF == 'on' && $luxAff->exifExists ()) {
   $page->MxText ('exif.lien', INDEX_FILE.'?p=infos_exif&amp;d='.$dir.'&amp;photo='.$file);
}
else {
   $page->MxBloc ('exif', 'delete');
}

if (SHOW_COMMENTAIRE == 'on') {
   $page->MxText ('commentaire.lien', INDEX_FILE.'?p=comment&amp;d='.$dir.'&amp;photo='.$file);
   $mysql = new MysqlInc (DBL_HOST, DBL_LOGIN, DBL_PASSWORD, DBL_NAME);
   $mysql->DbConnect ();
   $page->MxText ('commentaire.nb', $luxAff->getNbComment($mysql));
   $mysql->DbClose();
}
else {
   $page->MxBloc ('commentaire', 'delete');
}


//----------------
// Liens suivants et précédents

$nuxThumb = new luxBumGallery($dir);
$nuxThumb->addAllImages ();
$galleryCount = $nuxThumb->getCount ();
$imageIndex = $nuxThumb->getImageIndex ($file);

$newBack = false;
$newForward = false;
if ($imageIndex % LIMIT_THUMB_PAGE == 0) {
   $newBack = true;
}

if ($imageIndex % LIMIT_THUMB_PAGE == LIMIT_THUMB_PAGE - 1) {
   $newForward = true;
}

$page_courante++;

// Une seule image dans la galerie
if ($galleryCount == 1) {
   $page->MxBloc ('back', 'delete');
   $page->MxBloc ('forward', 'delete');
}

// Première image
else if ($imageIndex == 0) {
   $page->MxBloc ('back', 'delete');
   $page->MxUrl ('forward.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex+1]->getImageName (), $page_courante));
}

// Dernière image
else if ($imageIndex == $galleryCount-1) {
   if ($newBack == true) {
      $page->MxUrl ('back.lien',  lien_vignette_redirect ($page_courante-2, $dir, $nuxThumb->list[$imageIndex-1]->getImageName ()));
      $page->MxAttribut ('back.target', '_parent');
   }
   else {
      $page->MxUrl ('back.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex-1]->getImageName (), $page_courante));
      $page->MxAttribut ('back.target', '_self');
   }
   $page->MxBloc ('forward', 'delete');
}

// Les deux liens
else {
   if ($newBack == true) {
      $page->MxUrl ('back.lien',  lien_vignette_redirect ($page_courante-2, $dir, $nuxThumb->list[$imageIndex-1]->getImageName ()));
      $page->MxAttribut ('back.target', '_parent');
   }
   else {
      $page->MxUrl ('back.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex-1]->getImageName (), $page_courante));
      $page->MxAttribut ('back.target', '_self');
   }


   if ($newForward == true) {
      $page->MxUrl ('forward.lien',  lien_vignette ($page_courante, $dir));
      $page->MxAttribut ('forward.target', '_parent');
   }
   else {
      $page->MxUrl ('forward.lien', lien_apercu ($dir, $nuxThumb->list[$imageIndex+1]->getImageName (), $page_courante));
      $page->MxAttribut ('forward.target', '_self');
   }
}



//----------------
// Affichage de la page
$page->MxWrite();

?>