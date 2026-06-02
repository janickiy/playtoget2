<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_events extends Controller
{
	function __construct()
	{
		$this->model = new Model_events();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('events_view.php', $this->model);
	}
}