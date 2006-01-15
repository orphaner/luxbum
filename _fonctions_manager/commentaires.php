<?php

include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'class/commentaire.class.php');
$str_critere = ADMIN_FILE.'?p=commentaires';
$message = '';

// Page modelixe
definir_titre ($page, 'Commentaires - LuxBum Manager');
$page->MxAttribut ('class_commentaires', 'actif');

// Connection � la base de donn�es
$mysql = new MysqlInc (DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
$mysql->DbConnect();

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
      $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'commentaires/editer.mxt');
      $page->WithMxPath ('main', 'relative');
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

// Affichage des commentaires
if ($act == '') {
   $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'commentaires.mxt');
   $page->WithMxPath ('main', 'relative');
   $page->MxText('message', $message);
   
   // S�lection des galerie � afficher pour le filtre
   $sql = "SELECT DISTINCT galerie_comment FROM commentaire ORDER BY galerie_comment ASC";
   $res = $mysql->DbQuery ($sql);
   if ($mysql->DbNumRows($res) == 0) {
      $page->MxBloc('filtre', 'delete');
      $page->MxBloc('comments', 'delete');
      $message = 'Il n\'y a aucun commentaire.';
   }
   else {
      // Filtre par galerie
      $tab = array ();
      while ($row = $mysql->DbNextRow ($res)) {
         $tab[$row['galerie_comment']] = $row['galerie_comment'];
      }
      $page->MxAttribut('filtre.action', $str_critere);
      $page->MxSelect ('filtregalerie', 'galerie', '', $tab);
   }
   
   // Affichage des commentaires de la base
   $sql = "SELECT * FROM commentaire ";
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
         }
         else {
            $page->MxImage ('status', '_images/manager/check_on.png');
         }
         $page->MxBloc ('', 'loop');
      }
   }
}
$mysql->DbClose();

?>