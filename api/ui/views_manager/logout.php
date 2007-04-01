<?php

/**
 * @package ui
 */
class ManagerLogoutAction extends ManagerCommonAction {
   function run($match) {
   }

   function setToView() {
      $this->toView = 'ManagerLoginView';
   }
}

?>