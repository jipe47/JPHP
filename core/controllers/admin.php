<?php
/**
 * Generates subpanels based on admin links (defined by plugins).
 *
 * @author Jean-Philippe Collette
 * @package Controllers
 */
class AdminPanel extends Page
{
	
	private $nbr_col = 3;
	
	public function construct()
	{
		$this->setContainer(TPL."html/container.html");
		$this->setTitle("Admin Menu");
	}
	
	public function prerender()
	{
		$array_plugin = Plugins::getAdminInfos();;
		
		$this->assign("nbr_plugin", count($array_plugin));
		$this->assign("nbr_col", $this->nbr_col);
		$this->assign('array_plugin', $array_plugin);
		
		$this->setTemplate(PATH_TPL_COMMON."html/admin.html");
		JPHP::addOnloadFunction("adminInitScript()");
	}
}