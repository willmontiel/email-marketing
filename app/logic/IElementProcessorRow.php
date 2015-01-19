<?php

interface IElementProcessorRow {
	/**
	 * @param int
	 * @param array
	 */
	public function processline($line, $row);
}