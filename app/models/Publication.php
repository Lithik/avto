<?php

class Publication extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=256, nullable=false)
     */
    public $title;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $date;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $short_content;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $content;

    /**
     *
     * @var string
     * @Column(type="string", length=256, nullable=false)
     */
    public $author_name;

    /**
     *
     * @var string
     * @Column(type="string", length=256, nullable=false)
     */
    public $preview;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    public $type;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'publication';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Publication[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Publication
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
