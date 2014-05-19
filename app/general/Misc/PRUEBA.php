<?php

namespace EmailMarketing\General\Misc;

require 'TimePeriod.php';
require 'TotalTimePeriod.php';
require 'DayPeriod.php';
require 'WeekPeriod.php';
require 'HourPeriod.php';

$object = new TotalTimePeriod;
//$data = array(1399663088);

$data = array(1399663088, 1399139892, 1397747792);
sort($data);

$object->setData($data);
//		$object->setPeriodStart($start);
$object->processTimePeriod();

print_data($object);

foreach($data as $d) {
	echo date('d/M H:i', $d) . PHP_EOL;
}

function print_data(TimePeriod $obj, $indent = 0) {
	$ind = str_repeat("\t", $indent);
	echo $ind . 'Nombre: ' . $obj->getPeriodName() . PHP_EOL;
	echo $ind . 'Total: ' . $obj->getTotal() . PHP_EOL;

	foreach ($obj->getTimePeriod() as $child) {
		print_data($child, $indent+1);
	}
}


		