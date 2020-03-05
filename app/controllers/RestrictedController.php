<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 2/24/20
 * Time: 10:17 AM
 */
namespace App\Controllers;
use Core\Controller;

class RestrictedController extends Controller
{
	public function __construct($controller, $action)
	{
		parent::__construct($controller, $action);
	}

	public function indexAction()
	{
		$this->view->render('restricted/index');
	}

	public function badTokenAction(){
		$this->view->render('restricted/badToken');
	}
}