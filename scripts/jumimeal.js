$(function(){
	var availableUnits = [
		"Liter",
		"Gramm",
		"Kilogramm",
		"Milliliter",
		"Essloeffel",
		"Teeloeffel",
		"Stueck",
		"Paeckchen",
		"Flasche",
		"Glas",
		"Dose",
		"Packung",
		"Paket"
	]; 
	$('#ingUnit').autocomplete({
			source: availableUnits
		});                    	

	var arrayIngredients = new Array();

	$( "#dialogAdd" ).dialog({
				autoOpen: false,
				modal: true,
				width: 400,
				buttons: {
					"Add this Recipe": function() {
						saveRecipe(arrayIngredients);
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});         
			
	$( "#dialogAddIngredient" ).dialog({
				autoOpen: false,
				modal: true,
				width: 300,
				buttons: {
					"Add this Ingredient": function() {
						$("#ingredients").tinytbl('append', $('<tr><td>' + $('#ingAmount').val() + '</td><td>' + $('#ingUnit').val() + '</td><td>' + $('#ingIngredient').val() + '</td></tr>'));
						var lineEntry = [$('#ingAmount').val(),$('#ingUnit').val(), $('#ingIngredient').val()];
						arrayIngredients.push(lineEntry);
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});                       
	$( "#dialogRecipeCategory" ).dialog({
				autoOpen: false,
				modal: true,
				buttons: {
					"Apply Changes": function() {
						saveRecipeCategoryAssociation();						
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});        
	$("#dialog-error").dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});               
	$("#dialog-message").dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});               
	$( "#accordion" ).accordion( {
  		collapsible:true,
  		changestart: function(event, ui) {
  			if (!$manualMode) {
  				$loadDiv = "";
  				$contDiv = "";
  				ui.newContent.children('div').each( function(){
  					if ($(this).attr('cont_type') == 'load') {
  						$loadDiv = $(this);
					} else {
  						$contDiv = $(this);
  					}
  				});
  				if ($loadDiv == "") { // small workaround - for re-opening an already open element
		  				ui.oldContent.children('div').each( function(){
		  					if ($(this).attr('cont_type') == 'load') {
		  						$loadDiv = $(this);
							} else {
		  						$contDiv = $(this);
		  					}
		  				});  					
  					}
  				//$loadDiv.add("<img src='images/updateProgress.gif'/>");
  				$loadDiv.show();
  				$contDiv.hide();
  			}	
  		},
  		change: function(event, ui) {
  			if (!$manualMode) {
  				console.log("Accordion Text: " + ui.newHeader.text() + "/" + ui.newHeader.attr("rec_id"));
  				
  				// processing
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "ajaxHandler.php",
					data: "action=getDetailsForRecipe& recipeId=" + ui.newHeader.attr("rec_id"),
					success: function(data){
							console.log("got details from server for recipe: " + data ); 
							ui.newContent.find('td').each( function() {
								if ($(this).attr('colType') == 'ingredients') {
									$(this).empty();
									$pane = $(this)
									data['ingredients'].forEach( function(v,k) {
										$pane.append(v + ", ");
										});
								} else if ($(this).attr('colType') == 'instructions') {
									$(this).html(data['instructions'].replace(/\n/g, "</br>"));
								} else {
									$(this).empty();
									$pane = $(this)
									data['categories'].forEach( function(v,k) {
										$pane.append(v + "</br>");
										});
								}
							});

			  				$loadDiv.hide();
			  				$contDiv.show("normal");
			  				//$loadDiv.empty();
						},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
						 console.log("error on loading details for recipe");
						 console.log("details: " + textStatus);
						 $("#dialogError_message").text("Could not load details for recipe from server.");
						 $("#dialog-error").dialog("open");
						}
				});
			}						 
  		}
  	});
	$( "#radio" ).buttonset(); // Obere Liste mit den Kategorien
	$( "#bAdd" ).button();     // Ein Rezept hinzufügen
	$( "#ingredButtonSet" ).buttonset(); // im Dialog Rezept hinzufügen - Aktionen für Zutaten
  
  
	$( "#bAdd" ).click(function() {
		$( "#dialogAdd" ).dialog( "open" );
			 arrayIngredients.splice(0, arrayIngredients.length); // reset
	       convertTable($("#ingredients"));
	       $("#recipeName").val("");
	       $("#preparation").val("");
		return false;
	});
	
	$( "#bAddIngredient" ).click(function() {
		$( "#dialogAddIngredient" ).dialog( "open" );
		return false;
		});
	$( "#bClearIngredientList" ).click(function() {
		arrayIngredients.splice(0, arrayIngredients.length);
		recoverTable($("#ingredients"));
		$("#ingredients tbody").empty();
		convertTable($("#ingredients"));
		return false;
		});
});


$(document).ready(function() {
	$manualMode = false;	
	$('#accordion').accordion('activate', 0);	
	$('.accordion .head').click(function() {
				$(this).next().toggle('slow');
				return false;
			}).next().hide();
} );

function convertTable(theTable) {
	theTable.tinytbl({
         direction: 'ltr',      // text-direction (default: 'ltr')
         thead:     true,       // fixed table thead
         tfoot:     false,       // fixed table tfoot
         cols:      '0',          // fixed number of columns
         width:     '100%',     // table width (default: 'auto')
         height:    '100px'      // table height (default: 'auto')
     });									
    };
	
function recoverTable(theTable) {
	theTable.tinytbl('destroy');
};		 				

function catSelected(category) {
	$manualMode = true;
	$( "#accordion" ).accordion('activate', false);
	$( "#accordion" ).children('h3').each(function(){
		var heading = $(this);
		if (category == '0')
		{
			heading.show();
		} else {
		heading.hide();
		$(this).children('input').each(function(){
		var kid = $(this);
		if (! kid.attr("category").indexOf(category)) {
			heading.show();
				}   
			}); 	
		}											
	});
	$manualMode = false;
};
	
function editCategoryAssociation(recipeId) {
	$( "#dialogRecipeCat_recipeName" ).text($.trim(recipeId));
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "ajaxHandler.php",
		data: "action=getCatsForRecipe& recipeId=" + recipeId,
		success: function(data){
				console.log("got from server for categories: " + data ); 

  				$("#dialogRecipeCategory_items").children('label').each(function() {
	  					$catText = $.trim($(this).text());
  						$idCB = $(this).attr("for");
	  					if (data.indexOf($catText) != -1) {
	  						$("#" + $idCB).attr('checked', true);
	  					} else {
							$("#" + $idCB).attr('checked', false);
	  					}
  				});
				$( "#dialogRecipeCategory" ).dialog( "open" );
			},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			 console.log("error on getting cats for recipe");
			 $("#dialogError_message").text("Could not load categories from server.");
			 $("#dialog-error").dialog("open");
			}
	});						 

	}
	
function saveRecipe(arrayIngredients) {
	 st = "saving" + "\n" + $("#recipeName").val();
	 
	 st = st+ "\nZutaten:\n";
	 recoverTable($("#ingredients"));
	 arrayIngredients.forEach(function(v, k) {
	 	st = st + "- " + v[0] + " " + v[1] + " " + v[2] + "\n";
	 	});
	 st = st + "\n\nZubereitung:\n" + $("#preparation").val();
	 console.log(st);
	 
	 var jsonRet = new Object;
	 jsonRet.title = $("#recipeName").val();
	 jsonRet.preparation = $("#preparation").val();
	 jsonRet.ingredientCount = arrayIngredients.length;
	 jsonRet.ingredients = arrayIngredients;
	 stJson = JSON.stringify(jsonRet);
	 console.log(stJson);

	 $.ajax({
			type: "POST",
			url: "ajaxHandler.php",
			data: "action=addRecipe& json=" + stJson,
			success: function(){
				 $("#dialogMessage_message").text("Your recipe has been inserted successfully.");
				 $("#dialog-message").dialog("open");
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				 $("#dialogError_message").text("Recipe could not be saved.");
				 $("#dialog-error").dialog("open");			
			}
		});						 
	}

function saveRecipeCategoryAssociation() {
	$recId = $.trim($( "#dialogRecipeCat_recipeName" ).text());
	st = "saving association for " + $recId;
	var arrayCats = new Array();
	$("#dialogRecipeCategory_items").children('label').each(function() {
		$catText = $.trim($(this).text());
			$idCB = $(this).attr("for");
		if ($("#" + $idCB).attr('checked')) {
			arrayCats.push($catText);
			}
	});
	st = st + arrayCats;
	console.log(st);

	var jsonRet = new Object;
	jsonRet.recID = $recId;
	jsonRet.catCount = arrayCats.length;
	jsonRet.categories = arrayCats;
	stJson = JSON.stringify(jsonRet);
	// console.log(stJson);
	
	$.ajax({
		type: "POST",
		url: "ajaxHandler.php",
		data: "action=saveCategoryAssociations& json=" + stJson,
		success: function(){
				 $("#dialogMessage_message").text("New associations saved sucessfully.");
				 $("#dialog-message").dialog("open");
			},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
				 $("#dialogError_message").text("Could not save new associations.");
				 $("#dialog-error").dialog("open");			
		}
	});		
}