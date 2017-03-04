<?php

namespace DelamatreZendCmsAdmin\Form;

use DelamatreZendCmsAdmin\Form\Element\EmailTemplate;

class EmailForm extends \DelamatreZendCmsAdmin\Form\Superclass\ContentForm {

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

        $emailTemplate = new EmailTemplate('email_template_id',array(),$this->getEntityManager());
        $this->add($emailTemplate);

        $this->add(array(
            'name' => 'theme_color',
            'type' => 'text',
            'options' => array(
                'label' => 'Theme Color',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'category',
            'type' => 'text',
            'options' => array(
                'label' => 'Category',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'subtitle',
            'type' => 'text',
            'options' => array(
                'label' => 'Subtitle',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'calltoaction',
            'type' => 'text',
            'options' => array(
                'label' => 'Call To Action',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'calltoaction_url',
            'type' => 'text',
            'options' => array(
                'label' => 'Call To Action URL',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

    }

}