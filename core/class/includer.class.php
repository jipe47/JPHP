<?php
class Includer
{	
	// Handling functions stuff
	private static $handlers = array();
	private static $cache_plugin = null;
	private static $cache_includer = null;
	
	public static function addHandler($handlerName, $cache)
	{
		self::$handlers_cache[] = $handlerName;
	}
	
	public static function includePlugins($path)
	{
		echo "IncludePlugins in " . $path . "<br />";
		if(self::$cache_plugin == null)
			self::$cache_plugin = Singleton::getInstance("PluginsCache");
		
		// Registering default handlers
		self::addHandler("Includer::handlerPage");
		self::addHandler("Includer::handlerWidget");
		self::addHandler("Includer::handlerScript");
		
		$cache_plugin = self::$cache->get("plugins", $path);
	
		foreach(self::$cache_plugins as $p)
		{
			$path = PATH_PLUGIN.$p['dirname']."/";
			
			foreach($array_allowed_constant as $c)
				if($p['folder_'.$c])
					define(strtoupper($p['dirname'])."_".strtoupper($c), $path.$c."/");
			
			if($p['folder_css'])
				HtmlHeaders::includeDir("css", $path."css");
			if($p['folder_js'])
				HtmlHeaders::includeDir("js", $path."js");
			self::includePath($path."php", false);
			
			$plugin_instance = $p['classname'] != "" ? new $p['classname'] : null;
			
			if($plugin_instance != null)
			{
				$plugin_instance->setPath($p['dirname']."/");
				Plugins::addPlugin($plugin_instance);
				foreach($p['models'] as $class)
				{
					$model_instance = new $class();
					$plugin_instance->addModel($model_instance);
				}
				
				foreach($p['widgets'] as $class)
				{
					$widget = new $class($plugin_instance);
					$plugin_instance->addWidget($widget);
				}
			}
			
			foreach($p['pages'] as $class)
				PageRegister::registerPage($class, $p['pluginname']);
	
		}
		
		foreach(self::$handlers_uncache as $h)
			call_user_func_array($h, array());
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
	
	/**
	 * Includes recursively a directory. It skips svn and git folders and 
	 * executes a "require_once" on php files.
	 *
	 * @param string $path Path to the directory.
	 * @param boolean $recursive If set, the include is recursive.
	 */
	public static function includePath($path, $registerPage = true)
	{
		echo "IncludePlugins in " . $path . "<br />";
		if(self::$cache_includer == null)
			self::$cache_includer = Singleton::getInstance("IncluderCache");
		
		$phpFound = false;
		$included_class = get_declared_classes();
		
		$info = self::$cache_includer->get($path);
		$phpFound = count($info["php"]) > 0;
		if(!$phpFound)
		{
			//$time = $chrono->stop("including " .$path);
			return;
		}
		else
		{
			foreach($info["subdir"] as $s)
				Includer::includePath($path."/".$s, $registerPage);
			foreach($info["php"] as $f)
				require_once $path."/".$f;
		}
		
		if($registerPage)
		{
			$new_included_class = array_diff(get_declared_classes(), $included_class);
			
			foreach($new_included_class as $c)
			{
				if(is_subclass_of($c, "Page") && $c != "AjaxPage" && $c != "RequestPage" && $c != "AdminPage" && $c != "Page" && $c != "ScriptPage" && $c != "HandlerPage" && $c != "Widget")
				{
					PageRegister::registerPage($c);
				}
			}
		}
	}
	
	/**
	 * Specifies a template and includes all required files.
	 * @param string $t the template name.
	 * @throws Exception if the template does not exist.
	 */
	public static function includeTemplate($t)
	{
		$folder = PATH_TPL.$t;
	
		if(!is_dir($folder))
			throw new Exception("The template does not exists");
	
		HtmlHeaders::includeDir("css", $folder."/css");
		HtmlHeaders::includeDir("js", $folder."/js");
	}
}