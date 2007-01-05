<?php


  //Initialisation de l'include_path
  /*$os = ((stristr(getenv('SERVER_SOFTWARE'), 'win') || stristr(getenv('SERVER_SOFTWARE'), 'microsoft'))? ';' : ':');
   $path = ((defined('MX_GENERAL_PATH'))? MX_GENERAL_PATH : '').$os.((defined('MX_ERROR_PATH'))? MX_ERROR_PATH : '').$os.ini_get('include_path');
   ini_set('include_path', $path);
  */
  //Inclusion du fichier de configuration et de Error Manager
require(CONF_DIR.'Mxconf.php');
require(LIB_DIR.'ErrorManager.php');

//D�sactivation de magic_quotes_runtime de php.ini
if (get_magic_quotes_runtime()) set_magic_quotes_runtime(0);

class ModeliXe extends ErrorManager{

   var $template = '';
   var $absolutePath = '';
   var $relativePath = '';
   var $sessionParameter = '';
   var $mXParameterFile = '';
   var $mXTemplatePath = '';
   var $mXCachePath = '';
   var $mXUrlKey = '';

   var $outputSystem = '/>';
   var $flagSystem = 'xml';
   var $adressSystem = 'relative';
   var $mXVersion = '1.0';

   var $mXCacheDelay = 0;
   var $debut = 0;
   var $fin = 0;
   var $ExecutionTime = 0;

   var $mXcompress = false;
   var $mXsetting = false;
   var $mXmodRewrite = true;
   var $performanceTracer = false;
   var $mXoutput = false;

   var $mXsignature = true;
   var $isTemplateFile = true;

   var $templateContent = array();
   var $sheetBuilding = array();
   var $deleted = array();
   var $replacement = array();
   var $loop = array();
   var $IsALoop = array();
   var $xPattern = array();
   var $formField = array();
   var $checker = array();
   var $attribut = array();
   var $attributKey = array();
   var $htmlAtt = array();
   var $select = array();
   var $hidden = array();
   var $image = array();
   var $text = array();
   var $father = array();
   var $son = array();
   var $plugInMethods = array();

   var $flagArray = array(0 => 'hidden', 1 => 'select', 2 => 'image', 3 => 'text', 4 => 'checker', 5 => 'formField');
   var $attributArray = array(0 => 'attribut');

   //MX Generator----------------------------------------------------------------------------------------------------

   //Constructeur de ModeliXe
   function ModeliXe ($template, $sessionParameter = '', $templateFileParameter = '', $cacheDelay = -1)
   {

      $this -> ErrorManager();
                
      $time = explode(' ',microtime());
      $this -> debut = $time[1] + $time[0];
                
      //Gestion des param�tres par d�faut
      //Definition du systeme de compression
      if (defined('MX_COMPRESS')) $this -> SetMxCompress(MX_COMPRESS);
                
      //Activation du mode rewrite
      if (defined('MX_REWRITEURL')) $this -> SetMxModRewrite(MX_REWRITEURL);
                
      //Activation de la signature
      if (defined('MX_SIGNATURE')) $this -> SetMxSignature(MX_SIGNATURE);
                
      //D�finition du r�pertoire de template
      if (defined('MX_TEMPLATE_PATH')) $this -> SetMxTemplatePath(MX_TEMPLATE_PATH);
                
      //D�finition du fichier de param�trage
      if (defined('MX_DEFAULT_PARAMETER') && ! $templateFileParameter)
         $this -> SetMxFileParameter(MX_DEFAULT_PARAMETER);
      elseif ($templateFileParameter != '')
         $this -> SetMxFileParameter($templateFileParameter);
                
      //D�finition du type de balisage
      if (defined('MX_FLAGS_TYPE')) $this -> SetMxFlagsType(MX_FLAGS_TYPE);
                
      //D�finition du type de balisage en sortie
      if (defined('MX_OUTPUT_TYPE')) $this -> SetMxOutputType(MX_OUTPUT_TYPE);
                
      //D�finition du r�pertoire de cache
      if (defined('MX_CACHE_PATH')) $this -> SetMxCachePath(MX_CACHE_PATH);
      if (defined('MX_CACHE_DELAY')) $this -> SetMxCacheDelay(MX_CACHE_DELAY);
      if ($cacheDelay >= 0 && $cacheDelay != '') $this -> SetMxCacheDelay($cacheDelay);
                
      //Activation du traceur de performance
      if (defined('MX_PERFORMANCE_TRACER') && MX_PERFORMANCE_TRACER == 'on') $this -> performanceTracer = true;
                
      //Gestion des param�tres de sessions
      if ($sessionParameter) $this -> sessionParameter = $sessionParameter;
                
      //Instanciation de la ressource templates
      if (@is_file($this -> mXTemplatePath.$template)) $this -> template = $template;
      elseif (isset($template)) 
      {
         $this -> template = $template;
         $this -> isTemplateFile = false;
      }
      else $this -> ErrorTracker (5, 'No template file defined.', 'ModeliXe', __FILE__, __LINE__);
                
      //Affectation du path d'origine
      if ($this -> ErrorChecker()) 
      {
         $this -> absolutePath = substr(basename($this -> template), 0, strpos(basename($this -> template), '.'));
         $this -> relativePath = $this -> absolutePath;
      }
   }

   //Setting ModeliXe -------------------------------------------------------------------------------------------

   //M�thode d'instanciation du template
   function SetModelixe($out = '')
   {
      if ($this -> mXsetting)  $this -> ErrorTracker(4, 'You can\'t re-use this method after instanciate ModeliXe once time.', 'SetModelixe', __FILE__, __LINE__);
      if ($out) $this -> mXoutput = true;
                
      //Test du cache et insertion �ventuelle
      if ($this -> mXCacheDelay > 0)
      {
         $this -> mXUrlKey = $this -> GetMD5UrlKey();
                        
         if ($this -> MxCheckCache()) $this -> MxGetCache();
      }
                
      //Initialisation de la classe
      $this -> GetMxFile();
      if ($this -> ErrorChecker()) $this -> MxParsing($this -> templateContent[$this -> absolutePath]);
                
      $this -> mXsetting = true;
   }
        
   //Instanciation de la compression
   function SetMxCompress($arg = '')
   {
      if ($arg != 'on') $this -> mXcompress = false;
      else $this -> mXcompress = true;
                
      return $this -> mXcompress;
   }
        
   //Instanciation du mode rewrite
   function SetMxModRewrite($arg = '')
   {
      if ($arg != 'on') $this -> mXmodRewrite = false;
      else $this -> mXmodRewrite = true;
                
      return $this -> mXmodRewrite;
   }
        
   //Instanciation de la signature
   function SetMxSignature($arg = '')
   {
      if ($arg != 'on') $this -> mXsignature = false;
      else $this -> mXsignature = true;
                
      return $this -> mXsignature;
   }

   //Instanciation du template path
   function SetMxTemplatePath($arg = '')
   {
      if ($this -> mXsetting) $this -> ErrorTracker(1, 'You can\'t use this method after instanciate ModeliXe with setModeliXe method, it will be without effects.', 'SetMxTemplatePath', __FILE__, __LINE__);
      else 
      {
         if ($arg[strlen($arg) - 1] != '/' && $arg) $arg .= '/';
         if (! is_dir($arg)) $this -> ErrorTracker(5, 'The MX_TEMPLATE_PATH (<b>'.$arg.'</b>) is not a directory.', 'SetMxTemplatePath', __FILE__, __LINE__);
         else $this -> mXTemplatePath = $arg;
      }
        
      return $this -> mXTemplatePath;
   }

   //Instanciation du fichier de param�tre
   function SetMxFileParameter($arg = '')
   {
      if ($arg != '' && ! is_file($arg)) $this -> ErrorTracker(1, 'The parameter\'s file path (<b>'.$arg.'</b>) does not exist.', 'SetMxFileParameter', __FILE__, __LINE__);
      else $this -> mXParameterFile = $arg;
                
      return $this -> mXParameterFile;
   }

   //Instanciation du balisage du template
   function SetMxFlagsType($arg)
   {
      if ($this -> mXsetting) $this -> ErrorTracker(1, 'You can\'t use this method after instanciate ModeliXe with setModeliXe method, it will be without effects.', 'SetMxFlagsType', __FILE__, __LINE__);
      else 
      {
         switch (strtolower($arg))
         {
            case 'classical':
               $this -> flagSystem = 'classical';
               break;
            case 'pear':
               $this -> flagSystem = 'classical';
               break;
            case 'xml':
               $this -> flagSystem = 'xml';
               break;
            default:
               $this -> ErrorTracker(2, 'This type of flag system ('.$arg.') is unrecognized.', 'SetMxFlagsType', __FILE__, __LINE__);
         }
      }
                
      return $this -> flagSystem;
   }
        
   //Instanciation du balisage de sortie
   function SetMxOutputType($arg)
   {
      if ($this -> mXsetting) $this -> ErrorTracker(1, 'You can\'t use this method after instanciate ModeliXe with setModeliXe method, it will be without effects.', 'SetMxOutputType', __FILE__, __LINE__);
      else 
      {
         switch (strtolower($arg))
         {
            case 'xhtml':
               $this -> outputSystem = '/>';
               break;
            case 'html':
               $this -> outputSystem = '>';
               break;
            default:
               $this -> ErrorTracker(2, 'This type of output flag system ('.$arg.') is unrecognized.', 'SetMxOutputType', __FILE__, __LINE__);
         }
      }
      return $arg;
   }
        
   //Instanciation du r�pertoire de cache
   function SetMxCachePath($arg)
   {
   
//       if ($this -> mXsetting) $this -> ErrorTracker(1, 'You can\'t use this method after instanciate ModeliXe with setModeliXe method, it will be without effects.', 'SetMxCachePath', __FILE__, __LINE__);
//       else 
//       {
//          if ($arg[strlen($arg) - 1] != '/') $arg .= '/';
//          if (! is_dir($arg) && $arg != '') $this -> ErrorTracker(5, 'The MxCachePath (<b>'.$arg.'</b>) is not a directory.', 'SetMxCachePath', __FILE__, __LINE__);
//          elseif ($arg) $this -> mXCachePath = $arg;
//       }

      return $this -> mXCachePath;
	
   }

   //Instanciation du d�lai de cache
   function SetMxCacheDelay($arg){
      if ($this -> mXsetting) $this -> ErrorTracker(1, 'You can\'t use this method after instanciate ModeliXe with setModeliXe method, it will be without effects.', 'SetMxCachePath', __FILE__, __LINE__);
      elseif ($arg >= 0) $this -> mXCacheDelay = (integer)$arg;

      return $this -> mXCacheDelay;
   }

   //Instanciation des param�tres de session
   function SetMxSession($arg){
      $this -> sessionParameter = $arg;
   }

   //Setting tools -----------------------------------------------------------------------------------------------

   //Recherche du fichier de template
   function GetMxFile($source = ''){
      if (! $source) $source = $this -> mXTemplatePath.$this -> template;

      if (! $read = @fopen($source, 'rb')) $this -> ErrorTracker (3, 'Can\'t open this template file (<b>'.$source.'</b>) in read, see for change the read modalities.', 'GetMxFile', __FILE__, __LINE__);
      else {

         if (! $result = @fread($read, filesize($source)))  $this -> ErrorTracker (3, 'Can\'t read the template file (<b>'.$source.'</b>), see for file format and integrity.', 'GetMxFile', __FILE__, __LINE__);
         fclose($read);
      }

      if (empty($result)) $result = '[no parsing, template file not found or invalid]';
      if ($this -> mXsignature && $source != $this -> mXTemplatePath.$this -> template) $result = "\n<!--[ModeliXe ".$this -> mXVersion.']-- [StartOf'.$dyn.'Inclusion : '.$source."] -->\n\n".$result."\n\n<!--[ModeliXe ".$this -> mXVersion.']-- [EndOf'.$dyn.'Inclusion : '.$source."] -->\n";

      //Affectation du path d'origine, et du content du template
      if ($source == $this -> mXTemplatePath.$this -> template) $this -> templateContent[$this -> absolutePath] = $result;
      else return $result;
   }

   //Lecture du fichier de configuration et parsing
   function GetParameterParsing ($template){
      $ligne = '';
      $signal = '';

      if (! $read = @fopen($this -> mXParameterFile, 'r')) $this -> ErrorTracker(4, 'The mXParameterFile (<b>'.$this -> mXParameterFile.'</b>) can\'t be open, the first parsing can\'t be do.', 'GetParameterParsing', __FILE__, __LINE__);
      for ($multi = false; !feof($read) && $this -> ErrorChecker(); $ligne = trim(@fgets($read, 1200))){
         if (strlen($ligne)) {
            if ($ligne[0] == '#' && $ligne[1] != '#'){

               //Changement d'�tat pour les param�tres
               switch(strtolower($ligne)){
                  case '#flag'   :
                     $signal = 'flag';
                     break;
                  case '#attribut' :
                     $signal = 'attribut';
                     break;
                  default :
                     $this -> ErrorTracker(3, '<b>'.$ligne.' </b> is not a valid section parameter type', 'GetParameterParsing', __FILE__, __LINE__);
                     break;
               }
            }
            else {
               if($ligne[0] == '#') $ligne = substr($ligne,1);
               if (! $multi){
                  $keyC = chop(substr($ligne, 0, strpos($ligne, '=') - 1));

                  //Gestion du multiligne, d�but d'une valeur sur plusieurs lignes
                  if (($content = ltrim(substr($ligne, strpos($ligne, '=') + 1))) && substr($content, 0, 3) == '"""') {
                     $multi = true;
                     $content = substr($content, 3);
                  }
               }

               //Gestion du multiligne, fin d'une valeur sur plusieurs lignes
               else {
                  if (substr($ligne, strlen($ligne) - 3) == '"""') {
                     $multi = false;
                     $content .= ' '.substr($ligne, 0, strpos($ligne, '"""'));
                  }
                  else $content .= ' '.$ligne;
               }

               //Si nous ne sommes pas dans une valeur sur plusieurs lignes (valeur compl�te)
               if (! $multi){
                  switch ($this -> flagSystem){
                     case 'xml':
                        $flagRegexp = '<mx:preformating id="'.$keyC.'"/>';
                        $attRegexp = 'mXpreformating="'.$keyC.'"';
                        break;
                     case 'classical':
                        $flagRegexp = '{preformating id="'.$keyC.'"}';
                        $attRegexp = '{preformatingAtt id="'.$keyC.'"}';
                        break;
                  }

                  if ($signal == 'flag') $template = str_replace($flagRegexp, $content, $template);
                  if ($signal == 'attribut') $template = str_replace($attRegexp, $content, $template);
               }
            }
         }
      }
      if ($read) @fclose($read);

      return $template;
   }

   //MX Builder-----------------------------------------------------------------------------------------
   function MxBloc($index, $mod, $value = ''){
      $mod = substr(strtolower($mod), 0, 4);

      if ($this -> adressSystem == 'relative') {
         if ($index) $index = $this -> relativePath.'.'.$index;
         else $index = $this -> relativePath;
      }
      else $index = $this -> absolutePath.'.'.$index;

      $fat = $this -> father[$index];
      if (! $fat && $index != $this -> absolutePath) $this -> ErrorTracker(2, 'The current path (<b>'.$index.'</b>) does not exist, or was deleting, him or his father, before.', 'MxBloc', __FILE__, __LINE__);

      switch ($mod){
         //Looping
         case 'loop':
            $this -> MxLoopBuilder($index);
            break;
            //Deleting
         case 'dele':
            $this -> sheetBuilding[$index] = '   ';
            $this -> loop[$index] = '';
            $this -> deleted[$index] = true;
            break;
            //Concatenating
         case 'appe':
            if (@is_file($value)) $value = $this -> GetMxFile($value);
            $this -> templateContent[$index] .= $value;
            $this -> MxParsing($value, $index, $this -> father[$index]);
            break;
            //Replacing
         case 'repl':
            if (@is_file($value)) $value = $this -> GetMxFile($value);
            $this -> sheetBuilding[$index] = $value;
            $this -> replacement[$index] = true;
            break;
            //Modify template references of this bloc
         case 'modi':
            $this -> sheetBuilding[$index] = '';
            $this -> loop[$index] = '';
            if (@is_file($value)) $value = $this -> GetMxFile($value);
            $this -> templateContent[$index] = $value;
            $this -> MxParsing($value, $index, $this -> father[$index]);
            break;
            //Reset, destroy all references
         case 'rese':
            $this -> sheetBuilding[$index] = '';
            $this -> loop[$index] = '';
            $this -> templateContent[$index] = '';
            $ind = substr($index, strrpos($index, '.') + 1);
            $this -> templateContent[$fat] = str_replace('<mx:inclusion id="'.$ind.'"/>', '', $this -> templateContent[$fat]);
            $this -> deleted[$index] = true;
            $this -> xPattern['inclusion'][$index] = '';
            break;
      }
   }

   function MxFormField($index, $type, $name, $value, $attribut = ''){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $end = $this -> outputSystem;

      switch (strtolower($type)){
         case 'text':
            $replace = '<input type="text" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'password':
            $replace = '<input type="password" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'textarea':
            $replace = '<textarea name="'.$name.'" '.$attribut.' '.$this -> htmlAtt[$index].' >'.$value.'</textarea>';
            break;
         case 'file':
            $replace = '<input type="file" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'submit':
            $replace = '<input type="submit" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'reset':
            $replace = '<input type="reset" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'button':
            $replace = '<input type="button" name="'.$name.'" value="'.$value.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         case 'image':
            $replace = '<input type="image" name="'.$name.'" '.$attribut.' '.$this -> htmlAtt[$index].$end;
            break;
         default:
            $this -> ErrorTracker(3, 'This type (<b>'.$type.'</b>) is unknown for this formField manager.', 'MxFormField', __FILE__, __LINE__);
      }

      $this -> formField[$index] = $replace;
   }

   function MxImage($index, $imag, $title = '', $attribut = '', $size = false){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $end = $this -> outputSystem;

      if (($ima = '<img src="'.$imag.'"') && ! $size) {
         $size = @getimagesize($imag);
         $ima .= ' '.$size[3];
      }

      if ($title == 'no') $ima .= ' ';
      elseif ($title) $ima .= ' alt="'.$title.'" ';
      else $ima .= ' alt="no title - source : '.basename($imag).'" ';

      if ($attribut) $ima .= $attribut;
      $ima .= ' '.$this -> htmlAtt[$index].$end;

      $this -> image[$index] = $ima;
   }

   function MxText($index, $att){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      $this -> text[$index] = $att;
   }

   function MxAttribut($index, $att){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $marqueur = '';
                
      //Gestion des mailto et des javascripts dans les href
      if (strtolower($this -> attributKey[$index]) == 'mailto' || strtolower($this -> attributKey[$index]) == 'javascript') $marqueur = ' href="';

      //Gestion multi-attributs
      if (! ((isset($this -> attribut[$index]))? chop($this -> attribut[$index]): false) ) {
         if ($marqueur) $this -> attribut[$index] = $marqueur.$this -> attributKey[$index].':'.$att.'"';
         else $this -> attribut[$index] = $this -> attributKey[$index].'="'.$att.'"';
      }
      else {
         if (empty($this -> attribut[$this -> attribut[$index]])) {
            if ($marqueur) $this -> attribut[$this -> attribut[$index]] = ' '.$marqueur.$this -> attributKey[$index].':'.$att.'"';
            else $this -> attribut[$this -> attribut[$index]] = ' '.$this -> attributKey[$index].'="'.$att.'"';
         }
         else {
            if ($marqueur) $this -> attribut[$this -> attribut[$index]] .= $marqueur.$this -> attributKey[$index].':'.$att.'"';
            else $this -> attribut[$this -> attribut[$index]] .= ' '.$this -> attributKey[$index].'="'.$att.'"';
         }
      }
   }

   function MxSelect($index, $name, $value, $arrayArg, $defaut = '', $multiple = '', $javascript = '', $styles='',$class='') {
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $sel = '';
      $post = '';
      if ($styles == '')
         $styles = array();
      if ($class=='')
         $class = array();

      if ($multiple && $multiple > 0) {
         $attribut = 'size="'.$multiple.'" multiple="multiple" ';
//          if (!ereg('\[\]$',$name))
//             $post = '[]';
//          $post='';
      }
      else {
         $attribut = '';
//          $post = '';
      }

      //Build of a select tag from an array
      if (is_array($arrayArg)){
         $sel = "\n".'<select name="'.$name.$post.'" id="'.$name.$post.'" ';
         if ($attribut) $sel .= $attribut.' ';
         if ($javascript) $sel .= $javascript;
         $sel .= ' '.$this -> htmlAtt[$index].' '.">\n";

         if (isset($defaut) && $defaut) $sel .= "\t".'<option value="#">'.$defaut.'</option>'."\n";

         $debut = 0;
         $fin = count($arrayArg);

         reset($arrayArg);
//                $Astyle='';
//                $Aclass='';
         while (list($cle, $Avalue) = each($arrayArg)){
            $test = 0;

//             if (array_key_exists ($cle, $styles))
//                $Astyle = ' style="'.$styles[$cle].'"';
//             else
//                $Astyle='';

//             if (array_key_exists ($cle, $class))
//                $Aclass = ' class="'.$class[$cle].'"';
//             else
//                $Aclass='';

            //Build of multiple choice select from a value array
            if (is_array($value) && $multiple > 0){
               reset($value);
               while (list($Vcle, $Vvalue) = each($value)){

                  if ($cle == $Vvalue && $Vvalue != '') {
                     $sel .= "\t".'<option value="'.$cle.'" selected="selected"'.'>'.$Avalue.'</option>'."\n";
                     $test = 1;
                     break;
                  }
               }
               if ($test == 0) $sel .= "\t".'<option value="'.$cle.'"'.'>'.$Avalue.'</option>'."\n";
            }

            //Simple select
            else {
               if ($value != '' && $cle == $value) $sel .= "\t".'<option value="'.$cle.'" selected="selected"'.'>'.$Avalue.'</option>'."\n";
               else $sel .= "\t".'<option value="'.$cle.'"'.'>'.$Avalue.'</option>'."\n";
            }
         }
      }
      else {
         $this -> ErrorTracker(2, 'This function need an Array in fourth argument to build the select <b>'.$index.'</b>.', 'MxSelect', __FILE__, __LINE__);
         $sel = '<select name="'.$name.'">'."\n\t".'<option value="null">No record found</option>'."\n";
      }

      $sel .= '</select>';

      $this -> select[$index] = $sel;
   }

   function MxUrl($index, $urlArg, $param = '', $noSid = false, $attribut = '') {
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      $ok = false;
                
      //Ajout des param�tres de sessions en cas de cache ou non
      if ($this -> sessionParameter && ! $noSid) {
         if ($this -> mXCacheDelay > 0) $urlArg .= '?<mx:session />';
         else $urlArg .= '?'.$this -> sessionParameter;

         $ok = true;
      }
                
      //Construction du lien
      if (is_string($param) && $param) {
         $param = explode('&',$param);
         for($i = 0; $i < count($param) && $param[$i]; $i++){
            $cle = explode('=', $param[$i]);
            if (! $this -> mXmodRewrite) $urlArg .= ($i == 0 && !$ok) ? '?'.urlencode($cle[0]).'='.urlencode($cle[1]) : '&'.urlencode($cle[0]).'='.urlencode($cle[1]);
            else $urlArg .= '/'.urlencode($cle[0]).'/'.urlencode($cle[1]);
         }
      }
      elseif (is_array($param)){
         reset($param);
         if ($this->mXmodRewrite) {
            while (list($cle, $valeur) = each($param)) {
               $urlArg .= '/'.urlencode($cle).'/'.urlencode($valeur);
            }
         }
         else {
            while (list($cle, $valeur) = each($param)) {
               if (!$ok) {
                  $urlArg .= '?'.urlencode($cle).'='.urlencode($valeur);
                  $ok = true;
               }
               else $urlArg .= '&'.urlencode($cle).'='.urlencode($valeur);
            }
         }
      }
      elseif ($param) $this -> ErrorTracker(3, 'The third argument must be a queryString or an array.', 'MxUrl', __FILE__, __LINE__);
                
      //Ajout d'�ventuels attributs suppl�mentaires en dynamique
      $lien = ($attribut)? ' href="'.$urlArg.'" '.$attribut : ' href="'.$urlArg.'"';
                
      //Gestion multi-attributs
      if (! ((isset($this -> attribut[$index]))? chop($this -> attribut[$index]): false)) $this -> attribut[$index] = ' href="'.$urlArg.'"';
      else {
         if (empty($this -> attribut[$this -> attribut[$index]])) $this -> attribut[$this -> attribut[$index]] = ' href="'.$urlArg.'"';
         else $this -> attribut[$this -> attribut[$index]] .= ' href="'.$urlArg.'"';
      }
   }

   function MxHidden ($index, $param){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $end = $this -> outputSystem;

      $hidden = '';

      if ($this -> mXCacheDelay == 0 && $this -> sessionParameter) $param .= '&'.$this -> sessionParameter;

      if (is_string($param)) $param = explode('&',$param);
      else $this -> ErrorTracker(3,'The second argument must be a queryString.',  'MxHidden', __FILE__, __LINE__);

      if (! empty($param)){
         for($i = 0; $i < count($param); $i++){
            if ($param[$i]) {
               $cle = explode('=', $param[$i]);
               $hidden .= '<input type="hidden" name="'.$cle[0].'" value="'.$cle[1].'" '.$end."\n";
            }
         }
      }

      if ($this -> mXCacheDelay > 0) $hidden .= '<mx:hiddenSession />';

      $this -> hidden[$index] = $hidden;
   }

   function MxCheckerField($index, $type, $name, $value, $checked = false, $attribut = ''){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;
      $end = $this -> outputSystem;

      $type = strtolower($type);
      if ($type != "checkbox" && $type != "radio") $this -> ErrorTracker(2, 'This type (<b>'.$type.'</b>) is unknown for this CheckerField manager.', 'MxCheckerField', __FILE__, __LINE__);

      $replace = '<input type="'.$type.'" name="'.$name.'" id="'.$name.'" value="'.$value.'"';
      if ( $checked ) $replace .= ' checked="checked"';
      if ($attribut) $replace .= ' '.$attribut;
      $replace .= ' '.$this -> htmlAtt[$index].$end;

      $this -> checker[$index] = $replace;
   }

   //MX Extender----------------------------------------------------------------------------------------

   function AddMxPlugIn($name, $type, $fonction){

      if (! $name) $this -> ErrorTracker(2,  'You must give a name to identify the plug-in.', 'AddMxPlugIn',__FILE__, __LINE__);
      if (! $type) $this -> ErrorTracker(2, 'The type of the plug-in is necessary to instanciate it.', 'AddMxPlugIn', __FILE__, __LINE__);
      if (! $fonction || ! function_exists($fonction)) $this -> ErrorManager(3, 'The method addMxPlugin need the name of a function in third argument.', 'AddMxPlugIn',__FILE__, __LINE__);

      if ($this -> ErrorChecker()){
         switch ($type){
            case 'flag':
               for ($i = 0; $i < count($this -> flagArray); $i++){
                  if ($name == $this -> flagArray[$i]) $this -> ErrorTracker(2, 'This plug-in (<b>'.$name.'</b>) has got the same pattern as the native pattern of a ModeliXe flag.', 'AddMxPlugIn', __FILE__, __LINE__);
               }
               $this -> flagArray[count($this -> flagArray)] = $name;
               break;
            case 'attribut':
               for ($i = 0; $i < count($this -> attributArray); $i++){
                  if ($name == $this -> attributArray[$i]) $this -> ErrorTracker(2, 'This plug-in (<b>'.$name.'</b>) has got the same pattern as the native pattern of a ModeliXe attribut.', 'AddMxPlugIn', __FILE__, __LINE__);
               }
               $this -> attributArray[count($this -> attributArray)] = $name;
               break;
            default :
               $this -> ErrorTracker(2, 'This type of plug-in (<b>'.$type.'</b>) is unrecognized.', 'AddMxPlugIn', __FILE__, __LINE__);
         }
      }

      $this -> $name = array();
      $this -> plugInMethods[$name] = $fonction;
   }

   function SetMxPlugIn($name, $index, $arguments){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      if (isset($arguments) && ! is_array($arguments)) $this -> ErrorTracker(2, 'You must give an array of arguments for the plug-in function.', 'SetMxPlugIn', __FILE__, __LINE__);
      else {
         $tab = &$this -> $name;
         $tab[$index] = call_user_func($this -> plugInMethods[$name], $arguments);
      }
   }

   //MX tools -------------------------------------------------------------------------------------------------------------------

   //V�rifie l'existence d'un bloc
   function IsMxBloc($index){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      $fat = $this -> father[$index];
      if (! $fat && $index != $this -> absolutePath) return false;
      else return true;
   }

   //V�rifie l'existence d'une balise
   function IsMxFlag($index, $type){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      $tab = $this -> $type;
      if (! isset($tab[$index])) return false;
      else return $tab[$index];
   }

   //V�rifie l'existence d'un attribut
   function IsMxAttribut($index){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      if (! isset($this -> attributKey[$index])) return false;
      else {
         if (preg_match('/;/', $this -> attribut[$index])) return $this -> attribut[$this -> attribut[$index]];
         else return $this -> attribut[$index];
      }
   }

   //Retourne tout le contenu d'un bloc
   function GetMxBloc($index){
      if ($this -> adressSystem == 'relative') $index = $this -> relativePath.'.'.$index;

      if ($this -> sheetBuilding[$index]) return $this -> sheetBuilding[$index];
      else  return $this -> templateContent[$index];
   }

   //Construction d'une queryString
   function GetQueryString($keyString, $null = 1){
      $queryString = array();

      if (is_array($keyString)){
         reset($keyString);

         while (list($Akey, $value) = each($keyString)){
            if (is_array($value)) {
               while (list($k, $v) = each($value)) {
                  array_push($queryString, urlencode($Akey.'['.$k.']').'='.urlencode($v));
               }
            }
            elseif ($null || strlen($value)) array_push($queryString, urlencode($Akey).'='.urlencode($value));
         }
         return implode('&',$queryString);
      }
      else $this -> ErrorTracker(3, 'The argument for this function must be an associative array.', 'GetQueryString', __FILE__, __LINE__);
   }

   //Adressage simplifi�
   function WithMxPath($path = '', $origine = ''){

      if (! $origine) $origine = $this -> adressSystem;
      else {
         switch($origine){
            case 'relative':
               break;
            case 'absolute':
               break;
            default:
               $origine = 'relative';
               break;
         }
      }

      //Si on ne pr�cise pas de path on retourne au path origine
      if (empty($path)){
         $this -> relativePath = $this -> absolutePath;

         if ($origine == 'absolute') $this -> adressSystem = 'absolute';
         elseif ($origine == 'relative') $this -> adressSystem = 'relative';
      }

      //Sinon, en absolu on se situe dans ce path, en relatif on se situe par rapport au path relatif
      if ($path) {
         if ($origine == 'relative') {

            //On redescend dans la hi�rarchie jusqu'au path mentionn�
            if (($test = explode('../', $path)) && count($test) > 1) {
               $path = substr($path, strrpos($path, '/') + 1);
               $this -> relativePath = substr($this -> relativePath, 0, strlen($this -> relativePath) - strlen(strstr($this -> relativePath, $path)) - 1);
               if (! $this -> relativePath) $this -> ErrorTracker(3, 'This path (<b>'.$path.'</b>) does not exist, ModeliXe can\'t build relativePath.', 'WithMxPath', __FILE__, __LINE__);
            }

            $this -> relativePath .= '.'.$path;

            $this -> adressSystem = 'relative';
         }
         elseif ($origine == 'absolute') {
            $this -> relativePath = $path;
            $this -> adressSystem = 'absolute';
         }
      }
   }

   //Informations de licence
   function AboutModeliXe($out = ''){
      $texte = "\nLicence et conditions d'utilisations-----------------------------------------------------------------------------\n";
      $texte .= 'ModeliXe '.$this -> mXVersion."\nModeliXe est distribu� sous licence LGPL, merci de laisser cette en-t�te, gage et garantie de cette licence.\n";
      $texte .= "ModeliXe est un moteur de template destin� � �tre utilis� par des applications �crites en PHP.\n";
      $texte .= " \n";
      $texte .= "Copyright(c) 26 Juin 2001 - ANDRE Thierry (aka Th�o)\n";
      $texte .= " \n";
      $texte .= "Pour tout renseignements mailez � modelixe@free.fr ou thierry.andre@freesbee.fr\n";
      $texte .= "------------------------------------------------------------------------------------------------------------------\n";

      if ($out) return $texte;
      else print('<pre>'.$texte.'</pre>');
   }

   //Num�ro de version
   function GetMxVersion(){
      return $this -> mXVersion;
   }

   //Rafraichissement
   function MxRefresh($query = ''){
      $this -> MxClearCache('this', $query);
   }

   function MxRefreshAll()  {
      $this -> MxClearCache();
   }
                
   //Mesure de performances
   function GetExecutionTime(){
      $time = explode(' ',microtime());
      $fin = $time[1] + $time[0];
      $this -> ExecutionTime = intval(10000 * ((double)$fin - (double)$this -> debut)) / 10000;

      return($this -> ExecutionTime);
   }

   //MX Parsing Engine------------------------------------------------------------------------------------------------------------

   function MxParsing($doc = '', $path = '', $father = ''){
      $countPath = Array();

      //Initialisation
      if (! $path) {
         $original = true;
         $path = $this -> absolutePath;
      }
      else $original = false;

      $this -> father[$path] = $father;
      $this -> IsALoop[$path] = false;

      //Parsing des balises de bloc, extraction des sous blocs
      $ok = true;

      switch ($this -> flagSystem){
         case 'xml':
            $blocRegexp = '/<mx:bloc(?:[ ]+ref="([^"]+)")?[ ]+id="([^"]+)"[ ]*>/S';
            break;
         case 'classical':
            $blocRegexp = '/{start(?:[ ]+ref="([^"]+)")?[ ]+id="([^"]+)"[ ]*}/S';
            break;
      }

      if (preg_match_all($blocRegexp, $doc, $inclusion)){

         for($i = 0; $ok; $i++){

            //Extraction des diff�rentes informations extraites par la regex
            $id = $inclusion[2][0];
            $ref = $inclusion[1][0];
            $pattern = $inclusion[0][0];

            //Calcul des limites du bloc trait�
            switch ($this -> flagSystem){
               case 'xml':
                  $regexp = '</mx:bloc id="'.$id.'">';
                  break;
               case 'classical':
                  $regexp = '{end id="'.$id.'"}';
                  break;
            }

            $startOfIntrons = strpos($doc, $pattern) + strlen($pattern);
            $endOfIntrons = strpos($doc, $regexp);
            $length = $endOfIntrons - $startOfIntrons;

            if (! $endOfIntrons) $this -> ErrorTracker(4, 'The end of the "<b>'.$id.'</b>" bloc is not found, this bloc can\'t be generate. Verify that the end of bloc\'s flag exists and has a good form, like this pattern <b>'.htmlentities($regexp).'</b>.', 'MxParsing', __FILE__, __LINE__);

            //On teste si le bloc en cours poss�de une r�f�rence vers un autre template
            if (! $ref) $this -> templateContent[$path.'.'.$id] = substr($doc, $startOfIntrons, $length);
            else {
               if ($this -> mXTemplatePath) $ref = $this -> mXTemplatePath.$ref;
               $this -> templateContent[$path.'.'.$id] = $this -> GetMxFile($ref);
            }

            //Cr�ation du pattern du bloc trait�
            $this -> xPattern['inclusion'][$path.'.'.$id] = '<mx:inclusion id="'.$id.'"/>';
            $this -> deleted[$path.'.'.$id] = false;
            $this -> replacement[$path.'.'.$id] = false;

            //Extraction du contenu du bloc pour reconstruire le bloc en cours
            $doc = substr($doc, 0, $startOfIntrons - strlen($pattern)).'<mx:inclusion id="'.$id.'"/>'.substr($doc, $endOfIntrons + strlen($regexp));
            $this -> templateContent[$path] = $doc;

            //Construction de la r�f�rence � ce bloc pour la r�cursivit�
            $countPath[$i] = $path.'.'.$id;

            //Incr�mentation du nbre de fils pour le bloc en cours
            if (! empty($this -> son[$path][0])) $compt = $this -> son[$path][0];
            else {
               $compt = 0;
               $this -> son[$path][0] = 0;
            }

            //Construction de la r�f�rence au fils du bloc pars� pour le bloc en cours
            $this -> son[$path][++ $compt] = $path.'.'.$id;
            $this -> son[$path][0] ++;

            //Test de fin de boucle
            $ok = preg_match_all($blocRegexp, $doc, $inclusion);
         }
      }

      //Parsing des balises ModeliXe
      reset($this -> flagArray);
      while (list($Akey, $value) = each($this -> flagArray)){

         switch ($this -> flagSystem){
            case 'xml':
               $regexp = '/<mx:'.$value.'(?:[ ]+(?:ref|info)="(?:[^"]+)")?[ ]+id="([^"]+)"(([^>])*(?=\/>))\/>/S';
               break;
            case 'classical':
               $regexp = '/{'.$value.'(?:[ ]+(?:ref|info)="(?:[^"]+)")?[ ]+id="([^"]+)"[ ]*(?i:htmlAtt\[([^\]]*)\])?}/S';
               break;
         }

         if (preg_match_all($regexp, $doc, $flag)){
            for ($i = 0; ; $i++){
               if (empty ($flag[0][$i])) break;

               //Construction du pattern et des valeurs par d�faut de ces balises
               $this -> xPattern[$value][$path.'.'.$flag[1][$i]] = $flag[0][$i];

               //Modification Guillaume Lelarge compatibilit� PHP3
/*<PHP3>
 $ref = $this -> $value;
 $ref[$path.'.'.$flag[1][$i]] = '   ';
 $this -> $value = $ref;
 $this -> htmlAtt[$path.'.'.$flag[1][$i]] = $flag[2][$i];
 </PHP3>*/

               $ref = &$this -> $value;
               $ref[$path.'.'.$flag[1][$i]] = '   ';
               $this -> htmlAtt[$path.'.'.$flag[1][$i]] = $flag[2][$i];
            }
         }
      }

      //Parsing des attributs de ModeliXe
      switch ($this -> flagSystem){
         case 'xml':
            $regexp = '/mXattribut="([^"]{3,})"/Si';
            $separateur = ':';
            break;
         case 'classical':
            $regexp = '/{attribut ([^\}]+)}/Si';
            $separateur = '=';
            break;
      }

      if (preg_match_all($regexp, $doc, $flag)){
         for ($i = 0, $k = 0; ; $i++){

            if (empty($flag[0][$i])) break;

            $pattern = $flag[0][$i];
            $motif = $flag[1][$i];
            $k = 0;

            //Gestion de plusieurs couples de cl�-valeurs dans les attributs
            $tabVal = explode(';', $motif);
            for ($j = 0; $j < count($tabVal); $j++) {

               $tabCle = explode($separateur, trim($tabVal[$j]));
               $patternKey[++ $k] = trim($tabCle[0]);
               $indexValue[$k] = trim($tabCle[1]);

               //Gestion multi-attributs
               if (count($tabVal) > 1) {
                  $this -> attribut[$path.'.'.$indexValue[$k]] = $path.'.'.$indexValue[1].';';
                  if ($k == 1) $this -> xPattern['attribut'][$path.'.'.$indexValue[1].';'] = $pattern;
               }
               else {
                  $this -> attribut[$path.'.'.$indexValue[$k]] = '  ';
                  $this -> xPattern['attribut'][$path.'.'.$indexValue[$k]] = $pattern;
               }

               if ($patternKey[$k] != 'url') $this -> attributKey[$path.'.'.$indexValue[$k]] = $patternKey[$k];
            }
         }
      }


      for ($i = 0; $i < count($countPath); $i++) $this -> MxParsing($this -> templateContent[$countPath[$i]], $countPath[$i], $path);
   }

   //MX Compression System ------------------------------------------------------------------------------------------------------------------

   //V�rifie si la compression est possible et son type
   function MxCheckCompress($file){
      if((! $this -> mXcompress) || (! extension_loaded("zlib")) || (headers_sent()) || (strlen($file) / 1000 < 8)) return false;
      global $HTTP_SERVER_VARS;

      if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'x-gzip'))  return "x-gzip";
      if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip'))  return "gzip";

      return false;
   }

   //Compression des donnees destin�es au navigateur
   function MxSetCompress($filecontent){
      if ($encoding = $this -> MxCheckCompress($filecontent)){

         header('Content-Encoding: '.$encoding);
         $gzfilecontent =  "\x1f\x8b\x08\x00\x00\x00\x00\x00";
         $size = strlen($filecontent);
         $crc32 = crc32($filecontent);
         $gzfilecontent .= gzcompress($filecontent, 9);
         $gzfilecontent = substr($gzfilecontent, 0, strlen($gzfilecontent) - 4);
         $gzfilecontent .= pack('V',$crc32);
         $gzfilecontent .= pack('V',$size);

         return $gzfilecontent;
      }
      else return $filecontent;
   }


   //MX Cache system ------------------------------------------------------------------------------------------------------------------

   //Retourne une cl� unique pour les arguments en POST et GET diff�rents des param�tres de session
   function GetMD5UrlKey($query = ''){
      global $HTTP_POST_VARS, $HTTP_GET_VARS;

      if (! $query) $get = $HTTP_GET_VARS;
      else $get = $query;
      if (! $query) $post = $HTTP_POST_VARS;
      else $post = $query;
                
      //Int�gration du nom du fichier php appelant
      $uri = getenv('REQUEST_URI');
      $uri = substr($uri, 0, strpos($uri, '?'));
                
      //Suppression des param�tres de session en GET
      $chaine = '';
      $this -> DeleteSessionKey($chaine, $get);

      //Suppression des param�tres de session en POST
      $this -> DeleteSessionKey($chaine, $post);

      return (md5($chaine.$uri));
   }

   function DeleteSessionKey(&$chaine, $httpvar){
      $param = explode('&', $this -> sessionParameter);

      //Tri des tableaux pour les avoir dans le m�me ordre
      asort($httpvar);
      asort($param);

      //pr est un marqueur pour �viter de parcourir toutes les valeurs des param de session si ceux-ci ont d�ja �t� tous supprim�s
      $pr = 0;

      //suppression des param�tres de session(get ou post)
      for (reset($httpvar); $cle = key($httpvar); next($httpvar)){
         $ok = false;
         $compt = count($param);
         for ($i = 0; $i < $compt && ($pr != $compt); $i++){
            if (($cleU = explode('=', $param[$i])) && $cleU[0] == $cle) {
               $ok = true;
               $pr ++;
               break;
            }
         }
         if (! $ok) $chaine .= $cle.'='.$httpvar[$cle];
      }
   }

   //Vidage du cache
   function MxClearCache($fich = '', $query = ''){
      if (! $open = @opendir($this -> mXCachePath)) $this -> ErrorTracker(3, 'Can\'t open cache directory (<b>'.$this -> mXCachePath.'</b>) to clear old files.', 'MxClearCache', __FILE__, __LINE__);
      else {
         while ($fichier = @readdir($open)){

            if ($fichier != '.' && $fichier != '..'){
               if (! $fich){
                  if (($currentTime = filemtime($this -> mXCachePath.$fichier)) && time() - $currentTime > $this -> mXCacheDelay) {
                     if (! @unlink($this -> mXCachePath.$fichier)) $this -> ErrorTracker(3, 'Can\'t unlink this file "<b>'.$fichier.'</b>" in cache directory.', 'MxClearCache', __FILE__, __LINE__);
                  }
               }
               else {
                  //Supprime sp�cifiquement le fichier du template en cours
                  $ana = explode('~', $fichier);
                  if ($ana[1] == $this -> template) {

                     //Gestion des suppressions sp�cifiques � une queryString
                     if ($query && $this -> GetMD5UrlKey($query) == $ana[0]){
                        if (! @unlink($this -> mXCachePath.$fichier)) $this -> ErrorTracker(3, 'Can\'t unlink this file "<b>'.$fichier.'</b>" in cache directory.', 'MxClearCache', __FILE__, __LINE__);
                     }
                     /*elseif (! @unlink($this -> mXCachePath.$fichier)) $this -> ErrorTracker(3, 'Can\'t unlink this file "<b>'.$fichier.'</b>" in cache directory.', 'MxClearCache', __FILE__, __LINE__);*/
                  }
               }
            }
         }

         @closedir($open);
      }
   }

   //Initialisation du cache
   function MxSetCache($filecontent) {

      $this -> MxClearCache('this');

      if (! $cache = fopen($this -> mXCachePath.$this -> mXUrlKey.'~'.$this -> template, 'w')) $this -> ErrorTracker(4, 'Can\'t open in writing the cache file on "<b>'.$this -> mXCachePath.'/'.$this -> template.'</b>" path.', 'MxSetCache', __FILE__, __LINE__);

      //Sauvegarde du contenu
      if ($this -> ErrorChecker()) {
         if (! $write = fputs($cache, $filecontent)) $this -> ErrorTracker(5, 'Can\'t wite the cache file on "<b>'.$this -> mXCachePath.$this -> mXUrlKey.'~'.$this -> template.'</b>" path.', 'MxSetCache', __FILE__, __LINE__);
         @fclose($cache);
      }
   }

   //Retourne le fichier de cache
   function MxGetCache() {
      $cache_file = $this -> mXCachePath.$this -> mXUrlKey.'~'.$this -> template;

      if (! $open = @fopen($cache_file, 'rb')) $this -> ErrorTracker(5, 'Can\'t open the cache file on "<b>'.$cache_file.'</b>" path.', 'MxGetCache');
      if (! $read = @fread($open, filesize($cache_file))) $this -> ErrorTracker(5, 'Can\'t read the cache file on "<b>'.$cache_file.'</b>" path.', 'MxGetCache', __FILE__, __LINE__);

      @fclose($open);

      //Parsing des param�tres de sessions
      $read = $this -> MxSessionParameterParsing($read);

      //Si on cherche � mesurer les performances de ModeliXe
      if ($this -> performanceTracer) {
         $read = str_replace('<mx:performanceTracer />', $this -> GetExecutionTime().' [cache]', $read);
      }

      //Si il y a une gestion de la compression, envoie des en-t�tes correspondantes
      $this -> ErrorChecker();
      if ($this -> mXoutput) return $read;
      else print($this -> MxSetCompress($read));

      die();
   }

   //Teste si le fichier de cache existe et son �ch�ance
   function MxCheckCache() {
      $cache_file = $this -> mXCachePath.$this -> mXUrlKey.'~'.$this -> template;

      if (@is_file($cache_file)){
         if (($currentTime = filemtime($cache_file)) && (((time() - $currentTime) < $this -> mXCacheDelay && filemtime($this -> mXTemplatePath.$this -> template) < $currentTime))) return true;
      }
      else return false;
   }

   //MX Template Fusion Engine --------------------------------------------------------------------------------------------------
   function MxSessionParameterParsing($content) {
      $hidden = '';

      $param = $this -> sessionParameter;

      if ($param){
         $content = str_replace('<mx:session />', $param, $content);

         $param = explode('&', $this -> sessionParameter);
         for($i = 0; $i < count($param); $i++){
            if ($param[$i]) {
               $cle = explode('=', $param[$i]);
               $hidden .= '<input type="hidden" name="'.$cle[0].'" value="'.$cle[1].'" />'."\n";
            }
         }

         $content = str_replace('<mx:hiddenSession />', $hidden, $content);
      }

      return $content;
   }

   //Remplace le contenu des templates pass�s en arguments
   function MxReplace($path){

      if (! empty($this -> sheetBuilding[$path])) $cible = $this -> sheetBuilding[$path];
      else $cible = $this -> templateContent[$path];

      //Remplacement de l'ensemble des attributs ModeliXe par les valeurs qui ont �t� instanci�es ou leurs valeurs par d�faut
      reset($this -> attributArray);
      while (list($cle, $Fkey) = each($this -> attributArray)){
         $Farray = &$this -> $Fkey;

         if (is_array($Farray)){
            reset($Farray);

            while (list($Pkey, $value) = each($Farray)){

               if ($path == substr($Pkey, 0, strrpos($Pkey, '.'))) {
                  if (isset($this -> xPattern[$Fkey][$Pkey])){
                     $pattern = $this -> xPattern[$Fkey][$Pkey];
                     $cible = str_replace($pattern, $value, $cible);
                     unset($Farray[$Pkey]);
                  }
               }
            }
         }
      }

      //Remplacement de l'ensemble des balises ModeliXe par les valeurs qui ont �t� instanci�es ou leurs valeurs par d�faut
      reset($this -> flagArray);
      while (list($cle, $Fkey) = each($this -> flagArray)){
         $Farray = &$this -> $Fkey;

         if (is_array($Farray)){
            reset($Farray);

            while (list($Pkey, $value) = each($Farray)){
               if ($path == substr($Pkey, 0, strrpos($Pkey, '.'))) {
                  if (isset($this -> xPattern[$Fkey][$Pkey])){
                     $pattern = $this -> xPattern[$Fkey][$Pkey];
                     $cible = str_replace($pattern, $value, $cible);
                     unset($Farray[$Pkey]);
                  }
               }
            }
         }
      }
      return $cible;
   }

   //Construit les blocs et associe les blocs fils aux blocs parents
   function MxBlocBuilder($path = ''){
      $ordre = array();
      $hierarchie = 1;

      if (! $path) $path = $this -> absolutePath;
      $chemin = $path;

      //Classement de tout les fils de path du plus proche au plus lointain
      $base = count(explode('.', $path));
      $k = 1;
      $l = 1;
      $j = 1;

      for (; ;){

         //Si il existe un fils on le prend
         if (! empty($this -> son[$chemin][$j])) $fils = $this -> son[$chemin][$j];
         else $fils = '';

         //Si il existe on consid�re le dernier enregistrement trouv� pr�c�dant celui-ci
         if (! empty($ordre[$hierarchie])) $ancien = $ordre[$hierarchie][count($ordre[$hierarchie])];
         else $ancien = false;

         if ($fils == $ancien) break;

         //Si il n'y a plus de fils, on passe au noeud suivant
         if (empty($fils)) {
            $j = 1;

            if (! empty($ordre[$k][$l])) {
               $chemin = $ordre[$k][$l];
               $l ++;
            }
            else {
               $l = 1;
               $k ++;

               if (! empty($ordre[$k][$l])) $chemin = $ordre[$k][$l ++];
               else break;
            }
         }
         else {
            $j ++;

            //Si le fils n'a pas �t� d�truit on le consid�re
            if ($this -> templateContent[$fils]) {

               //hi�rarchie compte le nombre de blocs � partir du bloc de base
               $hierarchie = count(explode('.', $fils)) - $base;

               if (empty($ordre[$hierarchie])) $ordre[$hierarchie] = array();
               $ordre[$hierarchie][count($ordre[$hierarchie]) + 1] = $fils;
            }
         }
      }

      //Insertion des fils les plus lointains dans les fils les plus proches jusqu'au path
      for ($i = count($ordre); $i > 0; $i --){

         for ($j = 1; $j <= count($ordre[$i]); $j++){

            $fils = $ordre[$i][$j];
            $pattern = $this -> xPattern['inclusion'][$fils];
            $pere = $this -> father[$ordre[$i][$j]];

            //Insertion du bloc fils dans le p�re
            if ($pere == $path && $this -> IsALoop[$path]) {

               if ($this -> IsALoop[$fils]) {

                  if ($this -> deleted[$fils]) {
                     $rem = ' ';
                     $this -> deleted[$fils] = false;
                  }
                  else $rem = $this -> loop[$fils];

                  $this -> loop[$pere] = str_replace($pattern, $rem, $this -> loop[$pere]);
                  $this -> loop[$fils] = '';
               }
               else {

                  if ($this -> deleted[$fils]) {
                     $rem = ' ';
                     $this -> deleted[$fils] = false;
                  }
                  else $rem = $this -> MxReplace($fils);

                  $this -> loop[$pere] = str_replace($pattern, $rem, $this -> loop[$pere]);
                  $this -> sheetBuilding[$fils] = '';
               }
            }
            else {

               if (! empty($this -> sheetBuilding[$pere])) $source = $this -> sheetBuilding[$pere];
               else $source = $this -> templateContent[$pere];

               if ($this -> IsALoop[$fils]) {

                  if ($this -> deleted[$fils]) {
                     $rem = ' ';
                     $this -> deleted[$fils] = false;
                  }
                  else $rem = $this -> loop[$fils];

                  $this -> sheetBuilding[$pere] = str_replace($pattern, $rem, $source);
                  $this -> loop[$fils] = '';
               }
               else {

                  if ($this -> deleted[$fils]) {
                     $rem = ' ';
                     $this -> deleted[$fils] = false;
                  }
                  else $rem = $this -> MxReplace($fils);

                  $this -> sheetBuilding[$pere] = str_replace($pattern, $rem, $source);
                  $this -> sheetBuilding[$fils] = '';
               }
            }
                                
         }
      }
   }

   //Associe les boucles
   function MxLoopBuilder($path = ''){
      if (! $path) $path = $this -> absolutePath;

      $father = $this -> father[$path];
      $pattern = $this -> xPattern['inclusion'][$path];

      //On saute les blocs d�truits
      if ($pattern){
         $this -> IsALoop[$path] = true;
         if (empty($this -> loop[$path])) $this -> loop[$path] = '';

         //Gestion des blocs remplac�s temporairement
         if ($this -> replacement[$path]) {
            $this -> loop[$path] .= $this -> MxReplace($path);
            $this -> replacement[$path] = false;
            $this -> sheetBuilding[$path] = '';
         }

         //Gestion des boucles classiques
         else {
            $this -> sheetBuilding[$path] = '';
            if (empty($this -> loop[$path])) $this -> loop[$path] = '';
            $this -> loop[$path] .= $this -> MxReplace($path);
         }
      }

      //Insertion des fils de $path dans $path
      $this -> MxBlocBuilder($path);
   }

   //Mx Output -------------------------------------------------------------------------------------------------------------------

   //Sortie du fichier HTML g�n�r�
   function MxWrite ($out = ''){
      if (! $this -> mXsetting) $this -> ErrorTracker(5, 'You d\'ont intialize ModeliXe with setModeliXe method, there is no data to write.', 'MxWrite', __FILE__, __LINE__);
      if ($out) $this -> mXoutput = true;
      //Assemblage de l'ensemble des blocs fils
      $this -> MxBlocBuilder();

      if ($this -> mXsignature) $entete = '<!--[ModeliXe '.$this -> mXVersion.'] -- '.(($this -> isTemplateFile)? '[TemplateFile : '.$this -> mXTemplatePath.$this -> template.']' : '[Template : '.$this -> template.']').' -- [date '.date('j/m/Y H:i:s')."]-->\n";
      else $entete = '';

      if ($this -> ErrorChecker()) {
         $filecontent = (($entete)? str_replace('<head>', '<head>'."\n".$entete,$filecontent = $this -> MxReplace($this -> absolutePath)) : $filecontent = $this -> MxReplace($this -> absolutePath));

         //Remplacement des balises de param�tres
         if ($this -> mXParameterFile) $filecontent = $this -> GetParameterParsing($filecontent);

         //Mise en cache de la page g�n�r�e sans les param�tres de sessions
         if ($this -> mXCacheDelay > 0) {
            $this -> MxSetCache($filecontent);

            //Parsing des param�tres de sessions
            $filecontent = $this -> MxSessionParameterParsing($filecontent);
         }

         //Si on cherche � mesurer les performances de ModeliXe
         if ($this -> performanceTracer) {
            $filecontent = str_replace('<mx:performanceTracer />', $this -> GetExecutionTime(), $filecontent);
         }

         if ($this -> mXoutput) return $filecontent;
         else print($this -> MxSetCompress($filecontent));
      }
   }
}
?>