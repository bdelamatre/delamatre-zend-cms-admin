<?php

namespace DelamatreZendCmsAdmin\Controller;

use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;
use Zend\View\Model\ViewModel;

class FilemanagerController extends AbstractEntityAdminController
{

    public function indexAction(){

        //require authentication
        $this->requireAuthentication();



        $view = new ViewModel();
        //$view->connector = $connector;
        return $view;

    }

    public function connectorAction(){

        $this->requireAuthentication();

        $connector = $this->getFilemanager();
        $connector->run();
        exit();

    }

}
