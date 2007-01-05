<?php

global $UploadError;

  /**
   * http://miasmatik.maladoc.org/articles/
   * @package   upload2.0
   * @version 2.0.a (dernière révision le 07-11-2003)
   * @author    Olivier VEUJOZ <o.veujoz@miasmatik.net>
   * SECURITY CONSIDERATION: If you are saving all uploaded files to a directory accesible with an URL, remember to filter files not only by mime-type (e.g. image/gif), but also by extension. The mime-type is reported by the client, if you trust him, he can upload a php file as an image and then request it, executing malicious code. 
   I hope I am not giving hackers a good idea anymore than I am giving it to good-intended developers. Cheers.
   Some restrictive firewalls may not let file uploads happen via a form with enctype="multipart/form-data".
   We were having problems with an upload script hanging (not returning content) when a file was uploaded through a remote office firewall. Removing the enctype parameter of the form allowed the form submit to happen but then broke the file upload capability. Everything but the file came through. Using a dial-in or other Internet connection (bypassing the bad firewall) allowed everything to function correctly.
   So if your upload script does not respond when uploading a file, it may be a firewall issue.
   * Compatibilité :
   - compatible safe_mode
   - compatible open_basedir pour peu que les droits sur le répertoire temporaire d'upload soit alloué
   - marche avec les chemins relatifs et absolu
   - testé sous environnement Linux/Windows sous Apache 1.3
   - testé avec les version PHP 4.2.0, 4.3.1, 4.3.4
   - Version minimum de php : 4.2.0
   * Par défaut :
   - autorise tout type de fichier
   - autorise les fichier allant jusqu'à la taille maximale spécifiée dans le php.ini
   - envoie le(s) fichier(s) dans le répertoire de la classe
   - estime le temps d'execution du script par rapport à un modem 33.6Ko
   - n'affiche qu'un champ de type file
   - permet de laisser les champs de fichiers vides
   - écrase le fichier s'il existe déjà
   - n'exécute aucune vérification
   * Notes :
   - le chemin de destination peut être soit absolu soit relatif
   - si $SecurityMax est positionné à "true", la classe va ignorer tout type de fichier rentrant dans la catégorie des application/octetstream
   - set_time_limit n'a pas d'effet lorsque PHP fonctionne en mode safe mode . Il n'y a pas d'autre solution que de changer de mode, ou de modifier la durée maximale d'exécution dans le php.ini
   - la variable $UploadErreur (type bool) est réutilisable dans vos scripts afin de tester le bon déroulement des opérations. S'il y a eu des erreurs, la variable est positionnée à "true".
  */
class Upload {
    
   /**
    * Taille maximum exprimée en kilo-octets pour l'upload d'un fichier.
    * Type : numérique
    * Valeur par défaut : celle configurée dans le php.ini
    * @access public
    */
   var $MaxFilesize;
    
   /**
    * Largeur maximum d'une image exprimée en pixel.
    * Type : entier
    * Valeur par défaut : null (aucune vérification)
    * @access public
    */
   var $ImgMaxWidth;
    
   /**
    * Hauteur maximum d'une image exprimée en pixel.
    * Type : entier
    * Valeur par défaut : null (aucune vérification)
    * @access public
    */
   var $ImgMaxHeight;
    
   /**
    * Largeur minimum d'une image exprimée en pixel.
    * Type : entier
    * Valeur par défaut : null (aucune vérification)
    * @access public
    */
   var $ImgMinWidth;
    
   /**
    * Hauteur minimum d'une image exprimée en pixel.
    * Type : entier
    * Valeur par défaut : null (aucune vérification)
    * @access public
    */
   var $ImgMinHeight;
    
   /**
    * Répertoire de destination dans lequel vont être chargé les fichiers. Accepte les chemins relatifs et absolus
    * Type : chaine
    * Valeur par défaut : chaine vide (le répertoire dans lequelle est située la classe upload sera désigné comme chemin de destination)
    * @access public
    */
   var $DirUpload;
    
   /**
    * Débit de la connexion utilisateur, exprimée en kilobit, sur laquelle sera basée le calcul de la fonction set_time_limit
    * Type : valeur numérique
    * Valeur par défaut : 33.6
    * @access public
    */
   var $Debit;
    
   /**
    * Nombre de champs de type file que la classe devra gérer.
    * Type : entier
    * Valeur par défaut : 1
    * @access public
    */
   var $Fields;
    
   /**
    * Paramètres à ajouter aux champ de type file (ex: balise style, évenements JS...)
    * Type : chaine
    * Valeur par défaut : chaine vide
    * @access public
    */
   var $FieldOptions;
    
   /**
    * Définit si les champs sont obligatoires ou non.
    * Type : booléen
    * Valeur par défaut : false
    * @access public
    */
   var $Required;
    
   /**
    * Politique de sécurité max : ignore tous les fichiers exécutables / interprétable.
    * Type : booléen
    * Valeur par défaut : false
    * @access public
    */
   var $SecurityMax;
    
   /**
    * Permet de préciser un nom pour le fichier à uploader. Peut s'utiliser conjointement avec les propriétés $Suffixe / $Prefixe
    * Type : chaine
    * Valeur par défaut : chaine vide
    * @access public
    */
   var $Filename;
    
   /**
    * Préfixe pour un nom de fichier
    * Type : chaine
    * Valeur par défaut : chaine vide
    * @access public
    */
   var $Prefixe;
    
   /**
    * Suffixe pour un nom de fichier
    * Type : chaine
    * Valeur par défaut : chaine vide
    * @access public
    */
   var $Suffixe;
    
   /**
    * Méthode à employer pour l'écriture des fichiers :
    *  0 : si un fichier de même nom est présent dans le répertoire, il est écrasé par le nouveau fichier
    *  1 : si un fichier de même nom est présent dans le répertoire, le nouveau fichier est uploadé mais précédé de la mention 'copie_de_'
    *  2 : si un fichier de même nom est présent dans le répertoire, le nouveau fichier est ignoré
    * Type : entier compris entre 0 et 2
    * Valeur par défaut : 0
    * @access public
    */
   var $WriteMode;
    
   /**
    * Indique s'il faut vérifier la provenance du formulaire d'upload des fichiers. Si la chaine est vide, aucune vérification n'est effectuée.
    * Pour lancer la vérification, il faut indiquer l'URI du formulaire de soumission des données.
    * Type : chaine
    * Valeur par défaut : chaine vide
    * @access public
    */
   var $CheckReferer;
    
   /**
    * Chaine de caractères représentant les entêtes de fichiers autorisés (mime-type).
    * Les entêtes doivent être séparées par des points virgules.
    * Type : chaine
    * Valeur par défaut : chaine vide (tout type d'entête autorisé)
    * @access public
    */
   var $MimeType;
    
   /**
    * Définit si les erreurs de configuration de la classe doivent être affichées en sortie écran et doivent stopper le script courant.
    * Type : booléen
    * Valeur par défaut : true
    * @access public
    */
   var $TrackError;
    
    
    
   /***********************************************************************************
    METHODES PUBLIQUES                                                                          
   ***********************************************************************************/
        
   /**
    * Constructeur
    *
    * @access public
    * @return object   initialise les valeurs par défaut
    */
   function Upload() {
      $this-> Extension    = '';
      $this-> DirUpload    = '';
      $this-> MimeType     = '';
      $this-> Filename     = '';
      $this-> FieldOptions = '';
      $this-> Fields       = 1;
      $this-> WriteMode    = 0;
      $this-> Debit        = 33.6;
      $this-> SecurityMax  = false;
      $this-> CheckReferer = false;
      $this-> Required     = false;
      $this-> TrackError   = true;
      $this-> ArrOfError   = Array();
      $upSize =  ereg_replace('M', '',ini_get('upload_max_filesize'));
      $upSize =  ereg_replace('G', '',$upSize);
      $this-> MaxFilesize  = $upSize * 1024;
   }
        
        
        
   /**
    * Lance l'initialisation de la classe pour la génération du formulaire
    * @access public
    */
   function InitForm() {
      $this-> SetMaxFilesize();
      $this-> CreateFields();
   }
    
    
    
   /**
    * Retourne le tableau des erreurs survenues durant l'upload
    *
    * <code>
    * if ($UploadError) {
    *     print_r($Upload-> GetError);
    * }
    * </code>
    *
    * @access public
    * @param integer $num_field    numéro du champ 'file' sur lequel on souhaite récupérer l'erreur
    * @return array                tableau des erreurs
    */
   function GetError($num_field='') {
      if (Empty($num_field)) return $this-> ArrOfError;
      else                  return $this-> ArrOfError[$num_field];
   }
        
        
        
   /**
    * Retourne le tableau contenant les informations sur les fichiers uploadés
    *
    * <code>
    * if (!$UploadError) {
    *     print_r($Upload-> GetSummary());
    * }
    * </code>
    *
    * @access public
    * @param integer $num_field    numéro du champ 'file' sur lequel on souhaite récupérer les informations
    * @return array                tableau des infos fichiers
    */
   function GetSummary($num_field='') {
      if ($num_field == '') return $this-> Infos;
      else                  return $this-> Infos[$num_field];
   }
        
        
        
   /**
    * Lance les différents traitements nécessaires à l'upload
    * @access public
    */
   function Execute(){
      $this-> CheckConfig();
      $this-> VerifyReferer();
      $this-> SetTimeLimit();
      $this-> CheckUpload();
   }
        
        
        
        
   /*******************************************************************************************
    METHODES A USAGE INTERNE                                                                            
   ********************************************************************************************/
        
   /**
    * Méthode lançant les vérifications sur les fichiers. Initialisation de la variable $UploadError à true si erreur, lance la 
    * méthode d'écriture toutes les vérifications sont ok.
    * @access private
    */
   function CheckUpload() {
      global $UploadError;
        
      // Parcours des fichiers à uploader
      for ($i=0; $i < count($_FILES['userfile']['tmp_name']); $i++)  {
            
         // Récup des propriétés
         $this-> _field = $i+1;                                // position du champ dans le formulaire à partir de 1 (0 étant réservé au champ max_file_size)
         $this-> _size  = $_FILES['userfile']['size'][$i];     // poids du fichier
         $this-> _type  = $_FILES['userfile']['type'][$i];     // type mime
         $this-> _name  = $_FILES['userfile']['name'][$i];     // nom du fichier
         $this-> _temp  = $_FILES['userfile']['tmp_name'][$i]; // emplacement temporaire
         $this-> _ext   = strtolower(substr($this-> _name, strrpos($this-> _name, '.'))); // extension du fichier
            

         if ($this-> _name != '') {
            // On exécute les vérifications demandées
            if (is_uploaded_file($_FILES['userfile']['tmp_name'][$i])) {
               $this-> CheckSecurity();
               $this-> CheckMimeType();
               $this-> CheckExtension();
               $this-> CheckImg();
            } else $this-> AddError($_FILES['userfile']['error'][$i]); // Le fichier n'a pas été uploadé, on récupère l'erreur
            
            // Si le fichier a passé toutes les vérifications, on procède à l'upload, sinon on positionne la variable globale UploadError à 'true'
            if (!isset($this-> ArrOfError[$this-> _field])) $this-> WriteFile($this-> _name, $this-> _type, $this-> _temp, $this-> _size, $this-> _ext, $this-> _field);
            else $UploadError = true;
         }
      }
   }
        
        
        
   /**
    * Ecrit le fichier sur le serveur.
    *
    * @access private
    * @param string $name        nom du fichier sans son extension
    * @param string $type        entete du fichier
    * @param string $temp        chemin du fichier temporaire
    * @param string $size        taille du fichier en octets
    * @param string $temp        extension du fichier précédée d'un point
    * @param string $temp        extension du fichier précédée d'un point
    * @param string $num_fied    position du champ dans le formulaire à compter de 1
    * @return bool               true/false => succes/erreur
    */
   function WriteFile($name, $type, $temp, $size, $ext, $num_field) {
        
      $new_filename = NULL;
        
      if (is_uploaded_file($temp)) {
            
         // Nettoyage du nom original du fichier
         if (Empty($this-> Filename)) $new_filename = $this-> CleanStr(substr($name, 0, strrpos($name, '.')));
         else $new_filename = $this-> Filename;
            
         // Ajout préfixes / suffixes + extension :
         $new_filename = $this-> Prefixe . $new_filename . $this-> Suffixe . $ext;
            
         switch ($this-> WriteMode) {
            // Si le fichier existe, on écrase
            case 0 : $uploaded = move_uploaded_file($temp, $this-> DirUpload . $new_filename);
               break;
                    
               // Si le fichier existe, on en fait une copie
            case 1 : if ($this-> AlreadyExist($new_filename)) $new_filename = 'copie_de_' . $new_filename;
               $uploaded = move_uploaded_file($temp, $this-> DirUpload . $new_filename);
               break;
                
               // Si le fichier existe, on ne fait rien
            case 2 :  if ($this-> AlreadyExist($new_filename)) $uploaded = true;
               else $uploaded = move_uploaded_file($temp, $this-> DirUpload . $new_filename);
               break;
         }
            
         // Informations pouvant être utiles au développeur (si le fichier a pu être copié)
         if ($uploaded != false) {
            $this-> Infos[$num_field]['nom']          = $new_filename;
            $this-> Infos[$num_field]['nom_originel'] = $name;
            $this-> Infos[$num_field]['chemin']       = $this-> DirUpload . $new_filename;
            $this-> Infos[$num_field]['poids']        = number_format(filesize($this-> DirUpload . $new_filename)/1024, 3, '.', '');
            $this-> Infos[$num_field]['mime-type']    = $type;
            $this-> Infos[$num_field]['extension']    = $ext;
         }
            
         return true;
      }// End is_uploaded_file
        
      return false;
   } // End function
    
    
    
   /**
    * Vérifie si le fichier passé en paramètre existe déjà dans le répertoire DirUpload
    * @access private
    * @return bool
    */
   function AlreadyExist($file) {
      if (!file_exists($this-> DirUpload . $file)) return false;
      else return true;
   }
        
        
        
   /**
    * Vérifie la hauteur/largeur d'une image
    * @access private
    * @return bool
    */
   function CheckImg() {
      // Vérification de la largeur puis de la hauteur
      if ($taille = @getimagesize($this-> _temp) ) {
         if (!Empty($this-> ImgMaxWidth)  && $taille[0] > $this-> ImgMaxWidth)  $this-> AddError(8);
         if (!Empty($this-> ImgMaxHeight) && $taille[1] > $this-> ImgMaxHeight) $this-> AddError(9);
         if (!Empty($this-> ImgMinWidth)  && $taille[0] < $this-> ImgMinWidth) $this-> AddError(10);
         if (!Empty($this-> ImgMinHeight) && $taille[1] < $this-> ImgMinHeight) $this-> AddError(11);
      }
        
      return true;
   }
        
        
        
   /**
    * Vérifie l'extension des fichiers suivant celles précisées dans $Extension
    * @access private
    * @return bool
    */
   function CheckExtension() {
      $ArrOfExtension = explode(';', strtolower($this-> Extension));
        
      if (!Empty($this-> Extension) && !in_array($this-> _ext, $ArrOfExtension)) {
         $this-> AddError(7);
         return false;
      }
        
      return true;
   }
        
        
        
   /**
    * Vérifie l'entête des fichiers suivant ceux précisés dans $MimeType
    * @access private
    * @return bool
    */
   function CheckMimeType() {
      $ArrOfMimeType = explode(';', $this-> MimeType);
        
      if (!Empty($this-> MimeType) && !in_array($this-> _type, $ArrOfMimeType)) {
         $this-> AddError(6);
         return false;
      }
        
      return true;
   }
        
        
    
   /**
    * Ajoute une erreur pour le fichier spécifié dans le tableau des erreur
    * @access private
    */
   function AddError($code_erreur) {
       
      // Le tableau des erreurs est de la forme :$arr[position_du_champ][code_erreur] = 'description';
       
      switch ($code_erreur) {
         case 1 : $msg = 'Le fichier à charger excède la directive upload_max_filesize (php.ini) ('. $this-> _name .')';
            break;
            
         case 2 : $msg = 'Le fichier excède la directive MAX_FILE_SIZE de '.($this->MaxFilesize/1024).' ko  qui a été spécifiée dans le formulaire ('. $this-> _name .')';
            break;
            
         case 3 : $msg = 'Le fichier n\'a pu être chargé complètement ('. $this-> _name .')';
            break;
            
         case 4 : $msg = 'Le champ du formulaire est vide';
            break;
            
         case 5 : $msg = 'Fichier potentiellement dangereux ('. $this-> _name .')';
            break;
            
         case 6 : $msg = 'Le fichier n\'est pas conforme à la liste des entêtes autorisés ('. $this-> _name .')';
            break;
            
         case 7 : $msg = 'Le fichier n\'est pas conforme à la liste des extensions autorisées ('. $this-> _name .')';
            break;
            
         case 8 : $msg = 'La largeur de l\'image dépasse celle autorisée ('. $this-> _name .')';
            break;
            
         case 9 : $msg = 'La hauteur de l\'image dépasse celle autorisée ('. $this-> _name .')';
            break;
            
         case 10 : $msg = 'La largeur de l\'image est inférieure à celle autorisée ('. $this-> _name .')';
            break;
            
         case 11 : $msg = 'La hauteur de l\'image est inférieure à celle autorisée ('. $this-> _name .')';
            break;
      }
        
        
      if ($this-> Required && $code_erreur == 4) $this-> ArrOfError[$this-> _field][$code_erreur] = $msg;
      else if ($code_erreur != 4)                $this-> ArrOfError[$this-> _field][$code_erreur] = $msg;
   }
    
        
   /**
    * Vérifie les critères de la politique de sécurité
    * @access private
    * @return bool
    */
   function CheckSecurity() {
      // Bloque tous les fichiers executables, et tous les fichiers php pouvant être interprété mais dont l'entête ne peut les identifier comme étant dangereux
      if ($this-> SecurityMax===true) {
         // Note : is_executable ne fonctionne pas => ?
         if (ereg ('application/octet-stream', $this-> _type) || preg_match("/.php$|.inc$|.php3$/i", $this-> _ext) ) {
            $this-> AddError(5);
            return false;
         }
      }
                
      return true;
   }
        
        
        
   /**
    * Vérifie et formate le chemin de destination :
    *     - définit comme rep par défaut celui de la classe
    *     - teste l'existance du répertoire et son accès en écriture
    * @access private
    */
   function CheckDirUpload() {
      // Si aucun répertoire n'a été précisé, on prend celui de la classe
      if (Empty($this-> DirUpload)) $this-> DirUpload = dirname(__FILE__);
        
      $this-> DirUpload = $this-> FormatDir($this-> DirUpload);
        
      // Le répertoire existe?
      if (!is_dir($this-> DirUpload)) $this-> Error('Le répertoire de destination spécifiée par la propriété DirUpload n\'existe pas.');
      // Droit en écriture ?
      if (!is_writeable($this-> DirUpload)) $this-> Error('Le répertoire de destination spécifiée par la propriété DirUpload est inaccessible en écriture.');
   }
        
        
    
   /**
    * Formate le répertoire passé en paramètre
    *     - convertit un chemin relatif en chemin absolu
    *     - ajoute si besoin le dernier slash (ou antislash suivant le système)
    * @access private
    */
   function FormatDir($Dir) {
      // Convertit les chemins relatifs en chemins absolus
      if (function_exists('realpath')) {
         if (realpath($Dir)) $Dir = realpath($Dir);
      }
        
      // Position du dernier slash/antislash
      if ($Dir[strlen($Dir)-1] != DIRECTORY_SEPARATOR) $Dir .= DIRECTORY_SEPARATOR;
        
      return $Dir;
   }
        
    
    
   /**
    * Formate la chaine passée en paramètre en nom de fichier standard (pas de caractères spéciaux ni d'espaces)
    * @access private
    * @param  string $str   chaine à formater
    * @return string        chaine formatée
    */
   function CleanStr($str) {
      $return = '';
                
      for ($i=0; $i <= strlen($str)-1; $i++) {
         if (eregi('[a-z]',$str{$i}))              $return .= $str{$i};
         elseif (eregi('[0-9]', $str{$i}))         $return .= $str{$i};
         elseif (ereg('[àâäãáåÀÁÂÃÄÅ]', $str{$i})) $return .= 'a';
         elseif (ereg('[æÆ]', $str{$i}))           $return .= 'a';
         elseif (ereg('[çÇ]', $str{$i}))           $return .= 'c';
         elseif (ereg('[éèêëÉÈÊËE]', $str{$i}))    $return .= 'e';
         elseif (ereg('[îïìíÌÍÎÏ]', $str{$i}))     $return .= 'i';
         elseif (ereg('[ôöðòóÒÓÔÕÖ]', $str{$i}))   $return .= 'o';
         elseif (ereg('[ùúûüÙÚÛÜ]', $str{$i}))     $return .= 'u';
         elseif (ereg('[ýÿÝŸ]', $str{$i}))         $return .= 'y';
         elseif (ereg('[ ]', $str{$i}))            $return .= '_';
         elseif (ereg('[.]', $str{$i}))            $return .= '_';
         else                                      $return .= $str{$i};
      }
                
      return $return;
   }
        
        
        
   /**
    * Vérifie que la provenance du formulaire est bien celle précisée dans la propriétée CheckReferer.
    * @access private
    * @return bool
    */
   function VerifyReferer() {
      if (!Empty($this-> CheckReferer)) {
         $headerref = $_SERVER['HTTP_REFERER'];
            
         // On enlève toutes les variables passées par url
         if (ereg("\?",$headerref)){
            list($url, $getstuff) = split('\?', $headerref);
            $headerref = $url;
         }
            
         if ($headerref == $this-> CheckReferer) return true;
         else $this-> Error('Accès refusé');
      }
   }
        
        
        
   /**
    * Initialise si possible le temps d'exécution max du script en fonction du nombre de fichiers et de la propriété max_file_size
    * @access private
    */
   function SetTimeLimit(){
      // Le temps calculé est théoriquement le plus rapide => * 2
      @set_time_limit(ceil(ceil($this->  $_POST['MAX_FILE_SIZE'] * 8) / ($this-> Debit * 1000) * count($_FILES) * 2));
   }
        
    
    
   /**
    * Conversion du poids maximum d'un fichier exprimée en Ko en octets
    * @access private
    */
   function SetMaxFilesize() {
      (is_numeric($this-> MaxFilesize)) ? $this-> MaxFilesize = $this-> MaxFilesize * 1024 : $this-> Error('la propriété MaxFilesize doit être une valeur numérique');
   }
        
    
    
   /**
    * Crée les champs de type fichier suivant la propriété Fields dans un tableau $Field. Ajoute le contenu de FieldOptions aux champs.
    * @access private
    */
   function CreateFields() {
      if (!is_int($this-> Fields)) $this-> Error('la propriété Fields doit être un entier');
        
      for ($i=0; $i <= $this-> Fields; $i++) {
         if ($i == 0)  $this-> Field[] = '<input type="hidden" name="MAX_FILE_SIZE" value="'. $this-> MaxFilesize .'" />';
         else          $this-> Field[] = '<input type="file" name="userfile[]" '. $this-> FieldOptions .' />';
      }
   }
    
        
        
   /**
    * Vérifie la configuration de la classe.
    * @access private
    */
   function CheckConfig() {
      if (!version_compare(phpversion(), '4.2.0')) $this-> Error('la version de php sur ce serveur est trop ancienne. La classe ne peut fonctionner qu\'avec une version égale ou supérieure à la version 4.1.0');
      if (!is_string($this-> Extension)) $this-> Error('la propriété Extension est mal configurée.');
      if (!is_string($this-> MimeType)) $this-> Error('la propriété MimeType est mal configurée.');
      if (!is_string($this-> Filename)) $this-> Error('la propriété Filename est mal configurée.');
      if (!is_numeric($this-> Debit)) $this-> Error('la propriété Debit est mal configurée.');
      if (!is_bool($this-> Required)) $this-> Error('la propriété Required est mal configurée.');
      if (!is_bool($this-> SecurityMax)) $this-> Error('la propriété SecurityMax est mal configurée.');
      if ($this-> WriteMode != 0 && $this-> WriteMode != 1 && $this-> WriteMode != 2) $this-> Error('la propriété WriteMode est mal configurée.');
      if (!Empty($this-> CheckReferer) && !@fopen($this-> CheckReferer, 'r')) $this-> Error('la propriété CheckReferer est mal configurée.');
      $this-> CheckImgPossibility();
      $this-> CheckDirUpload();
   }
    
    
    
   /**
    * Vérifie les propriétés ImgMaxWidth/ImgMaxHeight
    * @access private
    */
   function CheckImgPossibility() {
      if (!Empty($this-> ImgMaxWidth)  && !is_numeric($this-> ImgMaxWidth))   $this-> Error('la propriété ImgMaxWidth est mal configurée.');
      if (!Empty($this-> ImgMaxHeight) && !is_numeric($this-> ImgMaxHeight))  $this-> Error('la propriété ImgMaxHeight est mal configurée.');
      if (!Empty($this-> ImgMinWidth)  && !is_numeric($this-> ImgMinWidth))   $this-> Error('la propriété ImgMinWidth est mal configurée.');
      if (!Empty($this-> ImgMinHeight) && !is_numeric($this-> ImgMinHeight))  $this-> Error('la propriété ImgMinHeight est mal configurée.');
   }
    
    
    
   /**
    * Affiche les erreurs de configuration et stoppe tout traitement 
    * @access private
    */
   function Error($error_msg) {
      if ($this-> TrackError) {
         echo 'Erreur classe Upload : ' . $error_msg;
         exit;
      }
   }
    
} // End Class
?>