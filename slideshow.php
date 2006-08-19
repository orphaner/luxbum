<?php

function extractPhotoName ($file) {
   $tab = array ();
   $tab = split('-', $file);
   return $tab[count($tab)-1];
  }

include_once ('common.php');
include_once (FONCTIONS_DIR.'luxbum.class.php');
include_once (FONCTIONS_DIR.'class/luxbumimage.class.php');
include_once (FONCTIONS_DIR.'class/files.php');

if (SHOW_SLIDESHOW == 'off') {
   exit (__('Slideshows are disabled.'));
}

/* XMLHTTPREQUEST code */
if (isset($_POST['action']) && $_POST['action']=='exif') {
   $dir = $_POST['dir'];
   $file = extractPhotoName($_POST['file']);
   $lux = new luxBumImage ($dir, $file);
   verif::photo($dir, $file);

   if ($lux -> findDescription () == true) {
      echo '<h2>'.__('Date / Description').'</h2>';
      echo $lux->getDateDesc ();
   }

   echo '<h2>'.__('EXIF data').'</h2>';
   if (SHOW_EXIF == 'on') {   
      $lux->exifInit ();
      if ($lux->exifExists ()) {
         echo '<strong>'.__('Camera').': </strong>'.$lux->getExifCameraMaker ().' '.$lux->getExifCameraModel ().'<br />';
         echo '<strong>'.__('Exposure').': </strong>'.$lux->getExifExposureTime ().'<br />';
         echo '<strong>'.__('Aperture').': </strong>'.$lux->getExifAperture ().'<br />';
         echo '<strong>'.__('Focal length').': </strong>'.$lux->getExifFocalLength ().'<br />';
         echo '<strong>'.__('Flash').': </strong>'.$lux->getExifFlash ().'<br />';
         echo '<strong>'.__('ISO').': </strong>'.$lux->getExifISO ().'<br />';
         echo '<strong>'.__('Date').': </strong>'.$lux->getExifCaptureDate ().'<br />';
      }
      else {
         echo __('Aucune information exif disponible');
      }
   }
   else {
      echo __('Exif Data are disabled.');
   }
   die();
}
// Return pictures
elseif (isset($_GET['base64'])) {
   $_GET['base64'] = addslashes($_GET['base64']);

   define('IMG_INFO', 'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAMAAABhEH5lAAACWFBMVEUAAAABAwMCAwMDBQUFCAkECgsGDxELERILExUMExUNFxgQGRsOICQUHyARJSgbKi0aKy4jODwgO0AePEMjPEIdQ0spQkcmSVEwS1E0UVcnV2EzW2UoY287ZW4uc4FIcHkweodJeIFKeoQ9gpBOfIVNgIo+ipo6kKRKi5g6kqRTjJg+lKZAlqZAlqhZjZhClqhbjphZj5pCmKxMlaZEmKxGnKxGnK5InK5NnKxInrBanKpMorRMpLROpLhQprhSqLhSqLpSqLxXqLlUqrxWrL5YrsBasMJasMRassJtq7hcssRpr71etMZitshguMpiuMpiuspmusxkvM5ovtBovtJowNB5uchowNJqwNBsv9BqwNJqwNRswtZuwtRuwtZ2wdF9v81uxthwxtZwxthwxtpyxthwyNhyyNhyyNp6xdd0yNqDxNN0ytx2zN54zN57y9x4zOB4zt54zuB6zuB40OB60OB60OJ60uKDz9580uZ81OR81OZ+1OiA1OaC0+Z+1uZ+1uiA1uiC1uiA2OqG1ueC2OqC2OyE2OqL1uSC2uyE2uyE2u6G2uyG2+uG3O6I3PCG3vCI3vCI3vKK3vCN3e6K4PKK4PSM4PSK4vKP4PGK4vSn1+GM4vSM4vaY3uyg2+eM5PaO5PaO5PiQ5PaQ5PiO5vaQ5viQ5vqS5vib4/CQ6PqS6PqS6PyU6PqU6PyS6vq72+KU6vyU6v6W6v6x4eyW7PyW7P6Y7P+24eqY7v+44+zM7PPV6u/W6/DY7fHY7fLZ7vPh8fXe8/jg9fnq9Pbt9/rw+vz///9tIGRhAAABFklEQVR42m3QMUpDQRRG4f9NXjAxCSqBYJQUioqCkFIQtRO0dx2uwcYduAE7G7Gxs7JzDwZECEGJGPJ8M3fuvZMZC1N6ylN+GQAAS8sr0RVfEQAyAGicXIrGoC93k/nqXvVUvKik2c37DBVg9bYpzpfei+X+8BsVLFy3mKjs7NkRC20PnEFvU6ko/Vq2Vbofa3eQ40K9F8dPx49WYgzriyY/dERM9Y10PrWOVGp5XZU9yUEbrDHOkqmaoImF6SEiBuagGnIadAJxUF1IojElqME9hSTkEpKEIDIucryFoClEg1rOqmmCCvxrn4lPuzD7z17tBzLA7J4Vrtq0zg6FRvIn0TuKjkXFTT91joNau9Wojqko8X+/7Nq6vNr0khMAAAAASUVORK5CYII=');
   define('IMG_OPTION', 'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAMAAABhEH5lAAACK1BMVEUAAAABAwECAwIDBQMFCAUFCQQHDgYMEAsMEgsNEgwPFgwRGBARHg4WHhMUIxEdKBscKRomNiIkOB8jOR4nOSMjPx0tPyorRSY0SDAuUSc4TjQxXCg5VzNBYTs4ay47cS5ObEhQcklHej1RdEpVdk5Vdk9JgT5VekxHhjxHiDxUg0pLij5NjD5NjEBPjEBPjkRchVNRjkRXjE5TkkRTkkZhh1phiFlVkkZjiFpVlEhZk01ZmExZmE5ZmkxbmlBklFhfnlJfnlRfoFRhoFRjolRnplpnplxnqFppqFx2pG1vrGBvrmJvsGJ1tGp9sHF1tmh1tmp3tmh3tmp3tmx5tWx5uG59vHB9vHJ/vHB9vnB/vnB/vnKFuneFvHyBwHSDwnaFwniFxHaFxHiFxniNwIOHxniHxnqJyH6OxoOJyn6LyoCUxYiLzICRyIWPyoKNzICPzICNzoKPzoKRzoKSzYaP0ISR0IaT0ISR0oST0YST0oaazZCV0oiT1IiV1IiV1IqX1IilyZ2Z1I2X1oqX1oyZ1oyX2IqX2IyZ2IyZ2I6b14+mz6CZ2o6f1pOb2o6b2pCd2o6d2pCb3I6sz6Wd3JCd3JKt0Kaf3JCn1p6d3pKf3pKf3pSh3pKh3pSf4JKu1aWy0qyq2aCh4JSh4Jaj4Jaj4pSj4pal4pil5Ji727a64rLJ4cXK4sbR483P5srO6snX59TZ6dba6tja7dXi8t/j8uDw+O/3+/f////oEng5AAABGElEQVR42m3Qyy5DURSH8f+50FNV90FbFCESYdSJiMTE3CN4ACNv4iGMTIyEiVcQTyBxSeRITpW2Z++191rrbJMOfcPf8IsAAJhfbOlgWFQAEAFA4+ScXcX++W4woc7lmpBx6oLcvCoSoHU168ZlSaW3fvunjxS1CyFjrPNKIchRv4jR3XHlqJzb2w1EhmgfKU7FWterV/FGfq9BFmbi9HhU0kEdDyZq17yvJIvrzrNpAF8MHAp7mYqFA0kA5B1osopIal+WPQVgqQHccqjACWTTcb8brTen6VGdDD4TlD1232+rtfjpWqxIYSJg62xsHal4Jq/mIyTAkNpOVVW8qM0VCRDyoqOqqsK/OU/mIFuZy7Lcjsb4vz8pD7NVXp3lCgAAAABJRU5ErkJggg==');
   define('IMG_HELP', 'iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAMAAABhEH5lAAACHFBMVEUAAAADAwIEAwEGBQMJBwUNCQQTDQYUEAsXEQsWEgwcFQwdFxAkHRMpHA4tIRExJxsyKBpCNCJINh9KNh5INyNVOx1NPSpaQiZYRjBfSzRtTSdwUzN+Vih5XTuTZC6DaEicai6NbkmQcEqPck+Rck6icz2ZdUytej6pfUqmgFO4fjylglq4gDyng1qog1m8gj6+hD6+hEDAhEC3hk7AhkTChkTEikTEikbBi03GikbGjEi9jljKkEzKkE7KkkzMklDOlFDQllDQllLQllTSmFTUmlbHnm3WnFjYnlrYnlzYoFraoFzcol7gpGDcpmbepmLgpmLgqGLiqmbmrGrmrmjmrmrmrmzormjormrormzssGzssG7stHDutHDotXzutHLwtHDfuIrutnDwtnDwtnLytnLhuovpuIHyuHTyuHb0unb2unb2unjzvH32vHb2vHjsv4P4vHj2vnjuv4n4vnj4vnr4wHroxJn6wnz6wn7pxZz8woD1xYb+wn78xID6xIb+xID/xID+xoL/xoL/xoT8yIb9yYT/yIT/yIb1zI3/yob/yoj/zIj3z4//zIr/zor/zoz/0Ir/z5H/0Iz30KH/0I7/0o7/0pD/1I7u1bf/1JD/1JLv1bjs1r7/1pL/1pTt2L7/2JL/2JT/2Jb/2pT/2pb/2pj/3Jj/3bL138b/3rL+3rj66NP36dj969b77dz/7dj87t388eX////smmXuAAABFUlEQVR42m3Qr07DUBTH8V9vOyhb+JOQQJdNYRCAAQeeBME78AokvAeWBMUz4MEiMCRYxEboQra1g7bbPfeccy9mkq/8yG8EAMDm1oan33EAgAgAOmdXokHk5bFcUvcmU7FB1Pu7gSIGstu2kiNHavkonyLB6rWw49Bb4fd5cOfFxKDfF7K0sxanx9WsrvdhcKksYj94IKZTN1W3bZITYmIqn7MENZFKmqQsLETNRQT3FXwwrUQlMNumjuAeWBEiMYuhqiXn55gsHDuyHGN2SK5p7FvvdSzKWuQGQxfE+3CweyrqnCtgMLv3ysEr1AtTMUUM/HzvzUXyzycRaUaCGMAkzzxrocrliJdzkG6vd1rTpqrwf3/bdcCZIzVqOAAAAABJRU5ErkJggg==');

   switch ($_GET['base64'])
   {
      case 'i': header("Content-type: image/png"); echo base64_decode(IMG_INFO); break;
      case 'o': header("Content-type: image/png"); echo base64_decode(IMG_OPTION); break;
      case 'h': header("Content-type: image/png"); echo base64_decode(IMG_HELP); break;
   }
   die();
}

// Return image path
else if (isset($_POST['action']) && $_POST['action']=='photo') {
   $dir = $_POST['dir'];
   $file = extractPhotoName($_POST['file']);
   echo $file;
   $luxAff = new luxBumImage ($dir, $file);
   $luxAff->getAsPreview();
}

?>