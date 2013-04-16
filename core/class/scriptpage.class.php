<?php
abstract class ScriptPage extends Page
{
	protected $scriptName = "";
	private $displayInMenu = true;
	private $silent = false;
	private $silentMessage = "";
	private $fromAdminPanel = false;
	
	protected $script_arg = array();
	
	public function __construct()
	{
		if(func_num_args() == 0)
			parent::__construct();
		else
			call_user_func_array("parent::__construct", func_get_args());
		$this->setFullRender(false);
		$this->showHeaders(false);
		$this->setPageType("script");
	}
	public function prerender(){
		$this->fromAdminPanel = Post::boolValue("jphp_fromadminpanel", "1", false);
	}
	public function selfrender()
	{
		$e = $this->exec();
		if($this->fromAdminPanel)
			return $this->silent ? $this->silentMessage : $e;
		else
			return $e;
	}
	public function getScriptName()
	{
		return $this->scriptName;
	}
	public function setScriptName($n)
	{
		$this->scriptName = $n;
	}
	
	public function addScriptArg($field, $friendlyname, $default = "")
	{
		$this->script_arg[] = array('friendlyname' => $friendlyname, 'fieldname' => $field, 'default' => $default);
	}
	
	public function getScriptArg()
	{
		return $this->script_arg;
	}
	
	public function setDisplayInMenu($b)
	{
		$this->displayInMenu = $b;
	}
	
	public function getDisplayInMenu()
	{
		return $this->displayInMenu;
	}
	
	public function setSilent($b)
	{
		$this->silent = $b;
	}
	
	public function getSilent()
	{
		return $this->silent;
	}
	
	public function setSilentMessage($s)
	{
		$this->silentMessage = $s;
	}
	
	public function getSilentMessage()
	{
		return $this->silentMessage;
	}
	
	public abstract function exec();
}
