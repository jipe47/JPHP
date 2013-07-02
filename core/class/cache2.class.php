<?php
abstract class Cache2
{
	protected $isBuild = false;
	protected $isLoaded = false;
	protected $data = array();
	
	protected $name;
	
	// Update times for all caches, from a cache file
	protected static $updateTimes = array();
	protected static $updateLoaded = false;
	
	public abstract function build();
	
	public function loadCache()
	{
		if($this->isBuild)
			return;
		
		// Load update times (if necessary)
		self::loadUpdateTimes();
		
		// Load data from session if it is defined
		
		$this->build();
		self::$buildTimes[$this->name] = time();
	}
	
	
	public function get($field)
	{
		$this->loadCache();
	}
	
	/****************************/
	/*** Time related methods ***/
	/****************************/
	
	public function getBuildTime()
	{
		
	}
	
	public function setBuildTime()
	{
		
	}
	
	/*******************************************/
	/*** Time related methods for all caches ***/
	/*******************************************/
	
	public static function loadUpdateTimes()
	{
		if(self::$updateLoaded)
			return;
		
		self::$updateLoaded = true;
		
		$cache_file = PATH_CACHE."updatetimes.cache";
		
		if(!file_exists($cache_file))
			return;
		
		self::$updateTimes = unserialize(file_get_contents($cache_file));
	}
	
	public static function saveUpdateTimes()
	{
		file_put_contents(PATH_CACHE."updatetimes.cache", serialize(self::$updateTimes));
	}
	
	public static function loadBuildTimes()
	{
		if(self::$buildLoaded)
			return;
	
		self::$buildLoaded = true;
	
		$cache_file = PATH_CACHE."buildtimes.cache";
	
		if(!file_exists($cache_file))
			return;
	
		self::$buildTimes = unserialize(file_get_contents($cache_file));
	}
	
	public static function saveBuildTimes()
	{
		file_put_contents(PATH_CACHE."buildtimes.cache", serialize(self::$buildTimes));
	}
	
	/*****************/
	/*** Accessors ***/
	/*****************/
	
	public function setName($n)
	{
		$this->name = $n;
	}
	
	public function getName()
	{
		return $this->name;
	}
}