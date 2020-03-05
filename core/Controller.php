<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/22/2018
 * Time: 1:28 PM
 */
namespace Core;
use Core\Application;
use App\Models\Contacts;

class Controller extends Application
{
    protected $_controller, $_action;

    public $view;
    public $request;

	public function __construct($controller, $action)
    {
		parent::__construct();
		$this->_controller = $controller;
		$this->_action     = $action;
		$this->request     = new Input();
		$this->view        = new View();
    }


	protected function load_model($model) {
		$modelPath = 'App\Models\\'.$model;
    	if(class_exists($modelPath)){
    		$this->{$model.'Model'} = new $modelPath();
		}
	}

	/**
	 * @abstract
	 */
	public function indexAction(){}

	/**
	 * @param array $response
	 */
	public function jsonResponse($response){
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		http_response_code(200);
		echo json_encode($response);
		exit;
	}

}