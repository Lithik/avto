<?php
 
// use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray; //Пагинатор для массива данных (MongoDB)
use Phalcon\Mvc\Model\Criteria;

class OrdersController extends ControllerBase
{
    /**
     * Index action
     */
    
    public function indexAction()
    {
        $this->persistent->parameters = null;
       
        $numberPage = 1;
        // if ($this->request->isPost()) {            
        //     $this->persistent->parameters = $query->getParams();
        // } 
        // else {
            $numberPage = $this->request->getQuery("page", "int");
        // }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["orders"] = "_id";
        //если нету записей - 
        $orders = Orders::find($parameters);


        $paginator = new PaginatorArray([
            'data' => $orders,
            'limit'=> 3,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function myAction()
    {
        $this->persistent->parameters = null;
       
        $numberPage = 1;
        // if ($this->request->isPost()) {            
        //     $this->persistent->parameters = $query->getParams();
        // } 
        // else {
           
            // $numberPage = $this->request->getQuery("page", "int");

        // }
        $parameters = $this->session->get('auth');
        // $parameters = $this->persistent->parameters;
        // if (!is_array($parameters)) {
        //     $parameters = [];
        // }
        // $parameters["orders"] = "_id";
        //если нету записей - 
        $orders = Orders::find(
                [
                    [
                        "id_user" => $parameters,
                    ]
                ]
            );


        $paginator = new PaginatorArray([
            'data' => $orders,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Edits a order
     *
     * @param string $id
     */

    /**
     * Searches for orders
     */

    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Orders', $_POST);-

            // $query = Orders::find($_POST); //+

            $this->persistent->parameters = $query->getParams(); //??
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "_id";
        //если нету записей - 
        $orders = Orders::find($parameters);
        if (count($orders) == 0) {
            $this->flash->notice("Не найдено ни одного автомобиля");

            $this->dispatcher->forward([
                "controller" => "orders",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new PaginatorArray([
            'data' => $orders,
            'limit'=> 3,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a order
     *
     * @param string $id
     */
    public function editAction($_id)
    {
        if (!$this->request->isPost()) {

            $orders = Orders::findById($_id);
            if (!$orders) {
                $this->flash->error("Автомобиль не найден!");

                $this->dispatcher->forward([
                    'controller' => "orders",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->_id = $orders->_id;
     
            $this->tag->setDefaults(array("_id" => $orders->_id));
            // $this->tag->setDefault("id_user", $orders->id_user);
            $this->tag->setDefault("avto", $orders->avto);
            $this->tag->setDefault("price", $orders->price);
            $this->tag->setDefault("img", $orders->img);
            
        }
    }

    /**
     * Saves a orders edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'index'
            ]);

            return;
        }

        $_id = $this->request->getPost("_id");
        $orders = Orders::findById($_id);
        

        if (!$orders) {
            $this->flash->error("orders does not exist " . $_id);

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'index'
            ]);

            return;
        }


        // $orders->id_user = $this->request->getPost("id_user");
        $orders->avto = $this->request->getPost("avto");
        $orders->price = $this->request->getPost("price");
        $orders->img = $this->request->getPost("img");
        

        if (!$orders->save()) {

            foreach ($orders->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'edit',
                'params' => [$orders->_id]
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно обновлен");

        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => 'index'
        ]);
    }

    /**
     * Creates a new order
     */
    public function createAction() //работает
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'index'
            ]);

            return;
        }

        $orders = new Orders();
        $orders->id_user = $this->session->get('id');
        // $orders->id_user = $this->request->getPost("id_user");
        $orders->avto = $this->request->getPost("avto");
        $orders->price = $this->request->getPost("price");
        $orders->img = $this->request->getPost("img");
        

        if (!$orders->save()) {
            echo 'Failed to insert into the database' . "\n";
            foreach ($orders->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно добавлен");

        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => 'index'
        ]);
    }



    /**
     * Deletes a orders
     *
     * @param string $id
     */
    public function deleteAction($_id)
    {
        $orders = Orders::findById($_id);
        if (!$orders) {
            $this->flash->error("Автомобиль не найден");

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'index'
            ]);

            return;
        }

        if (!$orders->delete()) {

            foreach ($orders->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "orders",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно удален!");

        $this->dispatcher->forward([
            'controller' => "orders",
            'action' => "index"
        ]);
    }

    public function initialize()
    {
        // Устанавливаем заголовок документа
        $this->tag->setTitle("Все автомобили");
        parent::initialize();
    }

}
