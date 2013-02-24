<?php
class News extends Page
{
	public function prerender()
	{
		$id_news = $this->arg->int(0);
		$news = $this->model->getNews($id_news);
		
		$this->request->query("UPDATE " . TABLE_NEWS . " SET view = view + 1 WHERE id='" . $id_news . "'");
		$this->assign("news", $news);
		$this->setTitle($news["title"]);
		$this->setTemplate(PATH_PLUGIN."news/html/news_view.html");
	}
}