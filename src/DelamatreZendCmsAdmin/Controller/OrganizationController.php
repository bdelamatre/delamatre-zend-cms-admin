<?php

namespace DelamatreZendCmsAdmin\Controller;

use DelamatreZend\Entity\User;
use DelamatreZendCmsAdmin\Form\UserForm;
use DelamatreZendCmsAdmin\Mvc\Controller\AbstractAdminActionController;
use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;
use Zend\Crypt\Password\Bcrypt;
use Zend\View\Model\ViewModel;

class OrganizationController extends AbstractEntityAdminController
{

    public $routeName = 'organization';
    public $entityName = 'Application\Entity\Organization';
    public $formName = 'DelamatreZendCmsAdmin\Form\OrganizationForm';

    public function buildQuery()
    {
        $qb = parent::buildQuery();
        $qb->orderBy('u.name','ASC');
        return $qb;
    }

    public function dashboardAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        $organizationClass = $this->getOrganizationClass();

        /** @var \DelamatreZend\Entity\Organization $organization */
        $organization = $this->getEntityManager()->find($organizationClass,$id);

        if(empty($organization)){
            throw new \Exception('No organization found for organization id '.$id);
        }

        $form  = $organization->getForm();

        $view = new ViewModel();
        $view->form = $form;
        $view->organization = $organization;
        return $view;
    }

}
