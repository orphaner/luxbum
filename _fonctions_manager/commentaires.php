<?php

include (FONCTIONS_DIR.'luxbum.class.php');
include (FONCTIONS_DIR.'class/commentaire.class.php');
$str_critere = ADMIN_FILE.'?p=parametres';

// Page modelixe
definir_titre ($page, 'Commentaires - LuxBum Manager');
$page->MxAttribut ('class_commentaires', 'actif');
$page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'commentaires.mxt');
$page->WithMxPath ('main', 'relative');

// Connection à la base de données
$mysql = new MysqlInc (DB_HOST, DB_LOGIN, DB_PASSWORD, DB_NAME);
$mysql->DbConnect();

// Sélection des galerie à afficher pour le filtre
$sql = "SELECT DISTINCT galerie_comment FROM commentaire ORDER BY galerie_comment ASC";
$res = $mysql->DbQuery ($sql);
while ($row = $mysql->DbNextRow ($res)) {
   $tab[$row['galerie_comment']] = $row['galerie_comment'];
}
$page->MxSelect ('galerie', 'galerie', '', $tab);


$sql = "SELECT * FROM commentaire ORDER by date_comment DESC";
$res = $mysql->DbQuery ($sql);
$page->WithMxPath ('comments', 'relative');
while ($row = $mysql->DbNextRow ($res)) {
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

$mysql->DbClose();

?>