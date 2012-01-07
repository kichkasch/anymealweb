<!DOCTYPE html>
<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
                <title>JumiMeal Meal Manager</title>
                <link type="text/css" href="css/sunny/jquery-ui-1.8.16.custom.css" rel="stylesheet" />  
                <script type="text/javascript" src="scripts/jquery-1.6.2.min.js"></script>
                <script type="text/javascript" src="scripts/jquery-ui-1.8.16.custom.min.js"></script> 
                
				    <!-- tiny table stuff -->
				    <link href="css/jquery.ui.tinytbl.css" rel="stylesheet" type="text/css" />
				    <script src="scripts/jquery.ui.tinytbl.js" type="text/javascript"></script>
				    <!-- tiny table stuff -->
				             
                               
                <script type="text/javascript">

                        $(function(){
										  $( "#dialogAdd" ).dialog({
														autoOpen: false,
														modal: true,
														width: 400,
														buttons: {
															"Add this Recipe": function() {
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
                                $( "#accordion" ).accordion( {
                                		collapsible:true
                                		});
                                $( "#radio" ).buttonset(); // Obere Liste mit den Kategorien
                                $( "#bAdd" ).button();     // Ein Rezept hinzufügen
                                $( "#ingredButtonSet" ).buttonset(); // im Dialog Rezept hinzufügen - Aktionen für Zutaten
                                
                                
                                $( "#bAdd" ).click(function() {
												$( "#dialogAdd" ).dialog( "open" );
											       convertTable($("#ingredients"));
												
												return false;
											});
											
											$( "#bAddIngredient" ).click(function() {
												$("#ingredients").tinytbl('append', $('<tr><td>200</td><td>Gramm</td><td>Mehl</td></tr>'));
												return false;
												});
											$( "#bClearIngredientList" ).click(function() {
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
									$( "#dialogRecipeCategory" ).dialog( "open" );
									}

                </script>
                <style type="text/css">
                        /*demo page css*/
                        body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 200px;}
                        .demoHeaders { margin-top: 2em; }
                        #dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
                        #dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
                        ul#icons {margin: 0; padding: 0;}
                        ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
                        ul#icons span.ui-icon {float: left; margin: 0 4px;}
                </style>    
        </head>
        
<?php
include 'config.php';     
?>   
        
        <body>
        <h1>JumiMeal Meal Manager</h1>
                
<div class="demo">

<button id="bAdd">Add new Recipe</button>
<div id="dialogAdd" title="Add new Recipe">
	<p>Please provide details for your recipe.</p>

	<form>
	<fieldset>
		<p><label for="name">Title of Recipe</label><br/>
		<input type="text" name="name" id="name" size="50" class="text ui-widget-content ui-corner-all" /></p>
		
		<p><label for="ingredients">Ingredients</label><br/>
		
<table class="ui-tinytable" name="ingredients" id="ingredients">
    <thead>
        <tr>
            <th>&nbsp;&nbsp;&nbsp;Amount&nbsp;&nbsp;&nbsp;</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>&nbsp;&nbsp;Ingredient&nbsp;&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>20</td>
            <td>Gramm</td>
            <td>Zucker</td>
        </tr>
        <tr>
            <td>500</td>
            <td>Gramm</td>
            <td>Butter</td>
        </tr>
    </tbody>
</table>
		</p>
<div id="ingredButtonSet">
<button id="bAddIngredient">Add an ingredient</button>
<button id="bClearIngredientList">Clear list</button>
</div>
		
		<p><label for="preparation">Preparation</label><br/>
		<textarea name="preparation" id="preparation" value="" cols="50" rows="8" class="text ui-widget-content ui-corner-all" ></textarea></p>
	</fieldset>
	</form>	
</div>

<p>
<div align="center">
<form>
	<div id="radio">
		<input type="radio" id="radio0" name="radio"  checked="checked" onClick="catSelected(0)" /><label for="radio0">*All*</label>
		
<?php
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$query = "SELECT ID, NAME FROM CATEGORIES ORDER BY NAME";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print('<input type="radio" id="radio_' . $row['NAME'] .  '" name="radio" onClick=\'catSelected("' . $row['NAME'] .  '")\' /><label for="radio_' . $row['NAME'] .  '">' . $row['NAME'] .  '</label>');
}
?>		
		
	</div>
</form>
</div>
</p>

<div id="accordion">
<?php
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$query = "SELECT RECIPE.TITLE as TITLE, RECIPE.ID as ID FROM RECIPE ORDER BY TITLE";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print("<h3><a href='#'>" . $row['TITLE'] .  "</a>");
 
 $query2 = "SELECT CATEGORIES.NAME as NAME FROM CATEGORIES, CATEGORY WHERE CATEGORIES.ID = CATEGORY.CATEGORYID AND CATEGORY.RECIPEID = " . $row['ID'] . " ORDER BY NAME";
 $resultID2 = mysql_query($query2, $linkID) or die("Data not found.");
 for($y = 0 ; $y < mysql_num_rows($resultID2) ; $y++){
    $row2 = mysql_fetch_assoc($resultID2);
 	$cats[] = $row2['NAME'];
	print("<input type='hidden' category='" . $row2['NAME'] .  "'/>");
 }
 ?>
 </h3>
 <div>
 <table width="100%">
 <thead>
 <th>Ingredients</th>
 <th>Preparation</th>
 <?php
 print('<th width="10%">Categories <a onclick="editCategoryAssociation(' . $row['ID'] . ')"><img src="images/edit.png" width="20" height="20" alt="Edit Categories for this Recipe"></a></th>'); 
 ?> 
 </thead>
 <tr>
 <td>n.a.</td>
 <td>n.a.</td>
 <td>
<?php
 foreach ($cats as $cat) {
print($cat);
print("<br/>");
}
unset($cats);
?> 
 </td>
 </tr>
 </table> 
 </div>
<?php
}
?>

<div id="dialogRecipeCategory" title="Assign Categories to Recipe">
	<p>Change categories under which recipe <div id="dialogRecipeCat_recipeName"></div> shall be filed.</p>

<?php
 $query2 = "SELECT CATEGORIES.NAME as NAME FROM CATEGORIES ORDER BY NAME";
 $resultID2 = mysql_query($query2, $linkID) or die("Data not found.");
 for($y = 0 ; $y < mysql_num_rows($resultID2) ; $y++){
   $row2 = mysql_fetch_assoc($resultID2);
 	print('<input type="checkbox" id="check_" /><label for="check1">' . $row2['NAME'] .  '</label><br/>');
}
?>

</div>


</div>

</div>

        </body>
</html>
