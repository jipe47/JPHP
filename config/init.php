<?php
/**
 * This file initialises the framework: it includes every mandatory files, 
 * plugins are imported, the template is loaded and request and template 
 * objets are instantiated.
 * 
 * It first checks if the cache exists, if not it creates it and redirect the
 * user.
 * 
 * @author Jean-Philippe Collette
 */

require_once "const.inc.php";
require_once PATH."lib/smarty/Smarty.class.php";

// Triggered when calling an undefined class.
function jphp_autoload($name)
{
	$f = PATH."core/class/". strtolower($name) .".class.php";

	if(file_exists($f))
		require_once $f;

	$f = PATH."core/controllers/". strtolower($name) .".php";

	if(file_exists($f))
		require_once $f;
}

spl_autoload_register("jphp_autoload");

Cache2::setForceRebuild(FORCE_CACHEREBUILD);
require_once PATH."core/class/chrono.class.php";

JPHP::importIni(PATH."config/config.ini", STRUCTURE_NAME);
JPHP::importJSON(PATH."config/config.json", STRUCTURE_NAME);

// Recursive include of basic classes and functions
Includer::includePath(PATH."core/inc/");
Includer::includePath(PATH."core/class/");

// Include plugins
Includer::includePlugins(PATH_PLUGIN);

// Controller inclusion
Includer::includePath(PATH."core/controllers/");
Includer::includePath(PATH."controllers/");

// Include external libraries
HtmlHeaders::includeDir("js", "lib/jquery");
HtmlHeaders::includeDir("js", "lib/jquery/plugins");


// Template loading
Includer::includeTemplate(JPHP::get("template"));

Includer::includeTemplate("common");

// Favicon
//<link rel="icon" type="image/png" href="favicon.png">
//<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
$favheader = new HtmlHeader(HtmlHeader::LINK);
$favheader->set("rel", "shortcut icon");
$favheader->set("type", "image/x-icon");
$favheader->set("href", TPL."images/favicon.ico");
HtmlHeaders::addHeader($favheader);

$_SESSION['jphp_ids'] = array();

JPHP::setDebug(true);

require_once "lib/openid/openid.php";
?>