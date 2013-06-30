<?php
class NewsModel extends Model
{
	public function getAllNews()
	{
		return $this->request->fetchQuery("SELECT * FROM " . TABLE_NEWS . " ORDER BY timestamp DESC");
	}
	
	function getNews($id_news)
	{
		if(Plugins::pluginExists("Comments"))
			return $this->request->firstQuery("
					SELECT n.*, u.nickname as nickname_user, cnt.nbr as nbr_comment
					FROM " . TABLE_NEWS . " n
					LEFT JOIN (
						SELECT COUNT(*) as nbr, id_type
						FROM " . TABLE_COMMENTS . " 
						WHERE id_type='".$id_news."' AND type='news') cnt ON cnt.id_type = n.id
					LEFT JOIN " . TABLE_USER . " u ON u.id = n.id_user
					WHERE n.id='" . intval($id_news)."'");
		else
			return $this->request->firstQuery("
				SELECT n.*, u.nickname as nickname_user 
				FROM " . TABLE_NEWS . " n 
				LEFT JOIN " . TABLE_USER . " u ON u.id = n.id_user
				WHERE n.id='" . intval($id_news)."'");
	}
	
	function getLastNews($page, $nbr_per_page)
	{
		$from = ($page - 1) * $nbr_per_page;
		
		if(Plugins::pluginExists("Comments"))
			return $this->request->fetchQuery("
					SELECT n.*, cnt.nbr as nbr_comment
					FROM " . TABLE_NEWS . " n 
					LEFT JOIN (
					SELECT COUNT(*) as nbr, id_type
					FROM " . TABLE_COMMENTS . "
					WHERE type='news'
					GROUP BY id_type) cnt ON cnt.id_type = n.id
					ORDER BY n.timestamp DESC 
					LIMIT ".$from.", ".$nbr_per_page."
					");
		else
			return $this->request->fetchQuery("
					SELECT * 
					FROM " . TABLE_NEWS . " 
					ORDER BY timestamp DESC 
					LIMIT ".$from.", ".$nbr_per_page);
	}
	function getNbrNews()
	{
		$r = $this->request->firstQuery("SELECT COUNT(*) as count FROM " . TABLE_NEWS);
		return $r['count'];
	}
}