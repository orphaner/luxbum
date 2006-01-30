<?php

/**
 * @author Nicolas LASSALLE
 */
class mysqlInc {

   /**
    * Lien vers la base de données
    * @access private
    */
   var $db_link;
   
   /**
    * Nombre de requêtes effectuées
    * @access private
    */
   var $nb_sql;
   
   /**
    * Hôte du serveur Mysql
    * @access private
    */
   var $mysqlserveur;
   
   /**
    * Login de l'utilisateur mysql
    * @access private
    */
   var $mysqlloggin;
   
   /**
    * Mot de passe de l'utilisateur
    * @access private
    */
   var $mysqlpassword;
   
   /**
    * Nom de la base de données
    * @access private
    */
   var $mysqlmaindb;

   /**
    * Constructeur obligatoire de la classe
    * @param string $mysqlserveur Hôte du serveur Mysql
    * @param string $mysqlloggin Login de l'utilisateur mysql
    * @param string $mysqlpassword Mot de passe de l'utilisateur
    * @param string $mysqlmaindb Nom de la base de données
    */
   function mysqlInc ($mysqlserveur, $mysqlloggin, $mysqlpassword, $mysqlmaindb) {
      $this->nb_sql = 0;
      $this->db_link = null;
      $this->result = null;
      $this->mysqlserveur = $mysqlserveur;
      $this->mysqlloggin = $mysqlloggin;
      $this->mysqlpassword = $mysqlpassword;
      $this->mysqlmaindb = $mysqlmaindb;
   }
   

   /**
    *  Connection au serveur et à la base de donnée
    * @access public
    */
   function DbConnect () {
      $this->db_link = @mysql_connect ($this->mysqlserveur , $this->mysqlloggin , $this->mysqlpassword) 
         or die('Connexion à la base de données impossible !! : '.$this->mysqlErr());
      @mysql_select_db ($this->mysqlmaindb) 
         or die('Sélection de la table impossible !!'.$this->mysqlErr());
   }
   
   /**
    * Teste une connection au serveur et à la base de données.
    * Il est conseillé d'apeller la fonction mysqlErr () dans la foulée pour
    * avoir un message d'erreur détaillé.
    * @access public
    * @return boolean true / false 
    */
   function testDbConnect () {
      if (!$this->db_link = @mysql_connect ($this->mysqlserveur , $this->mysqlloggin , $this->mysqlpassword)) {
         return false;
      }
      if (!@mysql_select_db ($this->mysqlmaindb)) {
         return false;
      }
      return true;
   }

   /**
    * Effectue une requête
    * @param string $squery Requete SQL à effectuer
    * @access public
    * @return resource
    */
   function DbQuery ($query) {
      $this->nb_sql++;
      $result = mysql_query ($query, $this->db_link) 
         or die ('<br /><strong>ERREUR</strong> '.($this->mysqlErr()).'<br /><strong>Requete</strong>: '.$query); 
      return $result;
   }

   /**
    * Fonction racourci pour compter le nombre de résultat.
    * La requête doit être de type SELECT count (*) FROM ...
    * @param string $squery Requete SQL count(*) à effectuer
    * @access public
    * @return Le nombre d'enregistrements comptés
    */
   function DbCount ($query) {
      $result = $this->DbQuery ($query);
      return mysql_result ($result, 0, "COUNT(*)");
   }

   /**
    * Parcours le résultat d'une requête et retourne un tableau de la ligne
    * courante. Retourne false si il n'y a plus de résultats.
    * @access public
    * @param resource $result Résultat d'un DbQuery
    * @return array Returns an array that corresponds to the fetched row, or
    * FALSE  if there are no more rows.
    */
   function DbNextRow ($result) {
      return mysql_fetch_array ($result);
   }

   /**
    * Compte le nombre de résultats d'une requête
    * @access public
    * @return integer Nombre de résultats d'une requête
    */
   function DbNumRows ($result) {
      return mysql_num_rows ($result);
   }

   /**
    * Ferme la connection à la base de données
    * @access public
    */
   function DbClose () {
      @mysql_close();
   }
   
   /**
    * Retourne l'id auto généré lors d'une requête INSERT
    * @access public
    * @return integer id auto généré
    */
   function DbGetInsertId () {
      return mysql_insert_id();
   }
   
   /**
    * Retourne un message d'erreur plus parlant que celui par défaut de la
    * fonction mysql_error()
    * @access public
    * @return string message d'erreur
    */
   function mysqlErr() {
      $partie = explode('\'', mysql_error() ); // On découpe le message d'erreur retourné par mysql_error()   
      print_r ($partie);
   
      switch   (mysql_errno() ) { // On cherche quel N° d'erreur SQL à été retouné
         case 1040 : // Too many connections
            return 'Trop de connections simultanées. Merci de revenir dans quelques minutes ou de recharger la page';
         case 1044 : // Access denied for user: 'login' to database 'nombase'
            return 'La base de données "'.$this->mysqlmaindb.'" n\'a pas été trouvée.';
         case 1045 : // Access denied for user: 'login' (Using password: YES)
            return 'L\'utilisateur désigné "'.$this->mysqlloggin.'" n\'a pas été trouvé. ' .
                  'Le mot de passe est peut être incorrect.';
         case 1046 : // No Database Selected
            return 'Aucune base de données n\'à été sélectionnée.</p>';
         case 1052 : // Column: 'champ' in where clause is ambiguous
            return 'La clause WHERE est ambiguë pour la colonne '.$partie[1].'.';
         case 1053 : // Server shutdown in progress
            return 'Le serveur SQL à été arrêté. Essayez de recharger la page ou revenez dans quelques minutes.';
         case 1054 :
            switch   ($partie[3]) { // On cherche quelle chaine est retournée par $morceau[3]
               case 'field list' : // Unknown column 'nomChamp' in 'field list'
                  return 'Le champ "'.$partie[1].'" précisé dans la liste des champs n\'a pas été trouvé.';
               case 'where clause' : // Unknown column 'nomChamp' in 'where clause'
                  return 'Le champ "'.$partie[1].'" précisé dans la clause WHERE n\'a pas été trouvé.';
               default :
                  return mysql_error();
            }
            break;
         case 1064 : // You have an error in your SQL syntax. Check the manual that corresponds to your MySQL server version for the right syntax to use near 'requete'
            return 'Une erreur de syntaxe SQL se trouve dans la requête "'.$partie[1].'".';         
         case 1065 : // Query was empty
            return 'Aucune requête SQL n\'à été trouvée.';         
         case 1109 : // Unknown table 'nom_table' in where clause
         case 1146 :
            return 'La table "'.$partie[1].'" n\'a pas été trouvée dans la clause WHERE.';         
         case 2002 : // Can't connect to local MySQL server through socket 'chemnin d'accès' (2)
            return 'Échec lors de la connection au serveur SQL.';
         case 2005 : // Unknown MySQL Server Host 'serveur' (2)
            return 'Le serveur SQL "'.$partie[1].'" n\'a pas été trouvé.';
         case 2013 : // Lost connection to MySQL server during query
            return 'La connection au serveur SQL à été perdue lors de la requête. Essayez de recharger la page pour résoudre le problème.';
         default :
            return mysql_error();
      }
   }
   
   /**
    * Getter du nombre de requêtes effectuées
    * @return integer le nombre de requêtes SQL effectuées
    */
   function getNbSql () {
      return $this->nb_sql;
   }
}

?>
