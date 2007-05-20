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
 * Class to compile a template file into the corresponding PHP script
 * to be run by the Template class.
 *
 * Compiler dataflow
 * 
 * The important elements of the compiler are the include extends
 * block and superblock directives. They cannot be handled in a linear
 * way like the rest of the elements, they are more like nodes.
 *
 * 
 * @credit Copyright (C) 2006 Laurent Jouanneau.
 */
class Pluf_Template_Compiler
{
    
    /** 
     * Store the literal blocks. 
     **/
    protected $_literals;
    
    /** 
     * Variables. 
     */
    protected $_vartype = array(T_CHARACTER, T_CONSTANT_ENCAPSED_STRING, 
                                T_DNUMBER, T_ENCAPSED_AND_WHITESPACE, 
                                T_LNUMBER, T_OBJECT_OPERATOR, T_STRING, 
                                T_WHITESPACE, T_ARRAY);

    /** 
     * Assignation operators. 
     */
    protected $_assignOp = array(T_AND_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL, 
                                 T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, 
                                 T_PLUS_EQUAL, T_PLUS_EQUAL, T_SL_EQUAL, 
                                 T_SR_EQUAL, T_XOR_EQUAL);

    /** 
     * Operators. 
     */
    protected  $_op = array(T_BOOLEAN_AND, T_BOOLEAN_OR, T_EMPTY, T_INC, 
                            T_ISSET, T_IS_EQUAL, T_IS_GREATER_OR_EQUAL, 
                            T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL,
                            T_IS_SMALLER_OR_EQUAL, T_LOGICAL_AND, T_LOGICAL_OR,
                            T_LOGICAL_XOR, T_SR, T_SL, T_DOUBLE_ARROW);

    /**
     * Authorized elements in variables.
     */
    protected $_allowedInVar;

    /**
     * Authorized elements in expression.
     */
    protected $_allowedInExpr;

    /**
     * Authorized elements in assignation.
     */
    protected $_allowedAssign;

    /**
     * Output filters.
     */
    protected $_modifier = array('upper' => 'strtoupper', 
                                 'lower' => 'strtolower',
                                 'escxml' => 'htmlspecialchars', 
                                 'strip_tags' => 'strip_tags', 
                                 'escurl' => 'rawurlencode',
                                 'capitalize' => 'ucwords',
                                 'debug' => 'var_export',
                                 'count' => 'count',
                                 'nl2br' => 'nl2br',
                                 'trim' => 'trim',
                                 'unsafe' => 'Pluf_Template_unhtmlspecialchars',
                                 'errorSpan' => 'Pluf_Template_errorSpan',
                                 'dateFormat' => 'Pluf_Template_dateFormat',
                                 );

    /**
     * Default allowed extra tags/functions. 
     *
     * These default tags are merged with the 'template_tags' defined
     * in the configuration of the application.
     */
    protected $_allowedTags = array(
                                    'url' => 'Pluf_Template_Tag_Url',
                                    );
    /**
     * During compilation, all the tags are created once so to query
     * their interface easily.
     */
    protected $_extraTags = array();

    /**
     * The block stack to see if the blocks are correctly closed.
     */
    protected $_blockStack = array();

    /**
     * Current template source file.
     */
    protected $_sourceFile;

    /**
     * Current tag.
     */
    protected $_currentTag;

    /**
     * Template folders.
     */
    public $templateFolders = array();

    /**
     * Template content. It can be set directly from a string.
     */
    public $templateContent = '';

    /**
     * The extend blocks.
     */
    public $_extendBlocks = array();

    /**
     * The extended template.
     */
    public $_extendedTemplate = '';

    /**
     * Construct the compiler.
     *
     * @param string Basename of the template file.
     * @param array Base folders in which the templates files 
     *              should be found. (array())
     * @param bool Load directly the template content. (true)
     */
    function __construct($template_file, $folders=array(), $load=true)
    {
        $allowedtags = Pluf::f('template_tags');
        if (is_array($allowedtags)) {
            $this->_allowedTags = array_merge($allowedtags, 
                                              $this->_allowedTags);
        }
        foreach ($this->_allowedTags as $name=>$model) {
            $this->_extraTags[$name] = new $model();
        }
        $this->_sourceFile = $template_file;
        $this->_allowedInVar = array_merge($this->_vartype, $this->_op);
        $this->_allowedInExpr = array_merge($this->_vartype, $this->_op);
        $this->_allowedAssign = array_merge($this->_vartype, $this->_assignOp, 
                                            $this->_op);
        $this->templateFolders = $folders;
        if ($load) {
            $this->loadTemplateFile($template_file);
        }
    }

    /**
     * Compile the template into a PHP code.
     *
     * @return string PHP code of the compiled template.
     */
    function compile() 
    {
        $this->compileBlocks();
        $tplcontent = $this->templateContent;
        preg_match_all("!{literal}(.*?){/literal}!s", $tplcontent, $_match);
        $this->_literals = $_match[1];
        $tplcontent = preg_replace("!{literal}(.*?){/literal}!s", '{literal}', $tplcontent);
        $result = preg_replace_callback("/{((.).*?)}/s", array($this,'_callback'), $tplcontent);
        /* '<?php ?>'."\n", */
        $result = str_replace(array('?><?php',  '<?php ?>'), '', $result);  
        // To avoid the triming of the \n after a php closing tag.
        $result = str_replace("?>\n", "?>\n\n", $result);  
        if (count($this->_blockStack)) {
            trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
        }
        return $result;
    }

    /**
     * Parse the extend blocks.
     *
     * If the current template extends another, it finds the extended
     * template and grabs the defined blocks and compile them.
     */
    function compileBlocks()
    {
        $tplcontent = $this->templateContent;
        $this->_extendedTemplate = '';
        // Match extends on the first line of the template
        if (preg_match("!{extends\s['\"](.*?)['\"]}!", $tplcontent, $_match)) {
            $this->_extendedTemplate = $_match[1];
        }
        // Get the blocks in the current template
        $cnt = preg_match_all("!{block\s(\S+?)}(.*?){/block}!s", $tplcontent, $_match);
        // Compile the blocks
        for ($i=0; $i<$cnt; $i++) {
            if (!isset($this->_extendBlocks[$_match[1][$i]]) 
                or false !== strpos($this->_extendBlocks[$_match[1][$i]], '~~{~~superblock~~}~~')) {
                $compiler = clone($this);
                $compiler->templateContent = $_match[2][$i];
                $_tmp = $compiler->compile();
                if (!isset($this->_extendBlocks[$_match[1][$i]])) {
                    $this->_extendBlocks[$_match[1][$i]] = $_tmp;
                } else {
                    $this->_extendBlocks[$_match[1][$i]] = str_replace('~~{~~superblock~~}~~', $_tmp, $this->_extendBlocks[$_match[1][$i]]);
                }
            }
        }
        if (strlen($this->_extendedTemplate) > 0) {
            // The template of interest is now the extended template
            // as we are not in a base template
            $this->loadTemplateFile($this->_extendedTemplate);
            $this->_sourceFile = $this->_extendedTemplate;
            $this->compileBlocks(); //It will recurse to the base template.
        } else {
            // Replace the current blocks by a place holder
            if ($cnt) {
                $this->templateContent = preg_replace("!{block\s(\S+?)}(.*?){/block}!s", "{block $1}", $tplcontent, -1); 
            }
        }
    }

    /**
     * Load a template file.
     *
     * The path to the file to load is relative and the file is found
     * in one of the $templateFolders array of folders.
     *
     * @param string Relative path of the file to load.
     */
    function loadTemplateFile($file)
    {
        // FIXME: Very small security check, could be better.
        if (strpos($file, '..') !== false) {
            throw new Exception(sprintf(__('Template file contains invalid characters: %s'), $file));
        }
        foreach ($this->templateFolders as $folder) {
            if (file_exists($folder.'/'.$file)) {
                $this->templateContent = file_get_contents($folder.'/'.$file);
                return;
            }
        }
        // File not found in all the folders.
        throw new Exception(sprintf(__('Template file not found: %s'), $file));
    }

    function _callback($matches) 
    {
        list(,$tag, $firstcar) = $matches;
        if (!preg_match('/^\$|[a-zA-Z\/]$/', $firstcar)) {
            trigger_error(sprintf(__('Invalid tag syntax: %s'), $tag), E_USER_ERROR);
            return '';
        }
        $this->_currentTag = $tag;
        if ($firstcar == '$'){
            return '<?php echo '.$this->_parseVariable($tag).'; ?>';
        } else {
            if (!preg_match('/^(\/?[a-zA-Z0-9_]+)(?:(?:\s+(.*))|(?:\((.*)\)))?$/', $tag, $m)) {
            trigger_error(sprintf(__('Invalid function syntax: %s'), $tag), E_USER_ERROR);
                return '';
            }
            if (count($m) == 4){
                $m[2] = $m[3];
            }
            if (!isset($m[2])) $m[2] = '';
            if($m[1] == 'ldelim') return '{';
            if($m[1] == 'rdelim') return '}';
            if ($m[1] != 'include') {
                return '<?php '.$this->_parseFunction($m[1], $m[2]).'?>';
            } else {
                return $this->_parseFunction($m[1], $m[2]);
            }
        }
    }

    function _parseVariable($expr)
    {
        $tok = explode('|', $expr);
        $res = $this->_parseFinal(array_shift($tok), $this->_allowedInVar);
        $res = 'Pluf_Template_htmlspecialchars('.$res.')';
        foreach ($tok as $modifier) {
            if (!preg_match('/^(\w+)(?:\:(.*))?$/', $modifier, $m)) {
                trigger_error(sprintf(__('Invalid modifier syntax: (%s) %s'), $this->_currentTag, $modifier), E_USER_ERROR);
                return '';
            }
            $targs = array($res);
            if(isset($m[2])){
                $args = explode(':',$m[2]);

                foreach($args as $arg){
                    $targs[] = $this->_parseFinal($arg,$this->_allowedInVar);
                }
                $res = $this->_modifier[$m[1]].'('.implode(',',$targs).')';
            }
            else if (isset($this->_modifier[$m[1]])) {
                $res = $this->_modifier[$m[1]].'('.$res.')';
            } else {
                trigger_error(sprintf(__('Unknown modifier: (%s) %s'), $this->_currentTag, $m[1]), E_USER_ERROR);
                return '';
            }
        }
        return $res;
    }

    function _parseFunction($name, $args)
    {
        switch ($name) {
        case 'if':
            $res = 'if ('.$this->_parseFinal($args, $this->_allowedInExpr).'): ';
            array_push($this->_blockStack, 'if');
            break;
        case 'else':
            if (end($this->_blockStack) != 'if') {
                trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
            }
            $res = 'else: ';
            break;
        case 'elseif':
            if (end($this->_blockStack) != 'if') {
                trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
            }
            $res = 'elseif('.$this->_parseFinal($args, $this->_allowedInExpr).'):';
            break;
        case 'foreach':
            $res = 'foreach ('.$this->_parseFinal($args, array(T_AS, T_DOUBLE_ARROW, T_STRING, T_OBJECT_OPERATOR), array(';','!'), true); //.'): ';
            array_push($this->_blockStack, 'foreach');
            break;
        case 'while':
            $res = 'while('.$this->_parseFinal($args,$this->_allowedInExpr).'):';
            array_push($this->_blockStack, 'while');
            break;
        case '/foreach':
        case '/if':
        case '/while':
            $short = substr($name,1);
            if(end($this->_blockStack) != $short){
                trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
            }
            array_pop($this->_blockStack);
            $res = 'end'.$short.'; ';
            break;
        case 'assign':
            $res = $this->_parseFinal($args, $this->_allowedAssign).'; ';
            break;
        case 'literal':
            if(count($this->_literals)){
                $res = '?>'.array_shift($this->_literals).'<?php ';
            }
            else{
                trigger_error(__('End tag of a block missing: literal'), E_USER_ERROR);
            }
            break;
        case '/literal':
            trigger_error(__('Start tag of a block missing: literal'), E_USER_ERROR);
            break;
        case 'block':
            $res = '?>'.$this->_extendBlocks[$args].'<?php ';
            break;
        case 'superblock':
            $res = '?>~~{~~superblock~~}~~<?php ';
            break;
        case 'i18n':
            $argfct = $this->_parseFinal($args, $this->_allowedAssign); 
            $res = 'echo __('.$argfct.');';
            break;
        case 'include':
            // XXX fixme: Will need some security check, when online editing.
            $argfct = preg_replace('!^[\'"](.*)[\'"]$!', '$1', $args);
            $_comp = new Pluf_Template_Compiler($argfct, $this->templateFolders);
            $res = $_comp->compile();
            break;
        default:
            $_end = false;
            $oname = $name;
            if (substr($name, 0, 1) == '/') {
                $_end = true;
                $name = substr($name, 1);
            }
            if (!isset($this->_allowedTags[$name])) {
                trigger_error(sprintf(__('The function tag "%s" is not allowed.'), $name), E_USER_ERROR);
            }
            $argfct = $this->_parseFinal($args, $this->_allowedAssign);
            // $argfct is a string that can be copy/pasted in the PHP code
            // but we need the array of args.
            $res = '';
            if (false == $_end) {
                if (method_exists($this->_extraTags[$name], 'start')) {
                    $res .= '$_extra_tag = Pluf::factory(\''.$this->_allowedTags[$name].'\', $t); $_extra_tag->start('.$argfct.'); ';
                }
                if (method_exists($this->_extraTags[$name], 'genStart')) {
                    $res .= $this->_extraTags[$name]->genStart();
                }
            } else {
                if (method_exists($this->_extraTags[$name], 'end')) {
                    $res .= '$_extra_tag = Pluf::factory(\''.$this->_allowedTags[$name].'\', $t); $_extra_tag->end('.$argfct.'); ';
                }
                if (method_exists($this->_extraTags[$name], 'genEnd')) {
                    $res .= $this->_extraTags[$name]->genEnd();
                }
            }
            if ($res == '') {
                trigger_error(sprintf(__('The function tag "{%s ...}" is not supported.'), $oname), E_USER_ERROR);
            }
        }
        return $res;
    }

    /*

    -------
    if:        op, autre, var
    foreach:   T_AS, T_DOUBLE_ARROW, T_VARIABLE, @locale@
    for:       autre, fin_instruction
    while:     op, autre, var
    assign:    T_VARIABLE puis assign puis autre, ponctuation, T_STRING
    echo:      T_VARIABLE/@locale@ puis autre + ponctuation
    modificateur: serie de autre séparé par une virgule

    tous : T_VARIABLE, @locale@

    */

    function _parseFinal($string, $allowed=array(), 
                         $exceptchar=array(';'), $foreach=false)
    {
        $tokens = token_get_all('<?php '.$string.'?>');
        $result = '';
        $first = true;
        $inDot = false;
        $firstok = array_shift($tokens);
        $afterAs = false;
        $f_key = '';
        $f_val = '';
        $sqbracketcount = 0;

        // il y a un bug, parfois le premier token n'est pas T_OPEN_TAG...
        if ($firstok == '<' && $tokens[0] == '?' && is_array($tokens[1])
            && $tokens[1][0] == T_STRING && $tokens[1][1] == 'php') {
            array_shift($tokens);
            array_shift($tokens);
        }
        //var_dump($tokens);$i=0;
        foreach ($tokens as $tok) {
           //echo "<h2> $i </h2>";$i++;
           //var_dump($tok);
            if (is_array($tok)) {
                list($type, $str) = $tok;
                $first = false;
                if($type == T_CLOSE_TAG){
                    continue;
                }
                if ($type == T_AS) {
                    $afterAs = true;
                }
                if ($type == T_STRING && $inDot) {
                    $result .= 'get'.ucfirst($str).'()';
                    $inDot = false;
                } 
                elseif ($type == T_VARIABLE && $afterAs) {
                    $result .= '$_'.substr($str, 1).'): $t->_vars[\''.substr($str, 1).'\'] = $_'.substr($str, 1).';';
                } 
                elseif ($type == T_VARIABLE) {
                    $result .= '$t->_vars[\''.substr($str, 1).'\']';
                } 
                elseif ($type == T_WHITESPACE || in_array($type, $allowed)) {
                    $result .= $str;
                } 
                else {
                    trigger_error(sprintf(__('Invalid syntax: (%s) %s.'), $this->_currentTag, $str), E_USER_ERROR);
                    return '';
                }
            } 
            else {
                if (in_array($tok, $exceptchar)) {
                    trigger_error(sprintf(__('Invalid character: (%s) %s.'), $this->_currentTag, $tok), E_USER_ERROR);
                } 
                elseif ($tok == '.') {
                    $inDot = true;
                    $result .= '->';
                } 
                elseif ($tok == '~') {
                    $result .= '.';
                } 
                elseif ($tok == '[') {
                   $sqbracketcount++;
                   $result .= $tok;
                } 
                elseif ($tok == ']') {
                   $sqbracketcount--;
                   $result .= $tok;
                }
                else { 
                    $result .= $tok;
                }
                $first = false;
            }
        }
        return $result;
    }
}

?>