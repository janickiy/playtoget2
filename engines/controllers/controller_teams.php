<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_teams extends Controller
{
	function __construct()
	{
		$this->model = new Model_teams();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('teams_view.php', $this->model);
	}
}