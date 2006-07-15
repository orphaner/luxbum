<?php

include_once (FONCTIONS_DIR.'luxbum.class.php');
include_once (FONCTIONS_DIR.'class/commentaire.class.php');
$str_critere = ADMIN_FILE.'?p=commentaires';
$message = '';

// Page modelixe
definir_titre ($page, 'Commentaires - LuxBum Manager');
$page->MxAttribut ('class_commentaires', 'actif');

if ($mysql->db_link == null) {
   $page->MxBloc ('main', 'modify', 'Pas de connection � la base de donn�es. ' .
         'L\'administration des commentaires est donc impossible');
   $page->MxWrite ();
   exit();
}

$act = '';
if (isset($_GET['action'])) {
	$act = $_GET['action'];
}
// �dition d'un commentaire
if ($act == 'editer') {
   if (!isset($_GET['id']) || (isset($_GET['id']) && empty ($_GET['id']))) {
      $message = 'Il faut mettre un id de commentaire � supprimer.';
      $act = '';
   }
   else {
      $erreur = false;
      $com = new Commentaire ();
      
      // Validation du formulaire
      if (isset($_GET['valid']) && $_GET['valid']==1) {
         $com->fillFromPost ();
      
         if ($com->isValidForm ()) {
            $com->setId ($_GET['id']);
            $com->updateRow();
            $act = '';
         }
         else {
            $erreur = true;
         }
      }
      
      if ($erreur == true || $act != '') {
         $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'commentaires/editer.mxt');
         $page->WithMxPath ('main', 'relative');
         $page->MxAttribut ('action', $str_critere.'&amp;action=editer&amp;id='.$_GET['id'].'&amp;valid=1');
         if ($erreur == false) {
            $com->fillFromId($_GET['id']);
         }
         
         $page->MxAttribut ('val_auteur', unprotege_input(html_entity_decode($com->getAuteur())));
         $page->MxAttribut ('val_site', $com->getSite());
         $page->MxAttribut ('val_email', $com->getEmail());
         $page->MxText ('val_content', unprotege_input(html_entity_decode($com->getContent())));
   
         $page->MxText ('err_auteur', $com->getErreur ('auteur'));
         $page->MxText ('err_site', $com->getErreur ('site'));
         $page->MxText ('err_email', $com->getErreur ('email'));
         $page->MxText ('err_content', $com->getErreur ('content'));
      }
   }
}

// Suppression d'un commentaire
else if ($act == 'supprimer') {
   if (!isset($_GET['id']) || (isset($_GET['id']) && empty ($_GET['id']))) {
      $message = 'Il faut mettre un id de commentaire � supprimer.';
   }
   else {
      $message = 'Le commentaire n�'.$_GET['id'].' � �t� supprim�.';
      $com = new Commentaire ();
      $com ->setId($_GET['id']);
      $com->deleteRow();
   }
   $act = '';
}

// Mettre en ligne un commentaire
else if ($act == 'enligne') {
   if (!isset($_GET['id']) || (isset($_GET['id']) && empty ($_GET['id']))) {
      $message = 'Il faut mettre un id de commentaire � mettre en ligne.';
   }
   else {
      $message = 'Le commentaire n�'.$_GET['id'].' � �t� mis en ligne.';
      $com = new Commentaire ();
      $com ->setId($_GET['id']);
      $com->setPublic();
   }
   $act = '';
}

// Mettre hors ligne un commentaire
else if ($act == 'horsligne') {
   if (!isset($_GET['id']) || (isset($_GET['id']) && empty ($_GET['id']))) {
      $message = 'Il faut mettre un id de commentaire � mettre hors ligne.';
   }
   else {
      $message = 'Le commentaire n�'.$_GET['id'].' � �t� mis hors ligne.';
      $com = new Commentaire ();
      $com ->setId($_GET['id']);
      $com->setPrivate();
   }
   $act = '';
}

// Affichage des commentaires
if ($act == '') {
   $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'commentaires.mxt');
   $page->WithMxPath ('main', 'relative');
   $page->MxText('message', $message);
   
   // S�lection des galerie � afficher pour le filtre
   $sql = "SELECT DISTINCT galerie_comment FROM ".DBL_PREFIX."commentaire ORDER BY galerie_comment ASC";
   $res = $mysql->DbQuery ($sql);
   if ($mysql->DbNumRows($res) == 0) {
      $page->MxBloc('filtre', 'delete');
      $message = 'Il n\'y a aucun commentaire.';
      $page->MxText('message', $message);
   }
   else {
      // Filtre par galerie
      $tab = array ();
      while ($row = $mysql->DbNextRow ($res)) {
         $tab[$row['galerie_comment']] = $row['galerie_comment'];
      }
      $page->MxAttribut('filtre.action', $str_critere);
      $page->MxSelect ('filtre.galerie', 'galerie', '', $tab);
   }
   
   // Affichage des commentaires de la base
   $sql = "SELECT * FROM ".DBL_PREFIX."commentaire ";
   if (isset($_POST['filtre']) && $_POST['filtre'] == 1) {
      $sql .= " WHERE galerie_comment='".$_POST['galerie']."'";
   }
   $sql .= "ORDER by date_comment DESC";
   $res = $mysql->DbQuery ($sql);
   if ($mysql->DbNumRows($res) == 0) {
      $page->MxBloc ('comments', 'delete');
      $message = 'Il n\'y a aucun commentaire.';
      $page->MxText('message', $message);
   }
   else {
      $page->WithMxPath ('comments', 'relative');
      while ($row = $mysql->DbNextRow ($res)) {
         $page->MxUrl ('supprimer', $str_critere.
                        '&amp;action=supprimer&amp;id='.$row['id_comment']);
         $page->MxUrl ('editer', $str_critere.
                        '&amp;action=editer&amp;id='.$row['id_comment']);
         $page->MxText ('photo', $row['galerie_comment'].'/'.$row['photo_comment']);
         $page->MxText ('auteur', $row['auteur_comment']);
         $page->MxText ('ip', $row['ip_comment']);
         $page->MxText ('date', $row['date_comment']);
         $page->MxText ('contenu', nl2br ($row['content_comment']));
         if ($row['email_comment'] == '') {
            $page->MxBloc ('email', 'delete');
         }
         else {
            $page->MxText ('email.email', $row['email_comment']);
            $page->MxUrl ('email.email', 'mailto:'.$row['email_comment']);
         }
         if ($row['site_comment'] == '') {
            $page->MxBloc ('site', 'delete');
         }
         else {
            $page->MxText ('site.site', $row['site_comment']);
            $page->MxUrl ('site.site', $row['site_comment']);
         }
         if ($row['pub_comment'] == 0) {
            $page->MxImage ('status', '_images/manager/check_off.png');
            $page->MxText ('pub_text', 'Mettre en ligne');
            $page->MxUrl ('pub', $str_critere.
                           '&amp;action=enligne&amp;id='.$row['id_comment']);
         }
         else {
            $page->MxImage ('status', '_images/manager/check_on.png');
            $page->MxText ('pub_text', 'Mettre hors ligne');
            $page->MxUrl ('pub', $str_critere.
                           '&amp;action=horsligne&amp;id='.$row['id_comment']);
         }
         $page->MxBloc ('', 'loop');
      }
   }
}
$mysql->DbClose();

?>