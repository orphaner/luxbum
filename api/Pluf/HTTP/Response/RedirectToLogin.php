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
 * Can be used as a response to return when a user must be logged to
 * access a page.
 */
class Pluf_HTTP_Response_RedirectToLogin extends Pluf_HTTP_Response
{
    /**
     * The $request object is used to know what the post login
     * redirect url should be.
     *
     * @param Pluf_HTTP_Request The request object of the current page.
     * @param string The action url of the login page.
     */
    function __construct($request, $loginurl='/login/')
    {
        $murl = new Pluf_HTTP_URL(Pluf::f('url_format'));
        $url = Pluf::f('app_base').$murl->generate($loginurl, array('_redirect_after' => $request->query), false);
        $encoded = Pluf::f('app_base').$murl->generate($loginurl, array('_redirect_after' => $request->query));
        $content = sprintf(__('<a href="%s">Please, click here to be redirected</a>.'), $encoded);
        parent::__construct($content);
        $this->headers['Location'] = $url;
        $this->status_code = 302;
    }
}


?>