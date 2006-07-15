<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include_once (FONCTIONS_DIR.'utils/formulaires.php');


class Commentaire {
   var $id;
   var $galerie;
   var $photo;
   var $date;
   var $auteur;
   var $email;
   var $site;
   var $content;
   var $ip;
   var $pub;

   var $erreurs = array ();

   function Commentaire () {
   }
   
   /**
    * 
    */
   function fillFromPost () {
      // Auteur, obligatoire
      if (isset ($_POST['auteur']) && $_POST['auteur'] != '') {
         $this->setAuteur (protege_input ($_POST['auteur']));
      }
      else {
         $this->erreurs['auteur'] = 'Champ vide !!!';
      }

      // Contenu, obligatoire
      if (isset ($_POST['content']) && trim ($_POST['content']) != '') {
         $this->setContent (protege_input ($_POST['content']));
      }
      else {
         $this->erreurs['content'] = 'Champ vide !!!';
      }

      // Site
      if (isset ($_POST['site']) && $_POST['site'] != '') {
         $this->setSite (protege_input ($_POST['site']));
         if (!verifsite ($this->site)) {
            $this->erreurs['site'] = 'Format de site incorrect';
         }
      }

      // Email
      if (isset ($_POST['email']) && $_POST['email'] != '') {
         $this->setEmail (protege_input ($_POST['email']));
         if (!verifEmail ($this->email)) {
            $this->erreurs['email'] = 'Format d\'email incorrect';
         }
      }
      return $this->isValidForm ();
   }

   /**
    * 
    */
   function getErreur ($champ) {
      if (array_key_exists ($champ, $this->erreurs)) {
         return $this->erreurs[$champ];
      }
      return '';
   }

   /**
    * 
    */
   function isValidForm () {
      return (count ($this->erreurs) == 0);
   }
   
   /**
    * 
    */
   function fillFromId ($id) {
      global $mysql;
      $sql = 'SELECT * FROM '.DBL_PREFIX.'commentaire WHERE id_comment='.$id;
      $res = $mysql->DbQuery($sql);
      $row = $mysql->DbNextRow ($res);
      $this->setId($id);
      $this->setGalerie($row['galerie_comment']);
      $this->setPhoto($row['photo_comment']);
      $this->setDate($row['date_comment']);
      $this->setAuteur($row['auteur_comment']);
      $this->setEmail($row['email_comment']);
      $this->setSite($row['site_comment']);
      $this->setContent($row['content_comment']);
      $this->setIp($row['ip_comment']);
      $this->setPub($row['pub_comment']);
   }

   /**
    * 
    */
   function insertRow () {
      global $mysql;
      $sql = sprintf ("INSERT INTO ".DBL_PREFIX."commentaire (galerie_comment, photo_comment, date_comment, "
                      ."auteur_comment, email_comment, site_comment, content_comment, ip_comment, pub_comment) "
                      ."VALUES (%s, %s, SYSDATE(), %s,%s, %s, %s, %s, %s)",
                      $mysql->escapeString($this->galerie),
                      $mysql->escapeString($this->photo),
                      $mysql->escapeString($this->auteur),
                      $mysql->escapeString($this->email),
                      $mysql->escapeString($this->site),
                      $mysql->escapeString($this->content),
                      $mysql->escapeString($_SERVER['REMOTE_ADDR']),
                      $mysql->escapeSet (1));
      $mysql->DbQuery ($sql);
      $this->id = $mysql->DbGetInsertId();
   }

   /**
    * 
    */
   function updateRow () {
      global $mysql;
      $sql = sprintf ("UPDATE ".DBL_PREFIX."commentaire SET "
                      ."auteur_comment=%s,"
                      ."email_comment=%s,"
                      ."site_comment=%s,"
                      ."content_comment=%s"
                      ." WHERE id_comment=%d",
                      $mysql->escapeString($this->auteur),
                      $mysql->escapeString($this->email),
                      $mysql->escapeString($this->site),
                      $mysql->escapeString($this->content),
                      $this->id);
      $mysql->DbQuery ($sql);
   }
   
   /**
    * 
    */
   function setPublic () {
      global $mysql;
      if (!empty($this->id)) {
         $sql = "UPDATE ".DBL_PREFIX."commentaire SET pub_comment='1' WHERE id_comment=".$this->id;
         $mysql->DbQuery ($sql);
         return true;
      }
      return false;
   }
   
   /**
    * 
    */
   function setPrivate () {
      global $mysql;
      if (!empty($this->id)) {
         $sql = "UPDATE ".DBL_PREFIX."commentaire SET pub_comment='0' WHERE id_comment=".$this->id;
         $mysql->DbQuery ($sql);
         return true;
      }
      return false;
   }
   
   /**
    * 
    */
   function deleteRow () {
      global $mysql;
      if (!empty($this->id)) {
         $sql = "DELETE FROM ".DBL_PREFIX."commentaire WHERE id_comment=".$this->id;
         $mysql->DbQuery ($sql);
         return true;
      }
      return false;
   }
   
   /**
    * 
    */
   function renameGalerie ($old, $new) {
      global $mysql;
      if ($mysql->db_link != null) {
         $query = sprintf ("UPDATE ".DBL_PREFIX."commentaire " .
                           "SET galerie_comment=%s " .
                           "WHERE  galerie_comment=%s",
                           $mysql->escapeString($new),
                           $mysql->escapeString($old)
            );
         $mysql->DbQuery ($query);
      }
   }
   
   /**
    * 
    */
   function deleteGalerie ($galerie) {
      global $mysql;
      if ($mysql->db_link != null) {
         $query = sprintf ("DELETE FROM ".DBL_PREFIX."commentaire " .
                           "WHERE  galerie_comment=%s",
                           $mysql->escapeString($galerie));
         $mysql->DbQuery ($query);
      }
   }
   
   /**
    * 
    */
   function deletePhoto ($galerie, $photo) {
      global $mysql;
      if ($mysql->db_link != null) {
         $query = sprintf ("DELETE FROM ".DBL_PREFIX."commentaire " .
                           "WHERE galerie_comment=%s AND photo_comment=%s",
                           $mysql->escapeString($galerie),
                           $mysql->escapeString($photo));
         $mysql->DbQuery ($query);
      }
   }
   
   /*function renamePhoto ($galerie, $old, $new) {
    global $mysql;
    $query = "UPDATE ".DBL_PREFIX."commentaire " .
    "SET photo_comment='$new' " .
    "WHERE  photo_comment='$old' AND galerie_comment='$galerie'";
    $mysql->DbQuery ($query);
    }*/
   
   /**
    * 
    */
   function selectQuery ($galerie, $photo) {
      global $mysql;
      $query = sprintf ("SELECT id_comment, date_comment, auteur_comment, email_comment, site_comment, content_comment "
                        ."FROM ".DBL_PREFIX."commentaire "
                        ."WHERE galerie_comment=%s AND photo_comment=%s AND pub_comment='1'",
                        $mysql->escapeString($galerie),
                        $mysql->escapeString($photo));
      return $query;
   }
   
   /**
    * 
    */
   function tableExists ($prefix) {
      global $mysqlParam;
      $tableName = $prefix.'commentaire';
      if ($mysqlParam->db_link != null) {
         $res = $mysqlParam->getTableList();
         while ($row = $mysqlParam->DbNextRow($res)) {
            if (in_array ($tableName, $row)) {//print_r($row);echo "$tableName: true";
               return true;
            }
         }
      }
      return false;
   }
   
   /**
    * 
    */
   function createTable ($prefix) {
      global $mysqlParam;
      $tableName = $prefix.'commentaire';
      if ($mysqlParam->db_link != null) {
         $mysqlParam->DbQuery (
            "CREATE TABLE `$tableName` (" .
            "  `id_comment` int(11) NOT NULL auto_increment," .
            "  `galerie_comment` varchar(240) NOT NULL default ''," .
            "  `photo_comment` varchar(240) NOT NULL default ''," .
            "  `date_comment` datetime NOT NULL default '0000-00-00 00:00:00'," .
            "  `auteur_comment` varchar(255) NOT NULL default ''," .
            "  `email_comment` varchar(255) default NULL," .
            "  `site_comment` varchar(255) default NULL," .
            "  `content_comment` longtext NOT NULL," .
            "  `ip_comment` varchar(15) default NULL," .
            "  `pub_comment` set('0','1') NOT NULL default ''," .
            "  PRIMARY KEY  (`id_comment`)," .
            "  KEY `galerie_comment` (`galerie_comment`,`photo_comment`)" .
            ")");
         return true;
      }
      return false;
   }

   function getId () {
      return $this->id;
   }
   function setId ($id) {
      $this->id = $id;
   }
   function getGalerie () {
      return $this->galerie;
   }
   function setGalerie ($galerie) {
      $this->galerie = $galerie;
   }
   function getPhoto () {
      return $this->photo;
   }
   function setPhoto ($photo) {
      $this->photo = $photo;
   }
   function getDate () {
      return $this->date;
   }
   function setDate ($date) {
      $this->date = $date;
   }
   function getAuteur () {
      return $this->auteur;
   }
   function setAuteur ($auteur) {
      $this->auteur = $auteur;
   }
   function getEmail () {
      return $this->email;
   }
   function setEmail ($email) {
      $this->email = $email;
   }
   function getSite () {
      return $this->site;
   }
   function setSite ($site) {
      $this->site = $site;
   }
   function getContent () {
      return $this->content;
   }
   function setContent ($content) {
      $this->content = trim ($content);
   }
   function getIp () {
      return $this->ip;
   }
   function setIp ($ip) {
      $this->ip = $ip;
   }
   function getPub () {
      return $this->pub;
   }
   function setPub ($pub) {
      $this->pub = $pub;
   }
}
?>