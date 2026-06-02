<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_calendar extends Controller
{
	function __construct()
	{
		$this->model = new Model_calendar();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('calendar_view.php', $this->model);
	}
}