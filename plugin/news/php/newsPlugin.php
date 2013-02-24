<?php
class NewsPlugin extends Plugin
{
	static $name = "Articles";
	static $sqlTables = array("news" => "article");
	public function __construct()
	{
		$this->setPluginName("News");
		$this->addSqlTable("news");
		$this->addAdminLink("Add a news", "add");
		$this->addAdminLink("News list", "list");
	}
}