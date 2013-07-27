<?php

class ClassLibrary
{
	private static $isLoaded = false;
	private static $folders = array();
	
	public static function getClassFolders()
	{
		self::loadClasses();
		return self::$folders;
	}
	
	private static function loadClasses()
	{
		if(self::$isLoaded)
			return;
		self::$isLoaded = true;
		
		// Core's classes and controllers
		self::$folders[] = PATH."core/class/";
		
		// List plugins folders
		$array_plugins = FileProcessing::listDir(PATH_PLUGIN);
		
		foreach($array_plugins as $plugin)
		{
			$folder = PATH_PLUGIN.$plugin."/class/";
			if(is_dir($folder))
				self::$folders[] = $folder;

		}
	}
}
?>