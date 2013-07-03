<?php
session_start();
unset($_SESSION["cache_Includer"]);

header("Location: testCache.php");
?>