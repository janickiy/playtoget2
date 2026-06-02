<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_content extends Controller
{
	function __construct()
	{
		$this->model = new Model_content();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('content_view.php', $this->model);
	}
}