<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_videoalbums extends Controller
{
	function __construct()
	{
		$this->model = new Model_videoalbums();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('videoalbums_view.php', $this->model);
	}
}