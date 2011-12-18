<!DOCTYPE html>
<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
                <title>JumiMeal Meal Manager</title>
                <link type="text/css" href="css/sunny/jquery-ui-1.8.16.custom.css" rel="stylesheet" />  
                <script type="text/javascript" src="scripts/jquery-1.6.2.min.js"></script>
                <script type="text/javascript" src="scripts/jquery-ui-1.8.16.custom.min.js"></script>                
                <script type="text/javascript">

                        $(function(){
										  $( "#dialogAdd" ).dialog({
														autoOpen: false,
														modal: true,
														buttons: {
															"Add this Recipe": function() {
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
                                $( "#radio" ).buttonset();
                                $( "#bAdd" ).button();
                                
                                $( "#bAdd" ).click(function() {
												$( "#dialogAdd" ).dialog( "open" );
												return false;
											});
								});
								
								
								$(document).ready(function() {
										$('.accordion .head').click(function() {
												$(this).next().toggle('slow');
												return false;
											}).next().hide();
								} );
								
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
    									//alert(category + st );
									};

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
	<p>This is an animated dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>

	<form>
	<fieldset>
		<p><label for="name">Name</label><br/>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" /></p>
		
		<p><label for="email">Email</label><br/>
		<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" /></p>
		
		<p><label for="password">Password</label><br/>
		<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" /></p>
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
 <th width="10%">Categories</th> 
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

</div>

</div>

        </body>
</html>
