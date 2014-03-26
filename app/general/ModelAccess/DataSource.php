<?php

namespace EmailMarketing\General\ModelAccess;

interface DataSource
{
	public function getName();
	public function getRows();
	public function getCurrentPage();
	public function getRowsPerPage();
	public function getTotalPages();
	public function getTotalRecords();
}