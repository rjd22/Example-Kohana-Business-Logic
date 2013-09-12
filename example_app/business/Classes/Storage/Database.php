<?php

class Storage_Database {

	protected $_table_name;
	protected $_entity_class;

	protected $_query_factory;
	protected $_database;

	public function __construct(Factory_QueryBuilder $query_builder, $database)
	{
		$this->_query_builder = $query_builder;
		$this->_database = $database;
	}

	public function load_object(array $parameters)
	{
		$select = $this->_query_builder->select();

		$select->from($this->_table_name);
		$select->as_object($this->_entity_class);

		$this->where($select, $parameters);

		return $select->execute($this->_database)->current();
	}

	public function load_set(array $parameters)
	{
		$select = $this->_query_builder->select();

		$select->from($this->_table_name);
		$select->as_object($this->_entity_class);

		$this->where($select, $parameters);

		$results = [];
		foreach ($select->execute($this->_database) as $entity)
		{
			$results[] = $entity;
		}

		return $results;
	}

	public function create($entity)
	{
		if ($entity->id)
		{
			throw new Exception('Cannot create a loaded object');
		}

		$insert 	= $this->_query_builder->insert();
		$properties = $this->get_reflection($entity);

		$columns = array();
		$values = array();
		foreach ($properties as $p)
		{
			$columns[] = $p->getName();
			$values[] = $entity->{$p->getName()};
		}

		$query = $insert->table($this->_table_name)->columns($columns)->values($values)->execute($this->_database);
		$entity->id = $query[0];

		return $entity;
	}

	public function update($entity)
	{
		if ( ! $entity->id)
		{
			throw new Exception('Cannot update a non-loaded object');
		}

		$update 	= $this->_query_builder->update();
		$properties = $this->get_reflection($entity);

		$set = array();
		foreach ($properties as $p)
		{
			$set[$p->getName()] = $entity->{$p->getName()};
		}

		$updated = $update->table($this->_table_name)->where('id', '=', $entity->id)->set($set)->execute($this->_database);

		return $entity;
	}

	public function delete($entity)
	{
		if ( ! $entity->id)
		{
			throw new Exception('Cannot delete a non-loaded object');
		}

		$delete = $this->_query_builder->delete();

		return (bool) $delete->table($this->_table_name)->where('id', '=', $entity->id)->execute($this->_database);
	}

	protected function where($query, $wheres)
	{
		foreach ($wheres as $where)
		{
			$query->where($where[0], $where[1], $where[2]);
		}
	}

	protected function get_reflection($entity)
	{
		$reflection = new ReflectionClass($entity);
		return $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
	}
}