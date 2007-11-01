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
 * Active Record Class
 *
 * TODO: Create a "delayed" init method to run it only when needed to
 * possibly optimize the speed. 
 * TODO: Need some speed tests.
 */
class Pluf_Model extends Pluf_Error
{
    /** Database connection. */
    public $_con = null;

    /** Database table for the model. */
    public $_table = 'model';

    /** Name of the model. */
    public $_model = 'Pluf_Model';

    /** Store the column definition. */
    public $_cols = array();

    /** Store the index definition. */
    public $_idx = array();

    /** Store the admin extra details. */
    public $_admin = array();

    /** 
     * Store the sql views. 
     */
    public $_views = array();

    /** Storage of the data.
     *
     * The object data are stored in an associative array.
     * @see function $this->f()
     */
    protected $_data = array(); 

    /**
     * Storage cached data for methods_get
     */
    protected $_cache = array();

    
    /** Store the error for each field after validation. */
    protected $_validate_errors = array();

    /** List of the foreign keys.
     *
     * Set by the init() method from the definition of the columns.
     */
    protected $_fk = array();
    /**
     * List of the defined methods.
     */
    protected $_methods_list = array();
    protected $_manytomany = array();
    protected $_methods_get = array();
    /**
     * Extra methods provided by a field.
     */
    protected $_methods_extra = array();

    function __construct($id=0)
    {
        $this->_getConnection();
        $this->init();
        $this->_reset();
        if ((int) $id > 0) {
            $this->get($id);
        }
    }

    /**
     * Represents the model in auto generated lists.
     * 
     * You need to overwrite this method to have a nice display of
     * your objects in the select boxes, logs.
     */
    function __toString()
    {
        return $this->_model.'('.$this->_data['id'].')';
    }

    /**
     * Define the list of methods for the model from the available
     * model relationship.
     */
    function init()
    {
        foreach ($GLOBALS['_PX_models'] as $model=>$val) {
            if (isset($val['relate_to'])) {
                foreach ($val['relate_to'] as $related) {
                    if ($this->_model == $related) {
                        // The current model is related to $model
                        // through one or more foreign key. We load
                        // the $model to check on which fields the
                        // foreign keys are set, as it is possible in
                        // one model to have several foreign keys to
                        // the same other model.
                        if ($model != $this->_model) {
                            $_m = new $model();
                            $_fkeys = $_m->getForeignKeysToModel($this->_model);
                        } else {
                            $_fkeys = $this->getForeignKeysToModel($this->_model);
                        }
                        foreach ($_fkeys as $_fkey=>$_fkeyval) {
                            //For each foreign key, we add the
                            //get_xx_list method that can have a
                            //custom name through the relate_name
                            //value.
                            if (isset($_fkeyval['relate_name'])) {
                                $mname = $_fkeyval['relate_name'];
                            } else {
                                $mname = strtolower($model);
                            }
                            $this->_methods_list['get_'.$mname.'_list'] = array($model, $_fkey);
                        }
                        break;
                    }
                }
            }
            if (isset($val['relate_to_many']) && 
                in_array($this->_model, $val['relate_to_many'])) {
                $this->_methods_list['get_'.strtolower($model).'_list'] = $model;
                $this->_manytomany[$model] = 'manytomany';
            }
        }
        foreach ($this->_cols as $col=>$val) {
            $field = new $val['type']($col);
            if ($field->type == 'foreignkey') {
                $this->_methods_get['get_'.strtolower($col)] = array($val['model'], $col);
                $this->_fk[$col] = 'foreignkey';
            }
            if ($field->type == 'manytomany') {
                $this->_methods_list['get_'.strtolower($col).'_list'] = $val['model'];
                $this->_manytomany[$val['model']] = 'manytomany';
            }
            foreach ($field->add_methods as $method) {
                $this->_methods_extra[$method[0]] = array(strtolower($col), $method[1]);
            }
        }
    }

    /**
     * Get the foreign keys relating to a given model.
     *
     * @param string Model
     * @return array Foreign keys
     */
    function getForeignKeysToModel($model)
    {
        $keys = array();
        foreach ($this->_cols as $col=>$val) {
            $field = new $val['type']();
            if ($field->type == 'foreignkey' and $val['model'] == $model) {
                $keys[$col] = $val;
            }
        }
        return $keys;
    }

    /**
     * Get the raw data of the object.
     *
     * @return array Associative array of the data.
     */
    function getData()
    {
        return $this->_data;
    }

    /**
     * Return the value of a given field.
     *
     * @param string Field
     * @return mixed Field value or false.
     */
    function f($field)
    {
        if (isset($this->_data[$field])) {
            return $this->_data[$field];
        } else {
            return '';
        }
    }

    /**
     * Set a field to a given value.
     *
     * @param string Field
     * @param mixed Value
     */
    function setField($field, $value)
    {
        $this->_data[$field] = $value;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param object Object to associate to the current object
     */
    function setAssoc($model)
    {
        if (!$this->delAssoc($model)) {
            return false;
        }
        $hay = array(strtolower($model->_model), strtolower($this->_model));
        sort($hay);
        $table = $hay[0].'_'.$hay[1].'_assoc';
        $req = 'INSERT INTO `'.$this->_con->pfx.$table.'` SET'."\n";
        $req .= '`'.$this->_model.'_id` = \''.$this->_con->esc($this->_data['id']).'\', ';
        $req .= '`'.$model->_model.'_id` = \''.$this->_con->esc($model->id).'\'';
        if (!$this->_con->execute($req)) {
            throw new Exception($this->_con->error());
        }
        return true;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param object Object to associate to the current object
     */
    function delAssoc($model)
    {
        //check if ok to make the association
        //current model has a many to many key with $model
        //$model has a many to many key with current model
        if (!isset($this->_manytomany[$model->_model])
            or strlen($this->_data['id']) == 0
            or strlen($model->id) == 0) {
            return false;
        }
        $hay = array(strtolower($model->_model), strtolower($this->_model));
        sort($hay);
        $table = $hay[0].'_'.$hay[1].'_assoc';
        $req = 'DELETE FROM `'.$this->_con->pfx.$table.'` WHERE'."\n";
        $req .= '`'.$this->_model.'_id` = \''.$this->_con->esc($this->_data['id']).'\'';
        $req .= ' AND `'.$model->_model.'_id` = \''.$this->_con->esc($model->id).'\'';
        if (!$this->_con->execute($req)) {
            throw new Exception($this->_con->error());
        }
        return true;
    }

    /**
     * Bulk association of models to the current one.
     *
     * @param string Model name
     * @param array Ids of Model name
     * @return bool Success
     */
    function batchAssoc($model_name, $ids)
    {
        $currents = $this->getRelated($model_name);
        foreach ($currents as $cur) {
            $this->delAssoc($cur);
        }
        foreach ($ids as $id) {
            $m = new $model_name($id);
            if ($m->id == $id) {
                $this->setAssoc($m);
            }
        }
        return true;
    }

    /**
     * Get a database connection.
     */
    function _getConnection()
    {
        $this->_con = Pluf::db();
    }

    /**
     * Overloading of the get method.
     *
     * @param string Property to get
     */
    function __get($prop)
    {
        if (isset($this->$prop)){
           return $this->$prop;
        }
        if (isset($this->_data[$prop])) {
            return $this->_data[$prop];
        }
        else try {
            return $this->__call($prop, array());
        } catch (Exception $e) {
            throw new Exception(sprintf('Property "%s" not available', $prop));
        }
    }

    /**
     * Overloading of the set method.
     *
     * @param string Property to set
     * @param mixed Value to set
     */
    function __set($prop, $val)
    {
        if (isset($this->$prop)) $this->$prop = $val;
        elseif (isset($this->_fk[$prop])) $this->_data[$prop] = $val->id;
        elseif (isset($this->_cols[$prop])) $this->_data[$prop] = $val;
        else throw new Exception(sprintf('Property "%s" not available', $prop));
    }

    /**
     * Overloading of the method call.
     *
     * @param string Method
     * @param array Arguments
     */
    function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $args);
        }
        // The foreign keys of the current object.
        if (isset($this->_methods_get[$method])) {
            if (isset($this->_cache[$method])) {
                return $this->_cache[$method];
            } else {
                $this->_cache[$method] = Pluf::factory($this->_methods_get[$method][0], $this->_data[$this->_methods_get[$method][1]]);
                return $this->_cache[$method];
            }
        }
        // Many to many or foreign keys on the other objects.
        if (isset($this->_methods_list[$method])) {
            if (is_array($this->_methods_list[$method])) {
                $model = $this->_methods_list[$method][0];
            } else {
                $model = $this->_methods_list[$method];
            }
            $args = array_merge(array($model, $method), $args);
            return call_user_func_array(array($this, 'getRelated'), $args);
        }
        // Extra methods added by fields
        if (isset($this->_methods_extra[$method])) {
            $args = array_merge(array($this->_methods_extra[$method][0], $method, $this), $args);
            Pluf::loadFunction($this->_methods_extra[$method][1]);
            return call_user_func_array($this->_methods_extra[$method][1], $args);
        }
        
        // Getter
        $prop = strtolower(substr($method, 3));
        if (substr($method, 0, 3) === "get") {
            if (isset($this->$prop)){
                 return $this->$prop;
            }
            if (isset($this->_data[$prop])) {
                 return $this->_data[$prop];
            }
        }
        
        // Setter
        $val = $args[0];
        if (substr($method, 0, 3) === "set") {
            if (isset($this->$prop)) {
                $this->$prop = $val;
                return;
            }
            elseif (isset($this->_fk[$prop])) {
                $this->_data[$prop] = $val->id;
                return;
            }
            elseif (isset($this->_cols[$prop])) {
                $this->_data[$prop] = $val;
                return;
            }
        }
        throw new Exception(sprintf('Method "%s" not available', $method));
    }

    /**
     * Get a given item.
     *
     * @param int Id of the item.
     * @param mixed Item or false if not found.
     */
    function get($id)
    {
        if (!is_int((int) $id)) {
            throw new Exception('Primary key in get() call must be an integer.');
        }
        $this->_getConnection();
        $req = 'SELECT * FROM `'.$this->_con->pfx.$this->_table.'` WHERE'."\n"
            .'`id` = \''.$this->_con->esc($id).'\'';

        if (false === ($rs = $this->_con->select($req))) {
            throw new Exception($this->_con->error());
        }
        if (count($rs) == 0) {
            return false;
        }
        foreach ($this->_cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany' && isset($rs[0][$col])) {
                $this->_data[$col] = $rs[0][$col];
            }
        }
        $this->restore();
        return $this;
    }

    /**
     * Get a list of items.
     *
     * The filter should be used only for simple filtering. If you want
     * a complex query, you should create a new view.
     * Both filter and order accept an array or a string in case of multiple
     * parameters:
     * Filter:
     *    array('col1=toto', 'col2=titi') will be used in a AND query
     *    or simply 'col1=toto'
     * Order:
     *    array('col1 ASC', 'col2 DESC') or 'col1 ASC'
     * 
     * This is modelled on the DB_Table pear module interface.
     *
     * @param array Associative array with the possible following
     *              keys:
     *    'view': The view to use
     *  'filter': The where clause to use
     *   'order': The ordering of the result set
     *  ' start': The start in the result set
     *      'nb': The number of items to get in the result set
     *   'count': Run a count query and not a select if set to true
     * @return ArrayObject of items or through an exception if
     * database failure
     */
    function getList($p=array()) 
    {
        $default = array('view' => null, 
                         'filter' => null, 
                         'order' => null, 
                         'start' => null, 
                         'nb' => null, 
                         'count' => false);
        $p = array_merge($default, $p);
        if (!is_null($p['view']) && !isset($this->_views[$p['view']])) {
            throw new Exception(sprintf(__('The view "%s" is not defined.'), $p['view']));
        }
        $query = array(
                       'select' => $this->getSelect(),
                       'from' => $this->_table,
                       'join' => '',
                       'where' => '',
                       'group' => '',
                       'having' => '',
                       'order' => '',
                       'limit' => '',
                       'props' => array(),
                       );
        if (!is_null($p['view'])) {
            $query = array_merge($query, $this->_views[$p['view']]);
        }
        if (!is_null($p['filter'])) {
            if (is_array($p['filter'])) {
                $p['filter'] = implode(' AND ', $p['filter']);
            }
            if (strlen($query['where']) > 0) {
                $query['where'] .= ' AND ';
            }
            $query['where'] .= ' ('.$p['filter'].') ';
        }
        if (!is_null($p['order'])) {
            if (is_array($p['order'])) {
                $p['order'] = implode(', ', $p['order']);
            }
            if (strlen($query['order']) > 0 and strlen($p['order']) > 0) {
                $query['order'] .= ', ';
            }
            $query['order'] .= $p['order'];
        }
        if (!is_null($p['start']) && is_null($p['nb'])) {
            $p['nb'] = 10000000;
        }
        if (!is_null($p['start'])) {
            if ($p['start'] != 0) {
                $p['start'] = ((int) $p['start']) - 1;
            }
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT '.$p['start'].', '.$p['nb'];
        }
        if (!is_null($p['nb']) && is_null($p['start'])) {
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT '.$p['nb'];
        }
        if ($p['count'] == true) {
            $query['select'] = 'COUNT(*) as \'nb_items\'';
            $query['order'] = '';
            $query['limit'] = '';
        }
        $req = 'SELECT '.$query['select'].' FROM `'
            .$this->_con->pfx.$query['from'].'` '.$query['join'];
        if (strlen($query['where'])) {
            $req .= "\n".'WHERE '.$query['where'];
        }
        if (strlen($query['group'])) {
            $req .= "\n".'GROUP BY '.$query['group'];
        }
        if (strlen($query['having'])) {
            $req .= "\n".'HAVING '.$query['having'];
        }
        if (strlen($query['order'])) {
            $req .= "\n".'ORDER BY '.$query['order'];
        }
        if (strlen($query['limit'])) {
            $req .= "\n".$query['limit'];
        }
        if (false === ($rs=$this->_con->select($req))) {
            throw new Exception($this->_con->error());
        }
        if (count($rs) == 0) {
            return new ArrayObject();
        } 
        if ($p['count'] == true) {
            return $rs;
        }
        $res = new ArrayObject();
        foreach ($rs as $row) {
            $this->_reset();
            foreach ($this->_cols as $col => $val) {
                if (isset($row[$col])) $this->_data[$col] = $row[$col];
            }
            foreach ($query['props'] as $prop => $key) {
                if (isset($row[$prop])) $this->_data[$key] = $row[$prop];
            }
            $this->restore();
            $res[] = clone($this);
        }
        return $res;
    }

    /**
     * Get the number of items.
     *
     * @see getList() for definition of the keys
     *
     * @param array with associative keys 'view' and 'filter'
     * @return int The number of items
     */
    function getCount($p=array())
    {
        $p['count'] = true;
        $count = $this->getList($p);
        if (empty($count) or count($count) == 0) { 
            return 0; 
        } else {
            return (int) $count[0]['nb_items'];
        }
    }

    /**
     * Get a list of related items.
     *
     * See the getList() method for usage of the view and filters.
     *
     * @param string Class of the related items
     * @param string Method call in a many to many related
     * @param array Parameters, see getList() for the definition of
     *              the keys
     * @return array Array of items
     */
    function getRelated($model, $method=null, $p=array())
    {
        $default = array('view' => null, 
                         'filter' => null, 
                         'order' => null, 
                         'start' => null, 
                         'nb' => null, 
                         'count' => false);
        $p = array_merge($default, $p);
        if ('' == $this->_data['id']) {
            return new ArrayObject();
        }
        $m = new $model();
        if (!isset($this->_manytomany[$model])) {
            if (is_array($this->_methods_list[$method])) {
                $foreignkey = $this->_methods_list[$method][1];
            } else {
                $foreignkey = '';
                foreach ($m->_cols as $col => $val) {
                    $field = new $val['type']();
                    if ($field->type == 'foreignkey' 
                        && $val['model'] == $this->_model) {
                        $foreignkey = $col;
                        break;
                    }
                }
            }
            if (strlen($foreignkey) == 0) {
                throw new Exception(sprintf(__('No matching foreign key found in model: %s for model %s'), $model, $this->_model));
            }
            if (!is_null($p['filter'])) {
                if (is_array($p['filter'])) {
                    $p['filter'] = implode(' AND ', $p['filter']);
                }
                $p['filter'] .=  ' AND ';
            } else {
                $p['filter'] = '';
            }
            $p['filter'] .= '`'.$foreignkey.'`=\''.$this->_con->esc($this->_data['id']).'\'';
        } else {
            // Many to many: We generate a special view that is making
            // the join
            $hay = array(strtolower(Pluf::factory($model)->_model), 
                         strtolower($this->_model));
            sort($hay);
            $table = $hay[0].'_'.$hay[1].'_assoc';
            if (isset($m->_views[$p['view']])) {
                $m->_views[$p['view'].'__manytomany__'] = $m->_views[$p['view']];
                if (!isset($m->_views[$p['view'].'__manytomany__']['join'])) {
                    $m->_views[$p['view'].'__manytomany__']['join'] = '';
                }
                if (!isset($m->_views[$p['view'].'__manytomany__']['where'])) {
                    $m->_views[$p['view'].'__manytomany__']['where'] = '';
                }
            } else {
                $m->_views['__manytomany__'] = array('join' => '',
                                                     'where' => '');
                $p['view'] = '';
            }
            $m->_views[$p['view'].'__manytomany__']['join'] .= 
                ' LEFT JOIN `'.$this->_con->pfx.$table.'` ON '
                .' `'.strtolower($m->_model).'_id` = `'.$this->_con->pfx.$m->_table.'`.`id`';

            $m->_views[$p['view'].'__manytomany__']['where'] = '`'.strtolower($this->_model).'_id`='.$this->_data['id'];
            $p['view'] = $p['view'].'__manytomany__';
        }
        return  $m->getList($p);
    }

    /**
     * Generate the SQL select from the columns
     */
    function getSelect()
    {
        $select = array();
        foreach ($this->_cols as $col=>$val) {
            if ($val['type'] != 'Pluf_Form_Field_Manytomany') {
                $select[] = '`'.$this->_con->pfx.$this->_table.'`.`'.$col.'` AS `'.$col.'`'; 
            }
        }
        return implode(', ', $select);
    }

    /**
     * Update the model into the database.
     *
     * If no where clause is provided, the index definition is used to
     * find the sequence. These are used to limit the update
     * to the current model.
     *
     * @param string Where clause to update specific items. ('')
     * @return bool Success
     */
    function update($where='')
    {
        $this->_getConnection();
        $this->preSave();
        $req = 'UPDATE `'.$this->_con->pfx.$this->_table.'` SET'."\n";
        $fields = array();
        $assoc = array();
        foreach ($this->_cols as $col=>$val) {
            $field = new $val['type']();
            if ($col == 'id') {
                continue;
            } elseif ($field->type == 'manytomany') {
                if (is_array($this->$col)) {
                    $assoc[$val['model']] = $this->$col;
                }
                continue;
            }
            $fields[] = '`'.$col.'` = \''.$this->_con->esc($this->$col).'\'';
        }
        $req .= implode(','."\n", $fields);
        if (strlen($where) > 0) {
            $req .= ' WHERE '.$where;
        } else {
            $req .= ' WHERE `id` = \''.$this->_con->esc($this->_data['id']).'\'';
        }
        if (!$this->_con->execute($req)) {
            throw new Exception($this->_con->error());
        }
        if (false === $this->get($this->_data['id'])) {
            return false;
        }
        foreach ($assoc as $model=>$ids) {
            $this->batchAssoc($model, $ids);
        }
        return true;
    }

    /**
     * Create the model into the database.
     * 
     * @return bool Success
     */
    function create()
    {
        $this->_getConnection();
        $this->preSave();
        $req = 'INSERT INTO `'.$this->_con->pfx.$this->_table.'` SET'."\n";
        $fields = array();
        $assoc = array();
        foreach ($this->_cols as $col=>$val) {
            $field = new $val['type']();
            if ($col == 'id') {
                continue;
            } elseif ($field->type == 'manytomany') {
                // If is a defined array, we need to associate.
                if (is_array($this->$col)) {
                    $assoc[$val['model']] = $this->$col;
                }
                continue;
            }
            $fields[] = '`'.$col.'` = \''.$this->_con->esc($this->$col).'\'';
        }
        $req .= implode(','."\n", $fields);
        if (!$this->_con->execute($req)) {
            throw new Exception($this->_con->error());
        }
        if (false === ($id=$this->_con->getLastID())) {
            throw new Exception($this->_con->error());
        }
        $this->_data['id'] = $id;
        foreach ($assoc as $model=>$ids) {
            $this->batchAssoc($model, $ids);
        }
        return true;
    }

    /**
     * Get models affected by delete.
     *
     * @return array Models deleted if deleting current model.
     */
    function getDeleteSideEffect()
    {
        $affected = array();
        foreach ($this->_methods_list as $method=>$details) {
            if (is_array($details)) {
                // foreignkey
                $related = $this->$method();
                $affected = array_merge($affected, (array) $related);
                foreach ($related as $rel) {
                    if ($details[0] == $this->_model
                        and $rel->id == $this->_data['id']) {
                        continue; // $rel == $this
                    }
                    $affected = array_merge($affected, (array) $rel->getDeleteSideEffect());
                }
            }
        }
        return Pluf_Model_RemoveDuplicates($affected);
    }

    /**
     * Delete the current model from the database.
     *
     * If another model link to the current model through a foreign
     * key, find it and delete it. If this model is linked to other
     * through a many to many, delete the association.
     *
     * FIXME: No real test of circular references. It can break.
     */
    function delete()
    {
        $this->_getConnection();
        if (false === $this->get($this->_data['id'])) {
            return false;
        }
        $this->preDelete();
        // Find the models linking to the current one through a foreign key.
        foreach ($this->_methods_list as $method=>$details) {
            if (is_array($details)) {
                // foreignkey
                $related = $this->$method();
                foreach ($related as $rel) {
                    if ($details[0] == $this->_model
                        and $rel->id == $this->_data['id']) {
                        continue; // $rel == $this
                    }
                    // We do not really control if it can be deleted
                    // as we can find many times the same to delete.
                    $rel->delete();
                }
            } else {
                // manytomany
                $related = $this->$method();
                foreach ($related as $rel) {
                    $this->delAssoc($rel);
                }
            }
        }
        $req = 'DELETE FROM `'.$this->_con->pfx.$this->_table.'` WHERE'."\n";
        $req .= '`id` = \''.$this->_con->esc($this->_data['id']).'\'';
        if (!$this->_con->execute($req)) {
            throw new Exception($this->_con->error());
        }
        $this->_reset();
        return true;
    }

    /**
     * Reset the fields to default values.
     */
    function _reset()
    {
        foreach ($this->_cols as $col => $val) {
            if (isset($val['default'])) {
                $this->_data[$col] = $val['default'];
            } else {
                 $this->_data[$col] = '';
            }
        }
    }

    /**
     * Set the data from a form.
     * 
     * @param array Associative array of the form data.
     * @param string Prefix used in the form ('')
     */
    function setFromFormData($data, $prefix='')
    {
        foreach ($this->_cols as $col => $val) {
            if (isset($data[$prefix.$col])) {
                $this->_data[$col] = $data[$prefix.$col];
            }
        }
    }

    /**
     * Hook run just after loading a model from the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function restore()
    {
    }

    /**
     * Hook run just before saving a model in the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function preSave()
    {
    }

    /**
     * Hook run just before deleting a model from the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function preDelete()
    {
    }

}


/**
 * Check if a model is already in an array of models.
 *
 * It is not possible to override the == function in PHP to directly
 * use in_array.
 *
 * @param Pluf_Model The model to test
 * @param Array The models
 * @return bool
 */
function Pluf_Model_InArray($model, $array) 
{
    if ($model->id == '') {
        return false;
    }
    foreach ($array as $modelin) {
        if ($modelin->_model == $model->_model 
            and $modelin->id == $model->id) {
            return true;
        }
    }
    return false;
}

/**
 * Return a list of unique models.
 *
 * @param array Models with duplicates
 * @return array Models with duplicates.
 */
function Pluf_Model_RemoveDuplicates($array)
{
    $res = array();
    foreach ($array as $model) {
        if (!Pluf_Model_InArray($model, $res)) {
            $res[] = $model;
        }
    }
    return $res;
}

?>