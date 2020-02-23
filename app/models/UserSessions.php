<?php


class UserSessions extends Model
{

	public function __construct() {
		$table = 'user_sessions';
		parent::__construct($table);
	}

	public static function getFromCookie() {
		$userSessionInstance = new self();
		if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
			$userSession = $userSessionInstance->findFirst([
					'conditions' => 'user_agent = ? AND session = ?',
					'bind'       => [
						Session::uagent_no_version(),
						Cookie::get(REMEMBER_ME_COOKIE_NAME)
					]
				]
			);
		}
		if(!isset($userSession)) {
			return false;
		}
		else {
			return $userSession;
		}

	}
}