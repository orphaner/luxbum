<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 # ***** BEGIN LICENSE BLOCK *****
 # This file is part of Plume Framework, a simple PHP Application Framework.
 # Copyright (C) 2001-2006 Loic d'Anterroches and contributors.
 #
 # Plume Framework is free software; you can redistribute it and/or modify
 # it under the terms of the GNU Lesser General Public License as published by
 # the Free Software Foundation; either version 2.1 of the License, or
 # (at your option) any later version.
 #
 # Plume Framework is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU Lesser General Public License for more details.
 #
 # You should have received a copy of the GNU Lesser General Public License
 # along with this program; if not, write to the Free Software
 # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 #
 # ***** END LICENSE BLOCK ***** */

/**
 * Custom tag example.
 */
class Pluf_Template_Tag_Style extends Pluf_Template_Tag
{
   /**
    * Perform some operations at the opening of the
    * tag {mytag 'param1', 'param2'}.
    *
    * You can access the template context through
    * $this->context
    */
   function start()
   {
      if (!array_key_exists (Pluf::f('template_theme'), $GLOBALS['themes_css'])) {
         $default = $GLOBALS['default_css'];
      }
      else {
         $default = Pluf::f('template_theme');
      }

      $result = '';
      //$result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>'."\n", 
      //                    TEMPLATE_DIR. Pluf::f('template').'/themes/'.$default.'/'.$default.'.css', '');
      while (list ($theme, $title) = each ($GLOBALS['themes_css'])) {
         if ($theme != $default) {
            $result .= sprintf('<link rel="alternate stylesheet" href="%s" title="%s" type="text/css"/>'."\n",
                                TEMPLATE_DIR. Pluf::f('template').'/themes/'.$theme.'/'.$theme.'.css', $title);
         }
         else {
            $result .= sprintf('<link rel="stylesheet" href="%s" title="%s" type="text/css"/>'."\n",
                                TEMPLATE_DIR. Pluf::f('template').'/themes/'.$default.'/'.$default.'.css', $title);
         }
      }
      echo $result;
   }
}
?>