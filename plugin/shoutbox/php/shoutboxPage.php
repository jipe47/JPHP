<?php
class ShoutboxPage extends Page
{
	public function construct()
	{
		$this->setAccessName("Shoutbox");
		$this->setTitle("Shoutbox Example");
	}
	
	public function prerender()
	{
		$this->assign("array_message", $this->model->getLastMessages());
		$this->setTemplate($this->path_html."shoutbox.html");
	}
}