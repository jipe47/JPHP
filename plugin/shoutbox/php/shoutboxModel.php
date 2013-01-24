<?php
class ShoutboxModel extends Model
{
	public function getLastMessages()
	{
		return $this->request->fetchQuery("	SELECT * FROM " . TABLE_SHOUTBOX . "
											ORDER BY id DESC LIMIT 15");
	}
}