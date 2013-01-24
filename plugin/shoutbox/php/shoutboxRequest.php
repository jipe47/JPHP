<?php
class ShoutboxRequest extends RequestPage
{
	public function construct()
	{
		$this->setAccessName("Shoutbox");
	}
	public function handler()
	{
		switch($this->arg->string(0))
		{
			case "insert":
				$nickname = Post::string("nickname");
				$message = Post::string("message");
				$this->request->insert(TABLE_SHOUTBOX, 
						array("nickname" => $nickname, "message" => $message));
				$this->setLocation("Shoutbox");
				break;
		}
	}
}