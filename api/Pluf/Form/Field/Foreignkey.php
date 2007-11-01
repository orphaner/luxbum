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

class Pluf_Form_Field_Foreignkey extends Pluf_Form_Field
{
    public $type = 'foreignkey';
    public $validators = array('Pluf_Encoder::foreignkey');

    function form()
    {
        $elts = Pluf::factory($this->related_model)->getList();
        $out = '<select id="'.$this->getName().'" name="'.$this->getName().'">'."\n";
        foreach ($elts as $elt) {
            if ($elt->id == $this->value) {
                $selected = 'selected="selected" ';
            } else {
                $selected = '';
            }
            $out .= '<option '.$selected.'value="'.$elt->id.'">'
                .htmlspecialchars($elt->__toString()).'</option>'."\n";
        }
        $out .= '</select>'."\n";
        return $out;
    }

}

?>