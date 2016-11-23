<?php

/**
 * SessionController
 *
 * Allows to register new users
 */
class RegisterController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Регистрация');
		parent::initialize();
	}

	/**
	 * Action to register a new user
	 */
	public function indexAction()
	{
		$form = new RegisterForm;

		if ($this->request->isPost()) {
			if (!empty($this->request->getPost('email', 'email')) ) {
				$email = $this->request->getPost('email');			
			}
			if (!empty($this->request->getPost('password')) ) {
				$pass = $this->request->getPost('password');			
			}
			if (!empty($this->request->getPost('repeatPassword')) ) {
				$repeatPassword = $this->request->getPost('repeatPassword');			
			}


			if (isset($email) && isset($pass) && isset($repeatPassword))
			{
				
				if ($pass === $repeatPassword) {

				
					$user = new User();
					$user->pass = sha1($pass);
					$user->email = $email;
					$user->active = '0';
					$user->role_id = '2';
					if ($user->save() == false) {
						foreach ($user->getMessages() as $message) {
							$this->flash->error((string) $message);
						}
					} else {
						$this->tag->setDefault('email', '');
						$this->tag->setDefault('pass', '');
						$this->flash->success('Спасибо за регистрацию, пожалуйста войдите используя свои данные');

						return $this->dispatcher->forward(
							[
								"controller" => "session",
								"action"     => "index",
							]
						);
					}
				}
				else {
					$this->flash->error('Пароли не совпадают');
				}
			}
			else {
				$this->flash->error('Заполните все поля');
			}

		}

		$this->view->form = $form;
	}
}
