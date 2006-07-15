<?php


  //------------------------------------------------------------------------------
  // Includes
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'utils/aff_page.inc.php');
include (FONCTIONS_DIR.'class/upload.class.php');

// FIXME : ....
function cut_sentence ($Texte, $nbcar=0) {
   if (strlen($Texte) > $nbcar && (0!=$nbcar)) {
      $Tmp_Tb = explode( ' ', $Texte );
      $Tmp_Count = 0;
      $Tmp_O = '';

      while (list(,$v) = each($Tmp_Tb)) {
         if (strlen($Tmp_O) >= $nbcar) {
            break;
         }
         $Tmp_O .= $v.' ';
      }
      $Tmp_O = chop($Tmp_O);
      if (count($Tmp_Tb) > 1) {
         $Tmp_O .= '...';
      }
   }
   else {
      $Tmp_O = $Texte;
   }

   return $Tmp_O;
}


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
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Nom de dossier incorrect !!</span>');
   $page->MxWrite ();
   exit (0);
}
else if (!files::isWritable (luxbum::getDirPath ($dir))) {
   $page->MxBloc ('main', 'modify', '<span class="erreur">ERREUR: Le dossier choisit doit être accessible en écriture !!</span>');
   $page->MxWrite ();
   exit (0);
}


//------------------------------------------------------------------------------
// Variables
//------------------------------------------------------------------------------
$str_critere = ADMIN_FILE. '?p=galerie&amp;d='.$dir.'&amp;page='.$page_courante;
$str_critere2 = ADMIN_FILE. '?p=galerie&amp;d='.$dir.'&amp;page=%d';


//------------------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------------------
function jour_select () {
   $tab = array ();
   for ($i = 1 ; $i <= 31 ; $i++) {
      $tab[sprintf("%02d",$i)] = $i;
   }
   return $tab;
}
function mois_select () {
   return array ('01' => 'Janvier', 
                 '02' => 'Février', 
                 '03' => 'Mars', 
                 '04' => 'Avril', 
                 '05' => 'Mai', 
                 '06' => 'Juin', 
                 '07' => 'Juillet',
                 '08' => 'Aout', 
                 '09' => 'Septembre', 
                 '10' => 'Octobre', 
                 '11' => 'Novembre', 
                 '12' => 'Décembre');
}
// function annee_select () {
//    $tab = array ();
//    for ($i = date ('Y') ; $i >= 1900 ; $i--) {
//       $tab[$i] = $i;
//    }
//    return $tab;
// }
function tri_select () {
   return array ('type=nom&order=asc'          => 'Nom (croissant)',
                 'type=nom&order=desc'         => 'Nom (décroissant)',
                 'type=date&order=asc'         => 'Date (croissant)',
                 'type=date&order=desc'        => 'Date (décroissant)',
                 'type=description&order=asc'  => 'Description (croissant)',
                 'type=description&order=desc' => 'Description (décroissant)',
                 'type=manuel&order=asc'       => 'Manuel');
}

//------------------------------------------------------------------------------
// Objet de la galerie
//------------------------------------------------------------------------------
$nuxThumb = new luxBumGallery ($dir);
$nuxThumb->addAllImages ();


//------------------------------------------------------------------------------
// Init
//------------------------------------------------------------------------------
// Page modelixe
definir_titre ($page, 'Galerie : '.$dir.' - LuxBum Manager');
$page->MxAttribut ('class_galeries', 'actif');
$page->MxAttribut ('onload', "addPosition('positionFile');");
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'galerie.mxt');
$page->WithMxPath ('main', 'relative');
$page->MxUrl ('action_vider_cache', $str_critere.'&amp;f=vider_cache');
$page->MxUrl ('action_generer_cache', ADMIN_FILE.'?p=generer_cache&amp;d='.$dir);
$page->MxAttribut ('action_meme_date', $str_critere.'&amp;f=meme_date');
$page->MxText ('galerie_nom', luxbum::niceName ($dir));
$page->MxAttribut ('action_ajout_photo', $str_critere.'&amp;f=ajout_photo');
//$page->MxAttribut ('max_file_size', $max_file_size);

// Paramètrage de l'upload Photo
$upload = new Upload ();
$upload->MaxFilesize = MAX_FILE_SIZE;
$upload->InitForm ();
$upload->DirUpload = luxbum::getDirPath ($dir);
$upload->WriteMode = 2;
$upload->Required = true;
$upload->Extension = '.gif;.jpg;.jpeg;.png';
$upload->MimeType = 'image/gif;image/pjpeg;image/jpeg;image/x-png;image/png';
$page->MxAttribut ('max_file_size', $upload->MaxFilesize);

// switch rapide
$nuxIndex = new luxBumIndex ();
$nuxIndex->addAllGallery (0);
while (list (,$gallery) = each ($nuxIndex->galleryList)) {
   $tabSwitch[$gallery->getName ()] = cut_sentence ($gallery->getNiceName (), 20);
}
unset ($nuxIndex);
$page->MxAttribut ('action_rapid_switch', $str_critere.'&amp;');
$page->MxSelect ('rapid_switch', 'd', $dir, $tabSwitch);

// Valeur par défaut des select de même date
if ($f != 'meme_date') {
   $page->MxSelect ('jour', 'jour', date ('d'), jour_select ());
   $page->MxSelect ('mois', 'mois', date ('m'), mois_select ());
   $page->MxAttribut ('val_meme_date', date('Y'));
}

// Tri
if (isset ($_POST['sortby'])) {
   $selectedSortBy = $_POST['sortby'];
}
else {
   $selectedSortBy = 'type='.$nuxThumb->getSortType().'&order='.$nuxThumb->getSortOrder();
}
$page->MxSelect('tri', 'sortby', $selectedSortBy, tri_select());
$page->MxAttribut('action_tri', $str_critere.'&amp;f=tri');
$page->MxUrl ('triUrl', ADMIN_FILE.'?p=tri_galerie&amp;d='.$dir);


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
         $nuxThumb = new luxBumGallery ($dir);
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
         $nuxThumb = new luxBumGallery ($dir);
         $nuxThumb->addAllImages ();
      }
   }
}


// Même date 
else if ($f == 'meme_date') {
   $meme_date = '';
   if (isset ($_POST['meme_date'])) {
      $meme_date = protege_input ($_POST['meme_date']);
   }
   $jour = '';
   if (isset ($_POST['jour'])) {
      $jour = $_POST['jour'];
   }
   $mois = '';
   if (isset ($_POST['mois'])) {
      $mois = $_POST['mois'];
   }
   $theDate = $jour.'/'.$mois.'/'.$meme_date;

   $page->MxSelect ('jour', 'jour', $jour, jour_select ());
   $page->MxSelect ('mois', 'mois', $mois, mois_select ());
   $page->MxAttribut ('val_meme_date', $meme_date);

   if ($meme_date == '') {
      $page->MxText ('err_meme_date', 'Date vide !!!');
   }
   else if (verif_date ('meme_date', $theDate)) {
      for ($i = 0 ; $i < $nuxThumb->getCount () ; $i++) {
         $nuxThumb->list[$i]->setDate ($theDate);
      }
      $nuxThumb->updateDescriptionFile();
      $page->MxText ('message', 'La date '.$theDate.' a été attribuée à toutes les photos.');
   }
}

// Date et description
else if ($f == 'date_desc') {
   $err_date_desc = false;
   $err_date = '';
   $err_description = '';
   $date = '';
   $mois = '';
   $jour = '';
   $description = '';
   $img = '';


   if (isset ($_GET['img'])) {
      $img = $_GET['img'];

      // La date
      if (isset ($_POST['date'])) {
         $annee = protege_input ($_POST['date']);
         if (isset ($_POST['jour'])) {
            $jour = $_POST['jour'];echo "==$jour==";
         }
         if (isset ($_POST['mois'])) {
            $mois = $_POST['mois'];
         }
         $theDate = $jour.'/'.$mois.'/'.$annee;

         if ($annee == '') {
            $err_date = 'Année Vide !!';
         }
         else if (!verif_date ('date', $theDate)) {
            $err_date = 'Mauvais format de date !!';
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
            $nuxThumb->list[$where]->setDate (unprotege_input ($theDate));
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
      $mess = '';
      $errors = $upload->GetError ();
      while (list (,$errFile) = each ($errors)) {
         while (list (, $err) = each ($errFile)) {
            $mess .= $err.'<br />';
         }
      }
      $page->MxText ('message', $mess);
   }
   else {
      if (count($_FILES['userfile']['tmp_name']) == 1) {
         $page->MxText ('message', 'Le fichier a été correctement envoyé');
      }
      else {
         $page->MxText ('message', 'Les fichiers ont été correctement envoyés');
      }

      unset ($nuxThumb);
      $nuxThumb = new luxBumGallery ($dir);
      $nuxThumb->addAllImages ();
   }
}

// Choix du tri
else if ($f == 'tri') {
   if (isset($_POST['sortby'])) {
      parse_str($_POST['sortby'], $order);
      $nuxThumb->setSortType($order['type']);
      $nuxThumb->setSortOrder($order['order']);
      $nuxThumb->saveSort();

      unset ($nuxThumb);
      $nuxThumb = new luxBumGallery ($dir);
      $nuxThumb->addAllImages ();
   }
}
//print_r ($nuxThumb->list);

$nuxThumb->createOrMajDescriptionFile ();
$nuxThumb->getDescriptions ();


//------------------------------------------------------------------------------
// Affichage
//------------------------------------------------------------------------------
//----------------
// Affichage des vignettes
if (isset($_POST['limitPage']) && is_numeric($_POST['limitPage'])) {
   $_SESSION[PREFIX_SESSION.'limitThumbPage'] = $_POST['limitPage'];
   $limitThumbPage = $_POST['limitPage'];
}
else if (isset ($_SESSION[PREFIX_SESSION.'limitThumbPage']) 
         && is_numeric($_SESSION[PREFIX_SESSION.'limitThumbPage'])) {
   $limitThumbPage = $_SESSION[PREFIX_SESSION.'limitThumbPage'];
}
else {
   $limitThumbPage = 15;
}
$galleryCount = $nuxThumb->getCount ();

if (ceil ($galleryCount / $limitThumbPage) < $page_courante) {
   $page_courante = 0;
}

if ($galleryCount == 0) {
   $page->MxBloc ('liste_photos', 'modify', 'Aucune photo dans la galerie');
}
else {
   $page->WithMxPath ('liste_photos', 'relative');


   $page_walk = $page_courante+1;

   // Parcours des vignettes
   for ($i = ($page_walk-1)   * $limitThumbPage  ; 
        $i < ($page_walk) * $limitThumbPage && $i < $galleryCount ; 
        $i++) {
      $file = $nuxThumb->list[$i];
      $file->findDescription ();
      $name = $file->getImageName ();

      $page->MxImage ('liste.photo', $file->getThumbLink(), $name, 
                      'id="'.$name.'" onclick="toggleBigImage(\''.$name.'\', \''.$file->getPreviewLink().'\');" ');
      //$page->MxAttribut ('liste.id_photo', $name);
      $page->MxAttribut ('liste.action_date_desc', $str_critere.'&amp;img='.$name.'&amp;f=date_desc#'.$name);

      if ($f == 'date_desc' && $img == $name) {
         echo "if date_desc $jour/$mois/$annee";
         $page->MxAttribut ('liste.val_description', unprotege_input ($description));
         $page->MxSelect ('liste.jour', 'jour', $jour, jour_select ());
         $page->MxSelect ('liste.mois', 'mois', $mois, mois_select ());
         $page->MxAttribut ('liste.val_date', unprotege_input ($annee));
         $page->MxText ('liste.err_description', $err_description);
         $page->MxText ('liste.err_date', $err_date);
      }
      else {
         $page->MxAttribut ('liste.val_description', $file->getDescription());
         $date =  $file->getDate();
         if ($date != '') {
            list ($jour, $mois, $annee) = explode ('/', $file->getDate());
         }
         else {
            $jour = '';
            $mois = '';
            $annee = '';
         }
         $page->MxSelect ('liste.jour', 'jour', $jour, jour_select ());
         $page->MxSelect ('liste.mois', 'mois', $mois, mois_select ());
         $page->MxAttribut ('liste.val_date',  $annee);
      }

      $page->MxText ('liste.defaut_oui_non', ($name == $nuxThumb->getPreview ()) ? 'Oui': 'Non');

      $page->MxUrl ('liste.defaut', $str_critere.'&amp;f=defaut&amp;img='.$name.'#'.$name);
      $page->MxUrl ('liste.del', "javascript:if(window.confirm('La suppression de cette photo entrainera la suppression de tous les commentaires qui lui sont associés. Etes-vous sûr de vouloir supprimer cette photo ?')) window.location='".$str_critere.'&amp;f=del&amp;img='.$name."';");
      $page->MxBloc ('liste', 'loop');
   }


   // Affichage par page
   $link = $str_critere2;
   $start = $page_courante * $limitThumbPage;
   $AffPage = aff_page2 ($galleryCount, $page_courante, $limitThumbPage, $start, $link);
   $page->MxText ('aff_page', $AffPage);
   
   $page->MxAttribut('actionLimitPage', $str_critere);
   $page->MxAttribut('limitPage', $limitThumbPage);
}


?>