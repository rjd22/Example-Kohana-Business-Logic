<?php

class Factory_QueryBuilder {

	public function new_select()
	{
		return new Database_Query_Builder_Select;
	}

	public function new_insert()
	{
		return new Database_Query_Builder_Insert;
	}

	public function new_update()
	{
		return new Database_Query_Builder_Update;
	}

	public function new_delete()
	{
		return new Database_Query_Builder_Delete;
	}
}