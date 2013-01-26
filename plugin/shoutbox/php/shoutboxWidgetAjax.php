<?php
class ShoutboxWidgetAjax extends Widget
{
	public function construct()
	{
		$this->setAccessName("ajax");
	}
	public function prerender()
	{
		$this->setTemplate($this->path_html."widgetAjax.html");
	}
}