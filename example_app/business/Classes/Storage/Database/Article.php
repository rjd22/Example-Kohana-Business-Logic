<?php

class Storage_Database_Article extends Storage_Database implements Repository_Article
{
	public function get_by_id($article_id)
	{
		$this->load_object(array(array('id', '=', $article_id)));
	}
}