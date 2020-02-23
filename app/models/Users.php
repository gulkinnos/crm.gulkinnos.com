<?php


class Users extends Model
{
	private       $_isLoggedIn; //unused yet
	private       $_sessionName;
	private       $_cookieName;
	public static $currentLoggedInUser = null;


	public function __construct($user = '') {
		$table = 'users';
		parent::__construct($table);
		$this->_sessionName = CURRENT_USER_SESSION_NAME;
		$this->_cookieName  = REMEMBER_ME_COOKIE_NAME;
		$this->_softDelete  = true;
		if(is_numeric($user)) {
			$u = $this->_db->findFirst('users', ['conditions' => 'id =?', 'bind' => [$user]]
			);
		}
		else {
			$u = $this->_db->findFirst('users', ['conditions' => 'username =?', 'bind' => [$user]]
			);
		}

		if($u && !empty($u)) {
			foreach ($u as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	public static function currentLoggedInUser() {
		if(is_null(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
			self::$currentLoggedInUser = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));
		}

		//@TODO
		/**
		 * Helpers::currentUser()->data()->username throws an error
		 * if Helpers::currentUser() is null
		 * Fatal error:  Uncaught Error: Call to a member function data() on null
		 *
		 * Implement logic which will return NULL value.
		 * /**/

		return self::$currentLoggedInUser;
	}

	public static function loginUserFromCookie() {
		$user        = null;
		$userSession = UserSessions::getFromCookie();
		if($userSession->user_id != '') {
			$user = new self((int)$userSession->user_id);
			if($user) {
				$user->login();
			}
		}
		return $user;
	}

	public function findByUsername($username) {
		$user = $this->findFirst(['conditions' => 'username = ?', 'bind' => [$username]]);

		//@TODO

		return $user;
	}


	public function login($remenberMe = false) {
		Session::set($this->_sessionName, $this->id);
		if($remenberMe) {
			$hash      = md5(uniqid() . rand(0, 100));
			$userAgent = Session::uagent_no_version();
			Cookie::set($this->_cookieName, $hash, REMEMBER_ME_COOKIE_EXPIRY);
			$fields = ['session' => $hash, 'user_agent' => $userAgent, 'user_id' => $this->id];
			$this->_db->query("DELETE FROM user_sessions WHERE user_id = ? AND user_agent = ?", [$this->id, $userAgent]
			);
			$this->_db->insert('user_sessions', $fields);
		}
	}

	public function logout() {
		$userSession = UserSessions::getFromCookie();
		if($userSession) {
			$userSession->delete();
		}
		Session::delete(CURRENT_USER_SESSION_NAME);
		if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
			Cookie::delete(REMEMBER_ME_COOKIE_NAME);
		}

		self::$currentLoggedInUser = null;
		return true;

	}
}