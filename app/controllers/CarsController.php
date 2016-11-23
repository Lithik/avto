<?php
 
// use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray; //Пагинатор для массива данных (MongoDB)
// use Phalcon\Mvc\Model\Criteria;

class CarsController extends ControllerBase
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
                    'controller' => "cars",
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
                'controller' => "cars",
                'action' => 'index'
            ]);

            return;
        }

        $_id = $this->request->getPost("_id");
        $orders = Orders::findById($_id);
        

        if (!$orders) {
            $this->flash->error("orders does not exist " . $_id);

            $this->dispatcher->forward([
                'controller' => "cars",
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
                'controller' => "cars",
                'action' => 'edit',
                'params' => [$orders->_id]
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно обновлен");

        $this->dispatcher->forward([
            'controller' => "cars",
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
                'controller' => "cars",
                'action' => 'index'
            ]);

            return;
        }

        $orders = new Orders();
        $orders->id_user = $this->session->get('auth');
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
                'controller' => "cars",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно добавлен");

        $this->dispatcher->forward([
            'controller' => "cars",
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
                'controller' => "cars",
                'action' => 'index'
            ]);

            return;
        }

        if (!$orders->delete()) {

            foreach ($orders->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "cars",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("Автомобиль успешно удален!");

        $this->dispatcher->forward([
            'controller' => "cars",
            'action' => "index"
        ]);
    }

    public function initialize()
    {
        // Устанавливаем заголовок документа
        $this->tag->setTitle("Мои автомобили");
        parent::initialize();
    }


}
