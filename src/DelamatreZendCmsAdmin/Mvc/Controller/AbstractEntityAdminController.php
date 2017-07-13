<?php

namespace DelamatreZendCmsAdmin\Mvc\Controller;

use DelamatreZend\Entity\AbstractEntity;
use Doctrine\DBAL\Schema\View;
use Html2Text\Html2Text;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class AbstractEntityAdminController extends AbstractAdminActionController
{

    public $routeName = 'entity';
    public $entityName = 'DelamatreZendCms\Entity\Superclass\Content';
    public $formName = 'DelamatreZend\Form\Form';
    public $requiredGroups = array('admin','superadmin');
    public $overrideGroups = array('admin','superadmin');
    public $ownerField = null;
    public $owns;

    public function ownerField(){

        $type = $this->getZfcUserAuthentication()->getIdentity()->getType();

        //if group doesn't require an ownership check, skip
        if(!is_null($this->overrideGroups)
            && in_array($type,$this->overrideGroups)) {
            return false;
        }

        return $this->ownerField;
    }

    public function owns(){
        return array();
    }

    public function requireOwnership($id,$overrideGroups=null){

        $type = $this->getZfcUserAuthentication()->getIdentity()->getType();
        $ownerField = $this->ownerField();

        //if there is no ownerfield, skip ownership check
        if(!$ownerField){
            return true;
        }

        //if group doesn't require an ownership check, skip
        if(!is_null($overrideGroups)
            && in_array($type,$overrideGroups)) {
            return true;
        }

        #if an object was passed
        if(is_object($id)){
            $type = get_class($id);
            $ownerField = $this->ownerField();
            #check that field exists on object
            if(property_exists($id,$ownerField)){
                $id = $id->$ownerField;
            }else{
                throw new \Exception('Ownership requires the field '.$ownerField.' on '.$type.', but it is not defined.');
            }
        }

        if(in_array($id,$this->owns())){
            return true;
        }else{
            throw new \Exception("You do not own this record");
        }

    }

    public function buildQuery(){

        $qb = $this->createQueryBuilder();
        $qb->select(array('u'))
            ->from($this->entityName,'u')
            ->orderBy('u.active', 'DESC')
            ->addOrderBy('u.title', 'ASC');
        $ownerField = $this->ownerField();
        if($ownerField && !in_array($this->getZfcUserAuthentication()->getIdentity()->getType(),$this->overrideGroups)){
            $qb->andWhere($qb->expr()->in('u.'.$ownerField,$this->owns()));
        }
        return $qb;
    }


    public function indexAction(){

        //require authentication
        $this->requireAuthentication($this->requiredGroups);

        //start building the users query
        $qb = $this->buildQuery();

        $records = $qb->getQuery()->getResult();
        $recordCount = count($records);

        $view = new ViewModel();
        $view->recordCount = $recordCount;
        $view->records = $records;
        $view->formName = $this->formName;
        $view->entityName = $this->entityName;
        $view->routeName = $this->routeName;
        return $view;

    }

    public function crudBusinessRules(AbstractEntity $entity){

        if(!$entity->created_datetime){
            $entity->created_datetime = new \DateTime('now');
        }

        $entity->updated_datetime = new \DateTime('now');

    }

    public function formAction(){

        $this->requireAuthentication($this->requiredGroups);

        $id = $this->params()->fromQuery('id');

        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            $entity = new $this->entityName();
        }else{

            /** @var \DelamatreZendCms\Entity\Blog $blog */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }

            $this->requireOwnership($entity,$this->overrideGroups);

        }
		
        //if this is a post than add the post to the lead
        if($this->getRequest()->isPost()){

            $post = $this->params()->fromPost();

            $entity->exchangeArray($post);

            try {
                $this->requireOwnership($entity, $this->overrideGroups);
            }catch(\Exception $e){
                throw new \Exception("Somehow, you have attempted to create a record that you wouldn't own.");
            }
            $this->crudBusinessRules($entity);

            try {
                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();
                if(method_exists($this,'dashboardAction')){
                    $this->redirect()->toUrl('/admin/'.$this->routeName.'/dashboard?id='.$entity->id);
                }else{
                    $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id.'&success=1');
                }
            }catch(\Exception $e){
                //error, shit is going down
            }



        }

        $formName = $this->formName;

        $form = new $formName('form',
                                array(
                                    'entityManager'=>$this->getEntityManager(),
                                    'owns'=>$this->owns(),
                                    'ownerField'=>$this->ownerField(),
                                    'config'=>$this->getConfig())
                                );

		//fix-me: bad
		/*if($form->hasElement('password')){
			$form->getElement('password')->setAttribute('required',true);
		}*/
		
        $data = $entity->getArrayCopy();
        $form->setData($data);

        $view = new ViewModel();
        $view->id = $id;
        $view->form = $form;
        $view->entity = $entity;
        $view->formName = $this->formName;
        $view->entityName = $this->entityName;
        $view->routeName = $this->routeName;
        $view->defaultTab = $this->params()->fromQuery('default-tab',false);
        $view->success = $this->params()->fromQuery('success',0);
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

            $this->requireOwnership($entity,$this->overrideGroups);

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

            $this->requireOwnership($entity,$this->overrideGroups);

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

            $this->requireOwnership($entity,$this->overrideGroups);

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

            $this->requireOwnership($entity,$this->overrideGroups);

            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/index');

        }

    }

    public function generateMetadataAction(){

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

            $this->requireOwnership($entity,$this->overrideGroups);

            if(!empty($entity->content)){

                //fix-me: this chould change to using the appropriate route to get the content
                $view = new ViewModel();
                $view->entity = $entity;
                $view->em = $this->getEntityManager();
                $view->key = $entity->key;
                $view->category = $entity;
                $view->content = $entity->content;
                if($entity->title){
                    $this->getHeadTitle()->prepend($entity->getTitle(true));
                    $this->setHeadMetaKeywords($entity->getKeywords(true));
                    $this->setHeadMetaDescription($entity->getDescription(true));
                }

                $viewHelperManager = $this->getServiceLocator()->get('ViewHelperManager');

                $partial = $viewHelperManager->get('partial'); // $escapeHtml can be called as function because of its __invoke method
                $view->partial = $partial;

                $icon = $viewHelperManager->get('icon'); // $escapeHtml can be called as function because of its __invoke method
                $view->icon = $icon;

                $cmsContent = $viewHelperManager->get('cmsContent'); // $escapeHtml can be called as function because of its __invoke method

                $description = $cmsContent($entity->content,$view);
                $descriptionHtml = new \Html2Text\Html2Text($description,array('width'=>0,'do_links'=>'none'));
                $descriptionPlainText = $descriptionHtml->getText();

                $mashapeKey = $this->getConfig()['mashape']['key'];

                // These code snippets use an open-source library. http://unirest.io/php
                $response = \Unirest\Request::get("https://aylien-text.p.mashape.com/summarize?title=".urlencode($entity->title)."&text=".urlencode($descriptionPlainText),
                    array(
                        "X-Mashape-Key" => $mashapeKey,
                        "Accept" => "application/json",
                    )
                );

                $entity->description = implode('',str_replace("\n",'',$response->body->sentences));

                // These code snippets use an open-source library. http://unirest.io/php
                $response2 = \Unirest\Request::get("https://aylien-text.p.mashape.com/entities?text=".urlencode($descriptionPlainText),
                    array(
                        "X-Mashape-Key" => $mashapeKey,
                        "Accept" => "application/json",
                    )
                );

                $entity->keywords = implode(',',$response2->body->entities->keyword);

                $this->getEntityManager()->flush();

            }

            $this->redirect()->toUrl('/admin/'.$this->routeName.'/form?id='.$entity->id);

        }

    }



}
