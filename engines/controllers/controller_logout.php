<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_logout extends Controller
{
	function __construct()
	{
		$this->model = new Model_logout();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('logout_view.php', $this->model);
	}
}