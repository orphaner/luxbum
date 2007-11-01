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

class Pluf_Form_Field_File extends Pluf_Form_Field
{
    public $type = 'file';
    public $validators = array();
    public $move_function = '';

    function form()
    {
        $out = ' <input type="file" id="'.$this->getName().'" name="'
            .$this->getName().'" value="" />'."\n";
        return $out;
    }

    /**
     * Constructor.
     *
     * @param string Name ('')
     */
    function __construct($name='')
    {
        $this->name = $name;
        $this->add_methods = array(array(strtolower($name).'_url', 'Pluf_Form_Field_File_Url'),
                                   array(strtolower($name).'_path', 'Pluf_Form_Field_File_Path')
                                   );
    }


    /**
     * Validate some possible input for the field.
     *
     * @param mixed Possible input modified by reference
     * @param array All the inputs in the form
     * @return mixed Possible input encoded nicely or Invalid exception.
     */
    function validate(&$raw_data, $raw_form)
    {
        $errors = array();
        $no_files = false;
        switch ($raw_data['error']) {
        case UPLOAD_ERR_OK:
            if ($raw_data == null and $this->blank) {
                $no_files = true;
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errors[] = __('The uploaded file is too large. Reduce the size of the file and send it again.');
            break;
        case UPLOAD_ERR_PARTIAL:
            $errors[] = __('The upload did not complete. Please try to send the file again.');
            break;
        case UPLOAD_ERR_NO_FILE:
            if (!$this->blank) {
                $errors[] = __('No files were uploaded. Please try to send the file again.');
            } else {
                $no_files = true;
            }
            break;
        default:
            $errors[] = __('An error occured when upload the file. Please try to send the file again.');
        }
        if (count($errors) > 0) {
            // We interrupt directly because of the error at the "PHP"
            // level.
            return $errors;
        }
        if ($no_files) {
            return $errors;
        }
        // give to the validators
        foreach ($this->validators as $validator) {
            list($model, $method) = split('::', $validator);
            $m = new $model();
            try {
                $raw_data = $m->$method($raw_data, $raw_form, $this->schema);
            } catch (Invalid $e) {
                $errors[] = $e->getMessage();
            }
        }
        if (count($errors) > 0) {
            return $errors;
        }
        // copy the file to the final destination and updated raw_data
        // with the final path name. 'final_name' is relative to
        // Pluf::f('upload_path')
        if ($no_files) {
            $raw_data['final_name'] = '';
            return $errors;
        }
        if ($this->move_function != '') {
            Pluf::loadFunction($this->move_function);
            try {
                $raw_data['final_name'] = call_user_func($this->move_function, $raw_data, $raw_form);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        } else {
            $name = Pluf_Utils::cleanFileName($raw_data['name']);
            if (!@move_uploaded_file($raw_data['tmp_name'], Pluf::f('upload_path').'/'.$this->schema['upload_to'].'/'.$name)) {
                $errors[] = __('An error occured when upload the file. Please try to send the file again.');
            } else {
                @chmod(Pluf::f('upload_path').'/'.$this->schema['upload_to'].'/'.$name, 0666);
                $raw_data['final_name'] = $this->schema['upload_to'].'/'.$name;
            }
        }
        return $errors;
    }
}

/**
 * Returns the url to access the file.
 */
function Pluf_Form_Field_File_Url($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_url').'/'.$model->$field;
    }
    return  '';
}

/**
 * Returns the path to access the file.
 */
function Pluf_Form_Field_File_Path($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_path').'/'.$model->$field;
    }
    return '';
}

?>