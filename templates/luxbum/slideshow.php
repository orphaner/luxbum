<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css"/>
    <meta http-equiv="Content-Language" content="fr"/>
    <script  type="text/javascript" src="_javascript/slideshow.js"></script>
    <title><?php lbPageTitle(); ?></title>

    <?php lbFavicon();?>
    <?php lbPageStyle();?>

<style type="text/css">
#slide {
  /*background-color: black;*/
  margin-left:auto;
  margin-right: auto;
  height:510px;
  width:650px;
  position:relative;
}

#cont_1, #cont_2{
  height:485px;
  position:absolute;
  left:0;
  top:0;
  background:transparent;
  text-align:center;
  width:650px;
}

#image_slide1 , #image_slide2{
  /*height:inherit;
  height:485px;*/
}

#image_slide1 {
  z-index:2;
}

#image_slide2 {
  z-index:1;
  opacity: 0;
}

#controle {
  background-color: black;
  text-align:center;
  color: white;
  font-weight:bold;
  height: 25px;
  position: absolute;
  bottom:0;
  left:0;
  z-index:1000;
  width:650px;
}

#s_prev, #s_next, #s_play, #s_pause, #s_info, #s_options, #s_help {
  display:inline;
  padding:0;
  margin:0;
  margin:0 5px;
  vertical-align:middle;
}

#s_info, #s_options, #s_help {
  position: relative;
  top:3px;
  margin: 0 0 0 2px;
}

#s_info {
  margin-left:40px;
}

#s_prev {
  float: left;
}

#s_next {
  float: right;
}

#s_exif_info, #s_options_box, #s_help_box {
  position:absolute;
  right:auto;
  bottom:auto;
  background-color: white;
  opacity: 0.8;
  filter : alpha(opacity=80);
  width:80%;
  left:10%;
  top:15%;
  text-align:left;
  border: 1px solid #666;
  padding: 10px 10px 20px 10px;
  visibility: hidden;
  z-index:4;
  -moz-border-radius:15px;
  border: 1px solid #999;
}

#s_exif_info h2, #s_options_box h2, #s_help_box h2 {
  text-align:center;
  color: black;
  font-size: 110%;
  font-weight: bold;
  margin: 5px 0 15px 0;
}


#controle div, #controle span, #s_prev, #s_next, #s_play, #s_pause, #s_options, #s_help {
  cursor: pointer;
}

#k_shortcuts {
  text-decoration:underline;
  margin-bottom:3px;
}

#s_help_box dt, #s_help_box dd { float: left; }
#s_help_box dt  { clear: left;font-weight:bold; }
#s_help_box dd  { clear: right; }
</style>

</head><body>

<script type="text/javascript">
<!--
var photosDir = "<?php lbPhotoDir();?>";
var currentDir = "<?php lbSlideshowDir();?>";
var photosURL = new Array();

<?php lbSlideshowPhotoList();?>

// Options
var smoothtrans = <?php lbSlideshowFadingText();?>; // Set this to false to prevent any fading effect
var slide_speed = <?php lbSlideshowTime();?>;
-->
</script>


  

<div id="slide">
  <div id="cont_1"><img src="" id="image_slide1" alt="<?php ___('Picture is loading');?>" /></div>
  <div id="cont_2"><img src="" id="image_slide2" alt="Picture buffer 2" /></div>

  <div id="controle">
    <div id="s_next">&raquo;</div>
    <div id="s_prev">&laquo;</div>
    <div id="s_play"><?php ___('Start');?></div>
    <div id="s_pause"><?php ___('Pause');?></div>
    <div id="s_info"><img class="myicon" src="slideshow.php?base64=i" alt="<?php ___('Informations about the picture');?>"/></div>
    <div id="s_options"><img class="myicon" src="slideshow.php?base64=o" alt="<?php ___('Options of the slideshow');?>" /></div>
    <div id="s_help"><img class="myicon" src="slideshow.php?base64=h" alt="<?php ___('About / Keyboard shortcuts');?>"/></div>
    <br />
  </div>

  <div id="s_options_box">
    <h2><?php ___('Options');?></h2>
    <input type="checkbox" id="smoothtrans" checked="<?php lbSlideshowFadingCheckbox();?>"/> <?php ___('Enable fading');?><br />
    <input type="text" id="slide_speed" size="1" maxlength="1" value="<?php lbSlideshowTime();?>" /> <?php ___('Time between each picture (sec)');?><br />
  </div>
  <div id="s_exif_info"></div>
  <div id="s_help_box">
    <h2><?php ___('About');?></h2>
    <p><strong>ESS</strong> par <strong>Yann HAMON</strong> adapté pour Luxbum</p>
    <p id="k_shortcuts"><?php ___('Keyboard shortcuts');?></p>
    <ul>
      <li><strong><?php ___('Space');?></strong> : <?php ___('Start/pause the slideshow');?></li>
      <li><strong><?php ___('Left/right arrows');?></strong> : <?php ___('Previous pictures, next');?></li>
      <li><strong>i</strong> : <?php ___('Exif Data about the picture');?></li>
      <li><strong>o</strong> : <?php ___('Options');?></li>
      <li><strong>h</strong> : <?php ___('Show this help');?></li>
    </ul>
  </div>
</div>

</body></html>
