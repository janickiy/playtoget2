<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_profile extends Controller
{
	function __construct()
	{
		$this->model = new Model_profile();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('profile_view.php', $this->model);
	}
}