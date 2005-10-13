<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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

      <h1><span><mx:text id="nom_galerie"/></span></h1>


      <div id="liste_apercu">
        <table border="0" width="100%">
          <mx:bloc id="dossiers">
            <tr>

              <td>
                <mx:bloc id="col1">
                  <div class="lg">
                    <a mXattribut="href:lien"><img mXattribut="src:apercu;alt:alt;title:title" /></a>
                  </div>
                  <div class="ld">
                    <h2><mx:text id="nom"/></h2>
                    <span class="infos"><mx:text id="nb_photo"/> photos pour <mx:text id="taille"/></span>.<br />
                    <div class="consulter"><img src="_images/fleche1.png" alt="-" />&nbsp;<a mXattribut="href:lien" mXattribut="title:title2">Consulter</a></div>
                  </div>
                </mx:bloc id="col1">
              </td>

              <td>
                <mx:bloc id="col2">
                  <div class="lg">
                    <a mXattribut="href:lien"><img mXattribut="src:apercu;alt:alt;title:title" /></a>
                  </div>
                  <div class="ld">
                    <h2><mx:text id="nom"/></h2>
                    <span class="infos"><mx:text id="nb_photo"/> photos pour <mx:text id="taille"/></span>.<br />
                    <div class="consulter"><img src="_images/fleche1.png" alt="-" />&nbsp;<a mXattribut="href:lien" mXattribut="title:title2">Consulter</a></div>
                  </div>
                </mx:bloc id="col2">
              </td>

              <td>
                <mx:bloc id="col3">
                  <div class="lg">
                    <a mXattribut="href:lien"><img mXattribut="src:apercu;alt:alt;title:title" /></a>
                  </div>
                  <div class="ld">
                    <h2><mx:text id="nom"/></h2>
                    <span class="infos"><mx:text id="nb_photo"/> photos pour <mx:text id="taille"/></span>.<br />
                    <div class="consulter"><img src="_images/fleche1.png" alt="-" />&nbsp;<a mXattribut="href:lien" mXattribut="title:title2">Consulter</a></div>
                  </div>
                </mx:bloc id="col3">
              </td>

            </tr>
          </mx:bloc id="dossiers">
        </table>
      </div>

      <div id="footer2"><a href="http://nico.tuxfamily.org/Projets/12-Luxbum-Script-De-Galerie-Photo-En-Php"><img src="_images/luxbum.png" alt="Powered By LuxBum"/></a><br />
        Luxbum by <a href="mailto:nico_at_tuxfamily.org">Nico</a></div>
      

    </body>
  </html>
