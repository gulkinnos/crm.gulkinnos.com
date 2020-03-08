<?php

namespace App\Controllers;

use Core\Controller;
use Core\Cookie;
use Core\Helpers;

class ExamplesController extends Controller
{
	public function __construct($controller, $action)
	{
		parent::__construct($controller, $action);
	}

	public function indexAction()
	{
		$this->view->render('examples/index');
	}

	public function testAjaxAction()
	{
		$response = [
			'success' => true,
			'data'    => [
				'id'            => 27,
				'name'          => 'Aleks',
				'favurite_food' => 'meat'
			]
		];
		$this->jsonResponse($response);
	}

}