<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 # ***** BEGIN LICENSE BLOCK *****
 # This file is part of Plume CMS, a website management application.
 # Copyright (C) 2001-2005 Loic d'Anterroches and contributors.
 #
 # Plume CMS is free software; you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation; either version 2 of the License, or
 # (at your option) any later version.
 #
 # Plume CMS is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program; if not, write to the Free Software
 # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 #
 # ***** END LICENSE BLOCK ***** */

/**
 * @package inc
 * Dispatcher of the requests to the handlers.
 */
class Dispatcher
{
   var $pluginPath = null;


   function Dispatcher($pluginPath = null) {
      $this->pluginPath = $pluginPath;
   }

   /**
    * The unique method to call.
    *
    * @param string Query string ('')
    */
   function Launch($query='') {
      $query = rawurldecode($query);
      $query = preg_replace('#^(/)+#', '/', '/'.$query);//echo $query;
      $this->loadBuiltinControllers();
      $this->loadControllers();
      //$this->match($query);

      $query = preg_replace('#^(/)+#', '/', '/'.$query);
      $req = new Pluf_HTTP_Request($query);
      $middleware = array();
      foreach (Pluf::f('middleware_classes', array()) as $mw) {
         $middleware[] = new $mw();
      }
      $skip = false;
      foreach ($middleware as $mw) {
         if (method_exists($mw, 'process_request')) {
            $res = $mw->process_request($req);
            if ($res !== false) {
               // $res is a response
               $res->render();
               $skip = true;
               break;
            }
         }
      }
      if ($skip === false) {
         $response = Dispatcher::match($req);
         foreach ($middleware as $mw) {
            if (method_exists($mw, 'process_response')) {
               $response = $mw->process_response($req, $response);
            }
         }
         $response->render();
      }
   }


   /**
    * Match a query against the actions controllers.
    *
    * @param string Query string
    */
   function match($req) {

      // Order the controllers by priority
      foreach ($GLOBALS['_PX_control'] as $key => $control) {
         $priority[$key] = $control['priority'];
      }
      array_multisort($priority, SORT_ASC, $GLOBALS['_PX_control']);

      $res = 200;
      foreach ($GLOBALS['_PX_control'] as $key => $ctl) {
         $match = array();
         if (preg_match($ctl['regex'], $req->query, $match)) {
            try {
               $m = new $ctl['plugin']();
               
               // Check if the view is allowed by the configuration
               if (!$m->checkACL()) {
	               $view = new ui_public_Error(__('Page not allowed.'));
	               return $view->aclError($req, $match);
               }
               
               // Call the good method on the view
               if (!isset($ctl['params'])) {
                  return $m->$ctl['method']($req, $match);
               }
               else {
                  return $m->$ctl['method']($req, $match, $ctl['params']);
               }
            }
            
            catch (Pluf_HTTP_FileSystemException $e) {
               $view = new ui_public_Error($e->getMessage());
               return $view->fileSystemError($req, $match);
            }
            
            catch (Pluf_HTTP_PrivateException $e) {
               $view = new ui_public_Private();
               return $view->action($req, array($match[0], $e->dir));
            }
            
            catch (Pluf_HTTP_Error404 $e) {
               return new Pluf_HTTP_Response_NotFound("");
            }
            
            catch (Exception $e) {
               if ($GLOBALS['debug'] == true) {
                  return new Pluf_HTTP_Response_ServerErrorDebug($e);
               }
               else {
                  return new Pluf_HTTP_Response_ServerError($e->getMessage());
               }
            }
         }
      }
      $view = new ui_public_Error("No Matching view");
      return $view->aclError($req, $match);
   }


   /**
    * Load the builtin controllers.
    *
    * The builtin controllers are for: news, article, category,
    * page not found, search and rss.
    */
   function loadBuiltinControllers() {
   }

   /**
    * Load the controllers.
    */
   function loadControllers() {
      if ($this->pluginPath == null) {
         return ;
      }

      $d = dir($this->pluginPath);
      while (($entry = $d->read()) !== false) {
         if ($entry != '.' && $entry != '..'
             && is_dir($this->pluginPath.$entry) 
         && file_exists($this->pluginPath.$entry.'/register.php')) {
            include_once($this->pluginPath.$entry.'/register.php');
         }
      }
      @$d->close();
   }


   /**
    * Register an action controller.
    *
    * - The plugin must provide a "standalone" action method
    * pluginname::action($querystring)
    * - The priority is to order the controller matches.
    * 5: Default, if the controller provides some content
    * 1: If the controller provides a control before, without providing
    * content, note that in this case the return code must be a redirection.
    * 8: If the controller is providing a catch all case to replace the
    * default 404 error page.
    *
    * @param string Plugin name providing the action controller
    * @param string Regex to match on the query string
    * @param int Priority (5)
    * @return void
    */
   function registerController($plugin, $method, $regex, $priority=5) {
      if (!isset($GLOBALS['_PX_control'])) {
         $GLOBALS['_PX_control'] = array();
      }
      $GLOBALS['_PX_control'][] = array('plugin' => $plugin,
								        'method' => $method,
								        'regex' => $regex,
								        'priority' => $priority);
   }
}

?>