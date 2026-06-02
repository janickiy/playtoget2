<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_confirmation extends Controller
{
	function __construct()
	{
		$this->model = new Model_confirmation();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('confirmation_view.php', $this->model);
	}
}