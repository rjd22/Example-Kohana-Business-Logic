<?php
interface Repository_Article {

	public function get_by_id($article_id);
	public function create($entity);
	public function update($entity);
	public function delete($entity);
}