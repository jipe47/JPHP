<?php

/**
 * Allows the manipulation of dates.
 *
 * @author Jean-Philippe Collette
 * @package Core
 * @subpackage Misc
 */
class Date
{
	
	const SEC_IN_DAY = 86400;
	const SEC_IN_YEAR = 31557600;
	
	/**
	 * Returns a (possibly short) month name based on it's number.
	 * @param int $number Month number.
	 * @param boolean $short If set, returns a short version of the month.
	 */
	
	public static function getMonth($number, $short = false)
	{
		$l = $short ? "M" : "F";
		return date($l, mktime(0, 0, 0, intval($number), 1, 1990));
	}
	public static function getHour($timestamp)
	{
		return date("H", intval($timestamp)). "h".date("i", intval($timestamp));
	}
	
	public static function getDiff($t1, $t2)
	{
		$diff = $t1 > $t2 ? $t1 - $t2 : $t2 - $t1;
		$second = $diff % 60;
		$minute = round(($diff% 3600) / 60);
		$hour = round(($diff % (3600 * 24)) / 3600);
		$day = round(($diff % (3600 * 24 * 365)) / (24 * 3600));
		$year = round($diff / (3600 * 24 * 365));
		return array(	'second' => $second,
						'minute' => $minute,
						'hour' => $hour,
						'day' => $day,
						'year' => $year			
						);
	}
	
	public static function toBeginOfDay($time) // 00h00
	{
		return $time - ($time % (3600 * 24));
	}
	
	public static function toEndOfDay($time) // 23h59
	{
		return self::toBeginOfDay($time) + 24 * 3600 - 1;
	}
	
	public static function toMiddleDay($time) // 12h00
	{
		return self::toBeginOfDay($time) + 12 * 3600;
	}
	
	public static function toBeginOfMonth($time) // 1st of M
	{
		return mktime (date("g", $time), date("i", $time), date("s", $time), date("n", $time), 1, date("Y", $time));
	}
	
	public static function toEndOfMonth($time)
	{
		return mktime (date("g", $time), date("i", $time), date("s", $time), date("n", $time), date("t", $time), date("Y", $time));
	}
	
	/**
	 * Returns holydays between $time_from and $time_to in the format "d/m/yyyy"
	 * @param unknown $time_from
	 * @param unknown $time_to
	 * @return multitype:string
	 */
	public static function getHolidays($time_from, $time_to, $separator = "/")
	{
		if(is_string($time_from))
			$time_from = strtotime($time_from);
		if(is_string($time_to))
			$time_to = strtotime($time_to);

		$year_from = intval(date("Y", $time_from));
		$year_to = intval(date("Y", $time_to));
	
		// Dates found on http://fr.wikipedia.org/wiki/F%C3%AAtes_et_jours_f%C3%A9ri%C3%A9s_en_Belgique
		// Static dates
		$static_dates = array("1/1", "1/5", "21/7", "11/11", "15/8", "1/11", "25/12");
		$array_holidays = array();
	
		for($y = $year_from ; $y <= $year_to ; $y++)
		{
			
			foreach($static_dates as $d)
				$array_holidays[] = str_replace("/", $separator, $d).$separator.$y;
	
			// Dynamic dates
			$easter = easter_date($y) + 12*3600;
				
			$dynamic_dates = array(1, 39, 50); // Nbr of days after easter
			foreach($dynamic_dates as $d)
			{
				$time_index = date("j/n/Y", $easter + $d * 24 * 3600);
				$array_holidays[] = str_replace("/", $separator, $time_index);
			}
		}
		return $array_holidays;
	}
}

?>