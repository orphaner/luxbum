<?php

class System {

   /**
    * The "which" command (show the full path of a command)
    *
    * @param string $program The command to search for
    * @param mixed  $fallback Value to return if $program is not found
    *
    * @return mixed A string with the full path or false if not found
    * @author Stig Bakken <ssb@php.net>
    */
   function which($program, $fallback = false)
   {
      // enforce API
      if (!is_string($program) || '' == $program) {
         return $fallback;
      }

      // available since 4.3.0RC2
      if (defined('PATH_SEPARATOR')) {
         $path_delim = PATH_SEPARATOR;
      } else {
         $path_delim = OS_WINDOWS ? ';' : ':';
      }
      // full path given
      if (basename($program) != $program) {
         $path_elements[] = dirname($program);
         $program = basename($program);
      } else {
         // Honor safe mode
         if (!ini_get('safe_mode') || !$path = ini_get('safe_mode_exec_dir')) {
            $path = getenv('PATH');
            if (!$path) {
               $path = getenv('Path'); // some OSes are just stupid enough to do this
            }
         }
         $path_elements = explode($path_delim, $path);
      }

      if (OS_WINDOWS) {
         $exe_suffixes = getenv('PATHEXT')
         ? explode($path_delim, getenv('PATHEXT'))
         : array('.exe','.bat','.cmd','.com');
         // allow passing a command.exe param
         if (strpos($program, '.') !== false) {
            array_unshift($exe_suffixes, '');
         }
         // is_executable() is not available on windows for PHP4
         $pear_is_executable = (function_exists('is_executable')) ? 'is_executable' : 'is_file';
      } else {
         $exe_suffixes = array('');
         $pear_is_executable = 'is_executable';
      }

      foreach ($exe_suffixes as $suff) {
         foreach ($path_elements as $dir) {
            $file = $dir . DIRECTORY_SEPARATOR . $program . $suff;
            if (@$pear_is_executable($file)) {
               return $file;
            }
         }
      }
      return $fallback;
   }
}

?>