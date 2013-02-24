<?php
class NewsWidget extends Widget
{
	public function prerender()
	{
		$view = $this->arg->string("view");
		
		switch($view)
		{
			case "popular":
				$array = $this->request->fetchQuery("
				SELECT *
				FROM " . TABLE_NEWS . "
				ORDER BY view DESC LIMIT 5");
				break;
			default:
			case "recent":
			case "last":
				$array = $this->request->fetchQuery("
				SELECT *
				FROM " . TABLE_NEWS . "
				ORDER BY id DESC LIMIT 5");
				break;
		}
		$this->assign("array_news", $array);
		$this->setTemplate(PATH_PLUGIN."news/html/news_widget.html");
	}
}
