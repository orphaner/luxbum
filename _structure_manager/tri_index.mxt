<h1 id="h1_admin">Trier l'index des galeries</h1>

<script language="JavaScript" type="text/javascript">
<!--
function populateHiddenVars() {
  document.getElementById('galleryOrder').value = Sortable.serialize('galeries');
  return true;
}
//-->
</script>
<script src="_javascript/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="_javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>

Faire glisser les galeries à la position voulue pour les trier.
<div id="galeries">
  <mx:bloc id="galeries">
    <div class="triIndex" mXattribut="id:id">
      <div class="lg">
        <mx:image id="image"/>
      </div>
      <div class="ld">
        <h2><mx:text id="nom"/></h2>
        <span class="infos"><mx:text id="nb_photo"/> photos pour <mx:text id="taille"/>.</span>
      </div>
    </div>
  </mx:bloc id="galeries">
</div>
<div style="clear:both;"></div>
<p>
<form mXattribut="action:actionTri" method="post" onSubmit="populateHiddenVars();" name="sortableListForm" id="sortableListForm">
  <fieldset><legend>Validation de l'ordre</legend>
    <p>
      <label for="retourEdition" class="float">Retourner à l'index des galeries après validation</label>
      <input type="checkbox" checked="checked" id="retourEdition" name="retourEdition"/>
    </p>
    <p>
      <input type="hidden" name="galleryOrder" id="galleryOrder" size="60"/>
      <input type="hidden" name="sortableListsSubmitted" value="true"/>
      <input type="submit" value="Enregistrer l'ordre" class="submit"/>
    </p>
  </fieldset>
</form>
</p>

<script type="text/javascript">
// <![CDATA[
        Sortable.create('galeries',{tag:'div',overlap:'horizontal',constraint:false});
        // ]]>
</script>
