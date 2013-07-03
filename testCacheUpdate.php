<?php
require_once "core/class/cache2.class.php";
require_once "core/class/includercache.class.php";

define("PATH_CACHE", "cache/");

file_put_contents("pouet.txt", intval(file_get_contents("pouet.txt") + 1));

IncluderCache::update("Includer");


?>