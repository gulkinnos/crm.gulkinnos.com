<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:57 PM
 */

class Router
{
	public static function route($url)
	{

		//controller
		$controller = DEFAULT_CONTROLLER;
		if (isset($url[0]) && $url[0] != '') {
			$controller = ucwords($url[0]);
			array_shift($url);
		}
		$controller_name = $controller;

		//action
		$action      = 'indexAction';
		$action_name = 'index';
		if (isset($url[0]) && $url[0] != '') {
			$action      = ucwords($url[0] . 'Action');
			$action_name = strtolower(array_shift($url));
		}


		//acl check
		$grantAccess = self::hasAccess($controller_name, $action_name);

		if (!$grantAccess) {
			$controller_name = $controller = ACCESS_RESTRICTED;
			$action          = 'indexAction';
		}

		//params
		$queryParams = $url;

		if (class_exists($controller)) {
			$dispatch = new $controller($controller_name, $action);
		}
		else {
			die(var_dump('Class "' . $controller . '" does not exist'));
		}


		if (method_exists($controller, $action)) {
			call_user_func_array([$dispatch, $action], $queryParams);
		}
		else {
			die(var_dump('That method does not exist in the controller \"' . $controller_name . '\"'));
		}
	}

	public static function redirect($location)
	{
		if (!headers_sent()) {
			header('Location:' . PROOT . $location);
			exit();
		}
		else {
			echo '
				<script type="text/javascript">
				window.location.href="' . PROOT . $location . '"
				</script>';
			echo '<noscript><meta http-equiv="refresh" content="0;url=' . $location . '" </noscript>';
			exit();
		}
	}

	private static function hasAccess(string $controller_name, string $action_name = 'index')
	{
		$acl_content = file_get_contents(ROOT . DS . 'app' . DS . 'acl.json');
		if ($acl_content) {
			$acl = json_decode($acl_content, true);
		}
		else {
			return false;
		}
		$currentUserACLs = ["Guest"];
		$grantAccess     = false;

		if (Session::exists(CURRENT_USER_SESSION_NAME)) {
			$currentUserACLs[] = "LoggedIn";
			if (!empty($savedACLs = Helpers::currentUser()->acls())) {
				foreach ($savedACLs as $key => $value) {
					$currentUserACLs[] = $value;
				}
			}
		}

		foreach ($currentUserACLs as $level) {

			if (isset($acl[$level][$controller_name])) {
				if (in_array($action_name, $acl[$level][$controller_name]) || in_array("*",
						$acl[$level][$controller_name])) {
					$grantAccess = true;
					break;
				}
			}
		}

		foreach ($currentUserACLs as $level) {
			$denied = isset($acl[$level]['denied']) ? $acl[$level]['denied'] : false;
			if (!empty($denied) && array_key_exists($controller_name, $denied) && in_array($action_name,
					$denied[$controller_name])) {
				$grantAccess = false;
				break;
			}
		}

		return $grantAccess;
	}

	public static function getMenu(string $menu)
	{
		$menuArray = [];
		$menuFile  = file_get_contents(ROOT . DS . 'app' . DS . $menu . '.json');
		if (!$menuFile) {
			return $menuArray;
		}
		else {

			$acl = json_decode($menuFile, true);

			//@TODO: Rewrite to recursion.
			foreach ($acl as $key => $value) {
				if (is_array($value)) {
					$sub = [];
					foreach ($value as $k => $val) {
						if ($k == 'separator' && !empty($sub)) {
							$sub[$k] = '';
							continue;
						}
						else {
							if ($finalVal = self::getLink($val)) {
								$sub[$k] = $finalVal;
							}
						}
					}
					if (!empty($sub)) {
						$menuArray[$key] = $sub;
					}
				}
				elseif ($finalVal = self::getLink($value)) {
					$menuArray[$key] = $finalVal;
				}
			}
		}

		Helpers::vd($menuArray);

		return $menuArray;

	}

	private static function getLink($value)
	{


		if(!empty($value)){
			if(preg_match('/https?:\/\//',$value) == 1){
				return $value;
			}else{
				$uArray = explode(DS, $value);
				$controllerName = ucwords($uArray[0]);
				$actionName = (isset($uArray[1])? $uArray[1]: '');
				if (self::hasAccess($controllerName, $actionName)){
					return PROOT.$value;
				}
			}
		}else{
			return '';
		}
	}
}