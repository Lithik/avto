<?php

use Phalcon\Mvc\Model; //-
// namespace Store\Users; //+
use Phalcon\Mvc\Collection; //+
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\ExclusionIn as ExclusionIn;


class User extends Collection
{
    public $_id;
    public $email;
    public $role_id;
    public $pass;
    public $active;
    public $uid;
    public $social;
    public $first_name;
    public $last_name;
        
    public function initialize() // +
    {
        $this->setSource("user");
    }

     /**
     * Возвращает имя коллекции из бд для отображения в модели.
     */
    public function getSource()
    {
        return 'user';
    }

    // public function validation()
    // {
       
    //     $validator = new Validation();
        
    //     $validator->add(
    //         'email',
    //         new EmailValidator([
    //         'message' => 'Invalid email given'
    //     ]));
    //     $validator->add(
    //         'email',
    //         new UniquenessValidator([
    //         'message' => 'Sorry, The email was registered by another user'
    //     ]));
    //     $validator->add(
    //         'username',
    //         new UniquenessValidator([
    //         'message' => 'Sorry, That username is already taken'
    //     ]));
        
    //     return $this->validate($validator);
    // }
    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    // public $active; //-

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    // public function validation()
    // {
    //     $validation = new Validation();

    //     $validation
    //         ->add('email', new Email())
    //         ->add('email', new UniquenessValidator(array(
    //             'model'   => $this,
    //             'message' => 'Этот e-mail уже зарегистрирован другим пользователем'
    //         )));
    //         // ->add('username', new UniquenessValidator(array(
    //         //     'model'   => $this,
    //         //     'message' => 'Sorry, That username is already taken'
    //         // )));

    //     return $this->validate($validation);
    // }




    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]
     */
    // public static function find($parameters = null)
    // {
    //     return parent::find($parameters);
    // }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    // public static function findFirst($parameters = null)
    // {
    //     return parent::findFirst($parameters);
    // }

}
