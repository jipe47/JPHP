<?php
class ShoutboxPlugin extends Plugin
{
	public function __construct()
	{
		$this->setPluginName("Shoutbox");
		$this->addSqlTable("shoutbox");
	}
}