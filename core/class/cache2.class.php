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
		echo "loadCache()<br />";
		// Load update if necessary)
		self::loadUpdateTimes();
		
		// Load data from session if it is defined
		if(!empty($_SESSION["cache_".$this->name]))
		{
			echo "loadCache - load from session<br />";
			$ser = unserialize($_SESSION["cache_".$this->name]);
			self::$buildTimes[$this->name] = $ser['buildTime'];
			$this->data = $ser['data'];
			$this->isBuild = true;
		}
		else if(file_exists(PATH_CACHE."cache_".$this->name.".cache")) // Disponible dans fichier cache
		{
			echo "loadCache - load from file<br />";
			$ser = unserialize(file_get_contents(PATH_CACHE."cache_".$this->name.".cache"));
			self::$buildTimes[$this->name] = $ser['buildTime'];
			$this->data = $ser['data'];
			$this->isBuild = true;
			$this->saveCacheInSession();
		}
		
		// Else build the cache
		if(!$this->isBuild || $this->getBuildTime() < $this->getUpdateTime())
		{
			echo "Building the cache ; isBuild = " . $this->isBuild . ", buildtime = " . $this->getBuildTime() . ", updatetime = " . $this->getUpdateTime() . "<br />";
			$this->build();
			$this->setBuildTime();
			$this->saveCache();
		}
	}
	
	
	public function get($field)
	{
		$this->loadCache();
		if($field == "")
			return $this->data;
		else
			return array_key_exists($field, $this->data) ? $this->data[$field] : null;
	}
	
	public function saveCacheInSession()
	{
		$d = serialize(array(	"buildTime" => self::$buildTimes[$this->name],
								"data" => $this->data));
		$_SESSION["cache_".$this->name] = $d;
	}
	
	public function saveCache()
	{
		echo "saveCache()<br />";
		$d = serialize(array(	"buildTime" => self::$buildTimes[$this->name],
								"data" => $this->data));
		$_SESSION["cache_".$this->name] = $d;
		file_put_contents(PATH_CACHE."cache_".$this->name.".cache", $d);
	}
	
	/*********************************/
	/*** Buildtime related methods ***/
	/*********************************/
	
	public function getBuildTime()
	{
		return array_key_exists($this->name, self::$buildTimes) ? self::$buildTimes[$this->name] : -1;
	}
	
	public function setBuildTime()
	{
		self::$buildTimes[$this->name] = time();
	}
	
	public function getUpdateTime()
	{
		if(array_key_exists($this->name, self::$updateTimes))
			return self::$updateTimes[$this->name];
		else
			return -1;
	}
	
	/*************************************************/
	/*** Updatetime related methods for all caches ***/
	/*************************************************/
	
	public static function loadUpdateTimes()
	{
		if(self::$updateLoaded)
			return;
		
		self::$updateLoaded = true;
		self::$updateTimes = array();
		$cache_file = PATH_CACHE."updatetimes.cache";
		
		if(!file_exists($cache_file))
			return;
		
		self::$updateTimes = unserialize(file_get_contents($cache_file));
	}
	
	public static function saveUpdateTimes()
	{
		file_put_contents(PATH_CACHE."updatetimes.cache", serialize(self::$updateTimes));
	}
	
	public static function update($cacheName)
	{
		self::loadUpdateTimes();
		self::$updateTimes[$cacheName] = time();
		self::saveUpdateTimes();
	}
	
	/*****************/
	/*** Accessors ***/
	/*****************/
	
	public function setName($n)
	{
		$this->name = md5($n);
	}
	
	public function getName()
	{
		return $this->name;
	}
}