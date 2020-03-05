<?php
namespace Core;

class Session
{
	public static function exists($name) {
		return isset($_SESSION[$name]);
	}

	public static function get($name) {
		return (self::exists($name) ? $_SESSION[$name] : null);
	}

	public static function set($name, $value) {
		return $_SESSION[$name] = $value;
	}

	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function uagent_no_version() {
		$regx = '/\/[a-zA-Z0-9.]+/';
		return preg_replace($regx, '', $_SERVER['HTTP_USER_AGENT']);
	}

	/**
	 * Adds a session alert message
	 * @method addMsg
	 *
	 * @param string $type can be info, success, warning or danger
	 * @param string $msg  the message you want to display in the alert
	 */
	public static function addMessage($type, $msg) {
		$sessionName = 'alert-' . $type;
		self::set($sessionName, $msg);
	}

	public static function displayMessage() {
		$alerts = ['alert-info', 'alert-success', 'alert-warning', 'alert-danger'];
		$html   = '';
		foreach ($alerts as $alert) {
			if(self::exists($alert)) {
				$html .= '<div class="alert ' . $alert . ' alert-dismissible" role="alert">';
				$html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>';
				$html .= self::get($alert);
				$html .= '</div>';
				self::delete($alert);
			}
		}

		return $html;
	}

}