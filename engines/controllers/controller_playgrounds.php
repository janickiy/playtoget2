<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_playgrounds extends Controller
{
	function __construct()
	{
		$this->model = new Model_playgrounds();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('playgrounds_view.php', $this->model);
	}
}