<?php
class NewsList extends Page
{
	public function prerender()
	{
		$array_arg = array();
		$back = "Articles";
		foreach($this->arg->getArray() as $a)
			$array_arg[$a[0]] = substr($a, 1);
		
		// Retreive date
		$date = array_key_exists("d", $array_arg) ? $array_arg["d"] : "";
		
		if($date != "")
			$back .= "/d".$date;
		
		// Retreive page
		$page = array_key_exists("p", $array_arg) ? intval($array_arg["p"]) : 1;
		$nbr_per_page = 2;
		$nbr_page = ceil(news_getNbrNews() / $nbr_per_page);
		
		$array_news = $this->model->getLastNews($page, $nbr_per_page, $date);
		
		$this->assign("array_news", $array_news);
		$this->assign("page", $page);
		$this->assign("nbr_page", $nbr_page);
		$this->assign("back", $back);
		
		$this->setTitle("Articles");
		
		$this->setTemplate(PATH_PLUGIN."news/html/allnews.html");
	}
}