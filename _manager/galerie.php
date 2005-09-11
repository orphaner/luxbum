<?php


//------------------------------------------------------------------------------
// Includes
//------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'utils/aff_page.inc.php');
include (FONCTIONS_DIR.'utils/formulaires.php');
include (FONCTIONS_DIR.'class/upload.class.php');


//------------------------------------------------------------------------------
// Paramètres
//------------------------------------------------------------------------------

if (!isset($_GET['d']) || !isset ($_GET['page'])) {
   exit ('manque des paramètres');
}

$dir = $_GET['d'];
$page_courante = $_GET['page'];

$f = '';
if (isset($_GET['f'])) {
   $f = $_GET['f'];
}

// Vérif du dossier
if ($dir == '') {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Il faut choisir un dossier !!</span>');
   $page->MxWrite ();
   exit (0);
}

else if (!verif_dir ($dir) || !is_dir (luxbum::getDirPath ($dir))) {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Nom de dossier incorrect !!');
   $page->MxWrite ();
   exit (0);
}
else if (!files::isWritable (luxbum::getDirPath ($dir))) {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Le dossier choisit doit être accessible en écriture !!');
   $page->MxWrite ();
   exit (0);
}


//------------------------------------------------------------------------------
// Variables
//------------------------------------------------------------------------------
$str_critere = ADMIN_FILE. '?p=galerie&amp;d='.$dir.'&amp;page='.$page_courante;
$str_critere2 = ADMIN_FILE. '?p=galerie&amp;d='.$dir.'&amp;page=%d';
$max_file_size = 1024*100;


//------------------------------------------------------------------------------
// Objet de la galerie
//------------------------------------------------------------------------------
$nuxThumb = new luxBumGalleryList ($dir);
$nuxThumb->addAllImages ();


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Galerie : '.$dir.' - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'galerie.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxAttribut ('action_vider_cache', $str_critere.'&amp;f=vider_cache');
$page->MxAttribut ('action_meme_date', $str_critere.'&amp;f=meme_date');
$page->MxText ('galerie_nom', luxbum::niceName ($dir));
$page->MxAttribut ('action_ajout_photo', $str_critere.'&amp;f=ajout_photo');

// Paramètrage de l'upload Photo
$upload = new Upload ();
$upload->MaxFilesize = $max_file_size;
$upload->FieldOptions = 'style="border-color:black;border-width:1px;"';
$upload->InitForm ();
$upload->DirUpload = luxbum::getDirPath ($dir);
$upload->WriteMode = 2;
$upload->Required = true;
$upload->Extension = '.gif;.jpg;.jpeg;.png';
$upload->MimeType = 'image/gif;image/pjpeg;image/jpeg;image/x-png'; 
$page->MxText ('form_upload', $upload-> Field[0] . $upload-> Field[1]);

// switch rapide
$nuxIndex = new  luxBumIndex ();
$nuxIndex->addAllGallery (0);
$nuxIndex->gallerySort ();
while (list (,$gallery) = each ($nuxIndex->galleryList)) {
   $tabSwitch[$gallery->getName ()] = $gallery->getNiceName ();
}
unset ($nuxIndex);
$page->MxAttribut ('action_rapid_switch', $str_critere.'&amp;');
$page->MxSelect ('rapid_switch', 'd', $dir, $tabSwitch);




//------------------------------------------------------------------------------
// Les actions
//------------------------------------------------------------------------------

// Vider le cache
if ($f == 'vider_cache') {
   $nuxThumb->clearCache ();
   $page->MxText ('message', 'Le cache de la galerie '.$dir.' a bien été vidé');
}

// Supprimer une photo
else if ($f == 'del') {
   if (isset ($_GET['img'])) {
      $img = $_GET['img'];
      $imageDel =  new luxBumImage ($dir, $img);

      if ($imageDel->delete ()) {
         $page->MxText ('message', 'La photo '.$img.' a été supprimée.');

         unset ($nuxThumb);
         $nuxThumb = new luxBumGalleryList ($dir);
         $nuxThumb->addAllImages ();
      }
   }
}

// Mettre une photo par défaut
else if ($f == 'defaut') {
   if (isset ($_GET['img'])) {
      $img = $_GET['img'];

      if ($nuxThumb->setNewDefaultImage ($img)) {
         $page->MxText ('message', 'La photo '.$img.' a été définie par défaut.');

         unset ($nuxThumb);
         $nuxThumb = new luxBumGalleryList ($dir);
         $nuxThumb->addAllImages ();
      }
   }
}


// Même date 
else if ($f == 'meme_date') {
   $meme_date = '';
   if (isset ($_POST['meme_date'])) {
      $meme_date = $_POST['meme_date'];
   }

   if ($meme_date == '') {
      $page->MxText ('err_meme_date', 'Champ vide !!!');
   }
   else if (verif_date ('meme_date', $meme_date)) {
      for ($i = 0 ; $i < $nuxThumb->getCount () ; $i++) {
         $nuxThumb->list[$i]->setDate ($meme_date);
      }
      $nuxThumb->updateDescriptionFile();
      $page->MxText ('message', 'La date '.$meme_date.' a été attribuée à toutes les photos.');
   }
   else {
      $page->MxAttribut ('val_meme_date', $meme_date);
   }
}

// Date et description
else if ($f == 'date_desc') {
   $err_date_desc = false;
   $err_date = '';
   $err_description = '';
   $date = '';
   $description = '';
   $img = '';


   if (isset ($_GET['img'])) {
      $img = $_GET['img'];

      // La date
      if (isset ($_POST['date'])) {
         $date = protege_input ($_POST['date']);

         if ($date != '') {
            if (!ereg("[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}", $date)) {
               $err_date = 'Mauvais format de date !!';
            }
            else {
               $tab = explode ('/', $date);

               if ($tab[0] <= 0 || $tab[0] > 31) {
                  $err_date = 'Le jour doit être comprit entre 1 et 31 !!';
               }
               else if ($tab[1] <= 0 || $tab[1] > 12) {
                  $err_date = 'Le mois doit être comprit entre 1 et 12 !!';
               }
            }
         }
      }

      // La description
      if (isset ($_POST['description'])) {
         $description = protege_input ($_POST['description']);
      }
      
      // On met à jour si tout est OK
      if (/*$err_description == '' &&*/ $err_date == '') {
         $where = $nuxThumb->getImageIndex ($img);
         if ($where > -1) {
            $nuxThumb->list[$where]->setDate (unprotege_input ($date));
            $nuxThumb->list[$where]->setDescription (unprotege_input ($description));
            $nuxThumb->updateDescriptionFile ();
            $page->MxText ('message', 'Date / description mis à jour');
         }
      }
   }
}

// Upload de photo
else if ($f == 'ajout_photo') {

   $upload-> Execute();

   if ($UploadError) {
      $errors = $upload->GetError ();
      list (,$err) = each ($errors[1]);
      $page->MxText ('err_upload', $err);
   }
   else {
      $page->MxText ('message', 'fichier envoyé');

      unset ($nuxThumb);
      $nuxThumb = new luxBumGalleryList ($dir);
      $nuxThumb->addAllImages ();
   }
}

$nuxThumb->createOrMajDescriptionFile ();
$nuxThumb->getDescriptions ();


//------------------------------------------------------------------------------
// Affichage
//------------------------------------------------------------------------------
//----------------
// Affichage des vignettes
define ('LIMIT_THUMB_PAGE', '15');
$galleryCount = $nuxThumb->getCount ();

if ($galleryCount == 0) {
   $page->MxBloc ('liste_photos', 'modify', 'Aucune photo dans la galerie');
}
else {
   $page->WithMxPath ('liste_photos', 'relative');


   $page_walk = $page_courante+1;

   // Parcours des vignettes
   for ($i = ($page_walk-1)   * LIMIT_THUMB_PAGE  ; 
        $i < ($page_walk) * LIMIT_THUMB_PAGE && $i < $galleryCount ; 
        $i++) {
      $file = $nuxThumb->list[$i];
      $file->findDescription ();
      $name = $file->getImageName ();

      $page->MxImage ('liste.photo', $file->getAsThumb (125,125));
      $page->MxAttribut ('liste.id_photo', $name);
      $page->MxAttribut ('liste.action_date_desc', $str_critere.'&amp;img='.$name.'&amp;f=date_desc#'.$name);

      if ($f == 'date_desc' && $img == $name) {
         $page->MxAttribut ('liste.val_description', unprotege_input ($description));
         $page->MxAttribut ('liste.val_date', unprotege_input ($date));
         $page->MxText ('liste.err_description', $err_description);
         $page->MxText ('liste.err_date', $err_date);
      }
      else {
         $page->MxAttribut ('liste.val_description', $file->getDescription());
         $page->MxAttribut ('liste.val_date', $file->getDate());
      }

      $page->MxText ('liste.defaut_oui_non', ($name == $nuxThumb->getPreview ()) ? 'Oui': 'Non');

      $page->MxUrl ('liste.defaut', $str_critere.'&amp;f=defaut&amp;img='.$name.'#'.$name);
      $page->MxUrl ('liste.del', "javascript:if(window.confirm('Etes-vous sûr de vouloir supprimer cette photo ?')) window.location='".$str_critere.'&amp;f=del&amp;img='.$name."';");
      $page->MxBloc ('liste', 'loop');
   }


   // Affichage par page
   $link = $str_critere2;
   $start = $page_courante * LIMIT_THUMB_PAGE;
   $AffPage = aff_page2 ($galleryCount, $page_courante, LIMIT_THUMB_PAGE, $start, $link);
   $page->MxText ('aff_page', $AffPage);
}


?>