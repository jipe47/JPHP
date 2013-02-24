<?php
class NewsRequest extends RequestPage
{
	public function construct()
	{
		$this->setAccessName("News");
	}
	
	public function handler()
	{
		$back = "Admin/News/list";
		switch($this->arg->string(0))
		{
			case "addedit":
				$id_news = Post::int("id_news");
				$title = Post::string("title");
				$content = Post::string("content");
				$header = Post::string("header");
				$id_author = User::getId();
				
				$array = array('title' => $title, 'content' => $content, 'header' => $header, 'id_user' => $id_author);
				
				if($id_news == -1)
					$this->request->insert(TABLE_NEWS, $array);
				else
					$this->request->update(TABLE_NEWS, "id='" . $id_news . "'", $array);
				
				$m = $id_news == -1 ? "News added" : "News edited";
				out::message($m, Message::SUCCESS);
				break;
				
			case "delete":
				$id_news = intval($arg[1]);
				$this->request->query("DELETE FROM " . TABLE_NEWS . " WHERE id='" . $id_news . "'");
				out::message("Article deleted", Message::SUCCESS);
				break;
		}
		
		$this->setLocation($back);
	}
}