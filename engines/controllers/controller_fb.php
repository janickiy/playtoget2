<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_fb extends Controller
{
	function __construct()
	{
		$this->model = new Model_fb();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('fb_view.php', $this->model);
	}
}