<?php

include('api/ui/views_manager/login.php');
include('api/ui/views_manager/logout.php');
include('api/ui/views_manager/index.php');
include('api/ui/views_manager/gallery.php');

/**
 * @package ui
 */
class ManagerBaseUI {
   var $match;

   function purgeGlobals() {
      $_SESSION['_LB_message'] = '';
   }

   function checkAuth(){
   }
}

/**
 * @package ui
 */
class ManagerCommonView extends ManagerBaseUI {
   var $template;

   function getTemplate() {
      return $this->template;
   }
   function setTemplate($template) {
      $this->template = $template;
   }

   function action($match) {
      $this->match = $match;
      $this->purgeGlobals();
      $this->checkAuth();
      $this->initTemplate();
      $this->run();


      $res     =& $GLOBALS['_LB_render']['res'];
      $affpage =& $GLOBALS['_LB_render']['affpage'];

      header('Content-Type: text/html; charset=UTF-8');
      include (TEMPLATE_MANAGER_DIR.$this->template.'.php');
      return 200;
   }

}

/**
 * @package ui
 */
class ManagerCommonAction extends ManagerBaseUI {
   var $viewSuccess;
   var $viewError;
   var $success;

   function isSuccess() {
      return $this->success;
   }
   function setSuccess($success) {
      $this->success = $success;
   }

   function setViewSuccess($viewSuccess) {
      $this->viewSuccess = $viewSuccess;
   }
   function getViewSuccess() {
      return $this->viewSuccess;
   }

   function setViewError($viewError) {
      $this->viewError = $viewError;
   }
   function getViewError() {
      return (String)$this->viewError;
   }

   function action($match) {
      $this->match = $match;
      $this->purgeGlobals();
      $this->checkAuth();
      $this->setViews();
      $res = $this->run($match);
      $this->setSuccess($res);

      if ($this->isSuccess()) {
         header('location: '.$this->viewSuccess());
      }
      else {
         header('location: '.$this->getViewError());
      }
   }
}


?>