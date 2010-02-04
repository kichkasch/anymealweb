<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
/* !--
  JuMiMeal - Anymeal Web Frontend
  by Michael Pilgermann (kichkasch@gmx.de)
  
    Copyright (C) 2008  Michael Pilgermann

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
  
  The purpose of this PHP-Script is simply accessing a database, which contains recipe information
  for reading purposes only in order to display them in a web browser. The accessed database must
  be in the design given by the Anymeal (http://www.wedesoft.demon.co.uk/anymeal-api/) application.
  This application will propably also be used for adding and modifying content in the database as
  this web frontend is not capable of supporting this.
-->
*/
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<meta name="Description" content="Web Frontend for Anymeal database" />
<meta name="Keywords" content="" />
<meta name="Copyright" content="Michael Pilgermann" />
<meta name="Designed By" content="www.kichkasch.de" />
<meta name="Language" content="English" />
<title>JuMiMeal - An AnyMeal Webfrontend (v0.1)</title>

<!-- All Images Created And Copyrighted By Christina Chun Unless Noted Otherwise.  All rights Reserved. -->

<style type="text/css" title="layout" media="screen"> @import url("style.css"); </style>

</head>

<?php

/* 

	Processing

*/
$cat=$_REQUEST['category'];
$rec_id=$_REQUEST['recipe_id'];

include 'config.php';

if (isset ($cat)) {
	$page = "cat";
} else {
	if (isset($rec_id)) {
		$page = "detail";
	} else {
		$page = "index";
	}
}

/* !--

END processing

*/
?>


<body>
<div id="container">
	<div class="contentheader"></div>		
		<div class="maincontainer"><div id="header"><a href="index.php">JuMiMeal</a></div>
			<div id="menu">
				<div id="nav">

<a href="index.php?category=@all@" title="Alle">Alle Rezepte</a> 

<?php
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$query = "SELECT distinct NAME from CATEGORIES";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print("| <a href='index.php?category=" . $row['NAME'] . "' title='" . $row['NAME'] . "'>" . $row['NAME'] . "</a> ");
}

?>

</div>
			</div>

<div class="content">
<hr /><br />
<?php
if (! strcmp($page, "index")){
	assemblePageIndex();
}
if (! strcmp($page, "cat")){
	assemblePageList();
}
if (! strcmp($page, "detail")){
	assemblePageDetail();
}
?>

</div>

<div class="bottom"></div>
<div class="footer">Designed By <a href="http://www.christinachun.com" title="Christina Chun - Digital Artist &amp; Web Designer">Christina Chun</a> &copy; 2005-2006 | Content By <a href="mailto:michael.pilgermann@gmx.de" title="Email to Michael Pilgermann">Michael Pilgermann</a> &copy; 2010</div>
</div></div>
</body>
</html>



<?php
/* 

	Pages and subpages

*/
function assemblePageIndex() {
global $host;
global $user;
global $pass;
global $database;

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$query = "SELECT COUNT(NAME) as name from CATEGORIES";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
$row = mysql_fetch_assoc($resultID);
$anzahlCats = $row['name'];

$query = "SELECT COUNT(TITLE) as title from RECIPE";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
$row = mysql_fetch_assoc($resultID);
$anzahlRecs = $row['title'];

?>

<img src="images/5chili.gif" class="floatright" width="300" height="225" alt="5Chili" />

<p><div id="contentHeading">JuMiMeal - das Rezeptebuch f&uuml;r Jule und Micha</div></p>

<p>JuMiMeal ist ein Webfrontend f&uuml;r die Rezeptedatenbank von Anymeal.</p>

<p>Um ein Rezept anzuzeigen, kann man oben aus einem der Schl&uuml;sselworte ausw&auml;hlen - es wird dann eine Liste mit m&ouml;glichen Treffern angezeigt. Alternativ k&ouml;nnen auch s&auml;mtliche Rezepte in einer Liste angezeigt werden.</p>

<p><div id="contentHeading">Inhalt aktuell</div></p>
<p>Es befinden sich aktuell <?php print ("<" . $anzahlRecs . ">") ?> Rezepte aufgeteilt auf insgesamt <?php print ("<" . $anzahlCats . ">") ?> Kategorien in der Datenbank.</p>

				</div>
		</div>

<?php
}


function assemblePageList() {
global $host;
global $user;
global $pass;
global $database;
global $cat;

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

if (! strcmp($cat, "@all@")){
?><p><div id="contentHeading">Liste aller Rezepte:</div></p><?php
$query = "SELECT * FROM RECIPE";
} else {
?><p><div id="contentHeading">Rezepte in der Kategorie '<?php print($cat)?>':</div></p><?php
$query = "SELECT RECIPE.TITLE as TITLE, RECIPE.ID as ID FROM CATEGORIES, CATEGORY, RECIPE WHERE CATEGORY.CATEGORYID=CATEGORIES.ID AND CATEGORY.RECIPEID=RECIPE.ID and CATEGORIES.NAME='" . $cat . "'";
}

print ("<div id='listRecipes'><ul>");
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print("<li><a href='index.php?recipe_id=" . $row['ID'] . "' title='" . $row['TITLE'] . "'>" . $row['TITLE'] . "</a> </li>");
}
print ("</ul></div>");
?>


</div>
		</div>

<?php
}


function assemblePageDetail() {
global $host;
global $user;
global $pass;
global $database;
global $rec_id;

$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");
$query = "SELECT * FROM RECIPE where ID='". $rec_id . "'";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
$row = mysql_fetch_assoc($resultID);

print ("<p><div id='contentHeadingEmph'><center>" . $row['TITLE'] . "</center></div></p>"); 
?>

<br/>
<hr width="70%"/>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
<td align="left" valign="top" width="80%">

<?php
$query = "SELECT TITLE as TITLE, ID AS ID FROM SECTION where RECIPEID='". $rec_id . "'";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print("<p><div id='contentHeading'>" . $row['TITLE'] . "</div></p>");
 $sectionID = $row['ID'];

 $query = "SELECT EDIBLE.NAME as NAME, AMOUNTDOUBLE, AMOUNTNOMINATOR, AMOUNTDENOMINATOR, UNIT from INGREDIENT, EDIBLE where INGREDIENT.EDIBLEID = EDIBLE.ID and INGREDIENT.SECTIONID='". $sectionID . "' and INGREDIENT.RECIPEID='" . $rec_id . "'" ;
 $resultID2 = mysql_query($query, $linkID) or die("Data not found.");
 print ("<ul>");
 for($y = 0 ; $y < mysql_num_rows($resultID2) ; $y++){
  $row2 = mysql_fetch_assoc($resultID2);
  
  $amount = "";
  if (strcmp($row2['AMOUNTDOUBLE'], "")) {
    $amount = $row2['AMOUNTDOUBLE'] . " ";
  } 
  if (strcmp($row2['AMOUNTNOMINATOR'], "")) {
    $amount = $row2['AMOUNTNOMINATOR'];
	if (strcmp($row2['AMOUNTDENOMINATOR'], "") and strcmp($row2['AMOUNTDENOMINATOR'], "1")) {
		$amount = $amount . "/" . $row2['AMOUNTDENOMINATOR'];
	}
    $amount = $amount . " ";
  }
  if (strcmp($row2['UNIT'], "")) {
    $amount = $amount . $row2['UNIT'] . " ";
  }

  print("<li>" . $amount . $row2['NAME'] . "</li>");
 }
 print ("</ul>");
}

?>

</td>

<?php
$query = "SELECT COUNT(url) from photos where recipeid='" . $rec_id . "'";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
$row = mysql_fetch_assoc($resultID);
$anzahl = $row['COUNT(url)'];

if ($anzahl > 0)
{

$query = "SELECT comment, url FROM photos where recipeid='". $rec_id . "'";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 $url = $row['url'];
 $comment = $row['comment'];
print ('<td align="right" valign="middle">');
print ('<a href="photos/' . $url . '" name="' . $comment . '">');
print ('<img src="photos/' . $url . '" height="150" border="0">');
?>
</a>
</td>

<?php
} /* for */
} /* if */
?>

<td width="3%">&nbsp;</td>
</tr>
</table>


<br/>
<hr width="70%"/>

<?php
$query = "SELECT TITLE, INSTRUCTIONS FROM INSTRUCTIONS where RECIPEID='". $rec_id . "'";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 print("<p><div id='contentHeading'>" . $row['TITLE'] . "</div></p>");
 $instr = $row['INSTRUCTIONS'];
 $instr_formatted = str_replace("\n", "<br/>", $instr);
 print("<div id='instructions'>" . $instr_formatted . "</div>");
}
?>

<?php
}
?>



