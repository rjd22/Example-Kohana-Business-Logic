<?php

class Factory_QueryBuilder {

	public function select()
	{
		return new Database_Query_Builder_Select;
	}

	public function insert()
	{
		return new Database_Query_Builder_Insert;
	}

	public function update()
	{
		return new Database_Query_Builder_Update;
	}

	public function delete()
	{
		return new Database_Query_Builder_Delete;
	}
}