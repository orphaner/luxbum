<?php

/**
 * @author Nicolas LASSALLE
 */
class mysqlInc {

   /**
    * Lien vers la base de donn�es
    * @access private
    */
   var $db_link;
   
   /**
    * Nombre de requ�tes effectu�es
    * @access private
    */
   var $nb_sql;
   
   /**
    * H�te du serveur Mysql
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
    * Nom de la base de donn�es
    * @access private
    */
   var $mysqlmaindb;

   /**
    * Constructeur obligatoire de la classe
    * @param string $mysqlserveur H�te du serveur Mysql
    * @param string $mysqlloggin Login de l'utilisateur mysql
    * @param string $mysqlpassword Mot de passe de l'utilisateur
    * @param string $mysqlmaindb Nom de la base de donn�es
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
    *  Connection au serveur et � la base de donn�e
    * @access public
    */
   function DbConnect () {
      $this->db_link = @mysql_connect ($this->mysqlserveur , $this->mysqlloggin , $this->mysqlpassword) 
         or die('Connexion � la base de donn�es impossible !! : '.$this->mysqlErr());
      @mysql_select_db ($this->mysqlmaindb) 
         or die('S�lection de la table impossible !!'.$this->mysqlErr());
   }
   
   /**
    * Teste une connection au serveur et � la base de donn�es.
    * Il est conseill� d'apeller la fonction mysqlErr () dans la foul�e pour
    * avoir un message d'erreur d�taill�.
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
    * Effectue une requ�te
    * @param string $squery Requete SQL � effectuer
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
    * Fonction racourci pour compter le nombre de r�sultat.
    * La requ�te doit �tre de type SELECT count (*) FROM ...
    * @param string $squery Requete SQL count(*) � effectuer
    * @access public
    * @return Le nombre d'enregistrements compt�s
    */
   function DbCount ($query) {
      $result = $this->DbQuery ($query);
      return mysql_result ($result, 0, "COUNT(*)");
   }

   /**
    * Parcours le r�sultat d'une requ�te et retourne un tableau de la ligne
    * courante. Retourne false si il n'y a plus de r�sultats.
    * @access public
    * @param resource $result R�sultat d'un DbQuery
    * @return array Returns an array that corresponds to the fetched row, or
    * FALSE  if there are no more rows.
    */
   function DbNextRow ($result) {
      return mysql_fetch_array ($result);
   }

   /**
    * Compte le nombre de r�sultats d'une requ�te
    * @access public
    * @return integer Nombre de r�sultats d'une requ�te
    */
   function DbNumRows ($result) {
      return mysql_num_rows ($result);
   }

   /**
    * Ferme la connection � la base de donn�es
    * @access public
    */
   function DbClose () {
      @mysql_close();
   }
   
   /**
    * Retourne l'id auto g�n�r� lors d'une requ�te INSERT
    * @access public
    * @return integer id auto g�n�r�
    */
   function DbGetInsertId () {
      return mysql_insert_id();
   }
   
   /**
    * Retourne un message d'erreur plus parlant que celui par d�faut de la
    * fonction mysql_error()
    * @access public
    * @return string message d'erreur
    */
   function mysqlErr() {
      $partie = explode('\'', mysql_error() ); // On d�coupe le message d'erreur retourn� par mysql_error()   
      print_r ($partie);
   
      switch   (mysql_errno() ) { // On cherche quel N� d'erreur SQL � �t� retoun�
         case 1040 : // Too many connections
            return 'Trop de connections simultan�es. Merci de revenir dans quelques minutes ou de recharger la page';
         case 1044 : // Access denied for user: 'login' to database 'nombase'
            return 'La base de donn�es "'.$this->mysqlmaindb.'" n\'a pas �t� trouv�e.';
         case 1045 : // Access denied for user: 'login' (Using password: YES)
            return 'L\'utilisateur d�sign� "'.$this->mysqlloggin.'" n\'a pas �t� trouv�. ' .
                  'Le mot de passe est peut �tre incorrect.';
         case 1046 : // No Database Selected
            return 'Aucune base de donn�es n\'� �t� s�lectionn�e.</p>';
         case 1052 : // Column: 'champ' in where clause is ambiguous
            return 'La clause WHERE est ambigu� pour la colonne '.$partie[1].'.';
         case 1053 : // Server shutdown in progress
            return 'Le serveur SQL � �t� arr�t�. Essayez de recharger la page ou revenez dans quelques minutes.';
         case 1054 :
            switch   ($partie[3]) { // On cherche quelle chaine est retourn�e par $morceau[3]
               case 'field list' : // Unknown column 'nomChamp' in 'field list'
                  return 'Le champ "'.$partie[1].'" pr�cis� dans la liste des champs n\'a pas �t� trouv�.';
               case 'where clause' : // Unknown column 'nomChamp' in 'where clause'
                  return 'Le champ "'.$partie[1].'" pr�cis� dans la clause WHERE n\'a pas �t� trouv�.';
               default :
                  return mysql_error();
            }
            break;
         case 1064 : // You have an error in your SQL syntax. Check the manual that corresponds to your MySQL server version for the right syntax to use near 'requete'
            return 'Une erreur de syntaxe SQL se trouve dans la requ�te "'.$partie[1].'".';         
         case 1065 : // Query was empty
            return 'Aucune requ�te SQL n\'� �t� trouv�e.';         
         case 1109 : // Unknown table 'nom_table' in where clause
         case 1146 :
            return 'La table "'.$partie[1].'" n\'a pas �t� trouv�e dans la clause WHERE.';         
         case 2002 : // Can't connect to local MySQL server through socket 'chemnin d'acc�s' (2)
            return '�chec lors de la connection au serveur SQL.';
         case 2005 : // Unknown MySQL Server Host 'serveur' (2)
            return 'Le serveur SQL "'.$partie[1].'" n\'a pas �t� trouv�.';
         case 2013 : // Lost connection to MySQL server during query
            return 'La connection au serveur SQL � �t� perdue lors de la requ�te. Essayez de recharger la page pour r�soudre le probl�me.';
         default :
            return mysql_error();
      }
   }
   
   /**
    * Getter du nombre de requ�tes effectu�es
    * @return integer le nombre de requ�tes SQL effectu�es
    */
   function getNbSql () {
      return $this->nb_sql;
   }
}

?>
