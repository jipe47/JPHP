<?php
class IncluderCache extends Cache2
{
	public function __construct()
	{
		$this->setName("Includer");
	}
	
	public function build()
	{
		$this->data["count"] = intval(file_get_contents("pouet.txt"));
	}
}