<?php

namespace DelamatreZendCmsAdmin\Controller;


use Admin\Mvc\Controller\RelatedContent;
use DelamatreZendCms\Entity\Email;
use DelamatreZendCmsAdmin\Mvc\Controller\AbstractEntityAdminController;

class EmailController extends AbstractEntityAdminController
{

    use RelatedContent;

    public $routeName = 'email';
    public $entityName = 'DelamatreZendCms\Entity\Email';
    public $formName = 'DelamatreZendCmsAdmin\Form\EmailForm';

    public function previewAction(){

        $this->requireAuthentication();

        $id = $this->params()->fromQuery('id');

        /* @var Email $entity */
        //if an id is specific get that lead, else create a new lead
        if(empty($id)){
            $entity = new $this->entityName();
        }else{

            /** @var \DelamatreZendCms\Entity\Blog $blog */
            $entity = $this->getEntityManager()->find($this->entityName,$id);

            if(empty($entity)){
                throw new \Exception('No '.$this->entityName.' found for id '.$id);
            }
        }

        echo $entity->generateHtml();
        exit();

    }

    /*&public function buildQuery()
    {
        $qb = parent::buildQuery();
        $qb->orderBy('u.posted_timestamp','DESC');
        return $qb;
    }

    public function crudBusinessRules(\DelamatreZendCms\Entity\Superclass\Content $blog){

        if(!$blog->created_datetime){
            //backwards compatability for posted_timestamp
            if($blog->posted_timestamp){
                $blog->created_datetime = $blog->posted_timestamp;
            }else{
                $blog->created_datetime = new \DateTime('now');
                $blog->posted_timestamp = new \DateTime('now');
            }
        }

        $blog->updated_datetime = new \DateTime('now');

    }*/

}
