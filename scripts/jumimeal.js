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
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});        
	$("#dialog-insertComplete").dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});                        
	$( "#accordion" ).accordion( {
  		collapsible:true
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
	};
	
function editCategoryAssociation(recipeId) {
	$( "#dialogRecipeCat_recipeName" ).text(recipeId);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "ajaxHandler.php",
		data: "action=getCatsForRecipe& recipeId=" + recipeId,
		success: function(data){
				console.log("got from server for categories: " + data); 
				$.each(data, function(key, val) {
    				console.log("  got from server for categories: " + val);
  				});
			},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			 //todo
			 console.log("error on getting cats for recipe");
			}
	});						 

	$( "#dialogRecipeCategory" ).dialog( "open" );
	}
	
function saveRecipe(arrayIngredients) {
	 st = "saving" + "\n" + $("#recipeName").val();
	 
	 st = st+ "\nZutaten:\n";
	 recoverTable($("#ingredients"));
	 arrayIngredients.forEach(function(v, k) {
	 	st = st + "- " + v[0] + " " + v[1] + " " + v[2] + "\n";
	 	});
	 st = st + "\n\nZubereitung:\n" + $("#preparation").val();
	 //alert(st);
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
					$("#dialog-insertComplete").dialog("open");
				}
		});						 
	}
