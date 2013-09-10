<?php

class Repository_Database
{
	/**
	 * @var Database
	 */
	protected $_database;

	public function __construct($database)
	{
		$this->_database = $database;
	}
}