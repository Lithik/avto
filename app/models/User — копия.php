<?php

// use Phalcon\Mvc\Model; //-
// namespace Store\Users; //+
use Phalcon\Mvc\Collection; //+
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as Email;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class User extends Collection
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $pass;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $role_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    public $active;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validation = new Validation();

        $validation
            ->add('email', new Email())
            ->add('email', new UniquenessValidator(array(
                'model'   => $this,
                'message' => 'Этот e-mail уже зарегистрирован другим пользователем'
            )));
            // ->add('username', new UniquenessValidator(array(
            //     'model'   => $this,
            //     'message' => 'Sorry, That username is already taken'
            // )));

        return $this->validate($validation);
    }

    /**
     * Initialize method for model.
     */
    // public function initialize() // -
    // {
    //     $this->hasMany('id', 'Orders', 'id_user', ['alias' => 'Orders']);
    //     $this->belongsTo('role_id', 'Role', 'id', ['alias' => 'Role']);
    // }

    public function initialize() // +
    {
        $this->setSource("user");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
