<?php

namespace App\Controllers;

use App\Models\Users;
use Core\Controller;
use Core\FormHelpers;
use Core\Session;
use Core\Router;
use App\Models\Contacts;

class ContactsController extends Controller
{

	public function __construct($controller, $action)
	{
		parent::__construct($controller, $action);
		$this->view->setLayout('default');
		$this->load_model('Contacts');
	}

	public function indexAction()
	{
		/**Declaring datatype to help IDE figure out with suggestions.
		 *
		 * @var Contacts $contactsModel
		 */
		$contactsModel = new $this->ContactsModel;
		$contacts      = $contactsModel->findAllByUserId(Users::currentUser()->id, ['order' => 'lname, fname']);

		$this->view->contacts = $contacts;
		$this->view->render('contacts/index');
	}

	public function addAction()
	{
		$contact = new Contacts();

		if ($this->request->isPost()) {
			$this->view->csrfToken = $this->request->get('csrf_token');
			$this->request->csrfCheck();
			$contact->assign($this->request->get());
			$contact->user_id = Users::currentUser()->id;
			if ($contact->save()) {
				Router::redirect('contacts');
			}
		}
		else {
			$this->view->csrfToken = FormHelpers::generateToken();
		}
		$this->view->contact       = $contact;
		$this->view->displayErrors = $contact->getErrorMessages();
		$this->view->postAction    = PROOT . 'contacts' . DS . 'add';
		$this->view->render('contacts/add');
	}

	public function detailsAction($id)
	{
		/** @var  Contacts $contact */
		$contact = $this->ContactsModel->findByIdAndUserId((int)$id, Users::currentUser()->id);

		if (!$contact) {
			Router::redirect('contacts');
		}
		$this->view->contact = $contact;
		$this->view->render('contacts/details');
	}

	public function deleteAction($id)
	{
		/** @var  Contacts $contact */
		$contact = $this->ContactsModel->findByIdAndUserId((int)$id, Users::currentUser()->id);
		if ($contact) {
			$contact->delete();
			Session::addMessage('success', 'Contact has been deleted successfully.');
		}
		Router::redirect('contacts');
	}

	public function editAction($id)
	{
		/** @var  Contacts $contact */
		$contact = $this->ContactsModel->findByIdAndUserId((int)$id, Users::currentUser()->id);
		if (!$contact) {
			Router::redirect('contacts');
		}

		if ($this->request->isPost()) {
			$this->view->csrfToken = $this->request->get('csrf_token');
			$this->request->csrfCheck();
			$contact->assign($this->request->get());
			if ($contact->save()) {
				Router::redirect('contacts');
			}
		}
		else {
			$this->view->csrfToken = FormHelpers::generateToken();
		}

		$this->view->contact       = $contact;
		$this->view->displayErrors = $contact->getErrorMessages();
		$this->view->postAction    = PROOT . 'contacts' . DS . 'edit' . DS . $contact->id;
		$this->view->render('contacts/edit');
	}

}