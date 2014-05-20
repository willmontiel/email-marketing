<?php

namespace EmailMarketing\General\Misc;

class DateFormat
{
	public function transformDateFormat($date, $oldFormat, $newFormat) {
		$separator = substr($oldFormat, 1, 1);
	
		$f = explode($separator, $oldFormat);
		$d = explode($separator, $date);
		
		$year = $this->getPart($f, $d, 'Y');
		$month = $this->getPart($f, $d, 'm');
		$day = $this->getPart($f, $d, 'd');
		
		return $this->dateFormat($year, $month, $day, $newFormat);
	}
	
	
	protected function dateFormat($year, $month, $day, $format) 
	{
		switch ($format) {
			case 'Y-m-d':
				$newFormat = "{$year}-{$month}-{$day}";
				break;
			
			case 'Y/m/d':
				$newFormat = "{$year}/{$month}/{$day}";
				break;
			
			case 'd-m-Y':
				$newFormat = "{$day}-{$month}-{$year}";
				break;
			
			case 'd/m/Y':
				$newFormat = "{$day}/{$month}/{$year}";
				break;
			
			case 'm-d-Y':
				$newFormat = "{$month}-{$day}-{$year}";
				break;
			
			case 'm/d/Y':
				$newFormat = "{$month}/{$day}/{$year}";
				break;
			
			default:
				$newFormat = "{$year}-{$month}-{$day}";
				break;
		}
		return $newFormat;
	}
	
	protected function getPart($format, $value, $criteria) {
		if ($format[0] == $criteria) {
			$v = $value[0];
		}
		else if ($format[1] == $criteria) {
			$v = $value[1];
		}
		else if ($format[2] == $criteria) {
			$v = $value[2];
		}
		return $v;
	}
}
