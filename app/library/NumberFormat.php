<?php

class NumberFormat
{
	public static function numberFormat($argument)
	{
		return number_format($argument, 0, ',', '.');
	}
}