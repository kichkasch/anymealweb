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
<title>[ADMIN] JuMiMeal Photo Admin</title>

<!-- All Images Created And Copyrighted By Christina Chun Unless Noted Otherwise.  All rights Reserved. -->

<link rel="stylesheet" type="text/css" href="default.css"/>

</head>

<?php
/* 

	Processing / Initializing

*/

include 'config.php';
include 'photomenu.php';
include 'tools.php';

/* !--

END processing

*/
?>


<body>
<div class="main">
	<div class="container">
<?php printMenu(); ?>
		<div class="content">

<?php saveFile(); ?>
<?php deleteFiles(); ?>
<div class="item">	
<table cellpadding="10" cellspacing="10" border="0" width="100%">
<tr>	
<td width="10%">&nbsp;</td>
<td width="50%" align="left" valign="top">
<form action="photoupload.php" method="post" enctype="multipart/form-data">
<p><h1>Upload new photo</h1><br/>
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /></p>
</td>
<td align="right" valign="middle">
<input type="submit" name="submit" value="Upload" />
</td>
</form>
<td width="10%">&nbsp;</td>
</tr>
</table>
</div>

<div class="item">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
<tr>
<td width="10%">&nbsp;</td>
<td width="80%">
<p><h1>Available photos</h1></p>
<table cellpadding="5" cellspacing="5" border="0" align="center" valign="top">

<form action="photoupload.php" method="post" enctype="multipart/form-data">
<?php
$path = "/var/www/anymeal/photos";
$jpgs = directoryToArray($path);
$i=0;
foreach ($jpgs as $jpeg)
{
if ($i % 4 == 0)
{
  echo "<tr>";
}
echo '<td width="33%" align="center" valign="middle">';
print('<p><a href="photos/' . $jpeg . '"><img src="photos/' . $jpeg . '" width="90" border="0" alt=""></a><br>');
print ('<input type="checkbox" name="deletecandidates[]" value="'. $jpeg . '">' . $jpeg . '</p>');
echo "</td>";
if (($i+1) % 4 == 0)
{
  echo "</tr>";
}

$i = $i + 1;
} /* foreach */
?>

<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan="2" align="right"><input type="submit" name="submit" value="Delete selected" /></td></tr>
</form>
</table>
</td>
<td width="10%">&nbsp;</td>
</tr>
</table>
</div>


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


/* Nicked from http://www.w3schools.com/PHP/php_file_upload.asp */
function saveFile()
{
if ($_FILES["file"])
{
?>
<div class="item">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
<tr>
<td width="10%">&nbsp;</td>
<td width="80%">
<p><h1>Feedback from last upload:</h1><br/>
<?php
if ((($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    if (file_exists("photos/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "photos/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "photos/" . $_FILES["file"]["name"];
      }
    }
  }
else
  {
  echo "Invalid file";
  }
?>
</td>
<td width="10%">&nbsp;</td>
</tr>
</table>
</div>
<?php
} /* if */
} /* function */




function deleteFiles()
{
if (isset($_REQUEST['deletecandidates']) )
{
$deletes = $_REQUEST['deletecandidates'];
?>
<div class="item">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
<tr>
<td width="10%">&nbsp;</td>
<td width="80%">
<p><h1>Feedback from last delete:</h1><br/>
<?php
foreach ($deletes as $delete)
{
	$delete = "photos/" . $delete;
	print ("Deleting: " . $delete . "<br/>");
	unlink ($delete) or print ('Unable to delete <' . $delete . '>'); 	
}
?>
</td>
<td width="10%">&nbsp;</td>
</tr>
</table>
</div>
<?php
} /* if */
} /* function */


?>