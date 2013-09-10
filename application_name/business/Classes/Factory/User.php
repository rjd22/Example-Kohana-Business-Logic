<?php

class Factory_User
{
	public static function creates_article($article_fields)
	{
		$article = new Repository_Database_Article;

		$guest_views_article = new Usecase_User_CreatesArticle($article);
		return $guest_views_article->execute($article_fields);
	}
}