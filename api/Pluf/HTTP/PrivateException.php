<?php

class Pluf_HTTP_PrivateException extends Exception
{
   public $dir;
   
   function __construct($dir) {
      $this->dir = $dir;
   }
}

?>