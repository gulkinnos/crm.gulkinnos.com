<?php


class Users extends Model
{

	public $id;
	public $username;
	public $email;
	public $password;
	public $fname;
	public $lname;
	public $acl;
	public $deleted = 0;

	private $_sessionName;
	private $_cookieName;
	private $_confirm;

	public static $currentLoggedInUser = null;


	public function __construct($user = '') {
		$table = 'users';
		parent::__construct($table);
		$this->_sessionName = CURRENT_USER_SESSION_NAME;
		$this->_cookieName  = REMEMBER_ME_COOKIE_NAME;
		$this->_softDelete  = true;
		if(!empty($user)) {
			if(is_numeric($user)) {
				$u = $this->_db->findFirst('users', ['conditions' => 'id =?', 'bind' => [$user], 'Users']);
			}
			else {
				$u = $this->_db->findFirst('users', ['conditions' => 'username =?', 'bind' => [$user], 'Users']);
			}

			if($u && !empty($u)) {
				foreach ($u as $key => $value) {
					$this->$key = $value;
				}
			}
		}
	}

	protected function beforeSave() {
		$this->password = password_hash($this->password, PASSWORD_DEFAULT);
	}

	public function validator() {
		$this->runValidation(new RequiredValidator($this,['field'=>'fname','msg'=>'First Name is required.']));
		$this->runValidation(new RequiredValidator($this,['field'=>'lname','msg'=>'Last Name is required.']));
		$this->runValidation(new RequiredValidator($this,['field'=>'email','msg'=>'Email is required.']));
		$this->runValidation(new EmailValidator($this, ['field'=>'email','msg'=>'You must provide a valid email address']));
		$this->runValidation(new MaxValidator($this,['field'=>'email','rule'=>150,'msg'=>'Email must be less than 150 characters.']));
		$this->runValidation(new MinValidator($this,['field'=>'username','rule'=>6,'msg'=>'Username must be at least 6 characters.']));
		$this->runValidation(new MaxValidator($this,['field'=>'username','rule'=>150,'msg'=>'Username must be less than 150 characters.']));
		$this->runValidation(new UniqueValidator($this,['field'=>'username','msg'=>'That username already exists. Please choose a new one.']));
		$this->runValidation(new RequiredValidator($this,['field'=>'password','msg'=>'Password is required.']));
		$this->runValidation(new MinValidator($this,['field'=>'password','msg'=>'Password must be a minimum of 6 characters']));
		$this->runValidation(new MatchesValidator($this,['field'=>'password','rule'=>$this->_confirm,'msg'=>"Your passwords do not match"]));
	}

	public static function currentUser() {
		if(is_null(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)) {
			self::$currentLoggedInUser = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));
		}

		//@TODO
		/**
		 * Users::currentUser()->data()->username throws an error
		 * if Users::currentUser() is null
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

	public function acls() {
		if(empty($this->acl)) {
			return [];
		}
		else {
			return json_decode($this->acl, true);
		}
	}

	/**
	 * @return string
	 */
	public function getConfirm()
	{
		return $this->_confirm;
	}

	/**
	 * @param string $confirm
	 */
	public function setConfirm($confirm)
	{
		$this->_confirm = $confirm;
	}

}