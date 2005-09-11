<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
  <head>
    <meta http-equiv="Content-Type"
          content="text/html; charset=ISO-8859-1" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />

    <title><mx:text id="titre_page"/></title>
    <mx:bloc id="stylesheet">
      <link mXattribut="rel:rel;href:href;title:title" type="text/css" />
    </mx:bloc id="stylesheet">
  </head>

  <body id="body">  
    <div id="page">

      <div id="center"> 
        <h1 class="vig_titre"><mx:text id="nom_dossier"/></h1>

        <table border="0" cellpadding="0px" cellspacing="0px">
          <tr>
            <td>


              <div id="apercu">
                <div id="menunav">
                  <ol class="tree">
                    <li><a href="index.php">Accueil</a></li>
                    <li><mx:text id="nom_dossier"/></li>
                  </ol>
                </div>

                <div class="liste_apercu">
                  <table border="0" width="300px">

                    <mx:bloc id="liste"></mx:bloc id="liste">

                  </table>

                  <div id="aff_page">
                    <mx:text id="aff_page"/>
                  </div>
                </div>
              </div>

            </td>


            <td>
              <div id="iframeaffichage">
                <iframe mXattribut="src:affichage" frameborder="0" scrolling="no" name="affichage"></iframe>
              </div>
            </td>
          </tr>
        </table>
      </div>

      <div id="footer"><a href="http://nico.tuxfamily.org/Projets/12-Luxbum-Script-De-Galerie-Photo-En-Php">Luxbum</a> by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
    </div>
  </body>
</html>
