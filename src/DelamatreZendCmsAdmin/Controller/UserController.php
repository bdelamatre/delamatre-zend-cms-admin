<?php

namespace DelamatreZendCmsAdmin\Controller;

use DelamatreZend\Entity\User;
use DelamatreZendCmsAdmin\Form\UserForm;
use DelamatreZendCmsAdmin\Mvc\Controller\AbstractAdminActionController;
use Zend\Crypt\Password\Bcrypt;
use Zend\View\Model\ViewModel;

class UserController extends AbstractAdminActionController
{

    public $routeName = 'user';
    public $entityName = 'DelamatreZendCms\Entity\User';
    public $formName = 'DelamatreZendCmsAdmin\Form\UserForm';

    public function indexAction(){

        //require authentication
        $this->requireAuthentication(array('admin','superadmin'));

        //start building the users query
        $qb = $this->createQueryBuilder();
        $qb->select(array('u'))->from($this->getUserClass(),'u')->orderBy('u.username', 'ASC');
        $users = $qb->getQuery()->getResult();
        $usersCount = count($users);

        $view = new ViewModel();
        $view->recordCount = $usersCount;
        $view->routeName = 'user';
        $view->users = $users;
        return $view;

    }

    public function dashboardAction(){

        $this->requireAuthentication(array('admin','superadmin'));

        $id = $this->params()->fromQuery('id');

        /** @var \DelamatreZend\Entity\User $user */
        $user = $this->getEntityManager()->find($this->getUserClass(),$id);

        if(empty($user)){
            throw new \Exception('No user found for user id '.$id);
        }

        $form  = $user->getForm();

        $view = new ViewModel();
        $view->routeName = 'user';
        $view->form = $form;
        $view->user = $user;
        return $view;
    }

    public function formAction(){

        $this->requireAuthentication(array('admin','superadmin'));

        $id = $this->params()->fromQuery('id');

        $userClass = $this->getUserClass();

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            $user = new $userClass;
        }else{
            $user = $this->getEntityManager()->find($userClass,$id);

            if(empty($user)){
                throw new \Exception('No user found for user id '.$id);
            }
        }

        //if this is a post than add the post to the lead
        if($this->getRequest()->isPost()){

            $post = $this->params()->fromPost();

            if(!empty($post['password'])){
                $bcrypt = new Bcrypt();
                $bcrypt->setCost($this->getConfig()['zfcuser']['password_cost']);
                $post['password'] = $bcrypt->create($post['password']);
            }

            $user->exchangeArray($post);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/user/dashboard?id='.$user->getId());

        }

        $form = $user->getForm();

        $data = $user->getArrayCopy();
        $form->setData($data);

        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        $view->user = $user;
        $view->name = 'User';
        $view->routeName = 'user';
        return $view;

    }

    public function deactivateAction(){

        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            $this->entityName = $this->getUserClass();

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $entity->state = false;
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id);

        }

    }

    public function activateAction(){


        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            $this->entityName = $this->getUserClass();

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $entity->state = true;
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id);

        }

    }

    public function deleteAction(){

        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            $this->entityName = $this->getUserClass();

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/index');

        }

    }

}
