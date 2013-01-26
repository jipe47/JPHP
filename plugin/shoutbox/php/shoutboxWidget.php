<?php
class ShoutboxWidget extends Widget
{
	public function prerender()
	{
		$this->setTemplate($this->path_html."widget.html");
		$this->assign("array_message", $this->model->getLastMessages());
	}
}