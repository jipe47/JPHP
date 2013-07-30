<?php
class PluginElement extends Object
{
	protected $plugin = null;
	public function __construct()
	{
		parent::__construct();
	}
	
	public function setPlugin($p)
	{
		$this->plugin = $p;
	}
}