<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_ok extends Controller
{
	function __construct()
	{
		$this->model = new Model_ok();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('ok_view.php', $this->model);
	}
}