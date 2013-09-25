<?php

require 'example_app/business/Classes/Entity.php';

class DescribeEntity extends \PHPSpec\Context
{
	public function itSetsInformationTroughAnArrayInTheConstruct()
	{
		$entity_data = array(
			'id' 	=> 10,
			'email' => 'test@test.nl',
		);

		$user = new Entity_Test($entity_data);

		$this->spec($user->id)->should->be(10);
		$this->spec($user->email)->should->be('test@test.nl');
	}
}

class Entity_Test extends Entity
{
	public $id;
	public $email;
}