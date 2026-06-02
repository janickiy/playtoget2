<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_groups extends Controller
{
	function __construct()
	{
		$this->model = new Model_groups();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('groups_view.php', $this->model);
	}
}