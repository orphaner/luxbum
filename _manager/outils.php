<?php


if (isset ($_GET['o']) && $_GET['o'] == 'diagnostic') {
   include '_manager/outils/diagnostic.php';
}



else {
   // Page modelixe
   definir_titre ($page, 'Outils - LuxBum Manager');
   $page->MxAttribut ('class_outils', 'actif');
   $page->MxBloc ('main', 'modify', ADMIN_STRUCTURE_DIR.'outils.mxt');
   $page->WithMxPath ('main', 'relative');

}


?>