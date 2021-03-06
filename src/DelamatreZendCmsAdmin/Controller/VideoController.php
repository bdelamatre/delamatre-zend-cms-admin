<?php

namespace DelamatreZendCmsAdmin\Controller;

use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;

class VideoController extends AbstractEntityAdminController
{

    public $routeName = 'video';
    public $entityName = 'DelamatreZendCms\Entity\Video';
    public $formName = 'DelamatreZendCmsAdmin\Form\VideoForm';

}
