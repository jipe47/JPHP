<?php
function smarty_modifier_jphp_toPng($string)
{
	$n = explode(".", $string);
	array_pop($n);
	return implode(".", $n).".png";
}

?>