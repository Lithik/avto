<?php

use Phalcon\Mvc\User\Component;
use ULogin\Auth;
/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class Elements extends Component
{

    private $_headerMenu = array(
        'navbar-left' => array(
            '' => array(
                'caption' => 'Главная',
                'action' => ''
            ),
            // 'orders' => array(
            //     'caption' => 'Автомобили',
            //     'action' => 'index'
            // ),
            'cars' => array(
                'caption' => 'Мои автомобили',
                'action' => '/index' 
            ),
            'admin' => array(
                'caption' => 'Админпанель',
                'action' => '/index'
            ),


        ),
        'navbar-right' => array(
            'session' => array(
                'caption' => 'Войти',
                'action' => '/index'
            ),
        ),
    );

    private $_tabs = array(
        'Автомобили' => array(
            'controller' => 'orders',
            'action' => 'index',
            'any' => true
        ),
        'Пользователи' => array(
            'controller' => 'user',
            'action' => 'index',
            'any' => false
        ),
       'Новые пользователи' => array(
            'controller' => 'user',
            'action' => 'newuser',
            'any' => false
        ),
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {
        $admin = $this->session->get('role_id');
        if ($admin!=='1') {
           unset($this->_headerMenu['navbar-left']['admin']);
           
        }

        $auth = $this->session->get('auth');
        if ($auth) {
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption' => 'Вы вошли как ' . $this->session->get('email') . $this->session->get('first_name') . " " . $this->session->get('last_name'). ' ---><b>Выйти</b>',
                'action' => '/end'
            );
        } else {
            unset($this->_headerMenu['navbar-left']['admin']);
            unset($this->_headerMenu['navbar-left']['orders']);
            unset($this->_headerMenu['navbar-left']['cars']);
        }

        $controllerName = $this->view->getControllerName();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<div class="nav-collapse">';
            echo '<ul class="nav navbar-nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo $this->tag->linkTo($controller . $option['action'], $option['caption']);
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }

    }

    /**
     * Returns menu tabs
     */
    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '</li>';
        }
        echo '</ul>';
    }
}
