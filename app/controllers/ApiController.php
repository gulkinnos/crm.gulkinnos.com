<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 2/18/20
 * Time: 10:07 AM
 */
namespace App\Controllers;
use Core\Controller;

class ApiController extends Controller
{
	public function __construct($controller, $action)
	{
		parent::__construct($controller, $action);
	}

	public function indexAction()
	{
		$foo = 'bar';
		$this->view->render('api/index');
	}

	public function readProductsAction()
	{
		echo json_encode([1 => 'foo']);
	}
}