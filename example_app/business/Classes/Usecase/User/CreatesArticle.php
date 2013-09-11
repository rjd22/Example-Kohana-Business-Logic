<?php

class Usecase_User_CreatesArticle
{
	/**
	 * @var Repository_Article
	 */
	private $_article;

	public function __construct(Repository_Article $article)
	{
		$this->_article = $article;
	}

	public function execute($article_fields)
	{
		$article = new Entity_Article($article_fields);
		return $this->_article->create($article);
	}
}