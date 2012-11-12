<?php
//var_dump(dirname(__FILE__));
//var_dump(__FILE__);

//var_dump(readdir(opendir($dir)));
// Open a known directory, and proceed to read its contents

define('DS', '\\');
$dir = 'C:'.DS.'Documents and Settings'.DS.'diego.saa'.DS.'My Documents'.DS.'leads'.DS;

readDirContents($dir);

foreach(directory_list($dir) as $dirKey => $dirContents)
{
	
}


function readDirContents($dir, $function = null)
{
	if (($dh = opendir($dir)) !== false)
	{
		while (($file = readdir($dh)) !== false)
		{
			if(!in_array($file, ['.', '..']))
			{
				if(is_dir($dir.DS.$file))
				{
					echo "is dir: ";
					var_dump($dir.DS.$file);
					readDirContents($dir.DS.$file);
				}
				elseif(is_file($dir.DS.$file))
				{
					if(pathinfo($dir.DS.$file)['extension'] == '.txt')
					{
						echo "is file: ";
						echo "tab:"; var_dump(basename(dirname(dirname($dir.DS.$file))));
						echo "camp: "; var_dump(basename(dirname($dir.DS.$file)));
						echo "csv fileName: "; var_dump(trim(basename($dir.DS.$file), 'txt'));
						var_dump($dir.DS.$file);
						var_dump(file($dir.DS.$file));
					}
				}
				
			}
		}
	}
}

function directory_list($directory_base_path, $filter_dir = false, $filter_files = false, $exclude = ".|..|.DS_Store|.svn", $recursive = true){
	$directory_base_path = rtrim($directory_base_path, "/") . "/";

	if (!is_dir($directory_base_path)){
		error_log(__FUNCTION__ . "File at: $directory_base_path is not a directory.");
		return false;
	}

	$result_list = array();
	$exclude_array = explode("|", $exclude);

	if (!$folder_handle = opendir($directory_base_path)) {
		error_log(__FUNCTION__ . "Could not open directory at: $directory_base_path");
		return false;
	}else{
		while(false !== ($filename = readdir($folder_handle))) {
			if(!in_array($filename, $exclude_array)) {
				if(is_dir($directory_base_path . $filename . "/")) {
					if($recursive && strcmp($filename, ".")!=0 && strcmp($filename, "..")!=0 ){ // prevent infinite recursion
						error_log($directory_base_path . $filename . "/");
						$result_list[$filename] = directory_list("$directory_base_path$filename/", $filter_dir, $filter_files, $exclude, $recursive);
					}elseif(!$filter_dir){
						$result_list[] = $filename;
					}
				}elseif(!$filter_files){
					$result_list[] = $filename;
				}
			}
		}
		closedir($folder_handle);
		return $result_list;
	}
}
/*
 if(is_file($file))
 {
echo 'hello';
echo "filename: var_dump($file) : filetype: " . filetype($dir . var_dump($file)) . "\n";
}
elseif(is_dir($file))
{
echo 'bye';
var_dump(trim($dir).$file);
var_dump($file);
readDirContents($dir.DS.$file);
}
}
closedir($dh);
}
else
{
echo "filename: var_dump($file) : filetype: " . filetype($dir . var_dump($file)) . "\n";
}
}
*/
?>