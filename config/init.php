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
require_once PATH."core/class/classlibrary.class.php";

// Triggered when calling an undefined class.
function jphp_autoload($name)
{
	$name = strtolower($name);
	// Look in the controller folder of the core (essentially for error pages);
	$f = PATH."core/controllers/". $name .".php";

	if(file_exists($f))
		require_once $f;
	
	$array_folder = ClassLibrary::getClassFolders();
	
	foreach($array_folder as $folder)
	{
		$file = $folder.$name.".class.php";
		if(!file_exists($file))
			continue;
		require_once $file;
		return;
	}
}

spl_autoload_register("jphp_autoload");

require_once PATH."core/class/chrono.class.php";
$chrono = Singleton::getInstance("Chrono");

JPHP::importIni(PATH."config/config.ini", STRUCTURE_NAME);


$generateCache = !Cache::load() || FORCE_CACHEREBUILD;
//$generateCache = true; // Forcing, the hard way
Includer::setGenerateCaching($generateCache);

// Recursive include of basic classes and functions
$chrono->start("Class inclusion");
Includer::includePath(PATH."core/inc/");
Includer::includePath(PATH."core/class/");
Includer::includePath(PATH."class/");
$chrono->stop("Class inclusion");


// Include plugins
$chrono->start("Plugin inclusion");
Includer::includePlugins("plugin/");
$chrono->stop("Plugin inclusion");

// Controller inclusion
Includer::includePath(PATH."core/controllers/");
Includer::includePath(PATH."controllers/");

// Include external libraries
HtmlHeaders::includeDir("js", "lib/jquery");
HtmlHeaders::includeDir("js", "lib/jquery/plugins");


// Template loading
//$chrono->start("Template inclusion");
Includer::includeTemplate(JPHP::get("template"));
//$chrono->stop("Template inclusion");

//$chrono->start("Common template inclusion");
Includer::includeTemplate("common");
//$chrono->stop("Common template inclusion");

// Favicon
//<link rel="icon" type="image/png" href="favicon.png">
//<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
$favheader = new HtmlHeader(HtmlHeader::LINK);
$favheader->set("rel", "shortcut icon");
$favheader->set("type", "image/x-icon");
$favheader->set("href", TPL."images/favicon.ico");
HtmlHeaders::addHeader($favheader);

$_SESSION['jphp_ids'] = array();

if($generateCache)
	Cache::save();

JPHP::setDebug(false);

require_once "lib/openid/openid.php";
?>