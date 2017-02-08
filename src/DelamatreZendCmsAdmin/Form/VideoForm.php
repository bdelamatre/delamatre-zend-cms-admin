<?php

namespace DelamatreZendCmsAdmin\Form;

class VideoForm extends \DelamatreZendCmsAdmin\Form\Superclass\ContentForm {

    public function __construct($name=null){

        parent::__construct($name);

        $this->add(array(
            'name' => 'youtubeUrl',
            'type' => 'text',
            'options' => array(
                'label' => 'Youtube URL',
            ),
            'attributes' => array(
                //'placeholder' => 'sjhdf873nd93nd9384nd93',
            ),
        ));

    }

}