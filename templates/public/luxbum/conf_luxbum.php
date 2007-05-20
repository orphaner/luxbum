<?php

define ('LIMIT_THUMB_PAGE', 6);
define ('MAX_NAVIGATION_ELEMENTS', 6);

define ('VIGNETTE_THUMB_W', 125);
define ('VIGNETTE_THUMB_H', 125);
define ('VIGNETTE_CROP', false);

define ('INDEX_THUMB_W', 85);
define ('INDEX_THUMB_H', 85);
define ('INDEX_CROP', false);

define ('PREVIEW_W', 650);
define ('PREVIEW_H', 485);
define ('PREVIEW_CROP', false);



$GLOBALS['default_css'] = 'light';
$GLOBALS['themes_css'] = array (
   'light' =>'Thème Blanc',
   'light2' =>'Thème Blanc Alternatif',
   'dark' =>'Thème Noir'/*,
   'l62' =>'Thème Linux 62'*/
   );
   
$GLOBALS['video_player'] = array (
   'light' => 'FlowPlayerWhite',
   'light2' => 'FlowPlayerWhite',
   'dark' => 'FlowPlayerBlack'
);   
$GLOBALS['video_player_bgcolor'] = array (
   'light' => '#ffffff',
   'light2' => '#ffffff',
   'dark' => '#2C2C2C'
);
?>