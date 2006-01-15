<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><mx:text id="titre_page"/></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="_styles/manager.css" />
  </head>

  <body mXattribut="onload:onload">

    <div id="header">
      <ul>
        <li mXattribut="class:class_galeries" id="menuGaleries"><a href="?p=listegaleries">Galeries</a></li>
        <li mXattribut="class:class_commentaires" id="menuCommentaires"><a href="?p=commentaires">Commentaires</a></li>
        <mx:bloc id="isadmin">
          <li mXattribut="class:class_parametres" id="menuParametres"><a href="?p=parametres">Paramètres</a></li>
        </mx:bloc id="isadmin">
        <li mXattribut="class:class_outils" id="menuOutils"><a href="?p=outils">Outils</a></li>
        <li class="last" id="menuDeconnection"><a href="?p=logout">Déconnection</a></li>
      </ul>
    </div>

    <div id="main">
      <mx:bloc id="main"></mx:bloc id="main">
    </div>

    <div id="footer_admin"><a href="http://nico.tuxfamily.org/Projets/12-Luxbum-Galerie-Dimages-En-Php"><img src="_images/luxbum.png" alt="Powered By LuxBum"/></a></div>
  </body>
</html>
