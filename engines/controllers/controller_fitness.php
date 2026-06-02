<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_fitness extends Controller
{
	function __construct()
	{
		$this->model = new Model_fitness();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('fitness_view.php', $this->model);
	}
}