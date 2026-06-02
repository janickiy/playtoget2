<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_news extends Controller
{
	function __construct()
	{
		$this->model = new Model_news();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('news_view.php', $this->model);
	}
}