<?php

  //------------------------------------------------------------------------------
  // Include
  //------------------------------------------------------------------------------


  /**
   * @package process
   */
class Commentaire {
   var $author;
   var $email;
   var $website;
   var $content;
   var $date;
   var $ip;
   var $public;

   var $errors = array ();

   function Commentaire () {
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    */
   function fillFromPost ($request) {
      // Auteur, obligatoire
      if (isset ($request->POST['author']) && $request->POST['author'] != '') {
         $this->setAuthor (protege_input ($request->POST['author']));
      }
      else {
         $this->errors['author'] = _('Champ vide !!!');
      }

      // Contenu, obligatoire
      if (isset ($request->POST['content']) && trim ($request->POST['content']) != '') {
         $this->setContent (protege_input ($request->POST['content']));
      }
      else {
         $this->errors['content'] = _('Champ vide !!!');
      }

      // Site
      if (isset ($request->POST['website']) && $request->POST['website'] != '') {
         $this->setWebsite (protege_input ($request->POST['website']));
         if (!verifsite ($this->website)) {
            $this->errors['website'] = _('Format de site incorrect');
         }
      }

      // Email
      if (isset ($request->POST['email']) && $request->POST['email'] != '') {
         $this->setEmail (protege_input ($request->POST['email']));
         if (!verifEmail ($this->email)) {
            $this->errors['email'] = _('Format d\'email incorrect');
         }
      }
      return $this->isValidForm ();
   }

   /**
    * 
    */
   function getError ($champ) {
      if (array_key_exists ($champ, $this->errors)) {
         return $this->errors[$champ];
      }
      return '';
   }

   /**
    * 
    */
   function isValidForm () {
      return (count ($this->errors) == 0);
   }

   /**
    * 
    */
   function fillInfos() {
      $this->setIp($_SERVER['REMOTE_ADDR']);
      $this->setDate(mktime());
   }

   //---------------------------------------------------------------------------
   // SETTERS / GETTERS
   //---------------------------------------------------------------------------

function __get($name) {
   return $this->$name;
}

function __set($name, $val) {
   $this->$name = $val;
}
   /**
    * @return 
    */
   function getDate () { 
      return $this->date;
   }

   /**
    * @param String date
    */
   function setDate ($date) { 
      $this->date = $date;
   }

   /**
    * @return 
    */
   function getAuthor () { 
      return $this->author;
   }

   /**
    * @param String author
    */
   function setAuthor ($author) { 
      $this->author = $author;
   }

   /**
    * @return 
    */
   function getEmail () { 
      return $this->email;
   }

   /**
    * @param String email
    */
   function setEmail ($email) { 
      $this->email = $email;
   }

   /**
    * @return 
    */
   function getWebsite () { 
      return $this->website;
   }

   /**
    * @param String website
    */
   function setWebsite ($website) { 
      $this->website = $website;
   }

   /**
    * @return 
    */
   function getContent () { 
      return $this->content;
   }

   /**
    * @param String content
    */
   function setContent ($content) { 
      $this->content = $content;
   }

   /**
    * @return 
    */
   function getIp () { 
      return $this->ip;
   }

   /**
    * @param String ip
    */
   function setIp ($ip) { 
      $this->ip = $ip;
   }

   /**
    * @return 
    */
   function isPublic () { 
      return $this->public;
   }

   /**
    * @param String public
    */
   function setPublic ($public) { 
      $this->public = $public;
   }

}
?>