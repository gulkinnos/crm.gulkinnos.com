<?php


class RegisterController extends Controller
{
	public function __construct($controller, $action) {
		parent::__construct($controller, $action);

		$this->load_model('Users');
		$this->view->setLayout('default');
	}

	public function loginAction() {
		$validation = new Validate();

		if($_POST) {
			// form validation

			$validation->check($_POST, [
					'username' => [
						'display'  => 'Username',
						'required' => true
					],
					'password' => [
						'display'  => 'Password',
						'required' => true
					]

				],
				true
			);
			if($validation->passed()) {
				$user = $this->UsersModel->findByUsername($_POST['username']);
				if($user && password_verify(Input::get('password'), $user->password)) {
					$remember = isset($_POST['remember_me'])
						&& Input::get('remember_me');
					$user->login($remember);
					Router::redirect('');
				}
				else {
					$validation->addError("There is an error with your username or password");
				}
			}
		}

		$this->view->displayErrors = $validation->displayErrors();

		$this->view->render('register/login');
	}

	public function logoutAction() {
		if(Users::currentUser()) {
			Users::currentUser()->logout();
			Router::redirect('register/login');
		}
	}


	public function registerAction() {
		$validation   = new Validate();
		$postedValues = [
			'fname'    => '',
			'lastname' => '',
			'username' => '',
			'email'    => '',
			'password' => '',
			'confirm'  => '',
		];

		if($_POST) {
			$postedValues = Helpers::postedValues($_POST);
			$validation->check($postedValues, [
					'fname'    => [
						'display'  => 'First Name',
						'required' => true,

					],
					'lastname' => [
						'display'  => 'Last Name',
						'required' => true,
					],
					'username' => [
						'display'  => 'Username',
						'required' => true,
						'unique'   => 'users',
						'min'      => 6,
						'max'      => 150,
					],
					'email'    => [
						'display'     => 'Email',
						'required'    => true,
						'unique'      => 'users',
						'max'         => 150,
						'valid_email' => true,
					],
					'password' => [
						'display'  => 'Password',
						'required' => true,
						'min'      => 6,
						'max'      => 150,
					],
					'confirm'  => [
						'display'  => 'Confirm password',
						'required' => true,
						'matches'  => 'password'
					],
				],
				true
			);

			if($validation->passed()) {
				$newUser = new Users();
				$newUser->registerNewUser($postedValues);
				$newUser->login();
				Router::redirect('register/login');
			}
		}


		$this->view->post          = $postedValues;
		$this->view->displayErrors = $validation->displayErrors();
		$this->view->render('register/register');
	}

}