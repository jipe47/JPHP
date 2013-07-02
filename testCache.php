<?php
session_start();
require_once "core/class/cache2.class.php";
require_once "core/class/includercache.class.php";

function myLog($m)
{
	echo $m."<br />";
}

$c = new IncluderCache();