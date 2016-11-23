<?php

use Phalcon\Mvc\Collection; 
use Phalcon\Di;
use Phalcon\Text;
use Phalcon\Mvc\Model\Criteria;
class Orders extends Collection
{
    public $_id;
    public $id_user;
    public $avto;
    public $price;
    public $img;

    public function initialize() // +
    {
        $this->setSource("orders");
    }
    // public function initialize($parameters = []) // +
    // {
    //     // $this->setSource("orders");

    //     $model = (new static(Di::getDefault()->get('mongo'), static::DbName('test'), static::getSource()));
    //     if (count($parameters) > 0) {
    //         $model->fill($attributes);
    //     }
    //     return $model;
    // }

     /**
     * Возвращает имя коллекции из бд для отображения в модели.
     */
    public function getSource()
    {
        return 'orders';
    }
    // public static function find(array $parameters = null)
    // {
    //     return parent::find($parameters);
    // }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Role
     */
    // public static function findFirst(array $parameters = null)
    // {
    //     return parent::findFirst($parameters);
    // }
}