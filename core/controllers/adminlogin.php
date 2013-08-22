<?php
/**
 * Generates subpanels based on admin links (defined by plugins).
 *
 * @author Jean-Philippe Collette
 * @package Controllers
 */
class AdminLogin extends Page
{
	private $members = array(
			//"admin" => "21232f297a57a5a743894a0e4a801fc3" // <=> admin
	);
	
	public function construct()
	{
		$this->setContainer(TPL."html/container.html");
		$this->setTitle("Admininistration Login");
	}
	
	public function prerender()
	{
		if(count($this->member == 0))
		{
			$_SESSION["isAdmin"] = true;
			$this->setLocation("AdminPanel");
			return;
		}
		if(Post::exists("admin_login"))
		{
			$login = Post::string("admin_login");
			$pass = md5(Post::string("admin_password"));
			
			$isAdmin = array_key_exists($login, $this->members) && $this->members[$login] == $pass;
			
			if($isAdmin)
				$this->setLocation("AdminPanel");
			$_SESSION["isAdmin"] = $isAdmin;
		}
		
		
		$this->setTemplate(PATH_TPL_COMMON."html/adminlogin.html");
	}
}