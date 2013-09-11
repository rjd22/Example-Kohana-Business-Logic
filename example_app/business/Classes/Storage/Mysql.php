<?php

class Storage_Mysql
{
	/**
	 * @var Database
	 */
	protected $_database;
	protected $_table_name;
	protected $_entity_class;

	public function __construct($database)
	{
		$this->_database = $database;
	}

	public function load_object(array $parameters)
	{
		$mysql_filters = '';
		foreach ($parameters as $column => $value)
		{
			$mysql_filters .= 'AND '.$column.'='.$value;
		}

		return $this->_database->query('SELECT', "
			SELECT * FROM ".$this->_table_name."
			WHERE 1=1
			".$mysql_filters."
		")->as_object($this->_entity_class)->current();
	}

	public function load_set(array $parameters, $select = NULL)
	{
		$mysql_filters = '';
		foreach ($parameters as $column => $value)
		{
			$mysql_filters .= 'AND '.$column.'='.$value;
		}

		$query = $this->_database->query('SELECT', "
			SELECT * FROM ".$this->_table_name."
			WHERE 1=1
			".$mysql_filters."
		")->as_object($this->_entity_class);

		$results = array();
		foreach ($query as $entity)
		{
			$results[] = $entity;
		}

		return $results;
	}

	public function create($entity, $insert = NULL)
	{
		if ($entity->id)
		{
			throw new Exception('Cannot create a loaded object');
		}

		$reflection = new ReflectionClass($entity);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		$columns = array();
		$values = array();
		foreach ($properties as $p)
		{
			$columns[] = $p->getName();
			$values[] = $entity->{$p->getName()};
		}

		if ($insert === NULL)
		{
			$insert = clone new Database_Query_Builder_Insert;
		}
		$query = $insert->table($this->_table_name)->columns($columns)->values($values)->execute($this->_database);
		$entity->id = $query[0];

		return $entity;
	}

	public function update($entity, $update = NULL)
	{
		if ( ! $entity->id)
		{
			throw new Exception('Cannot update a non-loaded object');
		}

		$reflection = new ReflectionClass($entity);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		$set = array();
		foreach ($properties as $p)
		{
			$set[$p->getName()] = $entity->{$p->getName()};
		}

		if ($update === NULL)
		{
			$update = clone new Database_Query_Builder_Update;
		}
		$updated = $update->table($this->_table_name)->where('id', '=', $entity->id)->set($set)->execute($this->_database);

		return $entity;
	}

	public function delete($entity, $delete = NULL)
	{
		if ( ! $entity->id)
		{
			throw new Exception('Cannot delete a non-loaded object');
		}

		if ($delete === NULL)
		{
			$delete = clone new Database_Query_Builder_Delete;
		}
		return (bool) $delete->table($this->_table_name)->where('id', '=', $entity->id)->execute($this->_database);
	}
}