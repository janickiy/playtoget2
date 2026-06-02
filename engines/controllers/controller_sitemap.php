<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_sitemap extends Controller
{
	function __construct()
	{
		$this->model = new Model_sitemap();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('sitemap_view.php', $this->model);
	}
}