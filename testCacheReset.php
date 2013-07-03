<?php
session_start();
unset($_SESSION["cache_Includer"]);
if(file_exists("cache/cache_Includer.cache"))
	file_put_contents("cache/cache_Includer.cache", "");
header("Location: testCache.php");
?>