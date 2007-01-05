<?php


/*===========================================
 FONCTION POUR FAIRE UN AFFICHAGE PAR PAGE
 =============================================*/
function boutton_navig_page (&$page, $texte, $lien, $classe)
{
   $page->MxUrl('aff_page.ligne.lien',$lien);
   $page->MxText('aff_page.ligne.valeur',$texte);
   $page->MxAttribut('aff_page.ligne.classe',$classe);
   $page->MxBloc('aff_page.ligne','loop');
}


function aff_page2 ($nb_total, $courant, $limit_nb,$dede, $action)
{
   //----------------------------------------------
   // Affichage de la navigation par pages
   $nb_bouton_page = 7;
   $nb_ligne_page  = $limit_nb;//MAX_AFF_PRODUIT_PAR_GROUPE;
   $limit_start = $courant * $nb_ligne_page;
   $limit_nb    = $nb_ligne_page;


   $nb_sql = ceil( $nb_total / $nb_ligne_page );
   $nb_sql_div = floor( $nb_sql / 2 );
   $nb_button_div = floor( $nb_bouton_page / 2);

   //--- On calcule l'interval maximal dans les bornes entre  $first_button et $last_button
   $first_button = $courant - $nb_button_div;
   $last_button  = $courant + $nb_button_div;
   $sens = 0;
   if ( $first_button < 0 )
      $first_button = 0;
   if ( $last_button > $nb_sql )
   {
      $last_button = $nb_sql;
      $sens = 1;
   }
   if ( ( $last_button - $first_button < $nb_bouton_page ) && $last_button != $first_button )
   {
      if( $sens )
      {
         while ( ( $last_button - $first_button < $nb_bouton_page ) && $first_button > 0 )
            $first_button --;
      }
      else
      {
         while ( ( $last_button - $first_button < $nb_bouton_page ) && $last_button < $nb_sql )
            $last_button ++;
      }
   }

   $pageMxt = new ModeliXe ('aff_page.mxt');
   $pageMxt->SetModeliXe ();
   $pageMxt->MxText('pagex', $courant+1);
   $pageMxt->MxText('surx', $nb_sql);

   //--- On affiche la fleche de premier et de precedant, si on peut revenir en arriere
   if ($first_button > 0) 
   {
      $mini = true;
      boutton_navig_page ($pageMxt, '&lt;&lt;&nbsp;', sprintf ($action, '0'),'alt1');
      boutton_navig_page ($pageMxt, '&lt;', sprintf ($action,($courant-1)),'alt1');
   }

   //--- On parcours la liste des bouttons
   for ($j=$first_button; $j < $last_button; $j++) 
   {
      $mini = true;
      boutton_navig_page ($pageMxt, $j+1, sprintf ($action ,$j), ($j==$courant) ? 'alt2' :'alt1' );
   }

   //--- On affiche la fleche de dernier et de suivant, si $last_button n'est pas la derniere page
   if ( $last_button < $nb_sql ) 
   {
      $mini = true;
      boutton_navig_page ($pageMxt, '&gt;', sprintf ($action, $j), 'alt1');
      boutton_navig_page ($pageMxt, '&nbsp;&gt;&gt;', sprintf ($action,$nb_sql-1), 'alt1');
   }

   //--- On supprime le bloc si celui-ci n'est pas utilise
   if (!$mini)
   {
      $pageMxt->MxBloc('aff_page','delete');
   }

   return $pageMxt->MxWrite (true);
}

?>