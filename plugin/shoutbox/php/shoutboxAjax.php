<?php
class ShoutboxAjax extends AjaxPage
{
	public function construct()
	{
		$this->setAccessName("Shoutbox");
	}
	
	public function handler()
	{
		switch($this->arg->string(0))
		{
			case "send":
				$nickname = Post::string("nickname");
				$message = Post::string("message");
				$this->request->insert(TABLE_SHOUTBOX, array("nickname" => $nickname, "message" => $message));
				return "1";
				
			case "refresh":
				$this->assign("array_message", $this->model->getLastMessages());
				$this->setTemplate($this->path_html."widgetAjaxMessages.html");
				return $this->renderTemplate(); 
		}
		
	}
}