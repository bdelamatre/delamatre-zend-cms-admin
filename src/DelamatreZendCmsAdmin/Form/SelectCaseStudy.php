<?php

namespace DelamatreZendCmsAdmin\Form;

use DelamatreZendCmsAdmin\Form\Element\CaseStudy;
use DelamatreZendCmsAdmin\Form\Element\Technology;
use Zend\Form\Form;

class SelectCaseStudy extends Form{

    use DoctrineIntegration;

    public function __construct($name=null,$options=array()){

        //grab the entity manager from options
        if(isset($options['entityManager'])){
            $this->setEntityManager($options['entityManager']);
        }

        if(isset($options['exclude'])){
            $exclude = $options['exclude'];
        }else{
            $exclude = array();
        }

        parent::__construct($name,$options);

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $owner = new CaseStudy('case-study',array('exclude'=>$exclude),$this->getEntityManager());
        $owner->setAttributes(array('required'=>true));
        //$owner->setLabel('Technology');

        $this->add($owner);

    }

}