<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <title><mx:text id="titre_page"/></title>
    <mx:bloc id="favicon"><link rel="shortcut icon" mXattribut="href:favicon"/></mx:bloc id="favicon">
    <mx:bloc id="stylesheet">
    <link mXattribut="rel:rel;href:href;title:title" type="text/css" /></mx:bloc id="stylesheet">
  </head>

  <body id="body">  
    <div id="page">
      <div id="center"> 
        <h1 class="vig_titre"><mx:text id="nom_dossier"/></h1>
          <div id="apercu">
            <div id="menunav">
              <ol class="tree">
                <li>&#187; <a href="index.php"><strong>Accueil</strong></a></li>
                <li>&#187; <mx:text id="nom_dossier"/></li>
              </ol>
            </div>

            <div class="liste_apercu">
              <mx:bloc id="liste">
              <div mXattribut="class:styleCol">
                <div class="num_photo"><mx:text id="num_photo"/></div>
                <div mXattribut="class:style">
                  <a mXattribut="href:lien" target="affichage"><img mXattribut="src:vignette;alt:alt;title:title"/></a>
                </div>
              </div></mx:bloc id="liste">

              <div class="spacer"></div>
              <div id="aff_page">
                <mx:text id="aff_page"/>
              </div>
            </div>
          </div>

          <div id="iframeaffichage">
            <iframe mXattribut="src:affichage" frameborder="0" scrolling="no" name="affichage"></iframe>
          </div>
      </div>

      <div id="footer"><a href="http://nico.tuxfamily.org/Projets/Support-LuxBum">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
    </div>
  </body>
</html>