<?php
function smarty_modifier_jphp_translate($string, $quiet="none")
{
	$isQuiet = $quiet == "quiet";
	 if(class_exists("Translator"))
    	return Translator::translate($string, $isQuiet);
    else 
    	return "Undefined Translator";
}

?>