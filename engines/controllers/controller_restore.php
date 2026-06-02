<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_restore extends Controller
{
	function __construct()
	{
		$this->model = new Model_restore();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('restore_view.php', $this->model);
	}
}