<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class IndexController extends ControllerBase
{
	public function initialize()
	{
		$this->tag->setTitle('Главная');
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
		$parameters["orders"] = "_id";
		$orders = Orders::find($parameters);


		$paginator = new PaginatorArray([
			'data' => $orders,
			'limit'=> 3,
			'page' => $numberPage
		]);

		$this->view->page = $paginator->getPaginate();
	}

}

