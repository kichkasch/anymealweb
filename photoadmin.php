<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php 
/* !--
  JuMiMeal - Anymeal Web Frontend - V 0.1 - 2008-11-11
  by Michael Pilgermann (michael.pilgermann@gmx.de / http://www.kichkasch.de)
  
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
<title>[ADMIN] JuMiMeal Photo Admin</title>

<!-- All Images Created And Copyrighted By Christina Chun Unless Noted Otherwise.  All rights Reserved. -->

<link rel="stylesheet" type="text/css" href="default.css"/>

</head>

<?php
/* 

	Processing / Initializing

*/

include 'photoconfig.php';
include 'photomenu.php'; 
include 'tools.php';

$action=$_REQUEST['action'];

if (isset ($action)) {
	if (! empty ($action)) {
		$action_photoid = $_REQUEST['photoid'];
		$action_recid = $_REQUEST['action_recid'];
		$action_photourl = $_REQUEST['photourl'];
		applyChange($action_photoid, $action_recid,$action_photourl);
	}
}
/* !--

END processing

*/
?>


<body>
<div class="main">
	<div class="container">
<?php printMenu(); ?>
		<div class="content">
		


<?php
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");


$names = array();
$recids = array();

$query = "select TITLE, ID from RECIPE order by TITLE";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 array_push($names, $row['TITLE']);
 array_push($recids, $row['ID']);
}

$path = "/var/www/anymeal/photos";
$jpgs = directoryToArray($path);

$list_url = array();
$list_title = array();
$list_photoid = array();
$list_recid = array();
$query = "SELECT TITLE, url, RECIPE.ID as recid, photos.id as photoid FROM photos,RECIPE where photos.recipeid=RECIPE.ID";
$resultID = mysql_query($query, $linkID) or die("Data not found.");
for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){
 $row = mysql_fetch_assoc($resultID);
 array_push($list_url, $row['url']);
 array_push($list_title, $row['TITLE']);
 array_push($list_photoid, $row['photoid']);
 array_push($list_recid, $row['recid']);  
}

foreach ($jpgs as $jpeg)
{
 if (in_array($jpeg, $list_url))
 {
 	$index = array_search($jpeg, $list_url); 
 	showOneEntry($list_url[$index], $list_title[$index], $list_recid[$index], $names, $recids, $list_photoid[$index]);
 } else {
	showOneEntry($jpeg, NULL, NULL, $names, $recids, NULL);
 }


} /* for */
?>

		</div>
		<?php printFooter(); ?>
	</div>	
</div>

</body>
</html>





<?php
/* 

	Pages and subpages

*/


function showOneEntry($url, $title, $recid, $names, $recids, $photoid)
{
?> 
<div class="item">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
<tr>
<td width="5%">&nbsp;</td>

<td align="left" width="10%">

<?php  
print ('<a href="photos/' . $url . '" name="' . $title . '">');
print ('<img src="photos/' . $url . '" width="70" border="0"/>');
?>
</a></td>

<form action="photoadmin.php">
<td valign="top">
<?php
print ("<p>Filename: photos/" . $url . "</p>");
if ($title)
{
print ("<p>Current association: <b><a href='index.php?recipe_id=" . $recid . "'>" . $title . "</a></b></p>");
} else {
print ('<p><em><span style="color:red">Not yet associated</span></em></p>');
}
?>
<p>Select new association:<br/>
<select name=action_recid>
<?php

for ($i=0; $i<sizeof($names); $i++)
{
  print ("<option name=one value=" . $recids[$i] . ">" . $names[$i] . "</option>");
}

?>
</select>
</p>
<input type="hidden" name="photoid" value="<?php print ($photoid); ?>"/>
<input type="hidden" name="action" value="change"/>
<input type="hidden" name="photourl" value="<?php print ($url); ?>"/>
</td>
<td align="right" valign="middle">
<div align="right"><input type="submit" value="Apply"></div>
</td>
</form>


<td width="5%">&nbsp;</td>
</tr>
</table>
</div>
<?php
}


function applyChange($action_photoid, $action_recid, $photourl = NULL) {
global $host;
global $user;
global $pass;
global $database;


$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

if ($action_photoid == "")
{
	$query = "insert into photos (recipeid, comment, url) values ('" . $action_recid . "', '', '" . $photourl . "')";
} else {
	$query = "update photos set recipeid=" . $action_recid . " where photos.id=" . $action_photoid;
}
$resultID = mysql_query($query, $linkID) or die("Could not update value.");
mysql_close($linkID);

}

?>