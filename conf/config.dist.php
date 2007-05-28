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

$cfg = array();

/******************************************************************
  Base configuration 
  Must Be edited
**/

// Set the base url of the installed luxbum.
// /!\ Warning : the tail / is needed
$cfg['url_base'] = '';

// Set the index file so that luxbum can be more easily integrated
$cfg['index_file'] = 'index.php';

// Set the template to use ;
// Choices : luxbum / photoblog
$cfg['template'] = 'luxbum';

// Set the color theme of the selected template
$cfg['template_theme'] = 'light';


/******************************************************************
  Base configuration 
  Can be edited
**/

// Set the name of the gallery. It will be displayed in 
// the title of all the pages
$cfg['gallery_name'] = '';

// Use rewrite rules to generate url's. The url looks like
// to be a path to a real file, but it doesn't.
// When disabled, a '?/' is displayed in the url
$cfg['use_rewrite'] = true;

// Set the date format to display dates in luxbum.
$cfg['date_format'] = '%A %e %B %Y';

// Set the min size in byte to generate a preview image
$cfg['min_size_for_preview'] = 0;

// ...
$cfg['max_file_size'] = 2000;

// Allow meta data
// Choices : true / false
$cfg['show_meta'] = true;

// Allow comments
// Choices : true / false
$cfg['show_comment'] = true;

// Allow slideshow
// Choices : true / false
$cfg['show_slideshow'] = true;

// create a fading effect into two images on the slideshow page
// Choices : true / false
$cfg['slideshow_fading'] = true;

// Time between two images on the slideshow page
$cfg['slideshow_time'] = 4;

// Allow selections
// Choices : true / false
$cfg['show_selection'] = true;

// Allow to download the selection
// Choices : true / false
$cfg['allow_dl_selection'] = false;

// Driver to use to generate thumbs
// Choices : gd / imagemagick 
$cfg['image_generation_driver'] = 'imagemagick';




/******************************************************************
  Internal configuration 
  MUST NOT BE EDITED. Don't change anything bellow this line !!!!!!!! 
**/
$cfg['color_theme_path'] = TEMPLATE_DIR.$cfg['template'].'/themes/'.$cfg['template_theme'];

// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['debug'] = false;

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = dirname(__FILE__).'/../tmp';

// The folder in which the templates of the application are located.
$cfg['template_folders'] = array(dirname(__FILE__).'/../'.TEMPLATE_DIR.$cfg['template']);

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';

// url to index file (not necessary index.php)
$cfg['url_index'] = $cfg['url_base'] . $cfg['index_file'];

// Middleware to load
if ($cfg['debug']) {
   $cfg['middleware_classes'] = array('Pluf_Middleware_Debug', 
                                      'Pluf_Middleware_Tidy');
}
else {
   $cfg['middleware_classes'] = array();
}

// Allowed tags
$cfg['template_tags'] = array(
	'style'          => 'Pluf_Template_Tag_Style',
	'NavigationMenu' => 'Pluf_Template_Tag_NavigationMenu'
);


// Don't forget to return the array
return $cfg;

?>