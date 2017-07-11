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

    public function indexAction(){

        //require authentication
        $this->requireAuthentication($this->requiredGroups);

        //start building the users query
        $qb = $this->createQueryBuilder();
        $qb->select(array('u'))->from($this->getUserClass(),'u')->orderBy('u.username', 'ASC');
        $users = $qb->getQuery()->getResult();
        $usersCount = count($users);

        $view = new ViewModel();
        $view->recordCount = $usersCount;
        $view->routeName = $this->routeName;;
        $view->users = $users;
        return $view;

    }

    public function dashboardAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        /** @var \DelamatreZend\Entity\User $user */
        $user = $this->getEntityManager()->find($this->getUserClass(),$id);

        if(empty($user)){
            throw new \Exception('No user found for user id '.$id);
        }

        $form  = $user->getForm();

        $view = new ViewModel();
        $view->form = $form;
        $view->routeName = $this->routeName;;
        $view->user = $user;
        return $view;
    }

    public function formAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');
		$userClass = $this->getUserClass();
		
        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            $user = new $userClass;
        }else{
            $user = $this->getEntityManager()->find($this->getUserClass(),$id);

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

		//$form = new \Admin\Form\SurgeonForm();
		$formName = get_class($user->getForm());
        $form = new $formName(null,array('entityManager'=>$this->getEntityManager()));

        $data = $user->getArrayCopy();
        $form->setData($data);
		$view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        $view->user = $user;
        $view->routeName = $this->routeName;;
        return $view;

    }

	public function deactivateAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $entity->active = false;
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id);

        }

    }

    public function activateAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $entity->active = true;
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id);

        }

    }

    public function duplicateAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

            /** @var \DelamatreZendCms\Entity\Superclass\Content $entity */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            //duplicate entity
            /** @var \DelamatreZendCms\Entity\Superclass\Content $newEntity */
            $newEntity = clone $entity;
            $newEntity->key = $newEntity->key.'-copy';
            $this->getEntityManager()->persist($newEntity);
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$newEntity->id);

        }

    }

    public function deleteAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            throw new \Exception('No id specified');
        }else{

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
