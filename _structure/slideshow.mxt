  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/HTML; charset=utf-8"/>
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css"/>
    <meta http-equiv="Content-Language" content="fr"/>

    <title>Slideshow</title>

<style type="text/css">
#slide {
  /*background-color: black;*/
  margin-left:auto;
  margin-right: auto;
  height:<mx:text id="slide_height_full"/>;
  width:<mx:text id="slide_width"/>;
  position:relative;
}

#cont_1, #cont_2{
  height:<mx:text id="slide_height"/>;
  position:absolute;
  left:0;
  top:0;
  background:transparent;
  text-align:center;
  width:<mx:text id="slide_width"/>;
}

#image_slide1 , #image_slide2{
  height:inherit;
  height:<mx:text id="slide_height"/>;

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
  width:<mx:text id="slide_width"/>;
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
  var photosURL = new Array();

   <mx:bloc id="photosSRC">
   photosURL[<mx:text id="i"/>] = "<mx:text id="photo"/>";
   </mx:bloc id="photosSRC">


current_fg_index = 0;
current_bg_index = 1;

var idshowcontroltimeout;
var slide;
var id_slidetimeout;
var in_slideshow = 0;
var mayControlsBeHidden = 1;

// Fade
var foreImage = 'image_slide1';
var backImage = 'image_slide2';
var backImageLoaded = false;
var fade_cursor = 0;

// Options
var smoothtrans = true; // Set this to false to prevent any fading effect
var slide_speed = <mx:text id="defaultspeed"/>;

// Startup script
window.onload = function () {
  idshowcontroltimeout = window.setTimeout(slide_hideControl, 4000);

  document.getElementById(backImage).style.visibility = 'hidden';
  document.getElementById(foreImage).onload = function(){slide_addBinds();return false;}
  document.getElementById(backImage).onload = function(){backImageLoaded=true;return false;}

  document.getElementById(foreImage).src = photosURL[current_fg_index];
  document.getElementById(backImage).src = photosURL[current_bg_index];

  if (smoothtrans==false) {
    smoothtrans = false;
    setOpacity(backImage,1);
  }

  document.getElementById("smoothtrans").onchange = function(){
    if (document.getElementById("smoothtrans").checked==true) {
      smoothtrans = true;
      setOpacity(backImage,0);
    }
    else {
      smoothtrans = false;
      setOpacity(backImage,1);
    }
  }

  document.getElementById("slide_speed").onchange = function(){
    slide_speed = document.getElementById("slide_speed").value;
  }
}

function slide_addBinds() {
   document.getElementById("slide").onmousemove = slide_showControl;
   window.onkeydown = slide_keyboardHook;
   document.getElementById("s_play").onclick = function(){slide_play(1); return true;};
   document.getElementById("s_pause").onclick = function(){slide_pause(); return true;};
   document.getElementById("s_info").onclick = function(){slide_img_info_doquery(); return true;};
   document.getElementById("s_options").onclick = function(){slide_showOptions(); return true;};
   document.getElementById("s_help").onclick = function(){slide_showHelp(); return true;};
   document.getElementById("s_prev").onclick = function(){slide_changeimage(-1); return true;}
   document.getElementById("s_next").onclick = function(){slide_changeimage(+1); return true;}
   document.getElementById("cont_1").onclick = function(){slide_changeimage(+1); return true;}
   document.getElementById("cont_2").onclick = function(){slide_changeimage(+1); return true;}
   document.getElementById(backImage).onload = function(){backImageLoaded=true;return false;}
   document.getElementById(foreImage).onload = function(){document.getElementById(backImage).style.visibility="visible";return false;}
   document.getElementById("controle").onmouseover = function(){mayControlsBeHidden=0;}
   document.getElementById("controle").onmouseout = function(){mayControlsBeHidden=1;idshowcontroltimeout = window.setTimeout(slide_hideControl, 4000);}
}

function slide_removeBinds() {
   document.getElementById("slide").onmousemove = function(){slide_showControl(); return true;};
   document.getElementById("s_play").onclick = function(){return true;};
   document.getElementById("s_pause").onclick = function(){return true;};
   document.getElementById("s_info").onclick = function(){return true;};
   document.getElementById("s_options").onclick = function(){return true;};
   document.getElementById("s_help").onclick = function(){return true;};
   document.getElementById("s_prev").onclick = function(){return true;}
   document.getElementById("s_next").onclick = function(){return true;}
   document.getElementById("cont_1").onclick = function(){return true;}
   document.getElementById("cont_2").onclick = function(){return true;}
   document.getElementById("controle").onmouseover = function(){return true;}
   document.getElementById("controle").onmouseout = function(){return true;}
}


/*
 *  This function is used to change the picture; if increment=1, then
 *  the next picture is loaded...
 */
function slide_changeimage(increment) {
  if (increment != 0 && current_bg_index!= current_fg_index+increment) {
    current_bg_index = current_fg_index+increment;
    if (current_bg_index<0) current_bg_index=photosURL.length - 1;
    if (current_bg_index>(photosURL.length - 1)) current_bg_index=0;

    backImageLoaded = false;

    document.getElementById(foreImage).onload = function(){return false;}
    document.getElementById(backImage).onload = function(){backImageLoaded=true;return false;}
    document.getElementById(backImage).src = photosURL[current_bg_index];
  }

  if (backImageLoaded == false)
    window.setTimeout("slide_changeimage(0)", 500);
  else {
    if (smoothtrans==false)
      slide_switchFgBg();
    else
      slide_fadeFromForeToBack();
  }
}


/*
 * To prevent more than one timeout (if the user clicks more than once
 * the play button) I had to add the event parameter;
 * event=1, user has clicked the button, event=2, the function has been
 * called by a timeout.
 */
function slide_play(event) {
  if (in_slideshow==0 && event==1) {
    // Starting a new slideshow
    in_slideshow = 1;
    id_slidetimeout = window.setTimeout("slide_play(2)", slide_speed*1000);
  }
  else {
    if (event == 2) {
      if (backImageLoaded == false) {
        // We want to display the next picture, but it still isn't fully loaded
        // in the buffer - we wait half a second...
        id_slidetimeout = window.setTimeout("slide_play(2)", 500);
      }
      else
        slide_changeimage(+1);
    }
  }
}


function slide_pause() {
  in_slideshow = 0;
  window.clearTimeout(id_slidetimeout);
  document.getElementById("image_slide1").onload = function(){return false;};
}

/* This function is used for the fading effect - it fades in the background
 * picture, and at the same time fades out the foreground.
 */
function slide_fadeFromForeToBack()
{
  var delay = 100;
  var i = 0;
  var bgop=0;
  var fgop=1;

  document.getElementById(backImage).style.visibility = 'visible';

  if (fade_cursor < 1.01)
  {
    bgop = fade_cursor;
    fgop = 1-fade_cursor;
    setOpacity(foreImage,fgop);
    setOpacity(backImage,bgop);
    fade_cursor += 0.2;
    window.setTimeout(slide_fadeFromForeToBack, delay);
  }
  else {
    // End of the effect, we put backImage on the foreground
    fade_cursor = 0;

    slide_switchFgBg();

    setOpacity(foreImage,1);
    setOpacity(backImage,0);
  }
}

function slide_switchFgBg()
{
  var zTemp = document.getElementById(foreImage).style.zIndex;
  document.getElementById(backImage).style.zIndex = zTemp;
  document.getElementById(foreImage).style.zIndex = document.getElementById(backImage).style.zIndex;

  var iTemp = foreImage;
  foreImage = backImage;
  backImage = iTemp;

  current_fg_index = current_bg_index;
  current_bg_index = current_fg_index+1;

  if (current_bg_index<0) current_bg_index=photosURL.length - 1;
  if (current_bg_index>(photosURL.length - 1)) current_bg_index=0;

  backImageLoaded = false;
  document.getElementById(backImage).style.visibility = 'hidden';
  document.getElementById(foreImage).style.visibility = 'visible';
  document.getElementById(backImage).onload = function(){backImageLoaded=true;return false;} ;
  document.getElementById(backImage).src = photosURL[current_bg_index];

  if (in_slideshow==1)
    id_slidetimeout = window.setTimeout("slide_play(2)", slide_speed*1000);

  if (document.getElementById("s_exif_info").style.visibility == "visible")
    slide_img_info_doquery();
}

function getOpacity(id)
{
  return window.getComputedStyle(document.getElementById(id),null).getPropertyValue("opacity");
}

/* There actually isn't any standard Opacity property. We use
 * navigator-specific ones as many of them implement some kind of opacity effect...
 */
function setOpacity(id,value)
{
  document.getElementById(id).style.opacity = value; //CSS3 - Gecko-based
  document.getElementById(id).style.KhtmlOpacity = value; // Konqueror - not tested
  document.getElementById(id).style.filter = "alpha(opacity:"+(value*100)+")"; //IE
}

/* Displays the control bar */
function slide_showControl() {
  if (document.getElementById("controle").style.visibility != "visible") {
    document.getElementById("controle").style.visibility = "visible";
    idshowcontroltimeout = window.setTimeout(slide_hideControl, 4000);
  }
}

/* Hide the control bar */
function slide_hideControl() {
  if (mayControlsBeHidden == 1) {
    if (document.getElementById("controle").style.visibility != "hidden") {
      document.getElementById("controle").style.visibility = "hidden";
      window.clearTimeout(idshowcontroltimeout);
    }
  }
}

/* Hide any message box */
function slide_closeMsgBox()
{
  slide_hideExifInfos();
  slide_hideOptions();
  slide_hideHelp();
  slide_addBinds();
  return true;
}

function slide_show_img_info(rawhtml)
{
  //alert (rawhtml);
  slide_removeBinds();

  document.getElementById("s_play").onclick = slide_closeMsgBox;
  document.getElementById("s_pause").onclick = slide_closeMsgBox;
  document.getElementById("s_info").onclick = slide_closeMsgBox;
  document.getElementById("s_help").onclick = slide_closeMsgBox;
  document.getElementById("s_prev").onclick = slide_closeMsgBox;
  document.getElementById("s_next").onclick = slide_closeMsgBox;
  document.getElementById("cont_1").onclick = slide_closeMsgBox;
  document.getElementById("cont_2").onclick = slide_closeMsgBox;
  document.getElementById("s_exif_info").onclick = slide_closeMsgBox;

  document.getElementById("s_exif_info").innerHTML = rawhtml;

  slide_showExifInfos();
}

function slide_showOptions() {
  slide_removeBinds();

  document.getElementById("s_play").onclick = slide_closeMsgBox;
  document.getElementById("s_pause").onclick = slide_closeMsgBox;
  document.getElementById("s_info").onclick = slide_closeMsgBox;
  document.getElementById("s_help").onclick = slide_closeMsgBox;
  document.getElementById("s_prev").onclick = slide_closeMsgBox;
  document.getElementById("s_next").onclick = slide_closeMsgBox;
  document.getElementById("cont_1").onclick = slide_closeMsgBox;
  document.getElementById("cont_2").onclick = slide_closeMsgBox;
  document.getElementById("s_options").onclick = slide_closeMsgBox;
  document.getElementById("s_exif_info").onclick = slide_closeMsgBox;

  if (smoothtrans == true)
    document.getElementById("smoothtrans").checked = true;
  else
    document.getElementById("smoothtrans").checked = false;

  document.getElementById("slide_speed").value = slide_speed;
  document.getElementById("s_options_box").style.visibility = "visible";
}

function slide_showHelp()
{
  //alert (rawhtml);
  slide_removeBinds();

  document.getElementById("s_play").onclick = slide_closeMsgBox;
  document.getElementById("s_pause").onclick = slide_closeMsgBox;
  document.getElementById("s_info").onclick = slide_closeMsgBox;
  document.getElementById("s_options").onclick = slide_closeMsgBox;
  document.getElementById("s_prev").onclick = slide_closeMsgBox;
  document.getElementById("s_next").onclick = slide_closeMsgBox;
  document.getElementById("cont_1").onclick = slide_closeMsgBox;
  document.getElementById("cont_2").onclick = slide_closeMsgBox;
  document.getElementById("s_exif_info").onclick = slide_closeMsgBox;
  document.getElementById("s_help_box").onclick = slide_closeMsgBox;
  document.getElementById("s_help").onclick = slide_closeMsgBox;

  document.getElementById("s_help_box").style.visibility = "visible";
}


function slide_hideOptions() {
  document.getElementById("s_options_box").style.visibility = "hidden";
}

function slide_hideHelp() {
  document.getElementById("s_help_box").style.visibility = "hidden";
}


function slide_showExifInfos() {
  document.getElementById("s_exif_info").style.visibility = "visible";
}


function slide_hideExifInfos() {
  document.getElementById("s_exif_info").style.visibility = "hidden";
}

/* XmlHTTPRequest function to get the EXIF data */
function slide_img_info_doquery() {
  var xmlhttp = getHTTPObject();
  xmlhttp.open("POST", "slideshow.php",true);
  xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xmlhttp.send("curimg="+photosURL[current_fg_index]);
}

/* Keyboard events */
function slide_keyboardHook(event)
{

  switch (event.which) {
    case 32: // space
      if (in_slideshow==0)
        slide_play(1);
      else
        slide_pause();
      break;

    case 37: // left arrow
      slide_changeimage(-1);
      break;

    case 39: // right arrow
      slide_changeimage(1);
      break;

    case 72: // h
      slide_showHelp();
      break;

    case 73: // i
      slide_img_info_doquery();
      break;

    case 79: // o
      slide_showOptions();
      break;

    default:
      break;
  }
}


/* XmlHTTPRequest standard function*/
function getHTTPObject()
{
  var xmlhttp = false;

  /* The IE part... */
  /*@cc_on
  @if (@_jscript_version >= 5)
     try
     {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
     }
     catch (e)
     {
        try
        {
           xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (E)
        {
           xmlhttp = false;
        }
     }
  @else
     xmlhttp = false;
  @end @*/

  /* we try to create the object */
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined')
  {
     try
     {
        xmlhttp = new XMLHttpRequest();
     }
     catch (e)
     {
        xmlhttp = false;
     }
  }

  if (xmlhttp)
  {
     /* we define what should be done once the server has answered */
     xmlhttp.onreadystatechange=function()
     {
        if (xmlhttp.readyState == 4) /* 4 : "complete" */
        {
           if (xmlhttp.status == 200) /* HTTP Code 200 "Ok" */
           {
              slide_show_img_info(xmlhttp.responseText);
           }
        }
     }
  }
  return xmlhttp;
}
//-->
</script>


  

<div id="slide">
  <div id="cont_1"><img src="" id="image_slide1" alt="Picture buffer 1" /></div>
  <div id="cont_2"><img src="" id="image_slide2" alt="Picture buffer 2" /></div>

  <div id="controle">
    <div id="s_next">&raquo;</div>
    <div id="s_prev">&laquo;</div>
    <div id="s_play">Play</div>
    <div id="s_pause">Pause</div>
    <div id="s_info"><img class="myicon" src="slideshow.php?base64=i" alt="Informations about the picture"/></div>
    <div id="s_options"><img class="myicon" src="slideshow.php?base64=o" alt="Options of the slideshow" /></div>
    <div id="s_help"><img class="myicon" src="slideshow.php?base64=h" alt="About / Keyboard shortcuts"/></div>
    <br />
  </div>

  <div id="s_options_box">
    <h2>Options</h2>
    <input type="checkbox" id="smoothtrans" checked="checked"/> Activate fading<br />
    <input type="text" id="slide_speed" size="1" maxlength="1"  /> Time between each picture (in seconds) <br />
  </div>
  <div id="s_exif_info"></div>
  <div id="s_help_box">
    <h2>About</h2>
    <p><strong>ESS</strong> by <strong>Yann HAMON</strong> - December 2005</p>
    <p id="k_shortcuts">Keyboard Shortcuts:</p>
    <ul>
      <li><strong>Space</strong> : Play/Pause the slideshow</li>
      <li><strong>Arrows, left and right</strong> : Previous, next picture</li>
      <li><strong>i</strong> : Informations about the picture</li>
      <li><strong>o</strong> : Options</li>
      <li><strong>h</strong> : Display this help</li>
    </ul>
  </div>
</div>

</body></html>