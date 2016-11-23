<?php
use Phalcon\Flash;
use Phalcon\Session;
/**
 * SessionController
 *
 * Allows to authenticate users
 */
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Авторизация');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('email', 'neolithik@mail.ru');
            $this->tag->setDefault('pass', '123');
        }
        
     
    }

    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession(User $user)
    {
        $this->session->set('auth', $user->_id);
        // $this->session->set('_id', $user->_id);
        $this->session->set('role_id', $user->role_id);
        $this->session->set('email', $user->email);
       
        // $this->session->set('auth', array(
        //     'id' => $user->_id,
        //     'role_id' => $user->role_id,
        //     'email'=> $user->email
        // ));
    }

    /**
     * This action authenticate and logs an user into the application
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email');
            $pass = $this->request->getPost('pass');

            $user = User::findFirst(
                
                [
                    "conditions" => [
                        "email" => $email,
                        "pass" => sha1($pass),
                        "active" => "1",
                    ],
                ]              
            );
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Добро пожаловать ' . $user->email);

                return $this->dispatcher->forward(
                    [
                        "controller" => "index",
                        "action"     => "",
                    ]
                );
            }

            $this->flash->error('Неверный e-mail или пароль');
        }

        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->session->remove('autt');
        $this->session->remove('role_id');
        $this->session->remove('email');
        $this->session->remove('uid');
        $this->session->remove('network');
        $this->session->remove('first_name');
        $this->session->remove('last_name');
        
        $this->flash->success('Вы вышли из админки.');

        return $this->dispatcher->forward(
            [
                "controller" => "index",
                "action"     => "index",
            ]
        );
    }
}
