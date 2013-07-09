<?php
abstract class AssociativeCache extends Cache2
{	
	public function build()
	{
		
	}
	
	public function get()
	{
		$arg = func_get_args();
		$argc = func_num_args();
		$this->loadCache($arg);
		
		if(func_num_args() == 0)
			return $this->data;
				
		// Traverse the array and retreive the desired value
		$c = $this->data;
		$alreadyMiss = false;
		
		for($i = 0 ; $i < $argc ; $i++)
		{
			$a = $arg[$i];
			if(!array_key_exists($a, $c))
			{
				if($alreadyMiss)
				{
					throw new Exception("Cache miss failed");
					return null;
				}
				
				call_user_func_array(array($this, "onMiss"), $arg);
				$i = -1;
				$c = $this->data;
				$alreadyMiss = true;
				continue;
			}
			$c = $c[$a];
		}
		
		return $c;
	}
	
	abstract public function onMiss();
}