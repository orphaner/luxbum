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
 * Create the schema of a given Pluf_Model for a given database.
 */
class Pluf_DB_Schema
{
    /**
     * Database connection object.
     */
    private $con = null;

    /**
     * Model from which the schema is generated.
     */
    public $model = null;

    /**
     * Schema generator object corresponding to the database.
     */
    private $schema = null;

    function __construct($db, $model=null)
    {
        $this->con = $db;
        $this->model = $model;
        $this->schema = Pluf::factory('Pluf_DB_Schema_'.$db->engine, $db);
    }


    /**
     * Get the schema generator.
     *
     * @return object Pluf_DB_Schema_XXXX
     */
    function getGenerator()
    {
        return $this->schema;
    }

    /**
     * Create the tables and indexes for the current model.
     *
     * @return mixed True if success or database error.
     */
    function createTables()
    {
        $sql = $this->schema->getSqlCreate($this->model);
        foreach ($sql as $k => $query) {
            if (false === $this->con->execute($query)) {
                return $this->con->error();
            }
        }
        $sql = $this->schema->getSqlIndexes($this->model);
        foreach ($sql as $k => $query) {
            if (false === $this->con->execute($query)) {
                return $this->con->error();
            }
        }
        return true;
    }

    /**
     * Drop the tables and indexes for the current model.
     *
     * @return mixed True if success or database error.
     */
    function dropTables()
    {
        $sql = $this->schema->getSqlDelete($this->model);
        if (false === $this->con->execute($sql)) {
            return $this->con->error();
        }
        return true;
    }



}


?>