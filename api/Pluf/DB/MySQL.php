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
 * MySQL connection class
 */
class Pluf_DB_MySQL
{
    public $con_id;
    private $error;
    private $errno;
    public $pfx = '';
    private $debug = false;
    /** The last query, set with debug(). Used when an error is returned. */
    public $lastquery = '';
    public $engine = 'MySQL';
    public $version = '';

    function __construct($user, $pwd, $alias='', $dbname, $pfx='', $debug=false, $version='4.0')
    {
        $this->error = '';
        $this->con_id = @mysql_connect($alias, $user, $pwd);
        $this->debug = $debug;
        $this->pfx = $pfx;
        $this->version = $version;
        $this->debug('* MYSQL CONNECT');
        if (!$this->con_id) {
            $this->setError();
        } else {
            $this->database($dbname);
        }
        if ($this->con_id 
            and strlen($version) and version_compare($version, '4.1', '>=')) { 
            $this->execute('SET NAMES \'utf8\'');
        }
    }

    function database($dbname)
    {
        $db = @mysql_select_db($dbname);
        $this->debug('* USE DATABASE '.$dbname);
        if (!$db) {
            $this->setError();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the version of the MySQL server.
     *
     * @return string Version string
     */
    function getServerInfo()
    {
        return preg_replace('/-log$/','',mysql_get_server_info($this->con_id));
    }

    /**
     * Log the queries. Keep track of the last query and if in debug mode
     * keep track of all the queries in 
     * $GLOBALS['_PX_debug_data']['sql_queries']
     *
     * @param string Query to keep track
     * @return bool true
     */
    function debug($query)
    {
        $this->lastquery = $query;
        if (!$this->debug) return true;
        if (!isset($GLOBALS['_PX_debug_data']['sql_queries'])) 
            $GLOBALS['_PX_debug_data']['sql_queries'] = array();
        $GLOBALS['_PX_debug_data']['sql_queries'][] = $query;
        return true;
    }

    function close()
    {
        if ($this->con_id) {
            mysql_close($this->con_id);
            return true;
        } else {
            return false;
        }
    }

    function select($query)
    {
        if (!$this->con_id) {
            return false;
        }
        $this->debug($query);
        $cur = mysql_unbuffered_query($query, $this->con_id);
        if ($cur) {
            $res = array();
            while ($row = mysql_fetch_assoc($cur)) {
                $res[] = $row;
            }
            mysql_free_result($cur);
            return $res;
        } else {
            $this->setError();
            return false;
        }
    }

    function execute($query)
    {
        if (!$this->con_id) {
            return false;
        }
        $this->debug($query);
        $cur = mysql_query($query, $this->con_id);
        if (!$cur) {
            $this->setError();
            return false;
        } else {
            return true;
        }
    }

    function getLastID()
    {
        if ($this->con_id) {
            $this->debug('* GET LAST ID');
            return mysql_insert_id($this->con_id);
        } else {
            return false;
        }
    }

    function setError()
    {
        if ($this->con_id) {
            $this->error = mysql_error($this->con_id).' - '.$this->lastquery;
            $this->errno = mysql_errno($this->con_id);
        } else {
            $this->error = mysql_error().' - '.$this->lastquery;
            $this->errno = mysql_errno();
        }
    }

    function resetError()
    {
        $this->error = '';
        $this->errno = '';
    }

    function error()
    {
        if ($this->error != '') {
            return $this->errno.' - '.$this->error;
        } else {
            return false;
        }
    }

    function esc($str)
    {
        return mysql_real_escape_string($str, $this->con_id);
    }
}

?>