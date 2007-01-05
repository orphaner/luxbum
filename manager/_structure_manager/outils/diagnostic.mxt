
<h1 id="h1_admin">Informations</h1>

<h2>Informations LuxBum</h2>
<p>Vous utilisez la version <mx:text id="luxbum_version"/> de LuxBum.<br />
<a href="#">Vérifier si il existe des mises à jour.</a></p>

<h2>Informations Fichiers</h2>
<mx:bloc id="perms">
  <p><mx:image id="img_check"/> <mx:text id="check"/></p>
</mx:bloc id="perms">

<h2>Informations Serveurs</h2> 
<p>Votre version de PHP est : <strong><mx:text id="php_version"/></strong></p>
<p>Votre serveur web est : <strong><mx:text id="web_server"/></strong></p>
<p>Votre libraire graphique GD est : <strong><mx:text id="gd_version"/></strong></p>

<h2>Informations Galeries</h2>
<table class="clean-table">
  <tr>
    <th>Galerie</th>
    <th>Nombre de photos</th>
    <th>Taille</th>
  </tr>
  <mx:bloc id="galeries">
    <tr>
      <td><mx:text id="nom"/></td>
      <td><mx:text id="nb_photo"/></td>
      <td><mx:text id="taille"/></td>
    </tr>
  </mx:bloc id="galeries">
</table>
