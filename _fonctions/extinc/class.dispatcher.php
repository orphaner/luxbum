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
   * Dispatcher of the requests to the handlers.
   */
class Dispatcher
{

   /**
    * The unique method to call.
    *
    * @param string Query string ('')
    */
   function Launch($query='')
   {
//      $GLOBALS['_PX_render']['error'] = new CError();

      $query = preg_replace('#^(/)+#', '/', '/'.$query);

      Dispatcher::loadBuiltinControllers();
//      Dispatcher::loadControllers();
      Dispatcher::match($query);
   }


   /**
    * Match a query against the actions controllers.
    *
    * @param string Query string
    */
   function match($query)
   {
      // Order the controllers by priority
      foreach ($GLOBALS['_PX_control'] as $key => $control) {
         $priority[$key] = $control['priority'];
      }
      array_multisort($priority, SORT_ASC, $GLOBALS['_PX_control']);

      $res = 200;
      foreach ($GLOBALS['_PX_control'] as $key => $control) {
         if (preg_match($control['regex'], $query)) {
            if ($res == 404 and $control['priority'] < 8) {
               continue;
            }
            //          config::setVar('action', $control['plugin']);
            $res = call_user_func(array($control['plugin'], 'action'), 
                                  $query);
//             if ($res != 301 and $res != 404) {
//                showDebugInfo();
//                return;
//             }
//             if ($res == 301 and !empty($GLOBALS['_PX_redirect'])) {
//                header('Location: '.$GLOBALS['_PX_redirect']);
//                return;
//             }
         }
      }
   }


   /**
    * Load the builtin controllers.
    *
    * The builtin controllers are for: news, article, category, 
    * page not found, search and rss.
    */
   function loadBuiltinControllers()
   {
      Dispatcher::registerController('Index', '#^/ssgal(.*)\.html$#i', 4);
//      Dispatcher::registerController('Error404', '#^/error404$#', 4);
  
//       Dispatcher::registerController('RSS', '#^/rss([^/]*)$#i', 4);
//       Dispatcher::registerController('Error404', '#^/error404$#', 4);
//       Dispatcher::registerController('Search', '#^/search/(.*)$#i', 4);
//       Dispatcher::registerController('Comment', '#^/comments/(\d+)/*$#i', 4);
//       Dispatcher::registerController('Category',
//                                      '#^(.*/)(index)([0-9]*)$|^.*/$|^$#i',
//                                      6);
//       Dispatcher::registerController('Article',
//                                      '#^(.*/)([a-z]|[a-z][a-z0-9\_\-]*[a-z])(\d*)$#i',
//                                      7);
//       Dispatcher::registerController('News', '#^(.*/)(\d+)\-([^\.]*)$#i', 7);
//       Dispatcher::registerController('Error404', '#.*#', 9);
   }

   /**
    * Load the controllers.
    */
//    function loadControllers()
//    {
//       $base = config::f('manager_path').'/tools/';
//       $d = dir($base);
//       while (($entry = $d->read()) !== false) {
//          if ($entry != '.' && $entry != '..' 
//              && is_dir($base.$entry) 
//              && file_exists($base.$entry.'/register.php')) {
//             include_once($base.$entry.'/register.php');
//          }
//       }
//       @$d->close();
//    }


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
   function registerController($plugin, $regex, $priority=5)
   {
      if (!isset($GLOBALS['_PX_control'])) {
         $GLOBALS['_PX_control'] = array();
      }
      $GLOBALS['_PX_control'][] = array('plugin' => $plugin,
                                        'regex' => $regex,
                                        'priority' => $priority);
   }

}

?>