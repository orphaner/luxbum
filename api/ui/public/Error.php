<?php
/**
 * @package ui
 */
class ui_public_Error {
   private $message;
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function fileSystemError($request, $match) {
      
      $context = new Pluf_Template_Context(array('error' => $this->message,
                                                 'cfg'   => $GLOBALS['_PX_config']));
                                                 
      $tmpl = new Pluf_Template('error.html');
      return new Pluf_HTTP_Response($tmpl->render($context));
   }
   
   /**
    * 
    * @param Pluf_HTTP_Request $request
    * @param array $match
    */
   function aclError($request, $match) {
      
      $context = new Pluf_Template_Context(array('error' => $this->message,
                                                 'cfg'   => $GLOBALS['_PX_config']));
                                                 
      $tmpl = new Pluf_Template('error.html');
      $response = new Pluf_HTTP_Response($tmpl->render($context));
      $response->status_code = 403;
      return $response;
   }
   
   /**
    * default constructor
    *
    * @param String $message
    */
   function __construct($message) {
      $this->message = $message;
   }
}
?>