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
 * Default form field.
 *
 * A form field is providing a defined set of methods and properties
 * to be used in the rendering of the fields in forms, in the
 * conversion of the data from the form to the model and provides the
 * basic DB storage type.
 */
class Pluf_Form_Field
{
    /**
     * The types are defined in the $mappings member variable of the
     * schema class of your database engine, for example
     * Pluf_DB_Schema_MySQL.
     */
    public $type = '';

    /**
     * The name of the field. It is the same as the name of a column
     * in the corresponding model.
     */
    public $name = '';

    /**
     * The verbose name of the field.
     */
    public $verbose = '';
    
    /**
     * Current value of the field.
     */
    public $value = '';

    /**
     * Allowed to be blank.
     */
    public $blank = true;

    public $prefix = '';
    public $field_errors = array(); /**< List of error messages. */
    public $choices = array(); /**< Possible choices. */
    public $related_model = ''; /**< Related model for foreignkey and manytomany. */
    public $model = ''; /**< Model this field belongs to. */
    public $helptext = ''; /**< Help text for the field. */
    public $default = ''; /**< Default value. */
    public $schema = array(); /**< Schema of the field in the model. */
    /**
     *  Validators of the field.
     */
    public $validators = array(); 

    /**
     * Provided methods.
     *
     * A field can provide new methods to the model. For example, you
     * can have a file field with an added 'fieldname_url()' method or
     * 'fieldname_path()' method.
     */
    public $add_methods = array();

    /**
     * Constructor.
     *
     * @param string Name ('')
     */
    function __construct($name='')
    {
        $this->name = $name;
    }

    /**
     * Populate a field from the model.
     *
     * @param Pluf_Model Model
     * @param string In which column of the model is the field
     */
    function populateFromModel($model, $col)
    {
        $this->value = $model->$col;
        if (isset($model->_cols[$col]['verbose'])) 
            $this->verbose = $model->_cols[$col]['verbose'];

        if (!empty($model->_cols[$col]['unique'])) {
            $this->unique = $model->_cols[$col]['unique'];
            $this->model = $model;
        }

        if (isset($model->_cols[$col]['help_text'])) 
            $this->helptext = $model->_cols[$col]['help_text'];

        if (isset($model->_cols[$col]['blank'])) 
            $this->blank = $model->_cols[$col]['blank'];

        if (isset($model->_cols[$col]['choices'])) 
            $this->choices = $model->_cols[$col]['choices'];

        if (isset($model->_cols[$col]['default'])) 
            $this->default = $model->_cols[$col]['default'];

        if (isset($model->_cols[$col]['model'])) 
            $this->related_model = $model->_cols[$col]['model'];

        if (isset($model->_cols[$col]['validators'])) 
            $this->validators = $model->_cols[$col]['validators'];
        $this->schema = $model->_cols[$col];
        
    }

    /**
     * Populate a field from the form or the flatten data of a model.
     *
     * @param array The form/flatten data.
     * @param array The possible errors in the data.
     */
    function populateFromForm($data, $errors=array())
    {
        if (isset($data[$this->name])) 
            $this->value = $data[$this->name];
        if (isset($errors[$this->name])) 
            $this->field_errors = $errors[$this->name];
    }


    /**
     * Validate some possible input for the field.
     *
     * @param mixed Possible input modified by reference
     * @param array All the inputs in the form
     * @return array Errors array
     */
    function validate(&$raw_data, $raw_form)
    {
        if (is_array($raw_data)) {
            $strlen = count($raw_data);
        } else {
            $strlen = strlen($raw_data);
        }
        if ($this->blank == false and $strlen == 0) {
            return array(__('This field is required.'));
        }
        if ($this->blank == true and $strlen == 0) {
            return;
        }
        $errors = array();
        foreach ($this->validators as $validator) {
            list($model, $method) = split('::', $validator);
            $m = new $model();
            try {
                $raw_data = $m->$method($raw_data, $raw_form, $this->schema);
            } catch (Invalid $e) {
                $errors[] = $e->getMessage();
            }
        }
        if (empty($errors) and isset($this->unique) 
            and true == $this->unique
            and !is_null($this->model)) {
            $sql = new Pluf_SQL();
            $sql->Q($this->name.'=%s', $raw_data);
            $m = Pluf::factory($this->model->_model);
            $founds = $m->getList(array('filter' => $sql->gen()));
            if ($founds == false or 
                (count($founds) == 1 
                 and $founds[0]->id != $this->model->id)) {
                return array(__('The field must be unique, please give another value.'));
            }
        }
        return $errors;
    }

    /**
     * Render the label of the field.
     *
     * @return string Rendered XHTML label.
     */
    function label()
    {
        $label = (strlen($this->verbose)) ? $this->verbose : $this->name;
        $blank = ($this->blank) ? '' : 'class="px-form-required" '; 
        return '<label '.$blank.'for="'.$this->prefix.$this->name.'">'
            .ucfirst($label).__(':').'</label>';
    }

    /**
     * Render the label of the field.
     *
     * @return string Rendered XHTML label.
     */
    function label_print()
    {
        $label = (strlen($this->verbose)) ? $this->verbose : $this->name;
        $blank = ($this->blank) ? '' : 'class="px-form-required" '; 
        return '<p '.$blank.'><strong>'
            .ucfirst($label).__(':').'</strong></p>';
    }

    /**
     * Get the name of the field.
     */
    function getName()
    {
        if (false !== strpos($this->prefix, '[')) {
            return $this->prefix.'['.$this->name.']';
        }
        return $this->prefix.$this->name;
    }

    /**
     * Render the list of errors of the field.
     *
     * @return string Rendered XHTML errors.
     */
    function errors()
    {
        if (empty($this->field_errors)) return '';
        if (count($this->field_errors) == 1) 
            return '<span class="px-form-error">'.$this->field_errors[0].'</span>';
        $out = '<ul class="px-form-error">';
        foreach ($this->field_errors as $error) {
            $out .= '<li>'.$error.'</li>'."\n";
        }
        $out .= '</ul>';
        return $out;
    }

    /**
     * Render the form of the field.
     *
     * @return string XHTML of the form field.
     */
    function form()
    {
    }

    /**
     * Render the complete field.
     *
     * Render the errors, the label, the help text and the form.
     *
     * @return string XHTML of the complete form.
     */
    function render()
    {
        return $this->errors().$this->label().$this->form().$this->help_text();
    }

    /**
     * Render the complete field for a print view.
     *
     * Render the errors, the label, the help text and the form.
     *
     * @return string XHTML of the complete form.
     */
    function render_print()
    {
        return $this->label_print().$this->help_text().$this->form_print();
    }

    /**
     * Render the help text of a field.
     *
     * @return string XHTML of the complete form.
     */
    function help_text()
    {
        if (strlen($this->helptext)) {
            return '<span class="px-form-help-text">'.$this->helptext.'</span>'."\n";
        }
        return '';
    }

    /**
     * Generate a list of choices from the available choices.
     *
     * @return string XHTML string of the options in the select.
     */
    protected function getChoices()
    {
        $out = '';
        foreach ($this->choices as $desc=>$value) {
            if ((is_array($this->value) and in_array($value, $this->value))
                or ($this->value == $value)) {
                $selected = 'selected="selected" ';
            } else {
                $selected = '';
            }
            $out .= '<option '.$selected.'value="'
                .htmlspecialchars($value).'">'
                .htmlspecialchars($desc).'</option>'."\n";
        }
        return $out;
    }

    /**
     * Generate a list of choices from the available choices.
     *
     * @return string XHTML string of the options in the select.
     */
    protected function getChoices_print()
    {
        $out = '<ul class="choices">';
        foreach ($this->choices as $desc=>$value) {
            if ((is_array($this->value) and in_array($value, $this->value))
                or ($this->value == $value)) {
                $selected = '[X] ';
            } else {
                $selected = '[ &nbsp;] ';
            }
            $out .= '<li>'.$selected.htmlspecialchars($desc).'</li>'."\n";
        }
        $out .= '</ul>';
        return $out;
    }

    /**
     * Overloading of the get method.
     *
     * This overloaded get method is to be able to return label()
     * errors() and form() as property call.
     */
    function __get($prop)
    {
        if (in_array($prop, array('render', 'label', 'errors', 'help_text', 'render_print', 'form'))) {
            return $this->$prop();
        }
        return $this->$prop;
    }
}

?>