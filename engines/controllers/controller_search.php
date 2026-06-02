<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_search extends Controller
{
	function __construct()
	{
		$this->model = new Model_search();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('search_view.php', $this->model);
	}
}