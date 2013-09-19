<?php

class Factory_User
{
	public static function creates_article()
	{
		$query_builder 	= new Factory_QueryBuilder;
		$database 		= Database::instance();
		$article 		= new Storage_Database_Article($query_builder, $database);

		return new Usecase_User_CreatesArticle($article);
	}
}