<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------
include (FONCTIONS_DIR.'utils/formulaires.php');


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

   function insertRow () {
      global $mysql;
      $sql = "INSERT INTO commentaire (galerie_comment, photo_comment, date_comment, "
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
      $this->content = $content;
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
