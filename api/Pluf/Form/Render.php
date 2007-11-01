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
 * Display the XHTML of a form without the head and the bottom.
 *
 */
class Pluf_Form_Render
{
    /**
     * Form object.
     */
    public $form = null;
    public $data = array();
    public $errors = array();
    public $action = '';
    public $method = 'post';
    public $fields = array();

    /**
     * Construct a new FormRender object from the given form.
     *
     * @param Form Form object
     * @param array Associative array of the data
     * @param array Associative array of the errors
     */
    function __construct($form, $data=array(), $errors=array())
    {
        $this->form = $form;
        $this->data = $data;
        $this->errors = $errors;
        $this->action = '';
        $this->fields = new Pluf_Template_ContextVars();
        foreach ($this->form->fields as $name => $field) {
            $field->populateFromForm($data, $errors);
            $this->fields[$name] = $field;
        }
    }

    /**
     * Render the form from the schema and the data.
     *
     * A very basic XHTML form.
     */
    function render()
    {
        $out = '';
        if ($this->action != '') {
            $out .= '<form method="'.$this->method.'" action="'.$this->action.'">'."\n";
        }
        $modulo = 0;
        $out .= '<fieldset class="module aligned">';
        foreach ($this->fields as $field) {
            if ($field->type != 'sequence') {
                $out .= '<div class="px-form-row'.(($modulo%2) ? ' px-form-row-odd':'').'">';
            }
            $out .= $field->render();
            if ($field->type != 'sequence') {
                $out .= '</div>'."\n";
                $modulo++;
            }
        }
        $out .= '</fieldset>';
        return $out;
    }

    /**
     * Overloading of the get method.
     *
     * @param string Property to get
     */
    function __get($prop)
    {
        if (isset($this->$prop)) return $this->$prop;
        if ($prop == 'render') return $this->render();
        return $this->$prop;
    }
}

?>