 

var nbMax=5;
var nb=nbMax;
function addPosition (divId)
{
    if (nb>0){
        cree_input = document.createElement('input');
        cree_input.type = 'file';
        cree_input.name = 'userfile[]';
        emplacement = document.getElementById(divId);
        cree_br = document.createElement('br');
        champ = emplacement.appendChild(cree_input);
        champ = emplacement.appendChild(cree_br);
        nb--;
    }
    else{
        alert('Vous ne pouvez ajouter plus de 5 images.');
    }
}