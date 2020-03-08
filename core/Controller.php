<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/22/2018
 * Time: 1:28 PM
 */

namespace Core;

class Controller extends Application
{
	/* @TODO in next refactoring double check are properties $_controller and $_action needed.
	 It seems unused at all.*/

	/**
	 * @var string $_controller
	 */
    protected $_controller;

	/**
	 * @var string $_action
	 */
    protected $_action;

	/**
	 * @var View $view
	 */
    public $view;

    /**
	 * @var Input $request
	 */
    public $request;


	public function __construct($controller, $action)
	{
		parent::__construct();
		$this->_controller = $controller; // It seems unused.
		$this->_action     = $action; // It seems unused.
		$this->request     = new Input();
		$this->view        = new View();
    }

	/**
	 * Loads new instance off model class to $this->{$model.'Model'} property.
	 * @param string $model - model name in PascalCase
	 */
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

	/** Prints the response as json.
	 * @param array $response
	 */
	public function jsonResponse($response)
	{
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		http_response_code(200);
		echo json_encode($response);
		exit;
	}

}