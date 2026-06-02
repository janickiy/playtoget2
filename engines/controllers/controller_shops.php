<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_shops extends Controller
{
	function __construct()
	{
		$this->model = new Model_shops();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('shops_view.php', $this->model);
	}
}