<?php

class Factory_Guest
{
	public static function views_article()
	{
		$query_builder 	= new Factory_QueryBuilder;
		$database 		= Database::instance();
		$article 		= new Storage_Database_Article($query_builder, $database);

		return new Usecase_Guest_ViewsArticle($article);
	}
}