<?php
/**
 * Manages plugins.
 *
 * @author Jean-Philippe Collette
 * @package Core
 * @subpackage Plugin
 */
class Plugins
{
	private static $plugins = array(); // (string) plugin name => instance
	
	public static function getDefaultModel($plugin)
	{
		return self::getPlugin($plugin)->getDefaultModel();
	}
	
	public static function pluginExists($name)
	{
		return array_key_exists($name, self::$plugins);
	}
	/**
	 * Includes a plugin in the framework.
	 * @param string $name Plugin name.
	 * @param string $classname Class that implements the plugin.
	 * @throws Exception Throwned of the plugin already exists.
	 */
	public static function addPlugin($plugin_instance)
	{
		if(array_key_exists($plugin_instance->getPluginName(), self::$plugins))
			throw new Exception("The plugin named '" . $plugin_instance->getPluginName() . "' already exists.");
		
		self::$plugins[$plugin_instance->getPluginName()] = $plugin_instance;
	}
	
	/**
	 * Returns admin links collected in the plugins.
	 * @return  Array where keys are plugin name and values are array
	 * 			an associative array (page argument => link name).
	 */
	public static function getAdminInfos()
	{
		//return self::$adminLinks;
		$array = array();
		
		foreach(self::$plugins as $pname => $p)
		{
			$hasLinks = count($p->getAdminLinks()) > 0;
			$hasScripts = count($p->getScripts()) > 0;
			
			if($hasLinks || $hasScripts)
			{
				$array[$pname]['links'] = $p->getAdminLinks();
				$array[$pname]['position'] = $p->getAdminPosition();
				$array[$pname]['scripts'] = $p->getScripts();
				$array[$pname]['name'] = $p->getAdminName() == "" ? $pname : $p->getAdminName();
			}
		}
		
		// Sort the administration panels by position and name
		uasort($array, "Plugins::sortPlugin");
		return $array;
	}
	
	public static function sortPlugin($a, $b)
	{
		$ap = $a['position'];
		$bp = $b['position'];
		
		if($ap != $bp)
			return $ap < $bp ? -1 : 1;
		else
			return strcmp($a['name'],$b['name']);
	}
	
	public static function addAdminLink($pluginName, $name, $link)
	{
		if(!array_key_exists($pluginName, self::$adminLinks))
			self::$adminLinks[$pluginName] = array();
		self::$adminLinks[$pluginName][$link] = $name;
	}
	
	/**
	 * Returns an instance of a plugin.
	 * @param string $name Plugin name.
	 * @throws Exception Throwned of the plugin does not exist.
	 */
	public static function getPlugin($name)
	{
		if(!array_key_exists($name, self::$plugins))
			throw new Exception("The plugin '". $name . "' does not exist.");
		return self::$plugins[$name];
	}
	
	/**
	 * Returns plugin list.
	 */
	public static function getPlugins()
	{
		return self::$plugins;
	}
} 


?>