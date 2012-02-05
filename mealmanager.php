<!DOCTYPE html>
<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
                <title>JumiMeal Meal Manager</title>
                <!-- jquery imports -->
                <link type="text/css" href="css/sunny/jquery-ui-1.8.16.custom.css" rel="stylesheet" />  
                <script type="text/javascript" src="scripts/jquery-1.6.2.min.js"></script>
                <script type="text/javascript" src="scripts/jquery-ui-1.8.16.custom.min.js"></script> 
				    <!-- tiny table stuff -->
				    <link href="css/jquery.ui.tinytbl.css" rel="stylesheet" type="text/css" />
				    <script src="scripts/jquery.ui.tinytbl.js" type="text/javascript"></script>
                <!-- my own imports -->
                <script src="scripts/jumimeal.js" type="text/javascript"></script>
					 <link href="css/jumimeal.css" rel="stylesheet" type="text/css" />
        </head>
        
<?php
include 'config.php';     
?>   
  
<body>
<h1>JumiMeal Meal Manager</h1>
<div class="demo">

<?php
include 'anymeal_dialogs.php';     
?>  

<button id="bAdd">Add new Recipe</button>

<p>
<div align="center">
<form>
	<div id="radio">
		<input type="radio" id="radio0" name="radio"  checked="checked" onClick="catSelected(0)" /><label for="radio0">*All*</label>
		
<?php
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
mysql_set_charset('utf8',$linkID);
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
mysql_set_charset('utf8',$linkID); 
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

</div>

</div>

</body>
</html>
