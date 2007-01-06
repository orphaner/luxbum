<?php

  /**
   * @package process
   */
class LbPaginator extends Recordset2 {
   var $currentPage;
   var $countPages;
   var $elementsByPage;
   var $totalPages;

   function LbPaginator($currentPage, $countPages, $elementsByPage = 7, $nb_bouton_page = 3) {
      parent::Recordset2();
      $this->currentPage = $currentPage;
      $this->countPages = $countPages;
      $this->elementsByPage = $elementsByPage;

      //----------------------------------------------
      // Affichage de la navigation par pages
      $nb_ligne_page  = $this->elementsByPage;
      $limit_start = $this->currentPage * $nb_ligne_page;


      $nb_sql = ceil( $this->countPages / $nb_ligne_page );
      $nb_sql_div = floor( $nb_sql / 2 );
      $nb_button_div = floor( $nb_bouton_page / 2);

      $this->totalPages = $nb_sql;

      //--- On calcule l'interval maximal dans les bornes entre  $first_button et $last_button
      $first_button = $this->currentPage - $nb_button_div;
      $last_button  = $this->currentPage + $nb_button_div;
      $sens = 0;
      if ($first_button < 0) {
         $first_button = 0;
      }
      if ($last_button > $nb_sql) {
         $last_button = $nb_sql;
         $sens = 1;
      }
      if ( ( $last_button - $first_button < $nb_bouton_page ) && $last_button != $first_button ) {
         if ($sens) {
            while ( ( $last_button - $first_button < $nb_bouton_page ) && $first_button > 0 )
               $first_button --;
         }
         else {
            while ( ( $last_button - $first_button < $nb_bouton_page ) && $last_button < $nb_sql )
               $last_button ++;
         }
      }

      //--- On affiche la fleche de premier et de precedant, si on peut revenir en arriere
      if ($first_button > 0) {
         $mini = true;
         $this->addToList(array('&lt;&lt;', $this->getPictureNumber(0), 0));
         $this->addToList(array('&lt;', $this->getPictureNumber($this->currentPage-2), 0));
      }

      //--- On parcours la liste des bouttons
      for ($j=$first_button; $j < $last_button; $j++) {
         $mini = true;
         $this->addToList(array($j+1, $this->getPictureNumber($j), $j+1));
      }

      //--- On affiche la fleche de dernier et de suivant, si $last_button n'est pas la derniere page
      if ( $last_button < $nb_sql ) {
         $mini = true;
         $this->addToList(array('&gt;', $this->getPictureNumber($j), 0));
         $this->addToList(array('&gt;&gt;', $this->getPictureNumber($nb_sql-1), 0));
      }
   }

   function getPictureNumber($page) {
      return ($page *  $this->elementsByPage) ;
   }

   function getCurrentPage () {
      return $this->currentPage;
   }
   function getTotalPages () {
      return $this->totalPages;
   }
   function nextPage () {
      return  ($this->currentPage * $this->elementsByPage);
   }
   function prevPage () {
      return  ($this->currentPage * $this->elementsByPage) - (2 * $this->elementsByPage);
   }

}
?>