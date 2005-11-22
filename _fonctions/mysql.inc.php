<?php

class mysqlInc {

   var $db_link;
   var $nb_sql;
   var $mysqlserveur, $mysqlloggin, $mysqlpassword, $mysqlmaindb;
   var $result;

   function mysqlInc ($mysqlserveur, $mysqlloggin, $mysqlpassword, $mysqlmaindb) {
      $this->nb_sql=0;
      $this->db_link = null;
      $this->result = null;
      $this->mysqlserveur = $mysqlserveur;
      $this->mysqlloggin = $mysqlloggin;
      $this->mysqlpassword = $mysqlpassword;
      $this->mysqlmaindb = $mysqlmaindb;
   }
   

   // CONNECTION AU SERVEUR
   function DbConnect () {
      $this->db_link = @mysql_connect ($this->mysqlserveur , $this->mysqlloggin , $this->mysqlpassword) 
         or die('Connexion à la base de données impossible !! : '.mysql_error());
      @mysql_select_db ($this->mysqlmaindb) 
         or die('Sélection de la table impossible !!');
   }

   // FONCTION POUR FAIRE UNE REQUETE
   function DbQuery ($query) {
      $this->nb_sql++;
      $this->result = mysql_query ($query, $this->db_link) 
         or die ('<br /><strong>ERREUR</strong> '.(mysql_error()).'<br /><strong>Requete</strong>: '.$query); 
      return $this->result;
   }

   // FONCTION POUR COMPTER LE NOMBRE D'ENREGISTREMENTS
   function DbCount ($query) {
      $this->result = $this->DbQuery ($query);
      return mysql_result ($this->result,0,"COUNT(*)");
   }

   // FONCTION POUR AFFICHER LE RESULTAT SUIVANT
   function DbNextRow () {
      return mysql_fetch_array ($this->result);
   }

   // FONCTION POUR COMPTER LE NOMBRE DE RESULTATS D'UNE REQUETE
   function DbNumRows () {
      return mysql_num_rows ($this->result);
   }

   // FONCTION POUR FERMER LA CONNEXION AU SERVEUR
   function DbClose () {
      @mysql_close();
   }
   // FONCTION POUR RECUPERER INSERT ID D'UNE REQUETE
   function DbGetInsertId () {
      return mysql_insert_id();
   }
}

?>
