<?php

namespace DelamatreZendCmsAdmin\Form\Element;

use Zend\Form\Element\Select;

class UserType extends Select{

    const TYPE_USER             = 'user';
    const TYPE_ADMIN            = 'admin';
    const TYPE_SUPERADMIN       = 'superadmin';

    public $valueOptions = array(
        self::TYPE_USER => 'User',
        self::TYPE_ADMIN => 'Admin',
        self::TYPE_SUPERADMIN => 'Superadmin',
    );

    public function valueOptions(){
        return $this->valueOptions;
    }

    public function __construct($name=null,$options=array()){

        parent::__construct($name,$options);

        $this->setValueOptions($this->valueOptions());
        $this->setLabel('User Type');

    }

}