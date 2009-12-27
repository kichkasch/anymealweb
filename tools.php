<?php 

/* nicked from http://www.hawkee.com/snippet/1281/ */
function directoryToArray($directory, $extension="", $full_path = true) {
	$array_items = array();
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($directory. "/" . $file)) {
					$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $extension, $full_path)); 
				}
				else { 
					if(!$extension || (ereg("." . $extension, $file)))
					{
						$array_items[] = $file;
					}
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}

?>