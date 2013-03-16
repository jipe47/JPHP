<?php
class NewsModel extends Model
{
	public function getAllNews()
	{
		return $this->request->fetchQuery("SELECT * FROM " . TABLE_NEWS . " ORDER BY timestamp DESC");
	}
	
	function getNews($id_news)
	{
		return $this->request->firstQuery("
				SELECT n.*, u.nickname as nickname_user 
				FROM " . TABLE_NEWS . " n 
				LEFT JOIN " . TABLE_USER . " u ON u.id = n.id_user
				WHERE n.id='" . intval($id_news)."'");
	}
	
	function getLastNews($page, $nbr_per_page)
	{
		$from = ($page - 1) * $nbr_per_page;
		return $this->request->fetchQuery("SELECT * FROM " . TABLE_NEWS . " ORDER BY timestamp DESC LIMIT ".$from.", ".$nbr_per_page);
	}
	function news_getNbrNews()
	{
		$r = $this->request->firstQuery("SELECT COUNT(*) as count FROM " . TABLE_NEWS);
		return $r['count'];
	}
}