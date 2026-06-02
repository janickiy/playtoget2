<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_authentication extends Controller
{
	function __construct()
	{
		$this->model = new Model_authentication();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('authentication_view.php', $this->model);
	}
}