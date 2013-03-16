<?php
class NewsAdmin extends AdminPage
{
	public function construct()
	{
		$this->setAccessName("News");
	}
	
	public function handler()
	{
		$f = $this->arg->string(0);
		switch($f)
		{
			case "category":
				$this->setLocation("Admin/Category/list/news");
				return;
			case "add":
			case "edit":
				
				$title = "";
				$content = "";
				$id_news = -1;
				$submit = "Add";
				$header = "";
				$h_title = "New";
				
				if($f == "edit")
				{
					$id_news = $this->arg->int(1);
					$infos = $this->model->getNews($id_news);
					$title = $infos['title'];
					$content = $infos['content'];
					$header = $infos['header'];
					$submit = "Edit";
					$h_title = "Editing"; 
				}
				
				$this->assignArray(array( 'h_title'=>$h_title, 'title' => $title, 'content' => $content, 'header' => $header, 'id_news' => $id_news, 'submit' => $submit));
				$f = "addedit";
				break;
				
			case "list":
				$this->assign("array_news", $this->model->getAllNews());
				break;
				
			case "delete":
				$info = $this->model->getNews(intval($arg[1]));
				$this->assign("info", $info);
				break;
		}
		
		return $this->renderTemplate(PATH_PLUGIN."news/html/admin/news_".$f.".html");
	}
	
}