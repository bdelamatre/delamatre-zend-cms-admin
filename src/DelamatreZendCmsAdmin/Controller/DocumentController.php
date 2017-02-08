<?php

namespace DelamatreZendCmsAdmin\Controller;

use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;

class DocumentController extends AbstractEntityAdminController
{

    public $routeName = 'document';
    public $entityName = 'Application\Entity\Document';
    public $formName = 'DelamatreZendCmsAdmin\Form\DocumentForm';

}
