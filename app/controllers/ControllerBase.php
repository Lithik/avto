<?php

use Phalcon\Mvc\Controller;


class ControllerBase extends Controller
{
    public function index()
    {


    }

    protected function initialize()
    {
        $this->assets->addCss("css/styles.css");
        $this->assets->addJs("js/jquery.js");
        $this->assets->addJs("js/ajaxupload.js");
        $this->assets->addJs("js/utils.js");
        $this->assets->addJs("js/script.js");
        // Дописываем в начало заголовка название приложения
        $this->tag->prependTitle(
            "Автопродажа | "
        );
    }

}
