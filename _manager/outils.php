<?php


if (isset ($_GET['o']) && $_GET['o'] == 'diagnostic') {
   include ADMIN_DIR.'/outils/diagnostic.php';
}


else if (isset ($_GET['o']) && $_GET['o'] == 'renomage') {
   include ADMIN_DIR.'/outils/renomage.php';
}



else {
   // Page modelixe
   definir_titre ($page, 'Outils - LuxBum Manager');
   $page->MxAttribut ('class_outils', 'actif');
   $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'outils.mxt');
   $page->WithMxPath ('main', 'relative');

}


?>