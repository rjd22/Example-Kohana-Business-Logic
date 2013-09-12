<?php

require 'example_app/business/Classes/Factory/QueryBuilder.php';
require 'example_app/business/Classes/Storage/Database.php';

class DescribeStorageDatabase extends \PHPSpec\Context
{
	public $repo;

	public function before()
	{
		$this->database = Mockery::mock('Database');
		$this->query_builder = Mockery::mock('Factory_QueryBuilder');
		$this->repo = new Mock_Storage_User(
			$this->query_builder,
			$this->database
		);
	}

	public function itLoadsASingleObject()
	{
		$saved_user = new Entity_User(1, 'foo@bar.com');
		$results = Mockery::mock('result');
		$results->shouldReceive('current')->once()->andReturn($saved_user);

		$this->query_builder->shouldReceive('select')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder->shouldReceive('from')
			->once()
			->with('users')
			->andReturn($this->query_builder);
		$this->query_builder->shouldReceive('where')
			->once()
			->with('id', '=', 1);
		$this->query_builder->shouldReceive('as_object')
			->once()
			->with('Entity_User');
		$this->query_builder->shouldReceive('execute')
			->once()
			->with($this->database)
			->andReturn($results);

		$user = $this->repo->load_object(array(array('id', '=', 1)));
		$this->spec($user)->should->be($saved_user);
	}

	public function itReturnsNullWhenNoObjectIsFound()
	{
		$results = Mockery::mock('result');
		$results->shouldReceive('current')->once()->andReturn(NULL);

		$this->query_builder->shouldReceive('select')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder->shouldReceive('from')
			->once()
			->with('users')
			->andReturn($this->query_builder);
		$this->query_builder->shouldReceive('where')
			->once()
			->with('id', '=', 1);
		$this->query_builder->shouldReceive('as_object')
			->once()
			->with('Entity_User');
		$this->query_builder->shouldReceive('execute')
			->once()
			->with($this->database)
			->andReturn($results);

		$user = $this->repo->load_object(array(array('id', '=', 1)), $this->query_builder);
		$this->spec($user)->should->beNull();
	}

	public function itLoadsMultipleObjects()
	{
		$user1 = new Entity_User(1, 'foo@bar.com');
		$user2 = new Entity_User(2, 'foo@bar.com');

		$results = [$user1, $user2];
		$this->query_builder->shouldReceive('select')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder->shouldReceive('from')
			->once()->with('users');
		$this->query_builder->shouldReceive('as_object')
			->once()->with('Entity_User');
		$this->query_builder->shouldReceive('where')
			->once()->with('id', '=', 1);
		$this->query_builder->shouldReceive('where')
			->once()->with('id', '=', 2);
		$this->query_builder->shouldReceive('execute')
			->once()->with($this->database)
			->andReturn($results);



		$users = $this->repo->load_set(
			array(
				array('id', '=', 1),
				array('id', '=', 2),
			),
			$this->query_builder
		);

		$this->spec($users)->should->be(array($user1, $user2));
	}

	public function itCreatesARecordFromAnUnloadedObject()
	{
		$this->query_builder
			->shouldReceive('insert')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('table')
			->once()
			->with('users')
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('columns')
			->once()
			->with(array('id', 'email'))
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('values')
			->once()
			->with(array(NULL, 'foo@bar.com'))
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('execute')
			->once()
			->with($this->database)
			->andReturn(array(1, 1));

		$user = new Entity_User(NULL, 'foo@bar.com');
		$new_user = $this->repo->create($user, $this->query_builder);
		$this->spec($new_user->id)->should->be(1);
	}

	public function itRaisesAnExceptionWhenInsertingObjectWithId()
	{
		$user = new Entity_User(1, 'foo@bar.com');
		$repo = $this->repo;
		$this->spec(
			function() use($user, $repo)
			{
				$repo->create($user)->should->throwException('Exception');
			}
		);
	}

	public function itUpdatesARecordFromALoadedObject()
	{
		$this->query_builder
			->shouldReceive('update')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('table')
			->once()
			->with('users')
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('set')
			->once()
			->with(array('id' => 1, 'email' => 'foo@bar.com'))
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('where')
			->once()
			->with('id', '=', 1)
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('execute')
			->once()
			->with($this->database)
			->andReturn(1);

		$user = new Entity_User(1, 'foo@bar.com');
		$new_user = $this->repo->update($user, $this->query_builder);
	}

	public function itRaisesAnExceptionWhenUpdatingObjectWithNoId()
	{
		$user = new Entity_User(NULL, 'foo@bar.com');
		$repo = $this->repo;
		$this->spec(
			function() use($user, $repo)
			{
				$repo->update($user);
			}
		)->should->throwException('Exception');
	}

	public function itDeletesALoadedRecord()
	{
		$user = new Entity_User(1, 'foo@bar.com');
		$this->query_builder
			->shouldReceive('delete')
			->once()
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('table')
			->once()
			->with('users')
			->andReturn($this->query_builder);
		$this->query_builder
			->shouldReceive('where')
			->once()
			->with('id', '=', 1)
			->andreturn($this->query_builder);
		$this->query_builder
			->shouldReceive('execute')
			->once()
			->with($this->database)
			->andReturn(1);

		$this->spec($this->repo->delete($user, $this->query_builder))->should->be(TRUE);
	}

	public function itRaisesAnExceptionWhenDeletingObjectWithNoId()
	{
		$user = new Entity_User(NULL, 'foo@bar.com');
		$repo = $this->repo;
		$this->spec(
			function() use($user, $repo)
			{
				$repo->delete($user)->should->throwException('Exception');
			}
		);
	}
}

class Entity_User {
	public $id;
	public $email;

	public function __construct($id, $email)
	{
		$this->id = $id;
		$this->email = $email;
	}
}

class Mock_Storage_User extends Storage_Database {

	protected $_table_name = 'users';
	protected $_entity_class = 'Entity_User';
}