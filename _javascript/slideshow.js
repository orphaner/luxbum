
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


// Startup script
window.onload = function () {
   idshowcontroltimeout = window.setTimeout(slide_hideControl, 4000);

   document.getElementById(backImage).style.visibility = 'hidden';
   document.getElementById(foreImage).onload = function(){slide_addBinds();return false;}
   document.getElementById(backImage).onload = function(){backImageLoaded=true;return false;}
   getCurrentPhoto(current_fg_index, "fg");
   getCurrentPhoto(current_bg_index, "bg");

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
      getCurrentPhoto(current_bg_index, "bg");
   }

   if (backImageLoaded == false) {
      window.setTimeout("slide_changeimage(0)", 500);
   }
   else {
      if (smoothtrans==false) {
         slide_switchFgBg();
      }
      else {
         slide_fadeFromForeToBack();
      }
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
function slide_fadeFromForeToBack() {
   var delay = 100;
   var i = 0;
   var bgop=0;
   var fgop=1;

   document.getElementById(backImage).style.visibility = 'visible';

   if (fade_cursor < 1.01) {
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

function slide_switchFgBg() {
   var zTemp = document.getElementById(foreImage).style.zIndex;
   document.getElementById(backImage).style.zIndex = zTemp;
   document.getElementById(foreImage).style.zIndex = document.getElementById(backImage).style.zIndex;

   var iTemp = foreImage;
   foreImage = backImage;
   backImage = iTemp;

   current_fg_index = current_bg_index;
   current_bg_index = current_fg_index+1;

   if (current_bg_index<0) {
      current_bg_index=photosURL.length - 1;
   }
   if (current_bg_index>(photosURL.length - 1)) {
      current_bg_index=0;
   }

   backImageLoaded = false;
   document.getElementById(backImage).style.visibility = 'hidden';
   document.getElementById(foreImage).style.visibility = 'visible';
   document.getElementById(backImage).onload = function() {
   	  backImageLoaded=true;
   	  return false;
   } ;
   getCurrentPhoto(current_bg_index, "bg");

   if (in_slideshow==1) {
      id_slidetimeout = window.setTimeout("slide_play(2)", slide_speed*1000);
   }

   if (document.getElementById("s_exif_info").style.visibility == "visible") {
      slide_img_info_doquery();
   }
}

function getOpacity(id) {
   return window.getComputedStyle(document.getElementById(id),null).getPropertyValue("opacity");
}

/* There actually isn't any standard Opacity property. We use
 * navigator-specific ones as many of them implement some kind of opacity effect...
 */
function setOpacity(id,value) {
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
function slide_closeMsgBox() {
   slide_hideExifInfos();
   slide_hideOptions();
   slide_hideHelp();
   slide_addBinds();
   return true;
}

function slide_show_img_info(rawhtml) {
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

function slide_showHelp() {
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
   var xmlhttp = getHTTPObject('infos');
   xmlhttp.open("POST", "slideshow.php",true);
   xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
   xmlhttp.send("action=exif&dir="+currentDir+"&file="+photosURL[current_fg_index]);
}

/* XmlHTTPRequest function to get the next Photo Path */
function getCurrentPhoto(index, pos) {
   var xmlhttp = getHTTPObject('photo');
   xmlhttp.open("POST", "slideshow.php", true);
   xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
   xmlhttp.send("action=photo&dir="+currentDir+"&file="+photosURL[index]);
   
   if (xmlhttp) {
      /* we define what should be done once the server has answered */
      xmlhttp.onreadystatechange=function() {
         /* 4 : "complete" */
         if (xmlhttp.readyState == 4) {
            /* HTTP Code 200 "Ok" */
            if (xmlhttp.status == 200) {
               if (pos == "fg") {
                  document.getElementById(foreImage).src = /*photosDir+currentDir+"/apercu/"+*/photosURL[index];
               }
               else if (pos == "bg") {
                  document.getElementById(backImage).src = /*photosDir+currentDir+"/apercu/"+*/photosURL[index];
               }
            }
         }
      }
   }
}


/* Keyboard events */
function slide_keyboardHook(event) {

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
function getHTTPObject (myAction) {
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
   if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
      try {
         xmlhttp = new XMLHttpRequest();
      }
      catch (e) {
         xmlhttp = false;
      }
   }

   if (xmlhttp) {
      /* we define what should be done once the server has answered */
      xmlhttp.onreadystatechange=function() {
         /* 4 : "complete" */
         if (xmlhttp.readyState == 4) {
            /* HTTP Code 200 "Ok" */
            if (xmlhttp.status == 200) {
               if (myAction == 'infos') {
                  slide_show_img_info(xmlhttp.responseText);
               }
            }
         }
      }
   }
   return xmlhttp;
}
