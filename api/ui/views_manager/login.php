<?php

/**
 * @package ui
 */
class ManagerLoginView extends ManagerCommonView {
   function run($match) {
   }

   function initTemplate() {
      $this->template = 'login';
      $GLOBALS['LB']['title'] = __('Login to the luxbum manager');
   }
}
?>