<div id="dialog-insertComplete" title="Insert complete">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		Your recipe has been inserted successfully.
	</p>
</div>


<div id="dialogAdd" title="Add new Recipe">
	<p>Please provide details for your recipe.</p>

	<form>
	<fieldset>
		<p><label for="recipeName">Title of Recipe</label><br/>
		<input type="text" name="recipeName" id="recipeName" size="50" class="text ui-widget-content ui-corner-all" /></p>
		
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


<div id="dialogAddIngredient" title="Add Ingredient to recipe">
	<form>
	<fieldset>
		<p><label for="ingAmount">Amount</label><br/>
		<input type="text" name="ingAmount" id="ingAmount" class="text ui-widget-content ui-corner-all" /></p>
		<p><label for="ingUnit">Unit</label><br/>
		<input type="text" name="ingUnit" id="ingUnit" class="text ui-widget-content ui-corner-all" /></p>
		<p><label for="ingIngredient">Ingredient</label><br/>
		<input type="text" name="ingIngredient" id="ingIngredient" class="text ui-widget-content ui-corner-all" /></p>
	</fieldset>
	</form>	
</div>



<div id="dialogRecipeCategory" title="Assign Categories to Recipe">
	<p>Change categories under which recipe <div id="dialogRecipeCat_recipeName"></div> shall be filed.</p>

<?php
 include 'config.php'; 
 $linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
 mysql_select_db($database, $linkID) or die("Could not find database.");
 mysql_set_charset('utf8',$linkID); 
 $query2 = "SELECT CATEGORIES.NAME as NAME FROM CATEGORIES ORDER BY NAME";
 $resultID2 = mysql_query($query2, $linkID) or die("Data not found.");
 for($y = 0 ; $y < mysql_num_rows($resultID2) ; $y++){
   $row2 = mysql_fetch_assoc($resultID2);
 	print('<input type="checkbox" id="check_" /><label for="check1">' . $row2['NAME'] .  '</label><br/>');
}
?>

</div>
