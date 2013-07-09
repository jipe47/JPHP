<?php
require_once "associativecache.class.php";
class PluginsCache extends AssociativeCache
{
	private $array_htmlheaders = array("js", "css");
	private $array_allowed_constant = array("js", "css", "php", "html");
	
	public function __construct()
	{
		$this->setName("Plugins");
	}
	
	public function onMiss()
	{
		$path = func_get_arg(0);
	}
	
	private function explorePlugin($folder)
	{
		// To complete
		$path = PATH_PLUGIN.$folder;
		
		$dir_handle = @opendir($path) or die("Cannot open <strong>" . $path . "</strong> for include.");
			
		$included_class = get_declared_classes();
		
		// Plugin's information
		$info_plugin = array(	"pluginname" => "",
				"classname" => "",
				"dirname" => $file,
				"models" => array(),
				"pages" => array(),
				"widgets" => array(),
				"scripts" => array());
		
		foreach($this->array_allowed_constant as $c)
			$info_plugin["folder_".$c] = false;
			
		$pdir = @opendir($path.$file) or die("Cannot open path of plugin <strong>" . $file . "</strong>.");
	
		// Include files
		while($f = readdir($pdir))
		{
			if($f == "." || $f == ".." || !is_dir($path.$file."/".$f))
				continue;
	
			if(in_array($f, $array_htmlheaders))
			{
				HtmlHeaders::includeDir($f, $path.$file."/".$f);
				$info_plugin["folder_".$f] = true;
			}
			else if($f == "php")
				self::includePath($path.$file."/".$f, false);
	
			if(in_array($f, $array_allowed_constant))
				define(strtoupper($f)."_".strtoupper($file), $path.$file."/".$f."/");
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
				$plugin_instance->setPath($file."/");
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
			foreach(self::$handlers_uncache as $handler)
				call_user_func_array($handler, array($class, &$plugin_instance, &$info_plugin));
		}
			
		// Include configuration file
		if(file_exists($path.$file."/config.ini"))
			JPHP::importIni($path.$file."/config.ini", $file);
				
		
		return $info_plugin;
	}
	
	/*
	public function build()
	{
		
		$dir_handle = @opendir($path) or die("Cannot open <strong>" . $path . "</strong> for include.");
			
		$included_class = get_declared_classes();
		
		while($file = readdir($dir_handle))
		{
			if($file == "." || $file == ".." || $file == ".svn" || $file == ".git" || !is_dir($path."/".$file))
				continue;
				
			// Plugin's information
			$info_plugin = array(	"pluginname" => "",
					"classname" => "",
					"dirname" => $file,
					"models" => array(),
					"pages" => array(),
					"widgets" => array(),
					"scripts" => array());
			foreach($array_allowed_constant as $c)
				$info_plugin["folder_".$c] = false;
				
			$pdir = @opendir($path.$file) or die("Cannot open path of plugin <strong>" . $file . "</strong>.");
		
			// Include files
			while($f = readdir($pdir))
			{
				if($f == "." || $f == ".." || !is_dir($path.$file."/".$f))
					continue;
		
				if(in_array($f, $array_htmlheaders))
				{
					HtmlHeaders::includeDir($f, $path.$file."/".$f);
					$info_plugin["folder_".$f] = true;
				}
				else if($f == "php")
					self::includePath($path.$file."/".$f, false);
		
				if(in_array($f, $array_allowed_constant))
					define(strtoupper($f)."_".strtoupper($file), $path.$file."/".$f."/");
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
					$plugin_instance->setPath($file."/");
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
				foreach(self::$handlers_uncache as $handler)
					call_user_func_array($handler, array($class, &$plugin_instance, &$info_plugin));
			}
				
			// Include configuration file
			if(file_exists($path.$file."/config.ini"))
				JPHP::importIni($path.$file."/config.ini", $file);
				
			self::$cache_plugins[] = $info_plugin;
		
		
	}
	*/
}