<?php

defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Controller_yandex extends Controller
{
	function __construct()
	{
		$this->model = new Model_yandex();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('yandex_view.php', $this->model);
	}
}