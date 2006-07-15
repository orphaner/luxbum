<h1 id="h1_admin">Trier une galerie</h1>

<script language="JavaScript" type="text/javascript">
<!--
function populateHiddenVars() {
  document.getElementById('imageOrder').value = Sortable.serialize('images');
  return true;
}
//-->
</script>
<script src="_javascript/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="_javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>

Faire glisser les images à la position voulue pour les trier.
<div id="images">
  <mx:bloc id="images"><mx:image id="image"/>
  </mx:bloc id="images">
</div>

<p>
<form mXattribut="action:actionTri" method="post" onSubmit="populateHiddenVars();" name="sortableListForm" id="sortableListForm">
  <fieldset><legend>Validation de l'ordre</legend>
    <p>
      <label for="retourEdition" class="float">Retourner à l'édition de la galerie après validation</label>
      <input type="checkbox" checked="checked" id="retourEdition" name="retourEdition"/>
    </p>
    <p>
      <input type="hidden" name="imageOrder" id="imageOrder" size="60"/>
      <input type="hidden" name="sortableListsSubmitted" value="true"/>
      <input type="submit" value="Enregistrer l'ordre" class="submit"/>
    </p>
  </fieldset>
</form>
</p>

<script type="text/javascript">
// <![CDATA[
        Sortable.create('images',{tag:'img',overlap:'horizontal',constraint:false});
        // ]]>
</script>

<a mXattribut="href:retourGalerie">Retour à l'édition de la galerie : 
<strong><mx:text id="galerie"/></strong></a>