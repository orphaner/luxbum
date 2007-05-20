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
 * Generate a ready to use URL to be used in location/redirect or forms.
 *
 * When redirecting a user, depending of the format of the url with
 * mod_rewrite or not, the parameters must all go in the GET or
 * everything but the action. This class provide a convinient way to
 * generate those url and parse the results for the dispatcher.
 */
class Pluf_HTTP_URL
{

    public $type='simple';

    /**
     * Constructor with the type of url.
     *
     * Available types are:
     * - 'simple': The resulting url will be in the form of:
     * "?_px_action=/my/action&param1=value&param2=value2
     * - 'mod_rewrite': The resulting url will be in the form of:
     * "/my/action?param1=value&param2=value2
     *
     * @param string Type of URL ('simple')
     */
    function __construct($type='simple')
    {
        $this->type = $type;
    }

    /**
     * Generate the URL.
     *
     * The & is encoded as &amp; in the url.
     *
     * @param string Action url
     * @param array Associative array of the parameters (array())
     * @param bool Encode the & in the url (true)
     * @return string Ready to use URL.
     */
    function generate($action, $params=array(), $encode=true)
    {
        if ($encode) {
            $amp = '&amp;';
        } else {
            $amp = '&';
        }
        $url = '';
        if ($this->type == 'simple') {
            $url .= '?_px_action='.urlencode($action);
            if (count($params) > 0) {
                $url .= $amp;
            }
        } else {
            $url .= $action;
            if (count($params) > 0) {
                $url .= '?';
            }
        }
        $params_list = array();
        foreach ($params as $key=>$value) {
            $params_list[] = urlencode($key).'='.urlencode($value);
        }
        $url .= implode($amp, $params_list);
        return $url;
    }

    /**
     * Get the action of the request.
     *
     * Depending of the format, the action is either the query string
     * or the _px_action parameter.
     *
     * @return string Action
     */
    function getAction()
    {
        if (isset($_GET['_px_action'])) {
            return $_GET['_px_action'];
        }
        return $_SERVER['QUERY_STRING'];
    }




  }

?>