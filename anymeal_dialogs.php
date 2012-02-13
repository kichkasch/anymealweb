<!-- generic dialog for errors -->
<div id="dialog-error" title="Error">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		<div id="dialogError_message">An error occurred.</div>
	</p>
</div>

<!-- generic dialog for messages -->
<div id="dialog-message" title="Notification">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		<div id="dialogMessage_message">Notification</div>
	</p>
</div>

<!-- generic dialog for messages -->
<div id="dialog-confirmDelete" title="Please confirm">
	<p>
		<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
		Please confirm to delete recipe <div id="dialogDelete_message"></div>.
	</p>
</div>


<div id="dialog-about" title="About JuMiMeal Meal Manager">
	<p>
		<p><em>JuMiMeal Meal Manager is a web-based recipe management application.</em></p>
		<p>Initially, it was intended to be a viewer for the Desktop application <a href="http://www.wedesoft.demon.co.uk/anymeal-api/">Anymeal</a> only.
		However, due to discontinuation of the development of Anymeal I decided, to also integrate manipulation of recipe information. 
		Additionally, you can manage photos for your recipe (not part of original Anymeal).</p>
		
		<p>(C) 2012 by <a href="mailto:kichkasch@gmx.de">Michael Pilgermann</a>, <a href="https://github.com/kichkasch/anymealweb/wiki" >Home Page</a>,
		License: <a href="http://www.gnu.org/licenses/gpl.html" >GPL</a></p>
		
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
	<p>Change categories under which recipe <div id="dialogRecipeCat_recipeName"> ... </div> shall be filed.</p>
	<div id="dialogRecipeCategory_items">
<?php
 include 'config.php'; 
 $linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
 mysql_select_db($database, $linkID) or die("Could not find database.");
 mysql_set_charset('utf8',$linkID); 
 $query2 = "SELECT CATEGORIES.NAME as NAME FROM CATEGORIES ORDER BY NAME";
 $resultID2 = mysql_query($query2, $linkID) or die("Data not found.");
 for($y = 0 ; $y < mysql_num_rows($resultID2) ; $y++){
   $row2 = mysql_fetch_assoc($resultID2);
 	print('<input type="checkbox" id="check_' . $y . '" /><label for="check_' . $y . '">' . $row2['NAME'] .  '</label><br/>');
}
?>
</div> 

</div> 
