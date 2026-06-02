<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_mailru extends Controller
{
	function __construct()
	{
		$this->model = new Model_mailru();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('mailru_view.php', $this->model);
	}
}