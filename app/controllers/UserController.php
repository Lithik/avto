<?php
 

use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;


class UserController extends ControllerBase
{

	public function initialize()
	{
		// Устанавливаем заголовок документа
		$this->tag->setTitle("Все пользователи");
		parent::initialize();
	}

	public function indexAction()
	{
		$this->persistent->parameters = null;
		$numberPage = 1;
		$numberPage = $this->request->getQuery("page", "int");


		$parameters = $this->persistent->parameters;
		if (!is_array($parameters)) {
			$parameters = [];
		}
		$parameters["user"] = "_id";
		$user = User::find($parameters);


		$paginator = new PaginatorArray([
			'data' => $user,
			'limit'=> 15,
			'page' => $numberPage
		]);

		$this->view->page = $paginator->getPaginate();      
	}

	/**
	 * Searches for user
	 */
	// public function searchAction()
	// {
	//     $numberPage = 1;
	//     if ($this->request->isPost()) {
	//         $query = Criteria::fromInput($this->di, 'User', $_POST);
	//         $this->persistent->parameters = $query->getParams();
	//     } else {
	//         $numberPage = $this->request->getQuery("page", "int");
	//     }

	//     $parameters = $this->persistent->parameters;
	//     if (!is_array($parameters)) {
	//         $parameters = [];
	//     }
	//     $parameters["order"] = "id";

	//     $user = User::find($parameters);
	//     if (count($user) == 0) {
	//         $this->flash->notice("The search did not find any user");

	//         $this->dispatcher->forward([
	//             "controller" => "user",
	//             "action" => "index"
	//         ]);

	//         return;
	//     }

	//     $paginator = new PaginatorArray([
	//         'data' => $user,
	//         'limit'=> 10,
	//         'page' => $numberPage
	//     ]);

	//     $this->view->page = $paginator->getPaginate();       

	// }

	/**
	 * Displays the creation form
	 */
	public function newAction()
	{

	}

	public function editAction($_id)
	{
		if (!$this->request->isPost()) {

			$user = User::findById($_id);
			if (!$user) {
				$this->flash->error("Пользователь не найден!");

				$this->dispatcher->forward([
					'controller' => "user",
					'action' => 'index'
				]);

				return;
			}

			$this->view->_id = $user->_id;

			$this->tag->setDefaults(array("_id" => $user->_id));
			$this->tag->setDefault("email", $user->email);
			// $this->tag->setDefault("pass", $user->pass);
			$this->tag->setDefault("role_id", $user->role_id);
			$this->tag->setDefault("active", $user->active);

			
		}
	}
	 
	public function saveAction()
	{

		if (!$this->request->isPost()) {
			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'index'
			]);

			return;
		}

		$_id = $this->request->getPost("_id");
		$user = User::findById($_id);

		if (!$user) {
			$this->flash->error("user does not exist " . $_id);

			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'index'
			]);

			return;
		}

		$user->email = $this->request->getPost("email");
		$user->pass = $this->request->getPost("pass");
		$user->role_id = $this->request->getPost("role_id");
		$user->active = $this->request->getPost("active");
		

		if (!$user->save()) {

			foreach ($user->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'edit',
				'params' => [$user->_id]
			]);

			return;
		}

		$this->flash->success("user was updated successfully");

		$this->dispatcher->forward([
			'controller' => "user",
			'action' => 'index'
		]);
	}

	public function createAction()
	{
		if (!$this->request->isPost()) {
			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'index'
			]);

			return;
		}

		if (!empty($this->request->getPost("email")) ) {
			$email = $this->request->getPost("email");			
		}
		if (!empty($this->request->getPost("pass")) ) {
			$pass = $this->request->getPost("pass");			
		}

		if (isset($email)&& isset($pass)) {
			$user = new User();
			$user->email = $email;
			$user->pass = $pass;
			$user->role_id = $this->request->getPost("role_id");
			$user->active = $this->request->getPost("active");
		}
		

		if (!$user->save()) {
			foreach ($user->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'new'
			]);

			return;
		}

		$this->flash->success("user was created successfully");

		$this->dispatcher->forward([
			'controller' => "user",
			'action' => 'index'
		]);
	}

	public function deleteAction($_id)
	{
		$user = User::findById($_id);
		if (!$user) {
			$this->flash->error("user was not found");

			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'index'
			]);

			return;
		}

		if (!$user->delete()) {

			foreach ($user->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'search'
			]);

			return;
		}

		$this->flash->success("user was deleted successfully");

		$this->dispatcher->forward([
			'controller' => "user",
			'action' => "index"
		]);
	}


	public function newuserAction()
	{
		$this->persistent->parameters = null;
	   
		$numberPage = 1;
		$numberPage = $this->request->getQuery("page", "int");
 
		$parameters = $this->persistent->parameters;
		if (!is_array($parameters)) {
			$parameters = [];
		}
		$parameters["user"] = "_id";
		$user = User::find(
		[
			[
				"active" => "0",
			]
		]);


		$paginator = new PaginatorArray([
			'data' => $user,
			'limit'=> 15,
			'page' => $numberPage
		]);

		$this->view->page = $paginator->getPaginate();      
	}

	public function confirmAction($_id)
	{
		$user = User::findById($_id);
		if (!$user) {
			$this->flash->error("user was not found");
			$this->dispatcher->forward([
				'controller' => "user",
				'action' => 'newuser'
			]);

			return;
		}

		$user->active = "1";
		$user->save();
		$this->flash->success('Пользователь подтвержден');

		$this->dispatcher->forward([
			'controller' => "user",
			'action' => "newuser"
		]);
	}       
	
}
