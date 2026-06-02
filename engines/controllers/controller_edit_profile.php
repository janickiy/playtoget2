<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_edit_profile extends Controller
{
	function __construct()
	{
		$this->model = new Model_edit_profile();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('edit_profile_view.php', $this->model);
	}
}