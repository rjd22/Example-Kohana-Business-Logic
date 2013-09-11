<?php

class Factory_User
{
	public static function creates_article($article_fields)
	{
		$database 	= Database::instance();
		$article 	= new Storage_Mysql_Article($database);

		$guest_views_article = new Usecase_User_CreatesArticle($article);
		return $guest_views_article->execute($article_fields);
	}
}