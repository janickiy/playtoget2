<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_ajax_action extends Controller
{
	function __construct()
	{
		$this->model = new Model_ajax_action();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('ajax_action_view.php', $this->model);
	}
}