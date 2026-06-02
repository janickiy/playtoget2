<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_photoalbums extends Controller
{
	function __construct()
	{
		$this->model = new Model_photoalbums();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('photoalbums_view.php', $this->model);
	}
}