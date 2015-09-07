



jQuery(document).ready(function() {
	$(function() {
	    $( ".sortable" ).sortable();
	$( ".sortable" ).disableSelection();
	  });


		//Récupère le div qui contient la collection de tags
	var collectionHolder = $('table#playlist_table');
	var audioEditHolder = $('tr.edit');
	var audioViewHolder = $('tr.view');
	audioEditHolder.hide();
	// ajoute un lien « add a tag »
	var $newLinkTr = $('<tr"></tr>');
	
	
    collectionHolder.find('tbody').each(function() {
    	$body=$(this);
    	$body.find("tr.view").each(function(){
			var $buttonAction = $(this).find("td.action_buttons");
	        addTagFormEditLink($body,$buttonAction);
	        $(this).append($buttonAction);
		});
    	$body.find("tr.edit").each(function(){
			var $buttonAction = $(this).find("td.action_buttons");
	        addTagFormViewLink($body,$buttonAction);
	        $(this).append($buttonAction);
		});
    });

	collectionHolder.find("tbody").each(function(){
		$body=$(this);
		$body.find("tr.audio_row").each(function(){
			var $buttonAction = $(this).find("td.action_buttons");
	        addTagFormDeleteLink($body,$buttonAction);
	        $(this).append($buttonAction);
		});
	});
	
    $("#add_tag_link").click(function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();

        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
        addTagForm(collectionHolder, $newLinkTr);
    });

    //On récupere l'ordre des morceau et on set l'attribut dans le form
    $( "#playlist_form" ).submit(function( event ) {
	    var row = 0;
	    $("#playlist_form").find('.order').each(function(){
	    	$(this).find('input').val(row);
		    row = row+1;
		});
    });
});

function addTagForm(collectionHolder, $newLinkTr) {
    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
    var prototype = collectionHolder.attr('data-prototype');
	
    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
    // la longueur de la collection courante
    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    // Affiche le formulaire dans la page dans un tr
    
    
    $newFormTbody=$('<tbody></tbody>'); 
    //met en place les td pour coller dans le tr
    $newFormTr=$('<tr class="info"></tr>')
    $newFormTr.append(newForm);
    $newFormTbody.append($newFormTr);
    //Création de bouton delete et edit
    var $buttonAction = $('<td class="col-md-2"></td>');
    addTagFormDeleteLink($newFormTr,$buttonAction);
    
    $newForm=$newFormTr.append($buttonAction);
    
	
    $newLinkTr.append($newForm);

    // ajoute un lien de suppression au nouveau formulaire
    collectionHolder.append($newFormTbody);
}


function addTagFormDeleteLink($contentToDelete,$insertButtonZone) {
	var $removeForm = $('<button type="button" class="btn btn-danger" aria-label="Right Align"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button>');
	if ($insertButtonZone)
		$insertButtonZone.append($removeForm);
	else
		$contentToDelete.append($removeForm);

    $removeForm.on('click', function(e) {
        // empêche le lien de créer un « # » dans l'URL
        e.preventDefault();
        // supprime l'élément tr pour le formulaire de tag
        $contentToDelete.remove();
    });
}

function addTagFormViewLink($body,$insertButtonZone) {
	var $editForm = $('<button type="button" class="btn btn-primary" aria-label="Left Align"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> </button>');
			
	$insertButtonZone.append($editForm);
    $editForm.on('click', function(e) {
        e.preventDefault();
        $body.find("tr.view").show();
        $body.find("tr.edit").hide();
        
        // supprime l'élément li pour le formulaire de tag
        //$tagFormTr.remove();
    });
}



function addTagFormEditLink($body,$insertButtonZone) {
	var $editForm = $('<button type="button" class="btn btn-primary" aria-label="Left Align"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </button>');
			
	$insertButtonZone.append($editForm);
    $editForm.on('click', function(e) {
        e.preventDefault();
        $body.find("tr.edit").show();
        $body.find("tr.view").hide();
        
        // supprime l'élément li pour le formulaire de tag
        //$tagFormTr.remove();
    });
}
