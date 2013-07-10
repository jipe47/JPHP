<?php
require_once "associativecache.class.php";
class PluginsCache extends AssociativeCache
{
	// Handling functions, applied to each plugin
	private static $handlers = array();
	
	public function __construct()
	{
		$this->setName("Plugins");
		
		// Registering default handlers
		self::addHandler("Includer::handlerPage");
		self::addHandler("Includer::handlerWidget");
		self::addHandler("Includer::handlerScript");
	}
	
	public function onMiss()
	{
		if(func_num_args() == 0)
			$path = PATH_PLUGIN;
		else
			$path = func_get_arg(0);
		echo "PluginsCache for " . $path. "<br />";
		
		$this->data[$path] = $this->explorePlugins($path);
	}
	
	private function explorePlugins($path)
	{
		$dir_handle = @opendir($path) or die("Cannot open <strong>" . $path . "</strong> for include.");
		$array_data = array();
		
		while($folder = readdir($dir_handle))
		{
			if($folder == "." || $folder == "..")
				continue;
			$array_data[$folder] = $this->explorePlugin($path.$folder."/");
		}
		
		closedir($dir_handle);
		return $array_data;
	}
	private function explorePlugin($path)
	{
		echo "Exploring path $path<br />";
			
		$included_class = get_declared_classes();
		
		// Plugin's information
		$info_plugin = array(	
				"pluginname" => "",
				"classname" => "",
				"dirname" => $path,
				"class" => array(),
				"models" => array(),
				"pages" => array(),
				"widgets" => array(),
				"scripts" => array(),
				"smarty" => array(),
				"css" => array(),
				"js" => array(),
				"config" => array() // array of available extension (ini or json)
				); 
		
		$pdir = @opendir($path.$file) or die("Cannot open path of plugin <strong>" . $file . "</strong>.");
	
		// Include files
		while($f = readdir($pdir))
		{
			if($f == "." || $f == ".." || !is_dir($path.$f))
				continue;
	
			switch($f)
			{
				case "php":
					Includer::includePath($path.$f, false);
					break;
					
				case "css":
				case "js":
				case "smarty":
				case "class":
					$info_plugin[$f] = FileProcessing::listDir($path.$f); 
					break;
					
				case "config.ini":
				case "config.json":
					$ext = pathinfo($path.$file, PATHINFO_EXTENSION);
					$info_plugin[$f]["config"][] = $ext;
					break;
			}
		}
			
		$new_included_class = array_diff(get_declared_classes(), $included_class);
		$included_class = get_declared_classes();
			
		// First find the subclass of Plugin
		$plugin_instance = null;
		foreach($new_included_class as $k => $class)
		{
			if(is_subclass_of($class, "Plugin"))
			{
				$plugin_instance = new $class();
				$plugin_instance->setPath($path."/");
				Plugins::addPlugin($plugin_instance);
				unset($new_included_class[$k]);
					
				$info_plugin['classname'] = $class;
				$info_plugin['pluginname'] = $plugin_instance->getPluginName();
					
				break;
			}
		}
			
		// Find models of plugins, before link other ressources
		foreach($new_included_class as $k => $class)
		{
			if(is_subclass_of($class, "Model"))
			{
				if(is_null($plugin_instance))
					throw new Exception("Plugin subclass not found in " . $path.$file);
					
				$model_instance = new $class();
				$plugin_instance->addModel($model_instance);
				unset($new_included_class[$k]);
					
				$info_plugin['models'][] = $class;
			}
		}
			
		// Link components to plugin
		foreach($new_included_class as $k => $class)
		{
			foreach(self::$handlers as $handler)
				call_user_func_array($handler, array($class, &$plugin_instance, &$info_plugin));
		}
			
		// Include configuration file
		if(file_exists($path."/config.ini"))
			JPHP::importIni($path."config.ini", $file);
		
		if(file_exists($path."/config.json"))
			JPHP::importJSON($path."config.json", $file);
				
		
		return $info_plugin;
	}
	
	public static function handlerScript($class, &$plugin_instance, &$info_plugin)
	{
		if(!is_subclass_of($class, "ScriptPage"))
			return;
		$info_plugin['scripts'][] = $class;
		$plugin_instance->addScript($class);
	}
	
	public static function handlerPage($class, &$plugin_instance, &$info_plugin)
	{
		if(!is_subclass_of($class, "Page") || is_subclass_of($class, "Widget"))
			return;
	
		PageRegister::registerPage($class, $info_plugin['pluginname']);
		$info_plugin['pages'][] = $class;
	}
	
	public static function handlerWidget($class, &$plugin_instance, &$info_plugin)
	{
		if(!is_subclass_of($class, "Widget"))
			return;
	
		if(is_null($plugin_instance))
			throw new Exception($class.": widget for undefined plugin");
		else
		{
			$widget = new $class($plugin_instance);
			$plugin_instance->addWidget($widget);
			$info_plugin['widgets'][] = $class;
		}
	}
	
	public static function addHandler($handlerName)
	{
		self::$handlers[] = $handlerName;
	}
}