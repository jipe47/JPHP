<?php
session_start();
require_once "core/class/cache2.class.php";
require_once "core/class/includercache.class.php";

define("PATH_CACHE", "cache/");

function myLog($m)
{
	echo $m."<br />";
}

$c = new IncluderCache();

$cnt = $c->get("count");
echo "cnt = $cnt<br />";
?>

<br />
<a href="testCacheReset.php">Reset</a> - 
<a href="testCacheKillSession.php">Kill session</a> 
<hr />
Sessions :
<pre>
<?php print_r($_SESSION); ?>
</pre>

<br />Content of temp file:
<pre>
<?php
echo file_get_contents(PATH_CACHE."cache_Includer.cache");
?>
</pre>

<br />Content of update times
<pre>
<?php
echo file_get_contents(PATH_CACHE."cache_Includer.cache");
?>
</pre>