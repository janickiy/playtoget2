<?php
defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_registration extends Controller
{
	function __construct()
	{
		$this->model = new Model_registration();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('registration_view.php', $this->model);
	}
}