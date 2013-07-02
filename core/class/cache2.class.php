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
	
	// Build times for all caches, from a session
	protected static $buildTimes = array();
	protected static $buildLoaded = false;
	
	public abstract function build();
	
	public function loadCache()
	{
		// Load update and build times (if necessary)
		self::loadUpdateTimes();
		self::loadBuildTimes();
		
		// Load data from session if it is defined
		if(!empty($_SESSION["cache_".$this->name]))
		{
			$ser = unserialize($_SESSION["cache_".$this->name]);
			self::$buildTimes[$this->name] = $ser['buildTime'];
			$this->data = $ser['data'];
			$this->isBuild = true;
		}
		else if(file_exists(PATH_CACHE."cache_".$this->name.".cache")) // Disponible dans fichier cache
		{
			$ser = unserialize(file_get_contents(PATH_CACHE."cache_".$this->name.".cache"));
			self::$buildTimes[$this->name] = $ser['buildTime'];
			$this->data = $ser['data'];
		}
		
		// Else build the cache
		if(!$this->isBuild || self::$buildTimes[$this->name] < self::$updateTimes[$this->name])
		{
			$this->build();
			self::$buildTimes[$this->name] = time();
			$this->saveCache();
		}
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
		
	}
	
	public function saveCache()
	{
		$d = serialize(array(	"buildtime" => self::$buildTimes[$this->name],
								"data" => $this->data));
		$_SESSION["cache_".$this->name] = $d;
		file_put_contents(PATH_CACHE."cache_".$this->name.".cache", $d);
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