
<?php
include 'config.php';
$action  = htmlspecialchars(trim($_POST['action']));


if ($action == "addRecipe") {
	$decoded = json_decode($_POST['json']);
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID); 
 
	$amount = "1";
	$language = 'en';
	$query = "INSERT INTO RECIPE (TITLE, AMOUNT, LANGUAGE) values ('" . $decoded->title . "', " . $amount . ", '" . $language . "')";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	$recipe_prim_key = mysql_insert_id();
	
	$query = "INSERT INTO INSTRUCTIONS (RECIPEID, TITLE, INSTRUCTIONS) values ('" . $recipe_prim_key . "', 'Zubereitung', '" . $decoded->preparation . "')";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	
	$query = "INSERT INTO SECTION (RECIPEID, TITLE) values ('" . $recipe_prim_key . "', 'Zutaten')";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	$section_prim_key = mysql_insert_id();
	
	
	foreach ($decoded->ingredients as $value) {
		$query = "SELECT ID, NAME FROM EDIBLE WHERE NAME = '" . $value[2] . "'";
		$resultID = mysql_query($query, $linkID) or die("Data not found.");
		$edibleId = -1;
		for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
			$row = mysql_fetch_assoc($resultID);
	 		$edibleId = $row['ID'];
	 	}
	 	if ($edibleId == -1) {
	 		   $query = "INSERT INTO EDIBLE (NAME) VALUES ('" . $value[2] . "')";
				$resultID = mysql_query($query, $linkID) or die("Data not found.");
				$edibleId = mysql_insert_id();
	 		}
	 
		$query = "INSERT INTO INGREDIENT (RECIPEID, EDIBLEID, SECTIONID, AMOUNTDOUBLE, UNIT, AMOUNTNOMINATOR, AMOUNTDENOMINATOR) VALUES ('" .  $recipe_prim_key . "', '" . $edibleId . "', '" . $section_prim_key . "', " . $value[0] . ", '" . $value[1] . "', NULL, NULL)";
		$resultID = mysql_query($query, $linkID) or die("Data not found.");
	 
	} // foreach 
} elseif($action == "getCatsForRecipe") {
	$recipeId = htmlspecialchars(trim($_POST['recipeId']));
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID); 
	$query = "SELECT CATEGORIES.NAME AS NAME FROM CATEGORIES, CATEGORY WHERE CATEGORY.RECIPEID = '" . $recipeId . "' AND CATEGORY.CATEGORYID = CATEGORIES.ID";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
		$row = mysql_fetch_assoc($resultID);
		$cats[] = $row['NAME'];
	}
	//echo json_encode(array("action"=>"getCatsForRecipe","recipe"=>$recipeId));
	echo json_encode($cats);
	unset($cats);
} elseif($action == "getInstructionsForRecipe") {	
	$recipeId = htmlspecialchars(trim($_POST['recipeId']));
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID); 
	$query = "SELECT INSTRUCTIONS  FROM INSTRUCTIONS WHERE RECIPEID = '" . $recipeId . "'";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
		$row = mysql_fetch_assoc($resultID);
		$instructions_st = $row['INSTRUCTIONS'];
	}
	echo json_encode($instructions_st);
} elseif($action == "saveCategoryAssociations") {
	$decoded = json_decode($_POST['json']);
	$recipeId = $decoded->recID;	
	
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID);

	$query = "DELETE FROM CATEGORY WHERE RECIPEID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	
	
	foreach ($decoded->categories as $value) {		
	 	$query = "INSERT IGNORE INTO CATEGORY (RECIPEID, CATEGORYID) SELECT '" . $recipeId . "', ID FROM CATEGORIES WHERE NAME = '" . $value . "'";
		$resultID = mysql_query($query, $linkID) or die("Data not found.");	 	
	 }
} elseif($action == "saveEditedInstructions") {
	$decoded = json_decode($_POST['json']);
	$recipeId = $decoded->recID;	
	
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID);
	
	$query = "UPDATE INSTRUCTIONS SET INSTRUCTIONS = '" . $decoded->instructions . "' WHERE RECIPEID = '" . $recipeId . "'";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	
	if (mysql_affected_rows() == 0)
	{
		$query = "INSERT INTO INSTRUCTIONS (ID, INSTRUCTIONS, RECIPEID, TITLE) VALUES ('0', '" . $decoded->instructions . "', '" . $recipeId . "', 'Zubereitung')";
		$resultID = mysql_query($query, $linkID) or die("Data not found.");
	}	
		
} elseif($action == "getDetailsForRecipe") {
	$recipeId = htmlspecialchars(trim($_POST['recipeId']));
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID); 

	$query = "SELECT CATEGORIES.NAME AS NAME FROM CATEGORIES, CATEGORY WHERE CATEGORY.RECIPEID = '" . $recipeId . "' AND CATEGORY.CATEGORYID = CATEGORIES.ID";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
		$row = mysql_fetch_assoc($resultID);
		$cats[] = $row['NAME'];	
	}

	$instructions = "";
	$query = "SELECT INSTRUCTIONS FROM INSTRUCTIONS WHERE RECIPEID = '" . $recipeId . "'";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
		$row = mysql_fetch_assoc($resultID);
		$instructions = $row['INSTRUCTIONS'];	
	}

	$query = "SELECT DISTINCT EDIBLE.NAME AS NAME FROM EDIBLE, INGREDIENT WHERE INGREDIENT.RECIPEID = '" . $recipeId . "' AND INGREDIENT.EDIBLEID = EDIBLE.ID";
	$resultID = mysql_query($query, $linkID) or die("Data not found.");
	for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
		$row = mysql_fetch_assoc($resultID);
		$ingredients[] = $row['NAME'];	
	}
		
	$ret['id'] = $recipeId;
	$ret['categories'] = $cats;
	$ret['instructions'] = $instructions;
	$ret['ingredients'] = $ingredients;
	echo json_encode($ret);
	unset($ret);	
} elseif($action == "deleteRecipe") {
	$recipeId = htmlspecialchars(trim($_POST['recipeId']));
	$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
	mysql_select_db($database, $linkID) or die("Could not find database.");
	mysql_set_charset('utf8',$linkID); 
	
	$query = "DELETE FROM CATEGORY WHERE RECIPEID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	

	$query = "DELETE FROM INSTRUCTIONS WHERE RECIPEID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	
	
	$query = "DELETE FROM SECTION WHERE RECIPEID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	
	
	$query = "DELETE FROM INGREDIENT WHERE RECIPEID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	
	
	$query = "DELETE FROM RECIPE WHERE ID = '" . $recipeId . "'"; 	
	$resultID = mysql_query($query, $linkID) or die("Data not found.");	
}
?>	