<?php

class Factory_Guest
{
	public static function views_article($article_id)
	{
		$article = new Repository_Database_Article;

		$guest_views_article = new Usecase_Guest_ViewsArticle($article);
		return $guest_views_article->execute($article_id);
	}
}