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

   function getErreur ($champ) {
      if (array_key_exists ($champ, $this->erreurs)) {
         return $this->erreurs[$champ];
      }
      return '';
   }

   function isValidForm () {
      return (count ($this->erreurs) == 0);
   }
   
   function fillFromId ($id) {
      global $mysql;
      $sql = 'SELECT * FROM '.DB_PREFIX.'commentaire WHERE id_comment='.$id;
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

   function insertRow () {
      global $mysql;
      $sql = "INSERT INTO ".DB_PREFIX."commentaire (galerie_comment, photo_comment, date_comment, "
         ."auteur_comment, email_comment, site_comment, content_comment, ip_comment, pub_comment) "
         ."VALUES ("
         ."'".$this->galerie."', "
         ."'".$this->photo."', "
         ."SYSDATE(), "
         ."'".$this->auteur."',"
         ."'".$this->email."',"
         ."'".$this->site."',"
         ."'".$this->content."',"
         ."'".$_SERVER['REMOTE_ADDR']."', "
         ."'1')";
      $mysql->DbQuery ($sql);
      $this->id = $mysql->DbGetInsertId();
   }

   function updateRow () {
      global $mysql;
      $sql = "UPDATE ".DB_PREFIX."commentaire SET "
         ."auteur_comment='".$this->auteur."',"
         ."email_comment='".$this->email."',"
         ."site_comment='".$this->site."',"
         ."content_comment='".$this->content."'"
         ." WHERE id_comment=".$this->id;
      $mysql->DbQuery ($sql);
   }
   
   function setPublic () {
      global $mysql;
      if (!is_empty($this->id)) {
         $sql = "UPDATE ".DB_PREFIX."commentaire SET pub_comment='1' WHERE id_comment=".$this->id;
         $mysql->DbQuery ($sql);
         return true;
      }
      return false;
   }
   
   function setPrivate () {
      global $mysql;
      if (!is_empty($this->id)) {
          $sql = "UPDATE ".DB_PREFIX."commentaire SET pub_comment='0' WHERE id_comment=".$this->id;
          $mysql->DbQuery ($sql);
          return true;
      }
      return false;
   }
   
   function deleteRow () {
      global $mysql;
      if (!empty($this->id)) {
          $sql = "DELETE FROM ".DB_PREFIX."commentaire WHERE id_comment=".$this->id;
          $mysql->DbQuery ($sql);
          return true;
      }
      return false;
   }
   
   function renameGalerie ($old, $new) {
      global $mysql;
      if ($mysql->db_link != null) {
         $query = "UPDATE ".DB_PREFIX."commentaire " .
               "SET galerie_comment='$new' " .
               "WHERE  galerie_comment='$old'";
         $mysql->DbQuery ($query);
      }
   }
   
   function deleteGalerie ($galerie) {
      global $mysql;
      if ($mysql->db_link != null) {
         $query = "DELETE FROM ".DB_PREFIX."commentaire " .
               "WHERE  galerie_comment='$galerie'";
         $mysql->DbQuery ($query);
      }
   }
   
   /*function renamePhoto ($galerie, $old, $new) {
      global $mysql;
      $query = "UPDATE ".DB_PREFIX."commentaire " .
            "SET photo_comment='$new' " .
            "WHERE  photo_comment='$old' AND galerie_comment='$galerie'";
      $mysql->DbQuery ($query);
   }*/
   
   function selectQuery ($galerie, $photo) {
      $query = "SELECT id_comment, date_comment, auteur_comment, email_comment, site_comment, content_comment "
      ."FROM ".DB_PREFIX."commentaire "
      ."WHERE galerie_comment='$galerie' AND photo_comment='$photo' AND pub_comment='1'";
      return $query;
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
