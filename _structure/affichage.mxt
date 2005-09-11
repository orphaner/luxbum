<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />

    <title>Affichage</title>
    <mx:bloc id="stylesheet">
      <link mXattribut="rel:rel;href:href;title:title" type="text/css" />
    </mx:bloc id="stylesheet">

    <script type="text/javascript">if (parent.frames.length < 1){document.location.href = '<mx:text id="redirect_script"/>';}</script> 
                                                              </head>
  <body id="body_affichage">  
    <div id="affichage_photo">
      <a mXattribut="href:lien" onclick="window.open(this.href,'',''); return false;" ><img mXattribut="src:photo;alt:alt;title:title" border="0" /></a>
      <br />
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="30px"><mx:bloc id="back"><a mXattribut="href:lien;target:target"><img src="_images/navig/back.gif" alt="back" border="0"></a></mx:bloc id="back"></td>
          <td><span class="description"><mx:text id="desc"/></span>
            <mx:bloc id="exif"> + <a href="#" onclick="window.open('<mx:text id="lien"/>','Comments','width=350,height=400,scrollbars=yes,resizable=yes');">Informations sur l'image</a></mx:bloc id="exif">
          </td>
          <td width="30px"><mx:bloc id="forward"><a mXattribut="href:lien;target:target"><img src="_images/navig/forward.gif" alt="back" border="0"></a></mx:bloc id="forward"></td>
        </tr>
      </table>
    </div>
    <noscript>
      Cette page est issue de l'affichage d'un cadre. <a mXattribut="href:redirect_noscript">Revenir à la navigation vers la galerie photo</a>.
    </noscript>
  </body>
</html>
