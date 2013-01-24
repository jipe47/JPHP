<?php
class ShoutboxPage extends Page
{
	public function construct()
	{
		$this->setAccessName("Shoutbox");
		$this->setTitle("Shoutbox Example");
		$this->setTemplate($this->path_html."shoutbox.html");
	}
	
	public function prerender()
	{
		$this->assign("array_message", $this->model->getLastMessages());
	}
}