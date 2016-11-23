<?php
use Phalcon\Mvc\Controller;
use Phalcon\Flash;
use Phalcon\Session;
class AdminController extends ControllerBase
{

    public function initialize()
    {
        // Устанавливаем заголовок документа
        $this->tag->setTitle("Админка");
        parent::initialize();
    }

    public function indexAction()
    {
    	  $this->assets->addCss("css/styles.css");
        $this->assets->addJs("js/utils.js");
    }

}

