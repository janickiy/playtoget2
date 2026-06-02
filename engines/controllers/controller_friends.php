<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_friends extends Controller
{
	function __construct()
	{
		$this->model = new Model_friends();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('friends_view.php', $this->model);
	}
}