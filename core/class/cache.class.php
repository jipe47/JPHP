<?php
class Cache
{
	public static $filename = "cache.tmp";
	public static $filename_timestamp = "timestamps.tmp";
	private static $data = array();
	
	private static $file = null;
	
	
	private static $times_stored = array();
	private static $times_file = array();
	
	public static function save()
	{
		FileProcessing::deleteFile(PATH_CACHE.self::$filename);
		$file = new File(PATH_CACHE.self::$filename);
		$file->write(serialize(self::$data));
		$_SESSION['jphp_cache'] = self::$data;
		//$_SESSION['jphp_cache_times'] = serialize(self::$times_stored);
	}
	
	public static function write($section, $data)
	{
		self::$data[$section] = $data;
	}
	
	public static function read($section)
	{
		return array_key_exists($section, self::$data) ? self::$data[$section] : "";
	}
	// True if cache hit, false if cache miss
	public static function load()
	{
		self::loadTimes();
		if(Session::keyExists("jphp_cache") && Session::string("jphp_cache") != "")
		{	
			//out::message("Cache loaded from session", Message::INFO);
			self::$data = Session::string("jphp_cache");
			return true;
		}
		else if(file_exists(PATH_CACHE.self::$filename))
		{
			//out::message("Cache loaded from file", Message::INFO);
			self::$data = unserialize(file_get_contents(PATH_CACHE.self::$filename));
			return true;
		}
		return false;
	}
	
	
	public static function loadTimes()
	{
		self::$times_file = array();
		
		if(self::$file == null)
		{
			self::$file = new AssociativeFile(PATH.PATH_CACHE.self::$filename_timestamp);
			self::$times_file = self::$file->dump();
		}
		
	/*	if(isset($_SESSION['jphp_cache_times']))
			out::message("Session cache contains " . $_SESSION['jphp_cache_times']);
		self::$times_stored = isset($_SESSION['jphp_cache_times']) ? unserialize("jphp_cache_times") : array();*/
	}
	
	public static function getTimes()
	{
		//return self::$times_file;
		
		$array = array();
		
		foreach(self::$times_file as $k => $v)
			$array[$k] = array('file' => $v, 'session' => array_key_exists($k, self::$times_stored) ? self::$times_stored[$k] : -1);
		return $array;
	}
	public static function refreshNotification($name)
	{
		self::$times_stored[$name] = time();
	}
	public static function notify($name)
	{
		if(self::$file == null)
			self::$file = new AssociativeFile(PATH.PATH_CACHE.self::$filename_timestamp);
		
		//out::message("Notify: " . $name, Message::WARNING);
		self::$times_file[$name] = time();
		self::$file->set($name, self::$times_file[$name]);
		
	}
	
	public static function hasBeenNotified($name)
	{/*
		if(!isset(self::$times_stored[$name]))
			out::message($name." does not exist in times_stored");
		
		if(!isset(self::$times_file[$name]))
			out::message($name." does not exist in times_file");*/
		if(!isset(self::$times_file[$name]) || !isset(self::$times_stored[$name]))
			return false;
		out::message("Comparing " . self::$times_file[$name]. " AND " . self::$times_stored[$name]);
		return self::$times_file[$name] > self::$times_stored[$name];
	}
}