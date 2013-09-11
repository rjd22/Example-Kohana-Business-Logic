<?php

class Usecase_Guest_ViewsArticle
{
	/**
	 * @var Repository_Article
	 */
	private $_article;

	public function __construct(Repository_Article $article)
	{
		$this->_article = $article;
	}

	public function execute($article_id)
	{
		return $this->_article->get_by_id($article_id);
	}
}