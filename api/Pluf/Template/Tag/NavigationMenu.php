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
class Pluf_Template_Tag_NavigationMenu extends Pluf_Template_Tag
{
   /**
    * Perform some operations at the opening of the
    * tag {mytag 'param1', 'param2'}.
    *
    * You can access the template context through
    * $this->context
    */
   function start($res, $elt, $sep = '&#187;', $linkLast='')
   {
      $dir = files::removeTailSlash($res->getDir());
      $list = explode('/', $dir);
      $count = count($list);
      if ($list[0] === '') {
         return;
      }
      $result = '';
      $concat = '';
      for ($i = 0 ; $i < $count ; $i++) {
         $name = luxbum::niceName($list[$i]);
         if ($i == $count-1) {
            $concat .= $list[$i];
            if ($linkLast == '') {
               $result .= sprintf($elt,  sprintf($sep.' %s', $name));
            }
            else {
               $link = $linkLast;
               $result .= sprintf($elt,sprintf($sep.' <a href="%s">%s</a>', $link, $name));
            }
         }
         else {
            $concat .= $list[$i].'/';
            $link = link::subGallery($concat);
            $result .= sprintf($elt,sprintf($sep.' <a href="%s">%s</a>', $link, $name));
         }
      }
      echo $result;
   }
}
?>