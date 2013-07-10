<?php
require_once "associativecache.class.php";
class IncluderCache extends AssociativeCache
{
	private $array_ignore 			= array(".svn", ".git"); 	// Ignored files for inclusion
	private $array_ext_include 		= array("php", "php5"); 	// Allowed PHP extensions for a require_once
	
	public function __construct()
	{
		$this->setName("Includer");
	}
	
	public function onMiss()
	{
		$path = func_get_arg(0);
		echo "IncluderCache : " .$path."<br />";
		
		$found = array();
		$subdir = array();
		
		$dir_handle = @opendir($path) or die("Cannot open <strong>" . $path . "</strong> for include");
		
		while($file = readdir($dir_handle))
		{
			if($file == "." ||$file == ".." || in_array($file, $this->array_ignore))
				continue;
	
			$ext = pathinfo($path.$file, PATHINFO_EXTENSION);
			if(is_dir($path."/".$file))
				$subdir[] = $file;
			else if(in_array($ext, $this->array_ext_include))
				$found[] = $file;
		}
		$this->data[$path] = array("php" => $found, "subdir" => $subdir);
	}
}