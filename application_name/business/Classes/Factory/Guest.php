<?php

class Factory_Guest
{
	public static function views_article($article_id)
	{
		$database 	= Database::instance();
		$article 	= new Storage_Mysql_Article($database);

		$guest_views_article = new Usecase_Guest_ViewsArticle($article);
		return $guest_views_article->execute($article_id);
	}
}