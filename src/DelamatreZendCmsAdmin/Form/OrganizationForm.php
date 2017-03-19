<?php

namespace DelamatreZendCmsAdmin\Form;

use DelamatreZend\Form\Element\OrganizationType;
use DelamatreZend\Form\Element\PreferredContactType;
use Zend\Form\Form;

class OrganizationForm extends Form {

    public function __construct($name=null){

        parent::__construct($name);

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $type = new OrganizationType('type');
        $this->add($type);

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $preferredContactType = new PreferredContactType('preferred_contact_type');
        $this->add($preferredContactType);

        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'options' => array(
                'label' => 'E-Mail',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'mobile',
            'type' => 'text',
            'options' => array(
                'label' => 'Mobile Phone',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => 'text',
            'options' => array(
                'label' => 'Office Phone',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'fax',
            'type' => 'text',
            'options' => array(
                'label' => 'Fax',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'notes',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Notes',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_street',
            'type' => 'text',
            'options' => array(
                'label' => 'Street',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_city',
            'type' => 'text',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_state',
            'type' => 'text',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_country',
            'type' => 'text',
            'options' => array(
                'label' => 'Country',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_zip',
            'type' => 'text',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'address_notes',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Address Notes',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'billing_street',
            'type' => 'text',
            'options' => array(
                'label' => 'Street',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'billing_city',
            'type' => 'text',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'billing_state',
            'type' => 'text',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'billing_country',
            'type' => 'text',
            'options' => array(
                'label' => 'Country',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        $this->add(array(
            'name' => 'billing_zip',
            'type' => 'text',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

        //surgery notes
        $this->add(array(
            'name' => 'billing_notes',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Billing Notes',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

    }

}