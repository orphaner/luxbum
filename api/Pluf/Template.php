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
 * Render a template file.
 */
class Pluf_Template
{
    public $tpl = '';
    public $folders = array();
    public $cache = '';
    public $compiled_template = '';
    public $template_content = '';

    /**
     * Constructor.
     *
     * If the folder name is not provided, it will default to
     * Pluf::f('template_folders')
     * If the cache folder name is not provided, it will default to
     * Pluf::f('tmp_folder')
     *
     * @param string Template name.
     * @param string Template folder paths (null)
     * @param string Cache folder name (null)
     */
    function __construct($template, $folders=null, $cache=null)
    {
        $this->tpl = $template;
        if (is_null($folders)) {
            $this->folders = Pluf::f('template_folders');
        } else {
            $this->folders = $folders;
        }
        if (is_null($cache)) {
            $this->cache = Pluf::f('tmp_folder');
        } else {
            $this->cache = $cache;
        }
    }

    /**
     * Render the template with the given context and return the content.
     *
     * @param Object Context.
     */
    function render($c=null)
    {
        $this->compiled_template = $this->getCompiledTemplate();
        if (!file_exists($this->compiled_template) or Pluf::f('debug')) {
            $compiler = new Pluf_Template_Compiler($this->tpl, $this->folders);
            $this->template_content = $compiler->compile();
            $this->write();
        }
        if (is_null($c)) {
            $c = new Pluf_Template_Context();
        }
        ob_start();
        $t = $c;
        try {
            include $this->compiled_template;
        } catch (Exception $e) {
            ob_clean();
            throw $e;
        }
        $a = ob_get_contents();
        ob_end_clean();
        return $a;
    }

    /**
     * Get the full name of the compiled template.
     *
     * Ends with .phps to prevent execution from outside if the cache folder
     * is not secured but to still have the syntax higlightings by the tools
     * for debugging.
     *
     * @return string Full path to the compiled template
     */
    function getCompiledTemplate()
    {
        // The compiled template not only depends on the file but also
        // on the possible folders in which it can be found.
        $_tmp = var_export($this->folders, true);
        return $this->cache.'/Pluf_Template-'.md5($_tmp.$this->tpl).'.phps';
    }

    /**
     * Write the compiled template in the cache folder.
     * Throw an exception if it cannot write it.
     *
     * @return bool Success in writing
     */
    function write() 
    {
        // mode "a" to not truncate before getting the lock
        $fp = @fopen($this->compiled_template, 'a'); 
        if ($fp !== false) {
            // Exclusive lock on writing
            flock($fp, LOCK_EX); 
            // We have the unique pointeur, we truncate
            ftruncate($fp, 0); 
            // Go back to the start of the file like a +w
            rewind($fp); 
            fwrite($fp, $this->template_content, strlen($this->template_content));
            // Lock released, read access is possible
            flock($fp, LOCK_UN);  
            fclose($fp);
            chmod($this->compiled_template, 0777);
            return true;
        } else {
            throw new Exception(sprintf(__('Cannot write the compiled template: %s'), $this->compiled_template));
        }
        return false;
    }

}


/**
 * Run the opposite of a simple call to htmlspecialchars.
 *
 * @param string String proceeded by htmlspecialchars
 * @return string String like if htmlspecialchars was not applied
 */
function Pluf_Template_unhtmlspecialchars($string)
{
    return str_replace(array('&amp;', '&lt;', '&gt;', '&quot;'), 
                       array('&',     '<',     '>',   '"'     ), $string);
}

/**
 * Special htmlspecialchars that can handle the objects.
 *
 * @param string String proceeded by htmlspecialchars
 * @return string String like if htmlspecialchars was not applied
 */
function Pluf_Template_htmlspecialchars($string)
{
    if (is_object($string)) {
        $text = $string->__toString();
    } elseif (is_array($string)) {
        return $string;
    } else {
        $text = $string;
    }
    return htmlspecialchars($text);
}

function Pluf_Template_errorSpan($string) 
{
   $string = trim($string);
   if ($string != '') {
      return '<span class="error">'.$string.'</span>';
   }
   return '';
}

/**
 * modifier plugin : format a date
 *
 * @param string $string input date string
 * @param string $format strftime format for output
 * @param string $default_date default date if $string is empty
 * @return string|void
 */
function Pluf_Template_dateFormat($string, $format="%b %e, %Y",
                                    $default_date=null) {

    if (substr(PHP_OS,0,3) == 'WIN') {
        $_win_from = array ('%e',  '%T',	   '%D');
        $_win_to   = array ('%#d', '%H:%M:%S', '%m/%d/%y');
        $format	= str_replace($_win_from, $_win_to, $format);
    }

    if ($string != '') {
        return strftime($format, strtotime($string));
    }
    elseif (isset($default_date) && $default_date != '') {
        return strftime($format, strtotime($default_date));
    }
    else {
        return '';
    }
}

?>